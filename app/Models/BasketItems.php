<?php
/**
 * Basket Items Model
 *
 * @author Radiant C. Juan <radiantcjuan@gmail.com>
 *
 * @copyright RCJuan Food Hub 2021
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BasketItems extends Model {

  /**
   * @var int current user ID
   */
  protected $p_user_id;

  /**
   * @var int current franchise ID
   */
  protected $p_franchise_id;

  /**
   * Basket Items construct setting up current user credentials
   */
  public function __construct() {
    $user = Auth::user();
    $this->p_user_id = $user->id;
    $this->p_franchise_id = $user->franchisees_id;
  }

  /**
   * Save cart information to DB
   *
   * @param \Illuminate\Http\Request $request http request data
   *
   * @return void
   */
  public function save_basket_items(Request $request) {
    $this->user_id = $this->p_user_id;
    $this->franchise_id = $this->p_franchise_id;
    $this->cart_total = !empty($request->cart_total) ? $request->cart_total : NULL;
    $this->cart_items = !empty($request->cart_items) ? $request->cart_items : NULL;
    $this->promo_code_setup = !empty($request->promo_code_setup) ? $request->promo_code_setup : NULL;
    $this->is_active = true;
    $this->save();
  }

  /**
   * update basket items if there is an existing items in the users cart
   *
   * @param \Illuminate\Http\Request $request http request data
   *
   * @return void
   */
  public function update_basket_items(Request $request) {
    $basket = self::where('user_id', $this->p_user_id)->where('franchise_id', $this->p_franchise_id)->where('is_active', true)->first();
    $update = self::find($basket->id);
    $update->cart_total = !empty($request->cart_total) ? $request->cart_total : NULL;
    $update->cart_items = !empty($request->cart_items) ? $request->cart_items : NULL;
    $update->promo_code_setup = !empty($request->promo_code_setup) ? $request->promo_code_setup : NULL;
    $update->update();
  }

  /**
   * fetching all items in the basket
   *
   * @return collection
   */
  public function get_all_basket_items() {
    $basket_items = self::where('user_id', $this->p_user_id)->where('franchise_id', $this->p_franchise_id)->where('is_active', true)->first();
    $supply_oos = Supplies::where('out_of_stock', 1)->get('id');
    $supply_oos_map = [];

    //check if one of items is oos;
    if (!$supply_oos->isEmpty()) {
      $supply_oos_map = $supply_oos->map(function ($oos) {
        return $oos->id;
      })->toArray();

      $basket_items_json = !empty($basket_items->cart_items) ? json_decode($basket_items->cart_items) : [];
      $filter_oos = [];
      if (!empty($supply_oos_map) && !empty($basket_items_json)) {
        $filter_oos = array_filter($basket_items_json, function ($val) use ($supply_oos_map) {
          return !in_array($val->id, $supply_oos_map);
        });
      }
      if (!empty($filter_oos)) {
        $basket_items->cart_items = json_encode(array_values($filter_oos));
      }
    }
    return $basket_items;
  }

  /**
   * check if bakset is active
   *
   * @return bool
   */
  public function check_if_basket_active() {
    $basket_count = self::where('user_id', $this->p_user_id)->where('franchise_id', $this->p_franchise_id)->where('is_active', true)->count();
    return ($basket_count > 0);
  }

  public function deactivate_basket() {
    $basket = self::where('user_id', $this->p_user_id)->where('franchise_id', $this->p_franchise_id)->where('is_active', true)->first();
    $deactivate = self::find($basket->id);
    $deactivate->is_active = false;
    $deactivate->update();
  }
}
