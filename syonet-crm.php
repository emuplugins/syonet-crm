<?php
/**
 * Plugin Name: Syonet CRM
 * Description: Integração com sistema Syonet CRM.
 * Version: 1.0.22
 * Author: Tonny Santana
 */

if ( ! defined('ABSPATH')) exit;

define('FORMULARIO_SYONET_DIR', plugin_dir_path(__FILE__));
define('FORMULARIO_SYONET_URL', plugin_dir_url(__FILE__) . '/');

require_once plugin_dir_path(__FILE__) . 'includes/shortcodes/form-render.php';
require_once plugin_dir_path(__FILE__) . 'process_form.php';

require_once FORMULARIO_SYONET_DIR . 'includes/options-page/core.php';
require_once FORMULARIO_SYONET_DIR . 'includes/post-type/post-type.php';
require_once FORMULARIO_SYONET_DIR . 'includes/taxonomies/register-custom-taxonomies.php';
require_once FORMULARIO_SYONET_DIR . 'includes/taxonomies/eventos/metaboxes.php';
require_once FORMULARIO_SYONET_DIR . 'includes/taxonomies/empresas/metaboxes.php';
require_once FORMULARIO_SYONET_DIR . 'includes/post-type/metaboxes.php';
require_once FORMULARIO_SYONET_DIR . 'includes/post-type/envios.php';
require_once FORMULARIO_SYONET_DIR . 'builders/core.php';
require_once FORMULARIO_SYONET_DIR . 'update-handler.php';

add_action('admin_menu', 'syonet_add_options_page');

add_action('admin_enqueue_scripts', function() {
    wp_enqueue_script('option-page-change', FORMULARIO_SYONET_URL . 'assets/js/option-page-change.js', array(), '1.0', true);
});
