<?php

namespace App\Http\Controllers\Franchisee;

use App\Http\Controllers\Controller;
use App\Mail\NewOrder;
use App\Models\BasketItems;
use App\Models\Orders;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ShippingController extends Controller {
  /**
   * Show Billing page
   */
  public function index() {
    return view('app.shop.shipping');
  }

  /**
   * Save order
   * @param Request $request
   * @return void
   */
  public function save_order(Request $request) {
    if ($request->shipping_option == 'pickup' || $request->shipping_option == 'deliver_to_branch') {
      $validation = [];
      if ($request->payment_method == 'bank_transfer') {
        $validation['proof_of_payment'] = ['required', 'mimes:jpg,png', 'max:2048'];
        $validation['payment_transaction_number'] = ['required'];
      }
      $request->validate($validation);
    } else {
      $validation = [
        'shipping_address_1' => 'required',
        'shipping_city' => 'required',
        'shipping_barangay' => 'required',
        'shipping_zip_code' => 'required',
      ];
      if ($request->payment_method == 'bank_transfer') {
        $validation['proof_of_payment'] = ['mimes:jpg,png', 'max:2048'];
        $validation['payment_transaction_number'] = ['required'];
      }
      $request->validate($validation);
    }

    $order = new Orders();
    $order_id = $order->store_orders($request);
    $basket_items = new BasketItems();
    $basket_items->deactivate_basket();
  
    // Mail::to('radiantcjuan@gmail.com')->send(new NewOrder($order_id));

    return redirect(route('thankyou', ['order_id' => $order_id]));
  }

  public function thank_you_page($order_id) {
    $Order = Orders::where('order_id', $order_id)->first();
    $ordered_items = json_decode($Order->ordered_items);
    $order_total = json_decode($Order->order_total);
    $promo_setup = json_decode($Order->promo_code_setup);

    return view('app.shop.thank-you', [
      'order' => $Order,
      'ordered_items' => $ordered_items,
      'order_total' => $order_total,
      'promo_code' => $promo_setup
    ]);
  }
}
