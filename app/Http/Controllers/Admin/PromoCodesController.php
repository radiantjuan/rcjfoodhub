<?php
/**
 * Promo Codes Controller
 *
 * @author Radiant C. Juan <radiantcjuan@gmail.com>
 *
 * @copyright RCJuan Food Hub 2021
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BreadRoutes;
use App\Models\PromoCodes;
use App\View\Components\Admin\Bread\Fields;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class PromoCodesController extends Controller {
  /**
   * @var string page title of bread
   */
  const PAGE_TITLE = 'Promo Codes';

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
    $this->route_model = new BreadRoutes('promo_codes');

    View::share('route_model', $this->route_model);
    View::share('page_title', self::PAGE_TITLE);
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index() {
    $data = PromoCodes::get_all_promo_codes();
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
    $fields = PromoCodes::get_all_promo_codes_fields();
    $renderedViews = [];
    foreach ($fields as $field) {
      $BreadFieldsView = new Fields(
        $field["field_name"],
        $field["data_type"],
        $field["step"],
        $field["options"],
        null,
        $field["attributes"]
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
      'code' => 'required',
      'value' => 'required',
    ]);

    $promo_codes = new PromoCodes();
    if ($promo_codes->store_promo_codes($request)) {
      $request->session()->flash('status', $request->name . ' is successfully added!');
      return redirect($this->route_model->get_route('index'));
    }

    $request->session()->flash('status', $request->name . ' is successfully added!');
    return back();
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id) {
    $fields = PromoCodes::get_all_promo_codes_fields();
    $renderedViews = [];
    $promo_codes_values = PromoCodes::get_promo_codes($id);
    foreach ($fields as $field) {
      $BreadFieldsView = new Fields(
        $field["field_name"],
        $field["data_type"],
        $field["step"],
        $field["options"],
        $promo_codes_values->{$field["field_name"]},
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
      'code' => 'required',
      'value' => 'required',
    ]);

    PromoCodes::update_promo_codes($request, $id);
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
    PromoCodes::destroy($id);
    return back();
  }

  /**
   * Activate or deactivate
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function set_status($id) {
    $promo_codes = PromoCodes::find($id);
    //deactivate
    if (!$promo_codes->is_inactive) {
      $promo_codes->is_inactive = true;
    } else {
      $promo_codes->is_inactive = false;
    }
    $promo_codes->update();
    return back();
  }
}
