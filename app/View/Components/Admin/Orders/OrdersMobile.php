<?php
/**
 * Orders Mobile View Comoonent
 *
 * @author Radiant C. Juan <radiantcjuan@gmail.com>
 *
 * @copyright RCJuan Food Hub 2021
 */

namespace App\View\Components\Admin\Orders;

use Illuminate\View\Component;

class OrdersMobile extends Component {
  /**
   * Create a new component instance.
   *
   * @return void
   */
  public function __construct($data, $headers) {
    $this->headers = $headers;
    $this->data = $data;
  }

  /**
   * Get the view / contents that represent the component.
   *
   * @return \Illuminate\Contracts\View\View|\Closure|string
   */
  public function render() {
    return view('components.admin.orders.orders-mobile', ['data' => $this->data, 'headers' => $this->headers]);
  }
}
