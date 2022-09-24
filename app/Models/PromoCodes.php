<?php
/**
 * Promo Codes Model
 *
 * @author Radiant C. Juan <radiantcjuan@gmail.com>
 *
 * @copyright RCJuan Food Hub 2021
 */

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class PromoCodes extends Model {
    use SoftDeletes;
  /**
   * @var int constant fixed amount
   */
  const PROMO_COVERAGE_ALL_ITEMS = 'All Items';

  /**
   * @var int constant percentage amount
   */
  const PROMO_COVERAGE_INDIVIDUAL = 'Individual Discount';

  /**
   * @var string constant table name
   */
  const TABLE_NAME = 'promo_codes';

  /**
   * @var string constant model name
   */
  const CLASS_NAME = 'PromoCodes';

  /**
   * Get all promo_codes
   *
   * @return object
   */
  public static function get_all_promo_codes() {
    $route_model = new BreadRoutes(self::TABLE_NAME);
    $all_promo_codes = self::all();
    $mapped = $all_promo_codes->map(function ($value) use ($route_model) {

      $franchisees_id = json_decode($value->franchisees);
      $franchisees_map = [];
      if ($franchisees_id) {
        $franchisees = Franchisees::whereIn('id', $franchisees_id)->get();
        $franchisees_map = $franchisees->map(function ($val) {
          return $val->name;
        });
      }

      $delete_button = '<button type="button" class="btn btn-sm btn-danger btn-delete" data-action="' . $route_model->get_route('delete', ['id' => $value->id]) . '">
        <i class="fa fa-trash"></i>
      </button>';

      $user = \Illuminate\Support\Facades\Auth::user();
      $role_name = Role::find_role_by_id($user->role_id);
      if ($role_name !== 'admin') {
        $delete_button = '';
      }

      $actions = [
        '<a href="' . $route_model->get_route('edit', ['id' => $value->id]) . '" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a> ',
        '<a href="' . $route_model->get_route('set_status', ['id' => $value->id]) . '" class="btn btn-sm ' . (!$value->is_inactive ? 'btn-success' : 'btn-secondary') . '"><i class="fa ' . (!$value->is_inactive ? 'fa-toggle-on' : 'fa-toggle-off') . '"></i></a> ',
        $delete_button,
      ];

      return [
        'id' => $value->id,
        'Name' => $value->name,
        'Type' => $value->type,
        'Code' => $value->code,
        'Franchisees' => !empty($franchisees_map) ? $franchisees_map->implode(',') : '',
        'Active?' => (!$value->is_inactive) ? '<span class="badge badge-success">Active<span>' : '<span class="badge badge-secondary">Not Active<span>',
        'Coverage' => $value->coverage,
        'Start Date' => date('Y-m-d', strtotime($value->start_date)),
        'End Date' => !empty($value->end_date) ? date('Y-m-d', strtotime($value->end_date)) : '',
        'Number Of Use' => $value->number_of_use,
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
   * Get all fields of promo_codes table
   * @return BreadFields
   */
  public static function get_all_promo_codes_fields() {
    $bread_fields = new BreadFields(self::TABLE_NAME, self::CLASS_NAME);
    return $bread_fields->get_all_fields();
  }

  /**
   * Store Supply
   *
   * @param Request
   *
   * @return bool
   */
  public function store_promo_codes($request) {
    try {
      $this->name = $request->name;
      $this->type = $request->type;
      $this->coverage = $request->coverage;
      $this->items_list = json_encode($request->items_list);
      $this->items_exception = json_encode($request->items_exception);
      $this->code = $request->code;
      $this->value = $request->value;
      $this->franchisees = json_encode($request->franchisees);
      $this->start_date = date('Y-m-d H:i:s', strtotime($request->start_date));
      if ($request->is_limited) {
        $this->number_of_use = $request->number_of_use;
        $number_of_use_per_branch = $request->number_of_use;
        $number_of_use_map = array_map(function ($val) use ($number_of_use_per_branch) {
          return [
            'id' => $val,
            'number_of_use' => $number_of_use_per_branch,
          ];
        }, $request->franchisees);
        $this->number_of_use_per_branch = json_encode($number_of_use_map);
      }

      if ($request->use_end_date) {
        $this->end_date = date('Y-m-d H:i:s', strtotime($request->end_date));
      }
      $this->is_limited = ($request->is_limited) ? true : false;
      $this->use_end_date = ($request->use_end_date) ? true : false;
      $this->save();
    } catch (\Exception$th) {
      //throw $th;
      dd($th);
      return false;
    }

    return true;
  }

  /**
   * Update Supply
   *
   * @param Request
   * @param int
   *
   * @return BreadFields
   */
  public static function update_promo_codes($request, $id) {
    try {
      $promo_codes = self::find($id);
      $promo_codes->name = $request->name;
      $promo_codes->type = $request->type;
      $promo_codes->coverage = $request->coverage;
      $promo_codes->items_list = json_encode($request->items_list);
      $promo_codes->items_exception = json_encode($request->items_exception);
      $promo_codes->code = $request->code;
      $promo_codes->value = $request->value;
      $promo_codes->franchisees = json_encode($request->franchisees);
      $promo_codes->start_date = date('Y-m-d H:i:s', strtotime($request->start_date));
      if ($request->is_limited) {
        $promo_codes->number_of_use = $request->number_of_use;
      }

      if ($request->use_end_date) {
        $promo_codes->end_date = date('Y-m-d H:i:s', strtotime($request->end_date));
      }
      $promo_codes->is_limited = ($request->is_limited) ? true : false;
      $promo_codes->use_end_date = ($request->use_end_date) ? true : false;
      $promo_codes->update();

    } catch (\Exception$th) {
      dd($th);
      // return false;
    }

    return true;
  }

  /**
   * get promo_codes
   *
   * @param int
   *
   * @return Collection
   */
  public static function get_promo_codes($id) {
    return self::find($id);
  }

  /**
   * Reduces number of use of promocode
   *
   * @param string $promo_setup
   *
   * @return void
   */
  public static function reduce_number_of_use($promo_setup) {
    $franchisees_id = Auth::user()->franchisees_id;
    $setup = json_decode($promo_setup);
    $promo_code = self::find($setup->promo_code_id);
    if (!empty($promo_code->is_limited)) {
      $number_of_use_per_branch = json_decode($promo_code->number_of_use_per_branch);
      foreach ($number_of_use_per_branch as $key => $nbupb) {
        if ($nbupb->id == $franchisees_id) {
          $prev_num_use = $number_of_use_per_branch[$key]->number_of_use;
          $number_of_use_per_branch[$key]->number_of_use = $prev_num_use - 1;
        }
      }
      $promo_code->number_of_use_per_branch = json_encode($number_of_use_per_branch);
      $promo_code->update();
    }
  }

  /**
   * special case fields depends on what model you need to customize
   *
   * @param object
   *
   * @return array|bool
   */
  public static function set_special_case_fields($field) {
    $options = [];
    $is_special_case = false;
    $attributes = [];
    if (strpos($field->Type, 'json') !== false) {
      $type = 'select-multiple-json';
      $is_special_case = true;
    }

    if (strpos($field->Field, 'value') !== false) {
      $type = 'number';
      $is_special_case = true;
      $attributes = ['step=0.01'];
    }

    if (strpos($field->Field, 'items_list') !== false) {
      $type = 'select-multiple-json';
      $is_special_case = true;
      $attributes = ['data-js-event=on-coverage-change-item-list', 'disabled', 'data-selection-js=true'];
      $options = Supplies::orderby('name', 'asc')->get();
    }

    if (strpos($field->Field, 'items_exception') !== false) {
      $type = 'select-multiple-json';
      $is_special_case = true;
      $attributes = ['data-selection-js=true', 'data-js-event=items_exception'];
      $options = Supplies::orderby('name', 'asc')->get();
    }

    if (strpos($field->Field, 'franchisees') !== false) {
      $type = 'select-multiple-json';
      $is_special_case = true;
      $attributes = ['data-selection-js=true'];
      $options = Franchisees::all();
    }

    if (strpos($field->Field, 'coverage') !== false) {
      $type = 'select-enum';
      $attributes = ['data-js-event=on-coverage-change'];
      $is_special_case = true;
      $options = BreadFields::getEnumValues($field->Type);
    }

    if (strpos($field->Field, 'is_limited') !== false) {
      $type = 'checkbox';
      $attributes = ["data-js-event=on-is_limited-check"];
      $is_special_case = true;
    }

    if (strpos($field->Field, 'is_inactive') !== false) {
      $type = 'hidden';
      $attributes = ['disabled'];
      $is_special_case = true;
    }

    if (strpos($field->Field, 'number_of_use') !== false) {
      $type = 'number';
      $attributes = ['data-js-event=number_of_use', 'disabled'];
      $is_special_case = true;
    }

    if ($field->Field == 'end_date') {
      $type = 'date';
      $attributes = ['disabled'];
      $is_special_case = true;
    }

    if (strpos($field->Field, 'number_of_use_per_branch') !== false) {
      $type = 'hidden';
      $is_special_case = true;
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
