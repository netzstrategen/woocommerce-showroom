<?php

/**
 * @file
 * Contains \Netzstrategen\WooCommerceShowroom\Admin.
 */

namespace Netzstrategen\WooCommerceShowroom;

/**
 * Showroom backend functionality.
 */
class Admin {

  /**
   * @implements admin_init
   */
  public static function init() {
    add_action('admin_enqueue_scripts', __CLASS__ . '::admin_enqueue_scripts');
  }

  /**
   * @implements admin_enqueue_scripts.
   */
  public static function admin_enqueue_scripts() {
    wp_enqueue_style(Plugin::PREFIX . '-admin', Plugin::getBaseUrl() . '/dist/styles/admin.css');
  }

}
