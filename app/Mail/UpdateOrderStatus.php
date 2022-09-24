<?php

namespace App\Mail;

use App\Models\Orders;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UpdateOrderStatus extends Mailable {
  use Queueable, SerializesModels;
  /**
   * @var int Order ID
   */
  protected $order_id;

  /**
   * @var int Order Status
   */
  protected $order_status;

  /**
   * Create a new message instance.
   *
   * @return void
   */
  public function __construct($order_id) {
    $Order = Orders::find($order_id);
    $this->order_id = $Order->id;
    $this->order_status = $Order->order_status;
  }

  /**
   * Build the message.
   *
   * @return $this
   */
  public function build() {
    return $this->from('noreply@rcjfoodhub.com.ph')->markdown('emails.orders.status', ['order_id' => $this->order_id, 'order_status' => ($this->order_status == 'COMPLETED' ? 'Complete!' : 'is now being processed')]);
  }
}
