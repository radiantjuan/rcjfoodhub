<?php

namespace App\Mail;

use App\Models\Orders;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewOrder extends Mailable {
  use Queueable, SerializesModels;

  /**
   * @var int Order ID
   */
  protected $order_id;

  /**
   * Create a new message instance.
   *
   * @return void
   */
  public function __construct($order_id) {
    $Order = Orders::where('order_id', $order_id)->first();
    $this->order_id = $Order->id;
  }

  /**
   * Build the message.
   *
   * @return $this
   */
  public function build() {
    return $this->from('noreply-new-order@rcjfoodhub.com.ph')->markdown('emails.orders.new',['order_id'=>$this->order_id]);
  }
}
