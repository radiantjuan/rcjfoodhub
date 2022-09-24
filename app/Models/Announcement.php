<?php
/**
 * Announcement Model
 *
 * @author Radiant C. Juan <radiantcjuan@gmail.com>
 *
 * @copyright RCJuan Food Hub 2021
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Announcement extends Model {
  /**
   * @var string constant table name
   */
  const TABLE_NAME = 'announcements';

/**
 * @var string constant model name
 */
  const CLASS_NAME = 'Announcement';

  /**
   * Get all announcements
   * @return object
   */
  public static function get_all_announcements() {
    $route_model = new BreadRoutes(self::TABLE_NAME);
    $all_announcements = self::all();
    $mapped = $all_announcements->map(function ($value) use ($route_model) {
      $delete_button = '<button type="button" class="btn btn-sm btn-danger btn-delete" data-action="' . $route_model->get_route('delete', ['id' => $value->id]) . '">
        <i class="fa fa-trash"></i>
      </button>';
      $user = \Illuminate\Support\Facades\Auth::user();
      $role_name = Role::find_role_by_id($user->role_id);
      if ($role_name !== 'admin') {
        $delete_button = '';
      }

      $color_code = '';
      switch ($value->status) {
      case 'published':
        $color_code = 'success';
        break;
      case 'draft':
        $color_code = 'secondary';
        break;
      case 'not_published':
        $color_code = 'danger';
        break;
      }

      return [
        'id' => $value->id,
        'Title' => $value->title,
        'Date Created' => $value->created_at,
        'Date Published' => $value->date_published,
        'Status' => '<span class="badge badge-' . $color_code . '">' . $value->status . '</span>',
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
   * Get all fields of announcements table
   * @return BreadFields
   */
  public static function get_all_announcements_fields() {
    $bread_fields = new BreadFields(self::TABLE_NAME, self::CLASS_NAME);
    return $bread_fields->get_all_fields();
  }

  /**
   * Store Supply
   * @return BreadFields
   */
  public function store_announcements($request) {
    try {
      $this->title = $request->title;
      if ($request->file('img_url')) {
        $img_path = Storage::putFile('public/announcement_images', $request->file('img_url'));
        $this->img_url = $img_path;
      }
      $this->content = $request->content;
      $this->excerpt = $request->excerpt;
      $this->status = $request->status;
      if ($request->status == 'published') {
        $this->date_published = date('Y-m-d H:i:s');
      }
      $this->save();
    } catch (\Throwable$th) {
      throw $th;
      // return false;
    }

    return true;
  }

  /**
   * Update Supply
   * @return BreadFields
   */
  public static function update_announcements($request, $id) {
    try {
      $announcements = self::find($id);
      $announcements->title = $request->title;
      $announcements->content = $request->content;
      $announcements->excerpt = $request->excerpt;
      if ($request->status == 'published') {
        if ($announcements->status !== 'published') {
          $announcements->status = $request->status;
          $announcements->date_published = date('Y-m-d H:i:s');
        }
      } else {
        $announcements->status = $request->status;
        $announcements->date_published = null;
      }
      $announcements->update();

    } catch (\Exception$th) {
      dd($th);
      // return false;
    }

    return true;
  }
  /**
   * get announcements
   * @return Collection
   */
  public static function get_announcements($id) {
    return self::find($id);
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

    if (strpos($field->Field, 'content') !== false) {
      $type = 'ckeditor';
      $is_special_case = true;
      $attributes = [];
    }

    if (strpos($field->Field, 'title') !== false) {
      $type = 'text';
      $is_special_case = true;
      $attributes = ['maxlength=50'];
    }

    if (strpos($field->Field, 'excerpt') !== false) {
      $type = 'text';
      $is_special_case = true;
      $attributes = ['maxlength=100'];
    }

    if (strpos($field->Field, 'date_published') !== false) {
      $type = 'date';
      $is_special_case = true;
      $attributes = ['disabled'];
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
