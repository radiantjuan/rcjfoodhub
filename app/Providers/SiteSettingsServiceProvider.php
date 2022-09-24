<?php
/**
 * Site Settings Helper
 *
 * @author Radiant C. Juan <radiantcjuan@gmail.com>
 *
 * @copyright RCJuan Food Hub
 */

namespace App\Providers;

use App\Services\SiteSettingsFacades;
use Illuminate\Support\ServiceProvider;

class SiteSettingsServiceProvider extends ServiceProvider {
  /**
   * Register services.
   *
   * @return void
   */
  public function register() {
    $this->app->bind('SiteSettingsHelper', function () {
      return new SiteSettingsFacades();
    });
  }

  /**
   * Bootstrap services.
   *
   * @return void
   */
  public function boot() {
    //
  }
}
