<?php
/**
 * Site Settings Helper
 *
 * @author Radiant C. Juan <radiantcjuan@gmail.com>
 *
 * @copyright RCJuan Food Hub 2021
 */

namespace App\Services;

use App\Models\SiteSettings;

class SiteSettingsHelper {
  /**
   * @param string $machine_name machine name of site settings
   * @return string site settings value
   */
  public static function get($machine_name) {
    $sitesetting = SiteSettings::get_site_settings_by_machine_name($machine_name);
    return ($sitesetting) ? $sitesetting->value : '';
  }
}