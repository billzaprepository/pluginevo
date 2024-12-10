<?php
/**
 * Plugin Name: Evolution API
 * Description: WooCommerce integration plugin with Evolution API.
 * Version: 1.0
 * Author: Mestres do WP
 * Text Domain: evolution-api
 * Domain Path: /languages
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define("EVOLUTION_PLUGIN_URL", plugin_dir_url(__FILE__));

// Load plugin textdomain for translations
function evolution_load_textdomain() {
    load_plugin_textdomain('evolution-api', false, dirname(plugin_basename(__FILE__)) . '/languages');
}
add_action('plugins_loaded', 'evolution_load_textdomain');

// Function to define constants
function evolution_define_constants() {
    define("EVOLUTION_URL", get_option('evolution_api_url'));
    define("EVOLUTION_API", get_option('evolution_api_token'));
}
add_action('plugins_loaded', 'evolution_define_constants');

// Include necessary files
$plugin_includes = [
    'core/database.php',
    'core/ajax.php',
    'core/functions.php',
    'view/frontend/form.php',
    'view/frontend/buttons.php',
    'view/frontend/list.php',
    'view/frontend/pending.php',
    'view/admin/config.php'
];

foreach ($plugin_includes as $file) {
    $filepath = plugin_dir_path(__FILE__) . $file;
    if (file_exists($filepath)) {
        include $filepath;
    }
}
