<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Route::get('/', [App\Http\Controllers\HomePageController::class,'show_home_page'])->name('webfront.home');

/**
 * Admin Routes
 */
Route::prefix('admin')->group(function () {
  // Route::get('', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
  Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard')->middleware('check.if.inactive');

  /**
   * Supplies Routes
   */
  Route::get('/supplies', [App\Http\Controllers\Admin\SuppliesController::class, 'index'])->name('supplies.index');
  Route::get('/supplies/create', [App\Http\Controllers\Admin\SuppliesController::class, 'create'])->name('supplies.add');
  Route::post('/supplies/create', [App\Http\Controllers\Admin\SuppliesController::class, 'store'])->name('supplies.add');
  Route::get('/supplies/{id}/edit', [App\Http\Controllers\Admin\SuppliesController::class, 'edit'])->name('supplies.edit');
  Route::put('/supplies/{id}/edit', [App\Http\Controllers\Admin\SuppliesController::class, 'update'])->name('supplies.edit');
  Route::delete('/supplies/{id}/delete', [App\Http\Controllers\Admin\SuppliesController::class, 'destroy'])->name('supplies.delete');
  Route::post('/supplies/extract-record', [App\Http\Controllers\Admin\SuppliesController::class, 'extract_record'])->name('supplies.extract_record');
  /**
   * Purchase Orders Routes
   */
  Route::get('/purchase_orders', [App\Http\Controllers\Admin\PuchaseOrdersController::class, 'index'])->name('purchase_orders.index');
  Route::get('/purchase_orders/create', [App\Http\Controllers\Admin\PuchaseOrdersController::class, 'create'])->name('purchase_orders.add');
  Route::post('/purchase_orders/create', [App\Http\Controllers\Admin\PuchaseOrdersController::class, 'store'])->name('purchase_orders.add');
  Route::get('/purchase_orders/{id}/edit', [App\Http\Controllers\Admin\PuchaseOrdersController::class, 'edit'])->name('purchase_orders.edit');
  Route::put('/purchase_orders/{id}/edit', [App\Http\Controllers\Admin\PuchaseOrdersController::class, 'update'])->name('purchase_orders.edit');
  Route::delete('/purchase_orders/{id}/delete', [App\Http\Controllers\Admin\PuchaseOrdersController::class, 'destroy'])->name('purchase_orders.delete');
  Route::put('/purchase_orders/{id}/cancel-purchase-order', [App\Http\Controllers\Admin\PuchaseOrdersController::class, 'cancel_purchase_order'])->name('purchase_orders.cancel_purchase_order');
  /**
   * Categories Routes
   */
  Route::get('/categories', [App\Http\Controllers\Admin\CategoriesController::class, 'index'])->name('categories.index');
  Route::get('/categories/create', [App\Http\Controllers\Admin\CategoriesController::class, 'create'])->name('categories.add');
  Route::post('/categories/create', [App\Http\Controllers\Admin\CategoriesController::class, 'store'])->name('categories.add');
  Route::get('/categories/{id}/edit', [App\Http\Controllers\Admin\CategoriesController::class, 'edit'])->name('categories.edit');
  Route::put('/categories/{id}/edit', [App\Http\Controllers\Admin\CategoriesController::class, 'update'])->name('categories.edit');
  Route::delete('/categories/{id}/delete', [App\Http\Controllers\Admin\CategoriesController::class, 'destroy'])->name('categories.delete');

  /**
   * Promo Codes Routes
   */
  Route::get('/promo_codes', [App\Http\Controllers\Admin\PromoCodesController::class, 'index'])->name('promo_codes.index');
  Route::get('/promo_codes/create', [App\Http\Controllers\Admin\PromoCodesController::class, 'create'])->name('promo_codes.add');
  Route::post('/promo_codes/create', [App\Http\Controllers\Admin\PromoCodesController::class, 'store'])->name('promo_codes.add');
  Route::get('/promo_codes/{id}/edit', [App\Http\Controllers\Admin\PromoCodesController::class, 'edit'])->name('promo_codes.edit');
  Route::put('/promo_codes/{id}/edit', [App\Http\Controllers\Admin\PromoCodesController::class, 'update'])->name('promo_codes.edit');
  Route::delete('/promo_codes/{id}/delete', [App\Http\Controllers\Admin\PromoCodesController::class, 'destroy'])->name('promo_codes.delete');
  Route::get('/promo_codes/{id}/set-status', [App\Http\Controllers\Admin\PromoCodesController::class, 'set_status'])->name('promo_codes.set_status');
  /**
   * Franchisees Routes
   */
  Route::get('/franchisees', [App\Http\Controllers\Admin\FranchiseeController::class, 'index'])->name('franchisees.index');
  Route::get('/franchisees/create', [App\Http\Controllers\Admin\FranchiseeController::class, 'create'])->name('franchisees.add');
  Route::post('/franchisees/create', [App\Http\Controllers\Admin\FranchiseeController::class, 'store'])->name('franchisees.add');
  Route::get('/franchisees/{id}/edit', [App\Http\Controllers\Admin\FranchiseeController::class, 'edit'])->name('franchisees.edit');
  Route::put('/franchisees/{id}/edit', [App\Http\Controllers\Admin\FranchiseeController::class, 'update'])->name('franchisees.edit');
  Route::delete('/franchisees/{id}/delete', [App\Http\Controllers\Admin\FranchiseeController::class, 'destroy'])->name('franchisees.delete');
  Route::get('/franchisees/{id}/set-status', [App\Http\Controllers\Admin\FranchiseeController::class, 'set_status'])->name('franchisees.set_status');
  /**
   * Users Routes
   */
  Route::get('/users', [App\Http\Controllers\Admin\UsersController::class, 'index'])->name('users.index');
  Route::get('/users/create', [App\Http\Controllers\Admin\UsersController::class, 'create'])->name('users.add');
  Route::post('/users/create', [App\Http\Controllers\Admin\UsersController::class, 'store'])->name('users.add');
  Route::get('/users/{id}/edit', [App\Http\Controllers\Admin\UsersController::class, 'edit'])->name('users.edit');
  Route::put('/users/{id}/edit', [App\Http\Controllers\Admin\UsersController::class, 'update'])->name('users.edit');
  Route::delete('/users/{id}/delete', [App\Http\Controllers\Admin\UsersController::class, 'destroy'])->name('users.delete');
  Route::get('/users/{id}/set-status', [App\Http\Controllers\Admin\UsersController::class, 'set_status'])->name('users.set_status');
  // Route::put('/users/{id}/active-inactive', [App\Http\Controllers\Admin\UsersController::class, 'update'])->name('users.edit');

  /**
   * Orders Routes
   */
  Route::get('/orders', [App\Http\Controllers\Admin\OrdersController::class, 'index'])->name('orders.index');
  Route::get('/orders/{id}/edit', [App\Http\Controllers\Admin\OrdersController::class, 'edit'])->name('orders.edit');
  Route::put('/orders/{id}/edit', [App\Http\Controllers\Admin\OrdersController::class, 'update'])->name('orders.edit');
  Route::delete('/orders/{id}/delete', [App\Http\Controllers\Admin\OrdersController::class, 'destroy'])->name('orders.delete');
  Route::post('/orders/extract-record', [App\Http\Controllers\Admin\OrdersController::class, 'extract_record'])->name('orders.extract_record');
  Route::put('/orders/cancel-order/{id}', [App\Http\Controllers\Admin\OrdersController::class, 'cancel_order'])->name('orders.cancel_order');
  /**
   * Site Settings Route
   */
  Route::get('/site_settings', [App\Http\Controllers\Admin\SiteSettingsController::class, 'index'])->name('site_settings.index');
  Route::get('/site_settings/create', [App\Http\Controllers\Admin\SiteSettingsController::class, 'create'])->name('site_settings.add');
  Route::post('/site_settings/create', [App\Http\Controllers\Admin\SiteSettingsController::class, 'store'])->name('site_settings.add');
  Route::get('/site_settings/{id}/edit', [App\Http\Controllers\Admin\SiteSettingsController::class, 'edit'])->name('site_settings.edit');
  Route::put('/site_settings/{id}/edit', [App\Http\Controllers\Admin\SiteSettingsController::class, 'update'])->name('site_settings.edit');
  Route::delete('/site_settings/{id}/delete', [App\Http\Controllers\Admin\SiteSettingsController::class, 'destroy'])->name('site_settings.delete');

  /**
   * Site Settings Route
   */
  Route::get('/overall-sales-report', [App\Http\Controllers\Admin\ReportsController::class, 'overall_sales_report_index'])->name('reports.over_all_sales_report');
  Route::get('/supplies-report', [App\Http\Controllers\Admin\ReportsController::class, 'supplies_report_index'])->name('reports.supplies_report');

  /**
   * Change log route
   */
  Route::get('/change-logs/{model}',[App\Http\Controllers\Admin\AuditTrailController::class, 'get_audit_trail_logs'])->name('change.logs');

  /**
   * Announcements
   */
  Route::get('/announcements', [App\Http\Controllers\Admin\AnnouncementsController::class, 'index'])->name('announcements.index');
  Route::get('/announcements/create', [App\Http\Controllers\Admin\AnnouncementsController::class, 'create'])->name('announcements.add');
  Route::post('/announcements/create', [App\Http\Controllers\Admin\AnnouncementsController::class, 'store'])->name('announcements.add');
  Route::get('/announcements/{id}/edit', [App\Http\Controllers\Admin\AnnouncementsController::class, 'edit'])->name('announcements.edit');
  Route::put('/announcements/{id}/edit', [App\Http\Controllers\Admin\AnnouncementsController::class, 'update'])->name('announcements.edit');
  Route::delete('/announcements/{id}/delete', [App\Http\Controllers\Admin\AnnouncementsController::class, 'destroy'])->name('announcements.delete');
  Route::get('/announcements/{id}', [App\Http\Controllers\Admin\AnnouncementsController::class, 'get_announcements'])->name('get.announcement');


  Route::get('/testing/{item_id}', [App\Http\Controllers\Admin\OrdersController::class, 'order_check'])->name('testing.orders');
});

Auth::routes();

/**
 * Franchisee Routes
 */
Route::prefix('franchisee')->group(function () {
  Route::get('/shop-supplies', [App\Http\Controllers\Franchisee\ShopController::class, 'index'])->name('shop');
  Route::get('/shipping', [App\Http\Controllers\Franchisee\ShippingController::class, 'index'])->name('shipping');
  Route::post('/save-order', [App\Http\Controllers\Franchisee\ShippingController::class, 'save_order'])->name('save-order');
  Route::get('/thank-you/{order_id}', [App\Http\Controllers\Franchisee\ShippingController::class, 'thank_you_page'])->name('thankyou');
  /**
   * Orders Routes
   */
  Route::get('/orders', [App\Http\Controllers\Admin\OrdersController::class, 'index'])->name('franchise.orders.index');
  Route::get('/orders/{id}/edit', [App\Http\Controllers\Admin\OrdersController::class, 'edit'])->name('franchise.orders.edit');
  Route::put('/orders/{id}/edit', [App\Http\Controllers\Admin\OrdersController::class, 'update'])->name('franchise.orders.edit');
  Route::delete('/orders/{id}/delete', [App\Http\Controllers\Admin\OrdersController::class, 'destroy'])->name('franchise.orders.delete');
});
