<?php

if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit; // Exit if accessed directly.
}

/**
 * Cleanup tasks for FormTrackr when the plugin is uninstalled.
 */
function formtrackr_cleanup()
{
    global $wpdb;

    // Delete the custom database table.
    $table_name = $wpdb->prefix . 'formtrackr_views';
    $wpdb->query("DROP TABLE IF EXISTS $table_name");

    // Optionally, delete other stored options or data here.
}

// Run the cleanup.
formtrackr_cleanup();
