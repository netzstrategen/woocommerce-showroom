<?php

/*
  Plugin Name: WooCommerce Showroom
  Version: 1.1.0
  Text Domain: woocommerce-showrooom
  Description: Products category slideshow for WooCommerce.
  Author: netzstrategen
  Author URI: http://www.netzstrategen.com/
  License: GPL-2.0+
  License URI: http://www.gnu.org/licenses/gpl-2.0
*/

namespace Netzstrategen\WooCommerceShowroom;

if (!defined('ABSPATH')) {
  header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
  exit;
}

/**
 * Loads PSR-4-style plugin classes.
 */
function classloader($class) {
  static $ns_offset;
  if (strpos($class, __NAMESPACE__ . '\\') === 0) {
    if ($ns_offset === NULL) {
      $ns_offset = strlen(__NAMESPACE__) + 1;
    }
    include __DIR__ . '/src/' . strtr(substr($class, $ns_offset), '\\', '/') . '.php';
  }
}
spl_autoload_register(__NAMESPACE__ . '\classloader');

add_action('admin_init', __NAMESPACE__ . '\Admin::init');
add_action('init', __NAMESPACE__ . '\Plugin::init');
