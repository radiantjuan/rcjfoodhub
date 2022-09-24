<?php
/**
 * Bread Routes Model
 *
 * @author Radiant C. Juan <radiantcjuan@gmail.com>
 *
 * @copyright RCJuan Food Hub 2021
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BreadRoutes extends Model {

  /**
   * route prefix for route names e.g. <route_name>.index
   * @var string
   */
  protected $route_prefix;

  /**
   * @param string route prefix
   */
  public function __construct($prefix) {
    $this->route_prefix = $prefix;
  }

  /**
   * generating route dynamically depends on what controller
   * @param string $bread_route
   * @param array $options route params
   * @return string|bool
   */
  public function get_route($bread_route, $options = []) {
    try {
      return route($this->route_prefix . '.' . $bread_route, $options);
    } catch (\Exception$e) {
      return false;
    }
  }

  /**
   * getting the prefix for extraction of record
   * @param
   * 
   */
  public function get_prefix() {
    return $this->route_prefix;
  }
}
