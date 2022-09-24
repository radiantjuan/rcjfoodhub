<?php
/**
 * Admin Modal Component
 *
 * @author Radiant C. Juan <radiantcjuan@gmail.com>
 *
 * @copyright RCJuan Food Hub 2021
 */

namespace App\View\Components\Admin\Bread;

use Illuminate\View\Component;

class Modal extends Component {
  /**
   * Create a new component instance.
   *
   * @return void
   */
  public function __construct() {
    //
  }

  /**
   * Get the view / contents that represent the component.
   *
   * @return \Illuminate\Contracts\View\View|\Closure|string
   */
  public function render() {
    return view('components.admin.bread.modal');
  }
}
