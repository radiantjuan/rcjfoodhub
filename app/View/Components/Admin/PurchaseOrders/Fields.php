<?php
/**
 * Purchase Orders Fields Components
 *
 * @author Radiant C. Juan <radiantcjuan@gmail.com>
 *
 * @copyright RCJuan Food Hub 2021
 */

namespace App\View\Components\Admin\PurchaseOrders;

use Illuminate\View\Component;

class Fields extends Component {
  /**
   * Name of the field
   * @var string
   */
  protected $name;

  /**
   * Data Type of the field (e.g. text, number, etc.)
   * @var string
   */
  protected $dataType;

  /**
   * If the datatype is number or float this needs to be applied in the step
   * @var float
   */
  protected $step;

  /**
   * if datatype is select these are the options
   * @var float
   */
  protected $options;

  /**
   * if on edit mode, here will be the defaul value
   * @var float
   */
  protected $value;

  /**
   * attributes of html element
   * @var float
   */
  protected $attr;

  /**
   * Create a new component instance.
   *
   * @return void
   */
  public function __construct($name, $dataType, $step, $options, $value, $attr = null) {
    $this->name = $name;
    $this->dataType = $dataType;
    $this->step = $step;
    $this->options = $options;
    $this->value = $value;
    $this->attr = $attr;
  }

  /**
   * Get the view / contents that represent the component.
   *
   * @return \Illuminate\Contracts\View\View|\Closure|string
   */
  public function render() {
    return view('components.admin.purchase-orders.fields', [
      'name' => $this->name,
      'data_type' => $this->dataType,
      'step' => $this->step,
      'options' => $this->options,
      'default_value' => $this->value,
      'attr' => $this->attr,
    ]);
  }
}
