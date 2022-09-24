<?php
/**
 * Image Fetching Component
 *
 * @author Radiant C. Juan <radiantcjuan@gmail.com>
 *
 * @copyright RCJuan Food Hub 2021
 */

namespace App\View\Components;

use Illuminate\View\Component;

class Image extends Component {

  protected $image_url;

  /**
   * Create a new component instance.
   *
   * @return void
   */
  public function __construct($image_url) {
    //
    $this->image_url = $image_url;
  }

  /**
   * Get the view / contents that represent the component.
   *
   * @return \Illuminate\Contracts\View\View|\Closure|string
   */
  public function render() {
    return view('components.image', ['data' => $this->image_url]);
  }
}
