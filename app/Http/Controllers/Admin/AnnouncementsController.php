<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\BreadRoutes;
use App\View\Components\Admin\Bread\Fields;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class AnnouncementsController extends Controller {
  /**
   * @var string page title of bread
   */
  const PAGE_TITLE = 'Announcement';

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
    $this->route_model = new BreadRoutes('announcements');

    View::share('route_model', $this->route_model);
    View::share('page_title', self::PAGE_TITLE);
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index() {
    $data = Announcement::get_all_announcements();
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
    $fields = Announcement::get_all_announcements_fields();
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

    $request->validate([
      'title' => 'required',
      'content' => 'required',
      'img_url' => 'required'
    ]);

    $announcements = new Announcement();
    if ($announcements->store_announcements($request)) {
      $request->session()->flash('status', $request->name . ' is successfully added!');
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
    $fields = Announcement::get_all_announcements_fields();
    $renderedViews = [];
    $announcements_values = Announcement::get_announcements($id);
    foreach ($fields as $field) {
      $BreadFieldsView = new Fields(
        $field["field_name"],
        $field["data_type"],
        $field["step"],
        $field["options"],
        $announcements_values->{$field["field_name"]},
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
    $request->validate([
      'title' => 'required',
      'content' => 'required'
    ]);
    Announcement::update_announcements($request, $id);
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
    Announcement::destroy($id);
    return back();
  }

  /**
   * Get announcements for dashboard
   *
   * @param int $id
   *
   * @return array
   */
  public function get_announcements($id) {
    return Announcement::get_announcements($id);
  }
}
