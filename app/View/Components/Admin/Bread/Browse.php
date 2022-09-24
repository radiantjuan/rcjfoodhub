<?php
/**
 * Admin Browse Component
 *
 * @author Radiant C. Juan <radiantcjuan@gmail.com>
 *
 * @copyright RCJuan Food Hub 2021
 */

namespace App\View\Components\Admin\Bread;

use Illuminate\View\Component;

class Browse extends Component {

/**
 * @var array TH from table
 */
  protected $headers;

/**
 * @var array rows from table
 */
  protected $data;

  /**
   * Create a new component instance.
   * @param array $data
   * @param array $headers
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
    return view('components.admin.bread.browse', ['data' => $this->data, 'headers' => $this->headers]);
  }
}
