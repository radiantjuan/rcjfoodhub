<?php
/**
 * Layouts Component for admin
 *
 * @author Radiant C. Juan <radiantcjuan@gmail.com>
 *
 * @copyright RCJuan Food Hub 2021
 */

namespace App\View\Components\Admin;

use Illuminate\View\Component;

class Layout extends Component
{
    /**
     * @var bool react indicator
     */
    protected $is_react;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($isReact = false)
    {
        $this->is_react = $isReact;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.admin.layout',['is_react' => $this->is_react]);
    }
}
