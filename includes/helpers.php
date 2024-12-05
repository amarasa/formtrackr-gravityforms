<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * FormTrackr Helpers
 * A set of utility functions to be used throughout the plugin.
 */

/**
 * Sanitize a form ID.
 *
 * @param mixed $form_id The form ID to sanitize.
 * @return int Sanitized form ID.
 */
function formtrackr_sanitize_form_id($form_id)
{
    return absint($form_id);
}

/**
 * Sanitize a URL.
 *
 * @param string $url The URL to sanitize.
 * @return string Sanitized URL.
 */
function formtrackr_sanitize_url($url)
{
    return esc_url_raw(trim($url));
}

/**
 * Generate an admin URL for the pageviews report.
 *
 * @param int $form_id The Gravity Form ID.
 * @return string URL to the pageviews report for the given form.
 */
function formtrackr_get_pageviews_url($form_id)
{
    return admin_url('admin.php?page=formtrackr-pageviews&form_id=' . absint($form_id));
}

/**
 * Check if the current user has sufficient permissions to view the admin page.
 */
function formtrackr_check_admin_permissions()
{
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.', 'formtrackr-gravityforms'));
    }
}
