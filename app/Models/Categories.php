<?php
/**
 * Categories Model
 *
 * @author    Radiant C. Juan <radiantcjuan@gmail.com>
 *
 * @copyright RCJuan Food Hub 2021
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categories extends Model {
    use HasFactory;

    /**
     * @var string constant table name
     */
    const TABLE_NAME = 'categories';

    /**
     * Get all categories
     *
     * @return object
     */
    public static function get_all_categories() {
        $route_model = new BreadRoutes(self::TABLE_NAME);
        $all_categories = self::all();
        $mapped = $all_categories->map(function ($value) use ($route_model) {
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
                'Name' => $value->name,
                'Actions' => '<a href="' . $route_model->get_route('edit', ['id' => $value->id]) . '" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a> ' . $delete_button,
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
     * Get all fields of categories table
     *
     * @return BreadFields
     */
    public static function get_all_categories_fields() {
        $bread_fields = new BreadFields(self::TABLE_NAME);
        return $bread_fields->get_all_fields();
    }

    /**
     * Store Supply
     *
     * @return BreadFields
     */
    public function store_categories($request) {
        try {
            $this->name = $request->name;
            $this->save();
        } catch (\Throwable$th) {
            //throw $th;
            return false;
        }

        return true;
    }

    /**
     * Update Supply
     *
     * @return BreadFields
     */
    public static function update_categories($request, $id) {
        try {
            $categories = self::find($id);
            $categories->name = $request->name;
            $categories->update();

        } catch (\Exception$th) {
            dd($th);
            // return false;
        }

        return true;
    }

    /**
     * get categories
     *
     * @return Collection
     */
    public static function get_categories($id) {
        return self::find($id);
    }
}
