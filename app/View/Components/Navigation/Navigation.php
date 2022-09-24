<?php
/**
 * Navigation Component
 *
 * @author Radiant C. Juan <radiantcjuan@gmail.com>
 *
 * @copyright RCJuan Food Hub 2021
 */

namespace App\View\Components\Navigation;

use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;
use App\View\Components\Navigation\FranchiseesNavigation;
use App\View\Components\Navigation\AdminNavigation;

class Navigation extends Component
{

    /**
     * serves the navigation component
     * @var Component
     */
    protected $navigation;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
        $user = Auth::user();
        $role_name = Role::find_role_by_id($user->role_id);
        if($role_name !== 'admin' && $role_name !== 'moderator') {
            $navigation = new FranchiseesNavigation();
            $this->navigation = $navigation->render();
        } else {
            if($role_name != 'admin') {
                $navigation = new ModeratorNavigation();
                $this->navigation = $navigation->render();
             
            } else {
                $navigation = new AdminNavigation();
                $this->navigation = $navigation->render();
            }

        }
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.navigation.navigation',['navigation' => $this->navigation]);
    }
}
