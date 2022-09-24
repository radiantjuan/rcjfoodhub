<?php
/**
 * Supplies Controller
 *
 * @author Radiant C. Juan <radiantcjuan@gmail.com>
 *
 * @copyright RCJuan Food Hub 2021
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BreadRoutes;
use App\Models\Supplies;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use App\View\Components\Admin\Bread\Fields;

class SuppliesController extends Controller
{

  /**
   * @var string page title of bread
   */
  const PAGE_TITLE = 'Supplies';

  /**
   * @var object route model
   */
  protected $route_model;

  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
    $this->middleware('auth');
    $this->middleware('check.if.admin');
    $this->route_model = new BreadRoutes('supplies');

    View::share('route_model', $this->route_model);
    View::share('page_title', self::PAGE_TITLE);
    View::share('extractable', true);
    View::share('has_audit_trail', true);
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $data = Supplies::get_all_supplies();
    return view('admin.bread.browse', [
      'data' => $data,
    ]);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    $fields = Supplies::get_all_supply_fields();
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
      'options' => []
    ];

    return view('admin.bread.add-edit', ['fields' => $renderedViews, 'page_sub_title' => 'Add', 'route_options' => $route_options]);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param \Illuminate\Http\Request $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $request->validate([
      'name' => 'required',
      'price' => 'required',
      'gram' => 'required',
      'img_url' => 'mimes:jpg,png|max:2048'
    ]);

    $supplies = new Supplies();
    if ($supplies->store_supply($request)) {
      $request->session()->flash('status', $request->name . ' is successfully added!');
      return redirect($this->route_model->get_route('index'));
    }

    return back();
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param int $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    $fields = Supplies::get_all_supply_fields();
    $renderedViews = [];
    $supply_values = Supplies::get_supply($id);
    foreach ($fields as $field) {
      $BreadFieldsView = new Fields(
        $field["field_name"],
        $field["data_type"],
        $field["step"],
        $field["options"],
        $supply_values->{$field["field_name"]},
        $field["attributes"]
      );
      $renderedViews[] = $BreadFieldsView->render();
    }

    $route_options = [
      'name' => 'edit',
      'options' => ['id' => $id]
    ];

    return view('admin.bread.add-edit', ['fields' => $renderedViews, 'page_sub_title' => 'Edit', 'route_options' => $route_options]);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param \Illuminate\Http\Request $request
   * @param int $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
  {
    //TODO VALIDATION
    $request->validate([
      'name' => 'required',
      'price' => 'required',
      'gram' => 'required',
      'img_url' => 'mimes:jpg,png|max:2048'
    ]);


    Supplies::update_supply($request, $id);
    $request->session()->flash('status', $request->name . ' is successfully updated!');
    return back();
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param int $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    Supplies::destroy($id);
    return back();
  }

  /**
   * Extraction of record
   *
   * @return void
   */
  public function extract_record()
  {
    $supplies = Supplies::with('category')->orderby('stock_count', 'asc')->get();
    header('Content-Type: application/csv');
    header('Content-Disposition: attachment; filename="supplies.csv";');
    $f = fopen('php://output', 'w');
    fputcsv($f, [
      "ID",
      "Name",
      "Category",
      "Gram",
      "Price",
      "Stock Count",
      "Image Url",
      "SKU",
      "Date Created",
      "Date Updated"
    ], ',');
    foreach ($supplies as $sup) {
      fputcsv($f, [
        $sup->id,
        $sup->name,
        $sup->category->name,
        $sup->gram,
        $sup->price,
        $sup->stock_count,
        $sup->img_url,
        $sup->sku,
        date('Y-m-d', strtotime($sup->created_at)),
        date('Y-m-d', strtotime($sup->updated_at)),
      ], ',');
    }
  }
}
