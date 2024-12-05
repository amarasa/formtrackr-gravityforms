<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Class FormTrackr_Tracker
 * Handles tracking of Gravity Form views and submissions.
 */
class FormTrackr_Tracker
{

    /**
     * Initialize the tracker.
     */
    public static function init()
    {
        // Track form views when the form is displayed.
        add_action('gform_enqueue_scripts', [__CLASS__, 'track_form_view'], 10, 2);

        // Track form submissions.
        add_action('gform_after_submission', [__CLASS__, 'track_form_submission'], 10, 2);
    }

    /**
     * Track the page where a form is viewed.
     *
     * @param array $form The Gravity Form object.
     * @param bool $is_ajax Whether the form is using AJAX.
     */
    public static function track_form_view($form, $is_ajax)
    {
        static $already_tracked = []; // Static array to track forms already counted on this page load.

        $form_id = absint($form['id']);
        $page_url = isset($_SERVER['REQUEST_URI']) ? esc_url_raw(home_url($_SERVER['REQUEST_URI'])) : '';

        // Ensure we have valid data to work with and prevent duplicate counts for the same form on the same page.
        if (!$form_id || !$page_url || isset($already_tracked[$form_id])) {
            return;
        }

        // Mark this form as tracked for this page load.
        $already_tracked[$form_id] = true;

        require_once FORMTRACKR_PLUGIN_DIR . 'includes/class-formtrackr-database.php';
        FormTrackr_Database::track_view($form_id, $page_url);
    }


    /**
     * Track the page where a form submission occurred.
     *
     * @param array $entry The Gravity Form entry object.
     * @param array $form The Gravity Form object.
     */
    public static function track_form_submission($entry, $form)
    {
        $form_id = absint($form['id']);
        $page_url = isset($entry['source_url']) ? esc_url_raw($entry['source_url']) : '';

        if (!$form_id || !$page_url) {
            return;
        }

        require_once FORMTRACKR_PLUGIN_DIR . 'includes/class-formtrackr-database.php';
        FormTrackr_Database::track_submission($form_id, $page_url);
    }
}
