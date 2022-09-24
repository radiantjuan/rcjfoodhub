<?php
/**
 * Franchisee Controller
 *
 * @author Radiant C. Juan <radiantcjuan@gmail.com>
 *
 * @copyright RCJuan Food Hub 2021
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BreadRoutes;
use App\Models\Franchisees;
use App\View\Components\Admin\Bread\Fields;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class FranchiseeController extends Controller {
  /**
   * @var string page title of bread
   */
  const PAGE_TITLE = 'Branches';

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
    $this->route_model = new BreadRoutes('franchisees');
    View::share('route_model', $this->route_model);
    View::share('page_title', self::PAGE_TITLE);
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index() {
    $data = Franchisees::get_all_franchisees();
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
    $fields = Franchisees::get_all_franchisees_fields();
    $renderedViews = [];
    foreach ($fields as $field) {
      $BreadFieldsView = new Fields(
        $field["field_name"],
        $field["data_type"],
        $field["step"],
        $field["options"],
        null
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

    $franchisees = new Franchisees();
    if ($franchisees->store_franchisees($request)) {
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
    $fields = Franchisees::get_all_franchisees_fields();
    $renderedViews = [];
    $franchisees_values = Franchisees::get_franchisees($id);
    foreach ($fields as $field) {
      $BreadFieldsView = new Fields(
        $field["field_name"],
        $field["data_type"],
        $field["step"],
        $field["options"],
        $franchisees_values->{$field["field_name"]}
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
    Franchisees::update_franchisees($request, $id);
    $request->session()->flash('status', $request->name . ' is successfully updated!');
    return back();
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id) {
    Franchisees::destroy($id);
    return back();
  }

  /**
   * Activate or deactivate
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function set_status($id) {
    $franchisee = Franchisees::find($id);
    //deactivate
    if (!$franchisee->is_inactive) {
      $franchisee->is_inactive = true;
    } else {
      $franchisee->is_inactive = false;
    }
    $franchisee->update();
    return back();
  }
}
