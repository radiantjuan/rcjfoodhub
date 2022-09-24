<?php

use App\Http\Controllers\Admin\ReportsController;
use App\Http\Controllers\Franchisee\ShopController;
use App\Http\Controllers\Franchisee\TokenController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('franchisee')->group(function(){
    Route::get('/supplies/list', [ShopController::class, 'get_supplies'])->middleware('auth:api');
    Route::get('/categories/list', [ShopController::class, 'get_categories'])->middleware('auth:api');
    Route::post('/add-to-cart', [ShopController::class, 'add_to_cart'])->middleware('auth:api');
    Route::post('/update-basket-items', [ShopController::class, 'update_basket_items'])->middleware('auth:api');
    Route::get('/get-basket-items', [ShopController::class, 'get_basket_items'])->middleware('auth:api');
    Route::post('/apply-promo-code', [ShopController::class, 'apply_promo_code'])->middleware('auth:api');
});

Route::prefix('reports')->group(function(){
//    Route::get('/sales_graph/{start_date}/{type}', [ReportsController::class, 'sales_graph'])->middleware('auth:api');
    Route::get('/get_overall_sales_report', [ReportsController::class, 'get_overall_sales_report'])->middleware('auth:api');
    Route::get('/supplies_report/get_top_ten_supplies', [ReportsController::class, 'get_top_ten_purchased_supplies'])->middleware('auth:api');
    Route::get('/supplies_report/get_overall_supplies_ordered', [ReportsController::class, 'get_overall_supplies_ordered'])->middleware('auth:api');
});
