<?php
/**
 * Audit Trail Model
 *
 * @author Radiant C. Juan <radiantcjuan@gmail.com>
 *
 * @copyright RCJuan Food Hub 2021
 */

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AuditTrail extends Model {
  /**
   * Save audit trail
   *
   * @return void
   */
  public function save_audit_trail($associated_id, $model, $change_log_message, $previous_values, $new_values) {
    $this->associated_id = $associated_id;
    $this->model = $model;
    $this->user_id = Auth::user()->id;
    $this->change_log_message = $change_log_message;
    $this->previous_values = $previous_values;
    $this->new_values = $new_values;
    $this->save();
  }

  /**
   * get audit trail change logs
   *
   * @param string $model
   *
   * @return array
   */
  public static function get_change_logs($model) {
    $result = self::where('model',$model)->where('created_at', '>=', date('Y-m-d H:i:s', strtotime('-1 months')))->orderby('created_at', 'DESC')->get()->toArray();
    return $result;
  }
}
