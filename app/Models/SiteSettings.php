<?php
/**
 * Site Settings Model
 *
 * @author Radiant C. Juan <radiantcjuan@gmail.com>
 *
 * @copyright RCJuan Food Hub 2021
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSettings extends Model {
  /**
   * @var string constant table name
   */
  const TABLE_NAME = 'site_settings';

  /**
   * Get all site_settings
   * @return object
   */
  public static function get_all_site_settings() {
    $route_model = new BreadRoutes(self::TABLE_NAME);
    $all_site_settings = self::all();
    $mapped = $all_site_settings->map(function ($value) use ($route_model) {
      
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
        'Machine Name' => $value->machine_name,
        'Value' => $value->value,
        'Created At' => $value->created_at,
        'Updated At' => $value->updated_at,
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
   * Get all fields of site_settings table
   * @return BreadFields
   */
  public static function get_all_site_settings_fields() {
    $bread_fields = new BreadFields(self::TABLE_NAME);
    return $bread_fields->get_all_fields();
  }

  /**
   * Store Site Settings
   * @return BreadFields
   */
  public function store_site_settings($request) {
    try {
      $this->name = $request->name;
      $this->machine_name = $request->machine_name;
      $this->value = $request->value;
      $this->save();
    } catch (\Throwable$th) {
      //throw $th;
      return false;
    }

    return true;
  }

  /**
   * Update Site Settings
   * @return BreadFields
   */
  public static function update_site_settings($request, $id) {
    try {
      $site_settings = self::find($id);
      $site_settings->name = $request->name;
      $site_settings->machine_name = $request->machine_name;
      $site_settings->value = $request->value;
      $site_settings->update();

    } catch (\Exception$th) {
      dd($th);
      // return false;
    }

    return true;
  }

  /**
   * get site_settings
   * @return Collection
   */
  public static function get_site_settings($id) {
    return self::find($id);
  }

  /**
   * get site_settings by machine name
   * @param string $machine_name
   * @return Collection
   */
  public static function get_site_settings_by_machine_name($machine_name) {
    return self::where('machine_name', $machine_name)->first();
  }
}
