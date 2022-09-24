<?php

namespace App\Http\Controllers\Franchisee;

use App\Http\Controllers\Controller;
use App\Models\BasketItems;
use App\Models\Categories;
use App\Models\PromoCodes;
use App\Models\Supplies;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ShopController extends Controller {

  /**
   * Init
   */
  public function __construct() {
    $this->middleware('auth');
  }

  /**
   * e-commerce index
   *
   * @param Request $request http request
   *
   * @return mixed
   */
  public function index(Request $request) {
    return view('app.shop.shop-supplies', ['auth_token' => (Auth::user()->tokens->isNotEmpty()) ? Auth::user()->api_token : '']);
  }

  /**
   * Generates supply API
   *
   * @return array
   */
  public function get_supplies() {
    $supplies = Supplies::get_supply_list_store();
    $suppliesMapped = $supplies->map(function ($value) {
      return [
        'id' => $value->id,
        'name' => $value->name,
        'price' => $value->price,
        'gram' => $value->gram,
        'img_url' => ($value->img_url) ? Storage::url($value->img_url) : 'https://via.placeholder.com/150',
        'categories_name' => $value->category->name,
        'categories_id' => $value->categories_id,
        'stock_count' => $value->stock_count,
        'product_franchise' => json_decode($value->product_franchise_category),
      ];
    });
    return $suppliesMapped;
  }

  /**
   * Generates API category data
   *
   * @return array
   */
  public function get_categories() {
    $categories = Categories::all();
    $categoriesMapped = $categories->map(function ($value) {
      return [
        'id' => $value->id,
        'name' => $value->name,
      ];
    });
    return $categoriesMapped;
  }

  /**
   * Add to cart event
   *
   * @param Request $request
   *
   * @return array
   */
  public function add_to_cart(Request $request) {
    return $this->manage_supply_info($request->id, $request->quantity);
  }

  /**
   * Update Basket items in DB
   *
   * @param Request $request
   *
   * @return array
   */
  public function update_basket_items(Request $request) {
    $basket_items = new BasketItems();
    if (!$basket_items->check_if_basket_active()) {
      $basket_items->save_basket_items($request);
    } else {
      $basket_items->update_basket_items($request);
    }
    return ['success' => true];
  }

  /**
   * get all items in the basket
   * 
   * @return collection;
   */
  public function get_basket_items() {
    $basket_items = new BasketItems();
    return $basket_items->get_all_basket_items();
  }

  /**
   * Generates supply data for API
   *
   * @param int $id supply ID
   * @param int $quantity quantity got from the front-end
   *
   * @return array
   */
  private function manage_supply_info($id, $quantity) {
    $supply = Supplies::get_supply($id);
    $total_cost = $quantity * $supply->price;
    return [
      'id' => $supply->id,
      'title' => $supply->name,
      'grams' => $supply->gram,
      'price' => $supply->price,
      'quantity' => $quantity,
      'img_url' => !empty($supply->img_url) ? Storage::url($supply->img_url) : '',
      'total_cost' => $total_cost,
    ];
  }

  /**
   * Promo code event on click
   *
   * @return array
   */
  public function apply_promo_code(Request $request) {
    $user = Auth::user();
    $promo_code = PromoCodes::where('code', $request->promo_code)->first();
    if ($promo_code) {
      //check eligibility
      $is_promo_code_legit = $this->check_promo_code_eligibility($user, $promo_code);
      if ($is_promo_code_legit) {
        if (!empty($request->cart_items)) {
          return $this->generate_promo_code_setup($request->cart_items, $promo_code);
        } else {
          throw new \Exception('Input items first', 403);
        }
      }
    }
    throw new \Exception('Promo Code Doesn\'t Exist', 403);
  }

  /**
   * Eligibility Check if user is legible to use the promo code
   *
   * @param User $user current logged in user
   * @param PromoCode $promo_code queried promocode collection
   *
   * @return bool
   */
  protected function check_promo_code_eligibility($user, $promo_code) {
    $is_legit = false;

    $franchisees = ($promo_code->franchisees) ? json_decode($promo_code->franchisees) : false;
    $user_id = $user->franchisees_id;
    //check if for franchisee
    if (in_array($user_id, $franchisees)) {
      //check if limited use
      if ($promo_code->is_limited && $promo_code->number_of_use > 0) {
        $number_of_use_per_branch = json_decode($promo_code->number_of_use_per_branch);
        $filter_branch = array_filter($number_of_use_per_branch, function ($val) use ($user_id) {
          return $val->id == $user_id;
        }, ARRAY_FILTER_USE_BOTH);

        if (!empty($filter_branch[key($filter_branch)])) {
          if ($filter_branch[key($filter_branch)]->number_of_use > 0) {
            $is_legit = true;
          }
        }
      }

      //check if it has end date
      if ($promo_code->use_end_date && strtotime('now') <= strtotime($promo_code->end_date)) {
        $is_legit = true;
      }

      if (!$promo_code->is_limited && !$promo_code->use_end_date) {
        $is_legit = true;
      }
    }

    return $is_legit;
  }

  /**
   * Generates promocode setup for calculation in the front-end
   *
   * @return array
   */
  protected function generate_promo_code_setup($cart_items, $promo_code) {
    $promo_code_setup = [
      'id' => $promo_code->id,
      'name' => $promo_code->code,
      'coverage' => [],
      'coverage_type' => $promo_code->coverage,
    ];

    if ($promo_code->coverage == PromoCodes::PROMO_COVERAGE_ALL_ITEMS) {
      $promo_code_setup['type'] = $promo_code->type;
      $promo_code_setup['coverage'][] = [
        'id' => 0,
        'value' => $promo_code->value,
      ];

      $promo_code_setup['promo_value'] = $promo_code->value;
      if (!empty($promo_code->items_exception)) {
        $items_exception = json_decode($promo_code->items_exception);
        if (!empty($items_exception)) {
          foreach ($cart_items as $cart_item) {
            if (in_array($cart_item['id'], $items_exception)) {
              $promo_code_setup['items_exception'][] = [
                'id' => $cart_item['id'],
                'title' => $cart_item['title'],
                'value' => $cart_item['total_cost'],
              ];
            }
          }
        }
      }
    } else if ($promo_code->coverage == PromoCodes::PROMO_COVERAGE_INDIVIDUAL) {
      $item_list = json_decode($promo_code->items_list);
      foreach ($cart_items as $cart_item) {
        if (in_array($cart_item['id'], $item_list)) {
          $promo_code_setup['coverage'][] = [
            'id' => $cart_item['id'],
            'value' => $promo_code->value,
          ];
        }
      }
      $promo_code_setup['type'] = $promo_code->type;
    }

    return $promo_code_setup;

  }
}
