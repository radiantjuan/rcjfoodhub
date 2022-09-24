<?php
/**
 * Users Controller
 *
 * @author Radiant C. Juan <radiantcjuan@gmail.com>
 *
 * @copyright RCJuan Food Hub 2021
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BreadRoutes;
use App\Models\User as Users;
use App\View\Components\Admin\Bread\Fields;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Laravel\Sanctum\PersonalAccessToken;

class UsersController extends Controller {
  /**
   * @var string page title of bread
   */
  const PAGE_TITLE = 'Users';

  /**
   * @var object route model
   */
  protected $route_model;

  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct() {
    $this->middleware('auth');
    $this->middleware('check.if.admin');
    $this->middleware('check.if.moderator');
    $this->route_model = new BreadRoutes('users');

    View::share('route_model', $this->route_model);
    View::share('page_title', self::PAGE_TITLE);
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index() {
    $data = Users::get_all_users();
    return view('admin.bread.browse', [
      'data' => $data,
    ]);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create() {
    $fields = Users::get_all_users_fields();
    $renderedViews = [];
    foreach ($fields as $field) {
      $BreadFieldsView = new Fields(
        $field["field_name"],
        $field["data_type"],
        $field["step"],
        $field["options"],
        null,
        $field["attributes"],
      );
      $renderedViews[] = $BreadFieldsView->render();
    }

    $route_options = [
      'name' => 'add',
      'options' => [],
    ];

    return view('admin.bread.add-edit', ['fields' => $renderedViews, 'page_sub_title' => 'Add', 'route_options' => $route_options]);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request) {
    //TODO VALIDATION
    $request->validate([
      'name' => 'required',
      'email' => 'required',
      'password' => 'required',
    ]);
    $users = new Users();
    if ($users->store_users($request)) {
      $request->session()->flash('status', $request->name.' is successfully added!');
      return redirect($this->route_model->get_route('index'));
    }
    return back();
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id) {
    $fields = Users::get_all_users_fields();
    $renderedViews = [];
    $users_values = Users::get_users($id);
    foreach ($fields as $field) {
      $BreadFieldsView = new Fields(
        $field["field_name"],
        $field["data_type"],
        $field["step"],
        $field["options"],
        (($field["field_name"] == 'password') ? '' : $users_values->{$field["field_name"]}),
        $field["attributes"]
      );
      $renderedViews[] = $BreadFieldsView->render();
    }

    $route_options = [
      'name' => 'edit',
      'options' => ['id' => $id],
    ];

    return view('admin.bread.add-edit', ['fields' => $renderedViews, 'page_sub_title' => 'Edit', 'route_options' => $route_options]);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id) {
    //TODO VALIDATION
    $request->validate([
      'name' => 'required',
      'email' => 'required',
      'password' => 'required',
    ]);
    
    Users::update_users($request, $id);
    $request->session()->flash('status', $request->name.' is successfully updated!');
    return back();
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id) {
    $token_id = PersonalAccessToken::where('tokenable_id', $id)->first();
    PersonalAccessToken::destroy($token_id->id);
    Users::destroy($id);
    return back();
  }

    /**
   * Activate or deactivate
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function set_status($id) {
    $user = Users::find($id);
    //deactivate
    if (!$user->is_inactive) {
      $user->is_inactive = true;
    } else {
      $user->is_inactive = false;
    }
    $user->update();
    return back();
  }
}
