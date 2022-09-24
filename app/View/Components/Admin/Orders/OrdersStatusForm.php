<?php

namespace App\View\Components\Admin\Orders;

use Illuminate\View\Component;

class OrdersStatusForm extends Component
{
    /**
     * @var int order id
     */
    protected $order;

    /**
     * @var string order status
     */
    protected $order_status;

    /**
     * Create a new component instance.
     *
     * @return voids
     */
    public function __construct(\App\Models\Orders $order)
    {
        $this->order_id = $order->id;
        $this->order_status = $order->order_status;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.admin.orders.orders-status-form', ['order_id' => $this->order_id, 'order_status' => $this->order_status]);
    }
}
