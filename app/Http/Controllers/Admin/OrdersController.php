<?php
/**
 * Orders Controller
 *
 * @author    Radiant C. Juan <radiantcjuan@gmail.com>
 *
 * @copyright RCJuan Food Hub 2021
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BasketItems;
use App\Models\BreadRoutes;
use App\Models\OrderCancellation;
use App\Models\OrderedItems;
use App\Models\Orders;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;

class OrdersController extends Controller {
    /**
     * @var string page title of bread
     */
    const PAGE_TITLE = 'Orders';

    /**
     * @var object route model
     */
    protected $route_model;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
//    $this->middleware('check.if.moderator');
        $this->route_model = new BreadRoutes('orders');

        View::share('route_model', $this->route_model);
        View::share('page_title', self::PAGE_TITLE);
        View::share('extractable', true);
        View::share('has_audit_trail', true);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $data = Orders::get_all_orders();
        return view('admin.orders.orders', [
            'data' => $data,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        //TODO VALIDATION

        $orders = new Orders();
        if ($orders->store_orders($request)) {
            // $request->session()->flash('status', $request->name . ' is successfully added!');
            return redirect($this->route_model->get_route('index'));
        }
        return back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function edit($id) {
        $Order = Orders::with('franchisee')->with('items_ordered')->with('order_totals')->with('order_promo_code_setup')->with('order_cancellation')->find($id);
        $promo_code_coverage = !empty($Order->order_promo_code_setup) ? json_decode($Order->order_promo_code_setup->promo_code_coverage, true) : [];
        $ordered_items = $this->process_ordered_items($Order->items_ordered->toArray(), $promo_code_coverage);
        $order_total = $Order->order_totals;
        $promo_setup = !empty($Order->order_promo_code_setup) ? $Order->order_promo_code_setup->toArray() : [];
        $user_role = Role::find(Auth::user()->role_id);

        return view('admin.orders.order-details', [
            'order' => $Order,
            'ordered_items' => $ordered_items,
            'order_total' => $order_total,
            'promo_code' => $promo_setup,
            'user_role' => $user_role->name,
            'proof_of_payment' => ($Order->proof_of_payment) ? Storage::url($Order->proof_of_payment) : false,
        ]);
    }

    /**
     * Process ordered items to show in the details
     *
     * @param array $ordered_items
     * @param       $promo_code_coverage
     *
     * @return array
     */
    private function process_ordered_items($ordered_items = [], $promo_code_coverage = []) {
        return array_map(function ($ordered_item) use ($promo_code_coverage) {
            $subtotal = $ordered_item['total_cost'];
            $discount = 0;
            if (!empty($promo_code_coverage)) {
                $filter = array_filter($promo_code_coverage, function ($promo_code_coverage) use ($ordered_item) {
                    return trim(preg_replace('/x[\d\s]+/', '', $promo_code_coverage['title'])) === $ordered_item['name'];
                }, ARRAY_FILTER_USE_BOTH);

                $filter = array_values($filter);

                if (count($filter) > 0) {
                    $subtotal = $filter[0]['new_costs'];
                    $discount = $filter[0]['total_discount'];
                }
            }

            return [
                'name' => $ordered_item['name'],
                'qty' => $ordered_item['quantity'],
                'discount' => $discount,
                'subtotal' => $subtotal,
            ];
        }, $ordered_items);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        //TODO VALIDATION
        if (empty($request->order_status)) {
            $request->validate([
                'proof_of_payment' => 'mimes:jpg,png|max:2048',
                'payment_transaction_number' => 'required',
            ]);
        }

        $order = Orders::update_orders($request, $id);
        $email = $order->customer_email;

        // Mail::to($email)->send(new UpdateOrderStatus($order->id));

        $request->session()->flash('status', $request->name . ' is successfully updated!');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        Orders::destroy($id);
        return back();
    }

    /**
     * Extraction of record
     *
     * @return void
     */
    public function extract_record() {
        $orders = Orders::with('franchisee')->with('user')->get();
        $orders_map = $orders->map(function ($val) {
            $order_total = json_decode($val->order_total);

            if ($val->promo_code_setup) {
                $promo_code_setup = json_decode($val->promo_code_setup);
            }
            return [
                'Order ID' => (string)$val->order_id,
                'Payment Transaction#' => $val->payment_transaction_number,
                'Special Instructions' => !empty($val->special_instructions) ? $val->special_instructions : '',
                'Franchisee' => !empty($val->franchisee->name) ? $val->franchisee->name : 'Branch Deleted',
                'Total (Not Discounted)' => $order_total->total_to_be_paid,
                'Total Discount' => isset($promo_code_setup) ? $promo_code_setup->promo_code_total_discount : '',
                'Payment Method' => $val->payment_method,
                'Ordered Date' => $val->created_at,
            ];
        });

        header('Content-Type: application/csv');
        header('Content-Disposition: attachment; filename="orders.csv";');
        $f = fopen('php://output', 'w');
        fputcsv($f, [
            'Order ID',
            'Payment Transaction#',
            'Special Instructions#',
            'Franchisee',
            'Total (Not Discounted)',
            'Total Discount',
            'Payment Method',
            'Ordered Date',
        ], ',');
        foreach ($orders_map as $orders) {
            fputcsv($f, [
                (string)$orders['Order ID'],
                $orders['Payment Transaction#'],
                $orders['Special Instructions'],
                $orders['Franchisee'],
                $orders['Total (Not Discounted)'],
                $orders['Total Discount'],
                $orders['Payment Method'],
                $orders['Ordered Date'],
            ], ',');
        }
    }

    /**
     * Cancel order
     *
     * @return void
     */
    public function cancel_order($id, Request $request) {
        $order = Orders::update_orders($request, $id);

        $cancellation_order = new OrderCancellation();
        $cancellation_order->cancel_order($id, $request->cancel_reason);

        $email = $order->customer_email;
        // Mail::to($email)->send(new UpdateOrderStatus($order->id));
        $request->session()->flash('status', $request->name . ' is successfully updated!');
        return back();
    }

    public function order_check($item_id) {

        $audit = \App\Models\AuditTrail::where('model', 'supplies')->where('previous_values', 'LIKE', '%"id": ' . $item_id . '%')->get();
        echo "<table width=\"100%\">";
        echo "<tr style=\"text-align: left;\">";
        echo "<th>Updated</th><th>Previous Value</th><th>New Value</th>";
        echo "</tr>";
        foreach ($audit as $at) {
            $previous_values = json_decode($at->previous_values);
            $new_values = json_decode($at->new_values);

            if (empty($new_values->stock_count)) {
                continue;
            }

            echo "<tr>";
            echo "<td>" . $at->created_at . "</td>";
            echo "<td>" . $previous_values->stock_count . "</td>";
            echo "<td>" . $new_values->stock_count . "</td>";
            echo "</tr>";
        }
        echo "</table>";

        echo "<hr />";
        $total_quantity_ordered = 0;
        $orders = Orders::where('ordered_items', 'LIKE', '%"id": ' . $item_id . '%')->get();
        echo "<table width=\"100%\">";
        echo "<tr style=\"text-align: left;\">";
        echo "<th>Date ordered</th><th>Order ID</th><th>Order status</th><th>Ordered Item</th><th>Order Quantity</th>";
        echo "</tr>";
        foreach ($orders as $order) {
            echo "<tr>";
            $ordered_items = json_decode($order->ordered_items);
            foreach ($ordered_items as $ordered_item) {
                if ($ordered_item->id == $item_id) {
                    echo "<td>" . $order->created_at . "</td>";
                    echo "<td>" . $order->id . "</td>";
                    echo "<td>" . $order->order_status . "</td>";
                    echo "<td>" . $ordered_item->title . "</td>";
                    echo "<td>" . $ordered_item->quantity . "</td>";
                    $total_quantity_ordered += $ordered_item->quantity;
                }
            }
            echo "</tr>";
        }
        echo "</table>";
        echo "Total Quantity: $total_quantity_ordered";
    }

}
