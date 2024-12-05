<?php

/**
 * Plugin Name: FormTrackr for Gravity Forms
 * Plugin URI: https://github.com/amarasa/formtrackr-gravityforms
 * Description: A plugin to track and display the URLs where Gravity Forms are viewed, with multisite and custom metrics support.
 * Version: 1.0.1
 * Author: Angelo Marasa
 * Author URI: https://github.com/amarasa
 * License: GPL-2.0-or-later
 * Text Domain: formtrackr-gravityforms
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Autoload dependencies if necessary (e.g., Composer).

// Load the Plugin Update Checker.
require 'puc/plugin-update-checker.php';

use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

$myUpdateChecker = PucFactory::buildUpdateChecker(
    'https://github.com/amarasa/formtrackr-gravityforms',
    __FILE__,
    'formtrackr-gravityforms'
);

/**
 * Define constants.
 */
define('FORMTRACKR_VERSION', '1.0.1');
define('FORMTRACKR_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('FORMTRACKR_PLUGIN_URL', plugin_dir_url(__FILE__));
define('FORMTRACKR_PLUGIN_BASENAME', plugin_basename(__FILE__));

/**
 * Activation hook: Initialize database or settings.
 */
register_activation_hook(__FILE__, function () {
    require_once FORMTRACKR_PLUGIN_DIR . 'includes/class-formtrackr-database.php';
    FormTrackr_Database::create_table();
    FormTrackr_Database::update_table_schema(); // Ensure schema is up-to-date.
});

function formtrackr_activate()
{
    require_once FORMTRACKR_PLUGIN_DIR . 'includes/class-formtrackr-database.php';
    FormTrackr_Database::create_table();
}

/**
 * Deactivation hook: Perform any cleanup if needed.
 */
register_deactivation_hook(__FILE__, 'formtrackr_deactivate');
function formtrackr_deactivate()
{
    // Optional: Add deactivation logic here.
}

/**
 * Load the core plugin files.
 */
function formtrackr_init()
{
    require_once FORMTRACKR_PLUGIN_DIR . 'includes/class-formtrackr-tracker.php';
    require_once FORMTRACKR_PLUGIN_DIR . 'includes/class-formtrackr-admin.php';
    require_once FORMTRACKR_PLUGIN_DIR . 'includes/helpers.php';

    // Initialize core functionality.
    FormTrackr_Tracker::init();
    FormTrackr_Admin::init();
}
add_action('plugins_loaded', 'formtrackr_init');
