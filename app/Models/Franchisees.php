<?php
/**
 * Franchisee Model
 *
 * @author    Radiant C. Juan <radiantcjuan@gmail.com>
 *
 * @copyright RCJuan Food Hub 2021
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Franchisees extends Model {

    use HasFactory;

    /**
     * @var string constant table name
     */
    const TABLE_NAME = 'franchisees';

    /**
     * Get all franchisees
     *
     * @return object
     */
    public static function get_all_franchisees() {
        $route_model = new BreadRoutes(self::TABLE_NAME);
        $all_franchisees = self::all();
        $mapped = $all_franchisees->map(function ($value) use ($route_model) {
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
                'Location' => $value->location,
                'Contact Number' => $value->contact_number,
                'Contact Person' => $value->contact_person,
                'Active?' => (!$value->is_inactive) ? '<span class="badge badge-success">Active<span>' : '<span class="badge badge-secondary">Not Active<span>',
                'Created Date' => $value->created_at,
                'Updated Date' => $value->updated_at,
                'Actions' => implode(' ', $actions)
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
     * Get all fields of franchisees table
     *
     * @return BreadFields
     */
    public static function get_all_franchisees_fields() {
        $bread_fields = new BreadFields(self::TABLE_NAME);
        return $bread_fields->get_all_fields();
    }

    /**
     * Store Supply
     *
     * @param Request
     * @return bool
     */
    public function store_franchisees($request) {
        try {
            $this->name = $request->name;
            $this->location = $request->location;
            $this->contact_number = $request->contact_number;
            $this->contact_person = $request->contact_person;
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
    public static function update_franchisees($request, $id) {
        try {
            $franchisees = self::find($id);
            $franchisees->name = $request->name;
            $franchisees->location = $request->location;
            $franchisees->contact_number = $request->contact_number;
            $franchisees->contact_person = $request->contact_person;
            $franchisees->update();
        } catch (\Exception$th) {
            dd($th);
            // return false;
        }

        return true;
    }

    /**
     * get franchisees
     *
     * @return Collection
     */
    public static function get_franchisees($id) {
        return self::find($id);
    }
}
