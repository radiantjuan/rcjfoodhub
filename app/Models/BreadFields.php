<?php
/**
 * Bread Fields Model
 *
 * @author Radiant C. Juan <radiantcjuan@gmail.com>
 *
 * @copyright RCJuan Food Hub 2021
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BreadFields extends Model {

  /**
   * @var string $table_name name of table to be used
   */
  protected $table_name;

  /**
   * @var string $class_name model name
   */
  protected $class_name;

  public function __construct($table_name, $class_name = false) {
    $this->table_name = $table_name;
    $this->class_name = $class_name;
  }

  /**
   * Get all fields for bread editor
   *
   * @return object
   */
  public function get_all_fields() {
    $db_fields = DB::select(DB::raw('DESC ' . $this->table_name));
    $db_fields_map = array_map(function ($value) {
      $type = 'text';
      $number_step = '0';
      $options = [];
      $attributes = [];

      if ($value->Field == 'id' || $value->Type == 'timestamp' || $value->Field == 'remember_token') {
        return null;
      }

      if (strpos($value->Type, 'int') !== false) {
        $type = 'number';
      }

      if (strpos($value->Type, 'tinyint') !== false) {
        $type = 'checkbox';
      }

      if (strpos($value->Type, 'datetime') !== false) {
        $type = 'date';
      }

      if (strpos($value->Type, 'float') !== false || strpos($value->Type, 'double') !== false) {
        $type = 'number';
        $number_step = '0.05';
      }

      if (strpos($value->Field, 'img') !== false) {
        $type = 'file';
      }

      if (strpos($value->Type, 'json') !== false) {
        $type = 'select-json';
      }

      if (strpos($value->Field, 'password') !== false) {
        $type = 'password';
      }

      if (strpos($value->Field, 'email') !== false) {
        $type = 'email';
      }

      if (strpos($value->Type, 'enum') !== false) {
        $type = 'select-enum';
        $options = self::getEnumValues($value->Type);
      }

      if (strpos($value->Field, 'api_token') !== false) {
        $type = 'hidden';
        $attributes = ['disabled'];
      }

      if (strpos($value->Field, '_id') !== false) {
        $type = 'select';
        $column_name = ucfirst(str_replace('_id', '', $value->Field));
        $model_name = '\App\\Models\\' . $column_name;
        if (class_exists($model_name)) {
          $options = $model_name::all();
        }
      }

      ////////////////////PUT SPECIAL CASES HERE/////////////////////////////
      if ($this->class_name) {
        $className = '\App\\Models\\' . $this->class_name;
        if ($className::set_special_case_fields($value)) {
          return $className::set_special_case_fields($value);
        }
      }

      return [
        'field_name' => $value->Field,
        'data_type' => $type,
        'step' => $number_step,
        'options' => $options,
        'attributes' => $attributes,
      ];

    }, $db_fields);

    $db_fields_filter = array_filter($db_fields_map, function ($value) {
      return $value != null;
    }, ARRAY_FILTER_USE_BOTH);

    return $db_fields_filter;
  }

  /**
   * Explode enum values from DB
   *
   * @param string $type enum string from DB
   *
   * @return array;
   */
  public static function getEnumValues($type) {
    preg_match('/^enum\((.*)\)$/', $type, $matches);
    $enum = [];
    foreach (explode(',', $matches[1]) as $value) {
      $v = trim($value, "'");
      $enum[] = $v;
    }
    return $enum;
  }
}
