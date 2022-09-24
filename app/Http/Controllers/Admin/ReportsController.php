<?php
/**
 * Reports Controller
 *
 * @author    Radiant C. Juan <radiantcjuan@gmail.com>
 *
 * @copyright RCJuan Food Hub 2021
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrderedItems;
use App\Models\OrderPromoCodeSetup;
use App\Models\Orders;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportsController extends Controller {
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
        $this->middleware('check.if.admin');
        $this->middleware('check.if.moderator');
    }

    /**
     * Suplies View index
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function supplies_report_index(Request $request) {
        return view('admin.reports.supplies-report', [
            'start_date' => $request->start_date ?? date('Y-m-01'),
            'end_date' => $request->end_date ?? date('Y-m-t'),
            'auth_token' => (Auth::user()->tokens->isNotEmpty()) ? Auth::user()->api_token : ''
        ]);
    }

    /**
     * Fetching top ten purchased supplies
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function get_top_ten_purchased_supplies(Request $request) {
        $dates = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date'
        ]);

        return !empty(OrderedItems::get_top_10_ordered_items($dates)) ? OrderedItems::get_top_10_ordered_items($dates) : abort(404);
    }

    /**
     * fetching overall supplies ordered
     *
     * @return array
     */
    public function get_overall_supplies_ordered(Request $request) {
        $request_data = $request->validate([
            'start_date' => 'date',
            'end_date' => 'date|after:start_date',
            'show_all' => 'boolean'
        ]);

        $ordered_items = OrderedItems::get_overall_supplies_ordered($request_data);
        $ordered_item_names = $ordered_items->map(function ($val) {
            $subtract_promo_value_for_item = OrderPromoCodeSetup::select('promo_code_coverage')->where('promo_code_coverage', 'LIKE', '%' . $val->name . '%')->get()
                ->map(function ($promo_code_coverage) use ($val) {
                    $coverage = array_filter(json_decode($promo_code_coverage->promo_code_coverage, true), function ($coverage) use ($val) {
                        return trim(preg_replace('/x[\d\s]+/', '', $coverage['title'])) === $val->name;
                    });
                    return array_values($coverage)[0];
                })->values()->sum('new_costs');
            $val->total_cost -= $subtract_promo_value_for_item;
            return $val;
        });

        return ['data' => $ordered_item_names];
    }

    /**
     * Overall sales report view
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function overall_sales_report_index() {
        return view('admin.reports.overall-sales-report', [
            'auth_token' => (Auth::user()->tokens->isNotEmpty()) ? Auth::user()->api_token : ''
        ]);
    }


    /**
     * Get overall sales report data
     *
     * @return array
     */
    public function get_overall_sales_report() {
        $data = [];
        $month_data_schema = [
            'year' => 0,
            'jan' => 0,
            'feb' => 0,
            'mar' => 0,
            'apr' => 0,
            'may' => 0,
            'jun' => 0,
            'jul' => 0,
            'aug' => 0,
            'sept' => 0,
            'oct' => 0,
            'nov' => 0,
            'dec' => 0,
            'total' => 0,
        ];

        $month = [
            'jan',
            'feb',
            'mar',
            'apr',
            'may',
            'jun',
            'jul',
            'aug',
            'sept',
            'oct',
            'nov',
            'dec',
        ];
        $sales_data = Orders::get_overall_sales_report_by_month();
        if (!empty($sales_data)) {
            $sales_data_map = $sales_data->map(function ($data) use ($month) {
                $data->month = $month[$data->month - 1];
                $data->test = 'asdasd';
                return $data;
            })->toArray();

            foreach ($sales_data_map as $sale) {
                //reinitialize if the year is not the same
                if ($month_data_schema['year'] !== $sale['year']) {
                    $month_data_schema = [
                        'year' => 0,
                        'jan' => 0,
                        'feb' => 0,
                        'mar' => 0,
                        'apr' => 0,
                        'may' => 0,
                        'jun' => 0,
                        'jul' => 0,
                        'aug' => 0,
                        'sept' => 0,
                        'oct' => 0,
                        'nov' => 0,
                        'dec' => 0,
                        'total' => 0,
                    ];
                }
                $month_data_schema['year'] = $sale['year'];
                $month_data_schema[$sale['month']] = $sale['total_revenue'];
                $month_data_schema['total'] += $sale['total_revenue'];
                $data[$sale['year']] = $month_data_schema;
            }
        }
        $data = array_values($data);
        return ['data' => $data];
    }
}
