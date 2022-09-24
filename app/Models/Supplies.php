<?php

/**
 * Supplies Model
 *
 * @author    Radiant C. Juan <radiantcjuan@gmail.com>
 *
 * @copyright RCJuan Food Hub 2021
 */

namespace App\Models;

use App\View\Components\Image;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Supplies extends Model {
    use HasFactory, SoftDeletes;

    /**
     * @var string constant table name
     */
    const TABLE_NAME = 'supplies';

    /**
     * @var string constant table name
     */
    const CLASS_NAME = 'Supplies';

    /**
     * Category relationship
     *
     * @return object
     */
    public function category() {
        return $this->hasOne(Categories::class, 'id', 'categories_id');
    }

    /**
     * Get all supplies
     *
     * @return object
     */
    public static function get_all_supplies() {
        $route_model = new BreadRoutes(self::TABLE_NAME);
        $all_supplies = self::with('category')->orderBy('name', 'asc')->get();
        $mapped = $all_supplies->map(function ($value) use ($route_model) {
            $ImageViewClass = new Image($value->img_url);

            $delete_button = '<button type="button" class="btn btn-sm btn-danger btn-delete" data-action="' . $route_model->get_route('delete', ['id' => $value->id]) . '">
        <i class="fa fa-trash"></i>
      </button>';
            $user = \Illuminate\Support\Facades\Auth::user();
            $role_name = Role::find_role_by_id($user->role_id);
            if ($role_name !== 'admin') {
                $delete_button = '';
            }

            return [
                'id' => $value->id,
                'Image' => $ImageViewClass->render(),
                'Supply Name' => '<a href="' . route('supplies.edit', ['id' => $value->id]) . '">' . $value->name . '</a>',
                'Gram' => $value->gram,
                'Price' => $value->price,
                'Stock' => $value->stock_count,
                'Out Of Stock' => ($value->out_of_stock) ? '<span class="badge badge-danger">Yes</span>' : '<span class="badge badge-success">No</span>',
                'Category' => $value->category->name,
                'Actions' => $delete_button,
                'color' => $value->stock_count < $value->stock_warning_count ? 'table-danger' : '',
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
     * Get all fields of supply table
     *
     * @return BreadFields
     */
    public static function get_all_supply_fields() {
        $bread_fields = new BreadFields(self::TABLE_NAME, self::CLASS_NAME);
        return $bread_fields->get_all_fields();
    }

    /**
     * Store Supply
     *
     * @return BreadFields
     */
    public function store_supply($request) {
        try {
            if ($request->file('img_url')) {
                $img_path = Storage::putFile('public/supplies_images', $request->file('img_url'));
            }

            $this->name = $request->name;
            $this->categories_id = $request->categories_id;
            $this->gram = $request->gram;
            $this->price = $request->price;
            $this->img_url = $img_path ?? null;
            $this->stock_count = $request->stock_count;
            $this->stock_warning_count = $request->stock_warning_count;
            $this->sku = $request->sku;
            $this->out_of_stock = ($request->out_of_stock) ? 1 : 0;
            $this->available_soon = ($request->available_soon) ? 1 : 0;
            $this->product_franchise_category = ($request->product_franchise_category) ? json_encode($request->product_franchise_category) : '';
            $this->save();
        } catch (\Throwable$th) {
            throw $th;
            // return false;
        }

        return true;
    }

    /**
     * Update Supply
     *
     * @return BreadFields
     */
    public static function update_supply($request, $id) {
        try {
            $supply = self::find($id);
            $previos_values = json_encode($supply);

            if ($request->file('img_url')) {
                $img_path = Storage::putFile('public/supplies_images', $request->file('img_url'));
                $supply->img_url = $img_path;
            }

            $supply->name = $request->name;
            $supply->categories_id = $request->categories_id;
            $supply->gram = $request->gram;
            $supply->price = $request->price;
            $supply->stock_count = $request->stock_count;
            $supply->stock_warning_count = $request->stock_warning_count;
            $supply->sku = $request->sku;
            $supply->out_of_stock = ($request->out_of_stock) ? 1 : 0;
            $supply->available_soon = ($request->available_soon) ? 1 : 0;
            $supply->product_franchise_category = ($request->product_franchise_category) ? json_encode($request->product_franchise_category) : '';
            $supply->update();

            if ($supply->changes) {
                $new_values = json_encode($supply->changes);
                $audit_trail = new AuditTrail();
                $audit_trail->save_audit_trail($supply->id, self::TABLE_NAME, 'Manual Update', $previos_values, $new_values);
            }
        } catch (\Exception$th) {
            dd($th);
            // return false;
        }

        return true;
    }

    /**
     * get supply
     *
     * @return Collection
     */
    public static function get_supply($id) {
        return self::find($id);
    }

    /**
     * @return object categories object
     */
    public static function get_supply_list_store() {
        return self::with('category')->where('out_of_stock', 0)->orderBy('name', 'asc')->get();
    }

    /**
     * stock management
     *
     * @param array  $cart_items ordered items
     * @param string $type       if stock management will be subtracted or added
     *
     * @return void
     */
    public static function manage_stock($cart_items, $type) {
        foreach ($cart_items as $cart_item) {
            $supplies = self::find($cart_item->id);
            $previos_values = json_encode($supplies);
            $stock_count = $supplies->stock_count;
            if ($type == 'sub') {
                $supplies->stock_count = $stock_count - $cart_item->quantity;
            } else {
                $supplies->stock_count = $stock_count + $cart_item->quantity;
            }
            $supplies->update();

            if ($supplies->changes) {
                $new_values = json_encode($supplies->changes);
                $audit_trail = new AuditTrail();
                $audit_trail->save_audit_trail($supplies->id, self::TABLE_NAME, 'Ordered items update', $previos_values, $new_values);
            }
        }
    }

    /**
     * Stock Management puchase order
     *
     * @param array
     *
     * @return void
     */
    public static function manage_stock_purchase_order($quantities, $type = null) {
        foreach ($quantities as $id => $quantity) {
            $supplies = self::find($id);
            $previos_values = json_encode($supplies);
            $stock_count = $supplies->stock_count;

            if ($type == 'sub') {
                $supplies->stock_count = $stock_count - $quantity;
            } else {
                $supplies->stock_count = $stock_count + $quantity;
            }

            $supplies->update();

            if ($supplies->changes) {
                $new_values = json_encode($supplies->changes);
                $audit_trail = new AuditTrail();
                $audit_trail->save_audit_trail($supplies->id, self::TABLE_NAME, 'Purchase Order Update', $previos_values, $new_values);
            }
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

        if (strpos($field->Field, 'product_franchise_category') !== false) {
            $type = 'select-multiple';
            $is_special_case = true;
            $attributes = ['data-selection-js=true'];
            $options = [
                'franchise1' => 'franchise1',
                'franchise2' => 'franchise2',
            ];
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

    /**
     * get all supplies that are in warning threshold
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function get_all_stock_warning_supply() {
        $supplies = self::all();
        $filter_supplies = $supplies->filter(function ($val) {
            return $val->stock_count < $val->stock_warning_count;
        });
        return $filter_supplies;
    }
}
