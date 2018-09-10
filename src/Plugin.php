<?php

/**
 * @file
 * Contains \Netzstrategen\WooCommerceShowroom\Plugin.
 */

namespace Netzstrategen\WooCommerceShowroom;

/**
 * Showroom functionality.
 */
class Plugin {

  /**
   * Namespace prefix.
   *
   * @var string
   */
  const PREFIX = 'woocommerce-showroom';

  /**
   * Gettext localization domain.
   *
   * @var string
   */
  const L10N = self::PREFIX;

  /**
   * @var string
   */
  private static $baseUrl;

  /**
   * @implements init
   */
  public static function init() {
    if (!function_exists('acf_add_local_field_group') || !function_exists('is_product_category')) {
      return;
    }

    static::register_acf();

    // Loads styles with lower priority to override gallerya plugin styles.
    add_action('wp_enqueue_scripts', __CLASS__ . '::wp_enqueue_scripts', 99);

    // Use higher priority to render at the top before any product filter additions.
    add_action('woocommerce_before_shop_loop', __CLASS__ . '::woocommerce_before_shop_loop', 5);
  }

  /**
   * Registers Showroom specific fields.
   */
  public static function register_acf() {
    acf_add_local_field_group([
      'key' => 'group_showroom',
      'title' => 'Showrooms',
      'fields' => [[
        'key' => 'woocommerce-showroom_rooms',
        'name' => 'woocommerce-showroom_rooms',
        'type' => 'repeater',
        'conditional_logic' => 0,
        'layout' => 'table',
        'button_label' => 'Add showroom',
        'sub_fields' => [[
          'key' => 'image',
          'name' => 'image',
          'label' => __('Image', Plugin::L10N),
          'type' => 'image',
          'required' => 1,
          'return_format' => 'array',
          'preview_size' => 'thumbnail',
          'library' => 'all',
        ],
        [
          'key' => 'description',
          'name' => 'description',
          'label' => __('Description', Plugin::L10N),
          'type' => 'wysiwyg',
          'required' => 1,
          'conditional_logic' => 0,
          'tabs' => 'all',
          'toolbar' => 'basic',
          'media_upload' => 0,
          'delay' => 0,
        ],
        [
          'key' => 'products',
          'name' => 'products',
          'label' => __('Shown products', Plugin::L10N),
          'type' => 'relationship',
          'required' => 0,
          'conditional_logic' => 0,
          'post_type' => [
            0 => 'product',
          ],
          'taxonomy' => [
          ],
          'filters' => [
            0 => 'search',
            1 => 'taxonomy_term',
          ],
          'elements' => [
            0 => 'featured_image',
          ],
          'min' => 0,
          'max' => 3,
          'return_format' => 'id',
        ]],
      ],
      [
        'key' => 'woocommerce-showroom_shuffle_slides',
        'name' => 'woocommerce-showroom_shuffle_slides',
        'label' => __('Suffle slides', Plugin::L10N),
        'type' => 'true_false',
        'required' => 0,
        'default_value' => 0,
      ]],
      'location' => [[
        [
          'param' => 'taxonomy',
          'operator' => '==',
          'value' => 'product_cat',
        ]
      ]],
      'menu_order' => 0,
      'position' => 'normal',
      'style' => 'default',
      'label_placement' => 'top',
      'instruction_placement' => 'label',
      'active' => 1,
    ]);
  }

  /**
   * @implements enqueue_scripts.
   */
  public static function wp_enqueue_scripts() {
    if (!is_product_category()) {
      return;
    }
    $term = get_queried_object();
    if (have_rows('woocommerce-showroom_rooms', 'product_cat_' . $term->term_id)) {
      wp_enqueue_style(Plugin::PREFIX, static::getBaseUrl() . '/dist/styles/main.css');
    }
  }

  /**
   * Outputs showrooms at the top of product category page.
   *
   * @implements woocommerce_before_shop_loop
   */
  public static function woocommerce_before_shop_loop() {
    if (!is_product_category()) {
      return;
    }
    $term = get_queried_object();
    if (have_rows('woocommerce-showroom_rooms', $term)) {
      static::renderTemplate(['templates/showroom.php'], [
        'term' => $term,
      ]);
    }
  }

  /**
   * Checks if wp-rocket plugin is active and images lazyload option is enabled.
   *
   * @return bool
   */
  public static function isLazyLoadActive() {
    return is_plugin_active('wp-rocket/wp-rocket.php') && get_rocket_option('lazyload');
  }

  /**
   * Renders a given plugin template, optionally overridden by the theme.
   *
   * WordPress offers no built-in function to allow plugins to render templates
   * with custom variables, respecting possibly existing theme template overrides.
   * Inspired by Drupal (5-7).
   *
   * @param array $template_subpathnames
   *   An prioritized list of template (sub)pathnames within the plugin/theme to
   *   discover; the first existing wins.
   * @param array $variables
   *   An associative array of template variables to provide to the template.
   *
   * @throws \InvalidArgumentException
   *   If none of the $template_subpathnames files exist in the plugin itself.
   */
  public static function renderTemplate(array $template_subpathnames, array $variables = []) {
    $template_pathname = locate_template($template_subpathnames, FALSE, FALSE);
    extract($variables, EXTR_SKIP | EXTR_REFS);
    if ($template_pathname !== '') {
      include $template_pathname;
    }
    else {
      while ($template_pathname = current($template_subpathnames)) {
        if (file_exists($template_pathname = static::getBasePath() . '/' . $template_pathname)) {
          include $template_pathname;
          return;
        }
        next($template_subpathnames);
      }
      throw new \InvalidArgumentException("Missing template '$template_pathname'");
    }
  }

  /**
   * The base URL path to this plugin's folder.
   *
   * Uses plugins_url() instead of plugin_dir_url() to avoid a trailing slash.
   */
  public static function getBaseUrl() {
    if (!isset(static::$baseUrl)) {
      static::$baseUrl = plugins_url('', static::getBasePath() . '/plugin.php');
    }
    return static::$baseUrl;
  }

  /**
   * The absolute filesystem base path of this plugin.
   *
   * @return string
   */
  public static function getBasePath() {
    return dirname(__DIR__);
  }

}
