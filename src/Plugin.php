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
   * Prefix for naming.
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
    if (!function_exists('register_field_group') || !function_exists('is_product_category')) {
      return;
    }

    static::register_acf();

    // Loads styles with lower priority to override gallerya plugin styles.
    add_action('wp_enqueue_scripts', __CLASS__ . '::wp_enqueue_scripts', 99);

    add_action('woocommerce_before_shop_loop', __CLASS__ . '::woocommerce_before_shop_loop');
  }

  /**
   * Registers Showroom specific fields.
   */
  public static function register_acf() {
    acf_add_local_field_group([
      'key' => 'group_showroom',
      'title' => 'Showroom',
      'fields' => [[
        'key' => 'field_showroom_window',
        'name' => 'field_showroom_window',
        'type' => 'repeater',
        'conditional_logic' => 0,
        'layout' => 'table',
        'button_label' => 'Add showroom',
        'sub_fields' => [[
          'key' => 'field_showroom_image',
          'name' => 'field_showroom_image',
          'label' => __('Image', Plugin::L10N),
          'type' => 'image',
          'required' => 1,
          'return_format' => 'array',
          'preview_size' => 'thumbnail',
          'library' => 'all',
        ],
        [
          'key' => 'field_showroom_description',
          'name' => 'field_showroom_description',
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
          'key' => 'field_showroom_related_products',
          'name' => 'field_showroom_related_products',
          'label' => __('Related products', Plugin::L10N),
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
    if ($term->taxonomy !== 'product_cat') {
      return;
    }

    if (have_rows('field_showroom_window', 'product_cat_' . $term->term_id)) {
      wp_enqueue_style(Plugin::PREFIX, static::getBaseUrl() . '/dist/styles/main.css');
    }
  }

  /**
   * @implements woocommerce_before_shop_loop
   *
   * Adds the Showroom slider to the category view page
   * before the WooCommerce products block.
   */
  public static function woocommerce_before_shop_loop() {
    if (!is_product_category()) {
      return;
    }

    $term = get_queried_object();
    if ($term->taxonomy !== 'product_cat') {
      return;
    }

    if (have_rows('field_showroom_window', $term)) {
      static::renderTemplate(['templates/showroom.php'], [
        'term_id' => $term->term_id,
      ]);
    }
  }

  /**
   * Checks if wp-rocket plugin is active and images lazyload option is set.
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
