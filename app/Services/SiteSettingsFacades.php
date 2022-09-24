<?php
/**
 * Site Settings Facade
 *
 * @author Radiant C. Juan <radiantcjuan@gmail.com>
 *
 * @copyright RCJuan Food Hub
 */

namespace App\Services;

use Illuminate\Support\Facades\Facade;

class SiteSettingsFacades extends Facade {
  /**
   *  @method static Collection get(string $machine_name)
   */
  protected static function getFacadeAccessor() { return 'SiteSettingsHelper'; }
}