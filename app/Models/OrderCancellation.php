<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderCancellation extends Model {
    /**
     * cancel order
     * 
     * @param int $order_id
     * @param string $reason
     * 
     * @return void
     */
    public function cancel_order($order_id, $reason) {
        $this->order_id = $order_id;
        $this->reason_for_cancellation = $reason;
        $this->save();
    }
}
