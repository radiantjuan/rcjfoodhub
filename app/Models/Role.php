<?php
/**
 * Role Model
 *
 * @author Radiant C. Juan <radiantcjuan@gmail.com>
 *
 * @copyright RCJuan Food Hub 2021
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model {
  use HasFactory;

  /**
   * finding role by role id
   *
   * @param int
   *
   * @return string|bool
   */
  public static function find_role_by_id($id) {

    $role = self::find($id);
    if ($role) {
      return $role->name;
    }

    return false;
  }
}
