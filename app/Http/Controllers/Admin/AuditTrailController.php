<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditTrail;
use App\Models\User;
use Illuminate\Http\Request;

class AuditTrailController extends Controller
{
    /**
     * Get all audit trail logs
     *
     * @param Request $request
     *
     * @return array
     */
    public function get_audit_trail_logs(Request $request)
    {
        $change_logs_model = AuditTrail::get_change_logs($request->model);
        $change_logs = array_map(function ($value) {
            //find associated id
            $className = '\App\\Models\\' . ucfirst($value['model']);
            $model_result = $className::find($value['associated_id']);
            $user = User::find($value['user_id']);

            $previous_values = json_decode($value['previous_values'], true);
            $next_values = json_decode($value['new_values'], true);

            $changes = [];
            foreach ($previous_values as $fields => $previous_value) {
                if ($fields == 'updated_at') {
                    continue;
                }
                if (isset($next_values[$fields])) {
                    $changes[$fields] = [
                        'previous' => $previous_value,
                        'new_value' => $next_values[$fields],
                    ];
                }
            }

            $item_changed = $model_result->name;
            if ($value['model'] == 'orders') {
                $item_changed = $model_result->order_id;
            }

            return [
                'item_changed' => $item_changed,
                'user' => $user->email,
                'message' => $value['change_log_message'],
                'date_of_change' => date('Y-m-d H:i:s', strtotime($value['created_at'])),
                'changes' => $changes,
            ];
        }, $change_logs_model);
        return $change_logs;
    }
}
