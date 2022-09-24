<?php
/**
 * Orders Model
 *
 * @author    Radiant C. Juan <radiantcjuan@gmail.com>
 *
 * @copyright RCJuan Food Hub 2021
 */

namespace App\Models;

// use charlieuki\ReceiptPrinter\ReceiptPrinter as ReceiptPrinter;

use App\Http\Resources\OrdersReportsResource;
use App\Services\ReceiptPrinterService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class Orders extends Model {
    /**
     * @var string constant table name
     */
    const TABLE_NAME = 'orders';

    /**
     * Order relationship with franchisee table
     *
     * @return object
     */
    public function franchisee() {
        return $this->hasOne(Franchisees::class, 'id', 'franchisee_id');
    }

    /**
     * Order relationship with user table
     *
     * @return object
     */
    public function user() {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    /**
     * Order relationship with user table
     *
     * @return object
     */
    public function order_cancellation() {
        return $this->hasOne(OrderCancellation::class, 'order_id', 'id');
    }

    /**
     * Get all orders
     *
     * @return object
     */
    public static function get_all_orders() {
        $user = Auth::user();
        $role = Role::find($user->role_id);

        if ($role->name !== 'admin') {
            $all_orders = self::with('franchisee')->where('franchisee_id', $user->franchisees_id)->get();
        } else {
            $all_orders = self::all();
        }

        $mapped = $all_orders->map(function ($value) use ($role) {

            $order_total = json_decode($value->order_total);
            if ($value->promo_code_setup) {
                $promo_code_setup = json_decode($value->promo_code_setup);
            }

            switch ($value->order_status) {
                case 'CANCELLED':
                    $color_status = 'danger';
                    break;
                case 'COMPLETED':
                    $color_status = 'success';
                    break;
                case 'UNDELIVERED-UNPAID':
                    $color_status = 'warning';
                    break;
                default:
                    $color_status = 'primary';
                    break;
            }

            $dataNeeded = [
                'id' => $value->id,
                'Order#' => '<a href="' . route('orders.edit', ['id' => $value->id]) . '">#' . $value->order_id . '</a>',
                'Order Status' => '<div class="badge badge-' . $color_status . '">' . $value->order_status . '</div>',
                'Payment Transaction#' => (!empty($value->payment_transaction_number)) ? '<a href="' . Storage::url($value->proof_of_payment) . '" class="text-primary" target="_blank">' . $value->payment_transaction_number . ' <i class="fa fa-external-link"></i></a>' : '',
                'Order Total' => (isset($promo_code_setup->new_amount_to_be_paid)) ? number_format($promo_code_setup->new_amount_to_be_paid, 2) : number_format($order_total->total_to_be_paid, 2),
                'Order Date' => $value->created_at,
            ];

//      if ($role->name == 'admin') {
            $dataNeeded['Franchisee'] = !empty($value->franchisee->name) ? $value->franchisee->name : 'Branch Deleted';
            $dataNeeded['Customer Email'] = $value->customer_email;
//      }

            $OrdersStatusForm = new \App\View\Components\Admin\Orders\OrdersStatusForm($value);
            $dataNeeded['Actions'] = [
                $OrdersStatusForm->render(),
            ];

//      if ($role->name !== 'admin') {
//        $dataNeeded['Actions'] = [];
//      }

            return $dataNeeded;
        })->toArray();

        $headers = [];
        if (isset($mapped[0])) {
            $mappedHeaders = $mapped[0];
            $headers = array_keys($mappedHeaders);
        }

        $return = [
            'headers' => $headers,
            'data' => $mapped,
        ];

        return $return;
    }

    /**
     * Get all fields of orders table
     *
     * @return BreadFields
     */
    public static function get_all_orders_fields() {
        $bread_fields = new BreadFields(self::TABLE_NAME);
        return $bread_fields->get_all_fields();
    }

    /**
     * Store Supply
     *
     * @return string
     */
    public function store_orders($request) {
        try {
            $user = Auth::user();
            $this->franchisee_id = $user->franchisees_id;
            $this->user_id = $user->id;
            $this->customer_email = $user->email;
            $this->ordered_items = $request->cart_items;
            $this->order_total = $request->cart_total;
            $this->payment_method = $request->payment_method;
            $this->special_instructions = $request->special_instructions;
            $this->shipping_option = $request->shipping_option;
            $this->shipping_address_1 = $request->shipping_address_1;
            $this->shipping_address_2 = $request->shipping_address_2;
            $this->shipping_city = $request->shipping_city;
            $this->shipping_barangay = $request->shipping_barangay;
            $this->shipping_zip_code = $request->shipping_zip_code;
            $this->payment_transaction_number = $request->payment_transaction_number;
            if ($request->payment_method == 'bank_transfer') {
                $proof_of_payment_img_path = Storage::putFileAs('public/orders', $request->file('proof_of_payment'), $request->payment_transaction_number . '.' . $request->file('proof_of_payment')->extension());
                $this->proof_of_payment = $proof_of_payment_img_path;
                $this->order_status = 'UNDELIVERED-PAID';
            } else {
                $this->order_status = 'UNDELIVERED-UNPAID';
            }
            $this->promo_code_setup = !empty($request->promo_code_setup) ? $request->promo_code_setup : null;

            $this->save();

            $this->order_id = date('YmdHi') . $this->id;
            $this->update();

            $cart_items = json_decode($request->cart_items);
            Supplies::manage_stock($cart_items, 'sub');

            if (!empty($request->cart_items)) {
                foreach ($cart_items as $cart_item) {
                    OrderedItems::create([
                        'grams' => $cart_item->grams,
                        'price' => $cart_item->price,
                        'name' => $cart_item->title,
                        'quantity' => $cart_item->quantity,
                        'total_cost' => $cart_item->total_cost,
                        'supply_id' => $cart_item->id,
                        'order_id' => $this->id,
                    ]);
                }
            }

            if (!empty($request->cart_total)) {
                $order_totals = json_decode($request->cart_total, true);
                OrderTotals::create([
                    'total_costs' => $order_totals['total_costs'],
                    'total_items' => $order_totals['total_items'],
                    'total_to_be_paid' => $order_totals['total_to_be_paid'],
                    'order_id' => $this->id,
                ]);
            }

            if (!empty($request->promo_code_setup)) {
                PromoCodes::reduce_number_of_use($request->promo_code_setup);
                $promo_code_setup = json_decode($request->promo_code_setup, true);
                OrderPromoCodeSetup::create([
                    'promo_code' => $promo_code_setup['promo_code'],
                    'promo_code_id' => $promo_code_setup['promo_code_id'],
                    'order_id' => $this->id,
                    'promo_code_coverage' => json_encode($promo_code_setup['promo_code_coverage']),
                    'promo_code_exceptions' => json_encode($promo_code_setup['promo_code_exceptions']),
                    'new_amount_to_be_paid' => $promo_code_setup['new_amount_to_be_paid'],
                    'promo_code_total_discount' => $promo_code_setup['promo_code_total_discount'],
                    'previous_amount_to_be_paid' => $promo_code_setup['previous_amount_to_be_paid'],
                ]);
            }

            return $this->order_id;
        } catch (\Throwable$th) {
            //throw $th;
            dd($th);
            return false;
        }

        return true;
    }

    /**
     * Update Supply
     *
     * @return BreadFields
     */
    public static function update_orders($request, $id) {
        try {
            $orders = self::with('franchisee')->find($id);
            $previos_values = json_encode($orders);

            if (isset($request->order_status)) {
                if ($request->order_status == 'COMPLETE') {
                    if ($orders->order_status == 'CANCELLED') {
                        $ordered_items = json_decode($orders->ordered_items);
                        Supplies::manage_stock($ordered_items, 'sub');
                    }
                    $orders->order_status = 'COMPLETED';
                } else if ($request->order_status == 'PRINT RECEIPT') {
                    self::print_receipt($orders);
                } else {
                    $orders->order_status = 'CANCELLED';
                    $ordered_items = json_decode($orders->ordered_items);
                    Supplies::manage_stock($ordered_items, 'add');
                }
            } else {
                $proof_of_payment_img_path = Storage::putFileAs('public/orders', $request->file('proof_of_payment'), $request->payment_transaction_number . '.' . $request->file('proof_of_payment')->extension());
                $orders->proof_of_payment = $proof_of_payment_img_path;
                $orders->order_status = 'UNDELIVERED-PAID';
                $orders->payment_transaction_number = $request->payment_transaction_number;
            }
            $orders->update();

            if ($orders->changes) {
                $new_values = json_encode($orders->changes);
                $audit_trail = new AuditTrail();
                $audit_trail->save_audit_trail($orders->id, self::TABLE_NAME, 'Manual Update', $previos_values, $new_values);
            }

            return $orders;
        } catch (\Exception$th) {
            dd($th);
            // return false;
        }

        return true;
    }

    /**
     * get orders
     *
     * @return Collection
     */
    public static function get_orders($id) {
        return self::find($id);
    }

    /**
     * Print docket in thermal printer
     *
     * @param object $orders placed order
     * @return void
     */
    public static function print_receipt($orders) {
        $transaction_id = $orders->order_id;

        $ordered_items = json_decode($orders->ordered_items, true);
        $promo_code_setup = json_decode($orders->promo_code_setup, true);

        $ordered_items_map = array_map(function ($val) use ($promo_code_setup) {
            if (!empty($promo_code_setup)) {
                foreach ($promo_code_setup['promo_code_coverage'] as $coverage) {
                    if (strpos($coverage['title'], $val['title']) !== false) {
                        $val['total_discount'] = $coverage['total_discount'];
                        break;
                    }
                }
            }
            return $val;
        }, $ordered_items);
        $order_total = json_decode($orders->order_total);
        $franchise = Franchisees::find($orders->franchisee_id);
        $receipt_printer_service = new ReceiptPrinterService(config('receiptPrinter'));
        $receipt_printer_service->set_transaction_id($transaction_id);
        $receipt_printer_service->set_items($ordered_items_map);
        if ($promo_code_setup) {
            $receipt_printer_service->set_promo_setup($promo_code_setup);
        }
        $receipt_printer_service->set_order_total($order_total);
        $receipt_printer_service->set_franchisee(!empty($franchise->name) ? $franchise->name : 'Branch Deleted');
        $receipt_printer_service->set_order_date($orders->created_at);
        $receipt_printer_service->print();

        header('Content-Disposition: attachment; filename=' . (!empty($franchise->name) ? str_replace(' ', '_', strtolower($franchise->name)) . '_' . $transaction_id : 'Branch-Deleted') . '.rcpt');
        header("Content-Type: text/plain");
        $file = readfile(public_path() . '/temp.txt');
        echo $file;
        die;
    }

    /**
     * Fetch all ordered items with revenue
     *
     * @param $request_data
     *
     * @return array
     */
    public static function get_ordered_items_with_revenue($request_data = []) {
        $orders_query = Orders::select('ordered_items', 'promo_code_setup', 'order_total')->where('order_status', 'COMPLETED');

        if (!empty($request_data['start_date']) && !empty($request_data['end_date'])) {
            $orders_query->where('created_at', '>=', $request_data['start_date'])->where('created_at', '<=', $request_data['end_date']);
        }

        $orders = $orders_query->get();
        $all_supplies_ordered = [];
        $all_promo_code_recieved_by_supplies_name = [];
        foreach ($orders as $order) {
            if (!empty($order->ordered_items)) {
                $ordered_items = array_map(function ($ordered_items) {
                    return [
                        'id' => $ordered_items['id'],
                        'quantity' => $ordered_items['quantity'],
                        'total_costs' => $ordered_items['total_cost'],
                        'title' => $ordered_items['title']
                    ];
                }, json_decode($order->ordered_items, true));

                if (!empty($order->promo_code_setup)) {
                    $promo_code_setup = json_decode($order->promo_code_setup, true);
                    $promo_code_setup_filter = array_filter($promo_code_setup['promo_code_coverage'], function ($promo_code_coverage) {
                        return $promo_code_coverage['title'] !== 'All Items in total';
                    });
                    if (!empty($promo_code_setup_filter)) {
                        foreach ($promo_code_setup_filter as $promo_code_filtered) {
                            $all_promo_code_recieved_by_supplies_name[trim(preg_replace('/x[\d\s]+/', '', $promo_code_filtered['title']))][] = $promo_code_filtered['total_discount'];
                        }
                    }
                }
            }

            foreach ($ordered_items as $ordered_item) {
                $all_supplies_ordered[$ordered_item['id'] . '_id'][] = $ordered_item;
            }
        }
        return [
            'all_supplies_ordered' => $all_supplies_ordered,
            'all_promo_code_recieved_by_supplies_name' => $all_promo_code_recieved_by_supplies_name
        ];
    }

    /**
     * OrderedItems Relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items_ordered() {
        return $this->hasMany(OrderedItems::class, 'order_id', 'id');
    }

    /**
     * OrderPromoCodesSetup relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function order_promo_code_setup() {
        return $this->hasOne(OrderPromoCodeSetup::class, 'order_id', 'id');
    }

    /**
     * OrderTotals relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function order_totals() {
        return $this->hasOne(OrderTotals::class, 'order_id', 'id');
    }

    /**
     * fetch order totals to be paid by month
     *
     * @return void
     */
    public static function get_overall_sales_report_by_month() {
        return self::selectRaw('SUM(order_totals.total_to_be_paid) - (CASE WHEN SUM(order_promo_code_setup.promo_code_total_discount) IS NULL THEN 0 ELSE SUM(order_promo_code_setup.promo_code_total_discount) END) as total_revenue, MONTH(orders.created_at) as month, YEAR(orders.created_at) as year')
            ->join('order_totals', 'order_totals.order_id', '=', 'orders.id')
            ->leftJoin('order_promo_code_setup', 'order_promo_code_setup.order_id', '=', 'orders.id')
            ->where('orders.order_status', 'COMPLETED')
            ->groupByRaw('YEAR(orders.created_at), MONTH(orders.created_at)')
            ->orderBy('orders.created_at')
            ->get();
    }
}

