<?php

/**
 * Purchase Orders Controller
 *
 * @author Radiant C. Juan <radiantcjuan@gmail.com>
 *
 * @copyright RCJuan Food Hub 2021
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BreadRoutes;
use App\Models\PurchaseOrders;
use App\Models\Supplies;
use App\View\Components\Admin\PurchaseOrders\Fields;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class PuchaseOrdersController extends Controller {
  /**
   * @var string page title of bread
   */
  const PAGE_TITLE = 'Purchase Orders';

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
    $this->middleware('check.if.admin');
    $this->route_model = new BreadRoutes('purchase_orders');

    View::share('route_model', $this->route_model);
    View::share('page_title', self::PAGE_TITLE);
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index() {
    $data = PurchaseOrders::get_all_purchase_orders();
    
    $supplies_stock_warning_check = Supplies::get_all_stock_warning_supply();

    $special_button = [];
    if (!$supplies_stock_warning_check->isEmpty()) {
      $special_button = [
        'route' => route('purchase_orders.add', ['stock_warning_purchase' => true]),
        'label' => '<i class="fa fa-plus"></i> ' . __('New PO with stock warning suppl.'),
        'classes' => 'btn-warning',
      ];
    }

    return view('admin.bread.browse', [
      'data' => $data,
      'special_button' => $special_button,
    ]);
  }

  
  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create(Request $request) {
    $fields = PurchaseOrders::get_all_purchase_orders_fields();
    $renderedViews = [];
    
    if(!empty($request->stock_warning_purchase)) {
      $supplies_with_stock_warning = Supplies::get_all_stock_warning_supply();
      $supplies_with_stock_warning_map = $supplies_with_stock_warning->map(function($val) {
        return [
          'id' => $val->id,
          'qty' => abs($val->stock_count)
        ];
      });
      $supplies_stock_warning_json = $supplies_with_stock_warning_map->values()->toJson();
    }

    foreach ($fields as $field) {

      if ($field["field_name"] == 'status' || $field["field_name"] == 'date_completed' || $field["field_name"] == 'po_number' || $field["field_name"] == 'received_qty' || $field["field_name"] == 'created_by') {
        continue;
      }

      $field_value = null;
      
      if (!empty($supplies_stock_warning_json) && $field["field_name"] == 'supplies_list') {
        $field_value = $supplies_stock_warning_json;
      }
      
      $BreadFieldsView = new Fields(
        $field["field_name"],
        $field["data_type"],
        $field["step"],
        $field["options"],
        $field_value,
        $field['attributes']
      );
      $renderedViews[] = $BreadFieldsView->render();
    }

    $route_options = [
      'name' => 'add',
      'options' => [],
    ];

    return view('admin.purchase_orders.purchase_order_add_edit', ['fields' => $renderedViews, 'page_sub_title' => 'Add', 'route_options' => $route_options]);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request) {
    //TODO VALIDATION
    $purchase_orders = new PurchaseOrders();
    if ($purchase_orders->store_purchase_orders($request)) {
      $request->session()->flash('status', $request->name . ' is successfully added!');
      return redirect($this->route_model->get_route('index'));
    }
    return back();
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id) {
    $purchase_orders_values = PurchaseOrders::get_purchase_orders($id);
    if ($purchase_orders_values->status == 'TO BE RECEIVED' || $purchase_orders_values->status == 'APPLIED') {

      //TODO
      $supplies_list_array = json_decode($purchase_orders_values->supplies_list);
      $supplies_list_received_qty_array = ($purchase_orders_values->received_qty) ? json_decode($purchase_orders_values->received_qty) : [];
      $supplies_list_ids = array_map(function ($val) {
        return $val->id;
      }, $supplies_list_array);

      $supplies = Supplies::whereIn('id', $supplies_list_ids)->get()->toArray();

      $supplies_list_map = array_map(function ($supply_list_json, $supply_list_db, $received_qty) {
        return [
          'id' => $supply_list_json->id,
          'ordered_qty' => $supply_list_json->qty,
          'name' => $supply_list_db['name'],
          'current_qty' => $supply_list_db['stock_count'],
          'received_qty' => (isset($received_qty->qty)) ? $received_qty->qty : '',
          'sku' => $supply_list_db['sku'],
          'img_url' => $supply_list_db['img_url'],
        ];
      }, $supplies_list_array, $supplies, $supplies_list_received_qty_array);

      $route_options = [
        'name' => 'edit',
        'options' => ['id' => $id],
      ];

      return view(
        'admin.purchase_orders.purchase_orders_details',
        [
          'order' => $purchase_orders_values,
          'supplies_list' => $supplies_list_map,
          'page_sub_title' => 'Edit',
          'route_options' => $route_options,
        ]
      );
    } else {
      $fields = PurchaseOrders::get_all_purchase_orders_fields();
      $renderedViews = [];
      foreach ($fields as $field) {

        if ($field["field_name"] == 'date_completed' || $field["field_name"] == 'po_number' || $field["field_name"] == 'received_qty') {
          continue;
        }

        $BreadFieldsView = new Fields(
          $field["field_name"],
          $field["data_type"],
          $field["step"],
          $field["options"],
          $purchase_orders_values->{$field["field_name"]},
          $field['attributes']
        );
        $renderedViews[] = $BreadFieldsView->render();
      }

      $route_options = [
        'name' => 'edit',
        'options' => ['id' => $purchase_orders_values->id],
      ];

      $purchase_orders_status = '';
      if (!empty($purchase_orders_values->status)) {
        switch ($purchase_orders_values->status) {
        case 'PENDING':
          $color_status = 'secondary';
          break;
        case 'CANCELLED':
          $color_status = 'danger';
          break;
        case 'TO BE RECEIVED':
          $color_status = 'primary';
          break;
        default:
          $color_status = 'success';
          break;
        }
        $purchase_orders_status = '<span class="badge badge-' . $color_status . '">' . $purchase_orders_values->status . '</span>';
      }

      $purchase_orders_reason_for_cancelling = $purchase_orders_values->reason_for_cancelling;

      return view('admin.purchase_orders.purchase_order_add_edit', ['fields' => $renderedViews, 'page_sub_title' => 'Edit', 'route_options' => $route_options, 'purchase_orders_status' => $purchase_orders_status, 'po_status' => $purchase_orders_values->status, 'purchase_orders_reason_for_cancelling' => $purchase_orders_reason_for_cancelling]);
    }
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id) {
    //TODO VALIDATION
    if ($request->received_qty) {
      Supplies::manage_stock_purchase_order($request->received_qty);
      $recieved_qty = [];
      foreach ($request->received_qty as $key => $received_qty) {
        array_push($recieved_qty, [
          'id' => $key,
          'qty' => $received_qty,
        ]);
      }
      $PurchaseOrders = PurchaseOrders::find($id);
      $PurchaseOrders->status = "APPLIED";
      $PurchaseOrders->received_qty = json_encode($recieved_qty);
      $PurchaseOrders->update();
    } else {
      PurchaseOrders::update_purchase_orders($request, $id);
    }

    $request->session()->flash('status', $request->name . ' is successfully updated!');
    return back();
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id) {
    PurchaseOrders::destroy($id);
    return back();
  }

  /**
   * Cancel purchase order
   *
   * @param int $id
   * @param Request $request
   *
   * @return \Illuminate\Http\Response
   */
  public function cancel_purchase_order($id, Request $request) {
    $PurchaseOrders = PurchaseOrders::find($id);
    //if applied already remove the stocks
    if ($PurchaseOrders->status == 'APPLIED') {
      $received_qty = json_decode($request->received_qty, true);
      $rq_data = [];

      foreach ($received_qty as $rq) {
        $rq_data[$rq['id']] = $rq['value'];
      }

      Supplies::manage_stock_purchase_order($rq_data, 'sub');
    }

    $PurchaseOrders->status = "CANCELLED";
    $PurchaseOrders->reason_for_cancelling = $request->cancel_reason;
    $PurchaseOrders->update();
    return back();
  }
}
