<?php

/**
 * Puchase Orders Model
 *
 * @author Radiant C. Juan <radiantcjuan@gmail.com>
 *
 * @copyright RCJuan Food Hub 2021
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class PurchaseOrders extends Model
{
  /**
   * @var string constant table name
   */
  const TABLE_NAME = 'purchase_orders';

  /**
   * @var string constant model name
   */
  const CLASS_NAME = 'PurchaseOrders';

  /**
   * Get all purchase_orders
   * @return object
   */
  public static function get_all_purchase_orders()
  {
    $route_model = new BreadRoutes(self::TABLE_NAME);
    $all_purchase_orders = self::all();
    $mapped = $all_purchase_orders->map(function ($value) use ($route_model) {

      switch ($value->status) {
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

      // $delete_button = '<button type="button" class="btn btn-sm btn-danger btn-delete" data-action="' . $route_model->get_route('delete', ['id' => $value->id]) . '">
      //   <i class="fa fa-trash"></i>
      // </button>';
      $delete_button = '<button type="button" name="status" value="Cancel" data-action="' . route('purchase_orders.cancel_purchase_order', ['id' => $value->id]) . '" class="js-btn-cancel-purchase-order btn btn-danger btn-sm">
                          <i class="fa fa-times"></i>
                        </button>';

      $user = \Illuminate\Support\Facades\Auth::user();
      $role_name = Role::find_role_by_id($user->role_id);
      if ($role_name !== 'admin' || $value->status == 'CANCELLED' || $value->status == 'APPLIED') {
        $delete_button = '';
      }

      $actions = [
        $delete_button,
      ];

      $created_by = User::find($value->created_by);

      return [
        'id' => $value->id,
        'PO Number' => '<a href="' . $route_model->get_route('edit', ['id' => $value->id]) . '">' . '#' . $value->po_number . '</a> ',
        'Supplier' => $value->warehouse,
        'Status' => '<div class="badge badge-' . $color_status . '">' . $value->status . '</div>',
        'Created By' => $created_by->email,
        'Date Completed' => $value->date_completed,
        'Date Created' => $value->created_at,
        'Actions' => implode(' ', $actions),
      ];
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
   * Get all fields of purchase_orders table
   * @return BreadFields
   */
  public static function get_all_purchase_orders_fields()
  {
    $bread_fields = new BreadFields(self::TABLE_NAME, self::CLASS_NAME);
    return $bread_fields->get_all_fields();
  }

  /**
   * Store Supply
   * @return BreadFields
   */
  public function store_purchase_orders($request)
  {
    $supplies_list = $request->supplies_list;
    $supply_quantity = $request->supply_quantity;
    unset($supplies_list['not_included']);
    unset($supply_quantity['not_included']);

    $supply_list_map = array_map(function ($supply_list, $supply_quantity) {
      return [
        'id' => $supply_list,
        'qty' => $supply_quantity,
      ];
    }, $supplies_list, $supply_quantity);

    $currentUser = Auth::user()->id;

    $supply_list_json = json_encode($supply_list_map);
    try {
      $this->po_number = 'PO' . date('YmdHis');
      $this->warehouse = $request->warehouse;
      $this->supplies_list = $supply_list_json;
      $this->status = 'PENDING';
      $this->created_by = $currentUser;
      $this->save();
    } catch (\Throwable $th) {
      //throw $th;
      dd($th);
      return false;
    }

    return true;
  }

  /**
   * Update Supply
   * @return BreadFields
   */
  public static function update_purchase_orders($request, $id)
  {
    if (!$request->status) {
      $supplies_list = $request->supplies_list;
      $supply_quantity = $request->supply_quantity;
      unset($supplies_list['not_included']);
      unset($supply_quantity['not_included']);

      $supply_list_map = array_map(function ($supply_list, $supply_quantity) {
        return [
          'id' => $supply_list,
          'qty' => $supply_quantity,
        ];
      }, $supplies_list, $supply_quantity);
      $supply_list_json = json_encode($supply_list_map);
      try {
        $purchase_orders = self::find($id);
        $purchase_orders->warehouse = $request->warehouse;
        $purchase_orders->supplies_list = $supply_list_json;
        $purchase_orders->update();
      } catch (\Exception $th) {
        dd($th);
        // return false;
      }
    } else {
      $purchase_orders = self::find($id);
      $purchase_orders->status = "TO BE RECEIVED";
      $purchase_orders->date_completed = date('Y-m-d H:i:s');
      $purchase_orders->update();
    }
    return true;
  }

  /**
   * get purchase_orders
   * @return Collection
   */
  public static function get_purchase_orders($id)
  {
    return self::find($id);
  }

  /**
   * special case fields depends on what model you need to customize
   *
   * @param object
   *
   * @return array|bool
   */
  public static function set_special_case_fields($field)
  {
    $options = [];
    $is_special_case = false;
    $attributes = [];

    if (strpos($field->Field, 'supplies_list') !== false) {
      $type = 'special';
      $is_special_case = true;
      $options = Supplies::orderBy('name', 'asc')->get();
      $attributes[] = 'class="mb-3"';
    }

    if (strpos($field->Field, 'reason_for_cancelling') !== false) {
      $type = 'hidden';
      $is_special_case = true;
      $attributes[] = 'disabled';
    }

    if (strpos($field->Field, 'created_by') !== false) {
      $type = 'hidden';
      $is_special_case = true;
      $attributes[] = 'disabled';
    }

    if (strpos($field->Field, 'status') !== false) {
      $type = 'hidden';
      $is_special_case = true;
      $attributes[] = 'disabled';
    }

    if ($is_special_case) {
      return [
        'field_name' => $field->Field,
        'data_type' => $type,
        'step' => 0,
        'options' => $options,
        'attributes' => $attributes,
      ];
    }

    return false;
  }
}
