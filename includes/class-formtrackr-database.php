<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Class FormTrackr_Database
 * Handles database operations for FormTrackr.
 */
class FormTrackr_Database
{

    /**
     * Create the database table for tracking form views and submissions.
     */
    public static function create_table()
    {
        global $wpdb;

        $table_name = $wpdb->prefix . 'formtrackr_views';
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
            id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            form_id BIGINT(20) UNSIGNED NOT NULL,
            page_url TEXT NOT NULL,
            view_count BIGINT(20) UNSIGNED DEFAULT 0,
            submission_count BIGINT(20) UNSIGNED DEFAULT 0,
            last_viewed DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY form_id (form_id)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    /**
     * Track a form view.
     *
     * @param int $form_id Gravity Form ID.
     * @param string $page_url URL where the form was viewed.
     */
    public static function track_view($form_id, $page_url)
    {
        global $wpdb;

        $table_name = $wpdb->prefix . 'formtrackr_views';

        $existing_record = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT id, view_count FROM $table_name WHERE form_id = %d AND page_url = %s",
                $form_id,
                $page_url
            )
        );

        if ($existing_record) {
            $wpdb->update(
                $table_name,
                ['view_count' => $existing_record->view_count + 1, 'last_viewed' => current_time('mysql')],
                ['id' => $existing_record->id],
                ['%d', '%s'],
                ['%d']
            );
        } else {
            $wpdb->insert(
                $table_name,
                ['form_id' => $form_id, 'page_url' => $page_url, 'view_count' => 1, 'last_viewed' => current_time('mysql')],
                ['%d', '%s', '%d', '%s']
            );
        }
    }

    /**
     * Track a form submission.
     *
     * @param int $form_id Gravity Form ID.
     * @param string $page_url URL where the form submission occurred.
     */
    public static function track_submission($form_id, $page_url)
    {
        global $wpdb;

        $table_name = $wpdb->prefix . 'formtrackr_views';

        $existing_record = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT id, submission_count FROM $table_name WHERE form_id = %d AND page_url = %s",
                $form_id,
                $page_url
            )
        );

        if ($existing_record) {
            $wpdb->update(
                $table_name,
                ['submission_count' => $existing_record->submission_count + 1, 'last_viewed' => current_time('mysql')],
                ['id' => $existing_record->id],
                ['%d', '%s'],
                ['%d']
            );
        } else {
            $wpdb->insert(
                $table_name,
                ['form_id' => $form_id, 'page_url' => $page_url, 'submission_count' => 1, 'last_viewed' => current_time('mysql')],
                ['%d', '%s', '%d', '%s']
            );
        }
    }

    /**
     * Retrieve the list of pages where a form has activity.
     *
     * @param int $form_id Gravity Form ID.
     * @return array List of URLs and counts.
     */
    public static function get_form_views($form_id)
    {
        global $wpdb;

        $table_name = $wpdb->prefix . 'formtrackr_views';

        return $wpdb->get_results(
            $wpdb->prepare(
                "SELECT page_url, view_count, submission_count, last_viewed FROM $table_name WHERE form_id = %d ORDER BY last_viewed DESC",
                $form_id
            ),
            ARRAY_A
        );
    }

    /**
     * Update the database table structure if necessary.
     */
    public static function update_table_schema()
    {
        global $wpdb;

        $table_name = $wpdb->prefix . 'formtrackr_views';

        // Check if the `submission_count` column exists.
        $column = $wpdb->get_results(
            $wpdb->prepare(
                "SHOW COLUMNS FROM $table_name LIKE %s",
                'submission_count'
            )
        );

        // If the column doesn't exist, add it.
        if (empty($column)) {
            $wpdb->query(
                "ALTER TABLE $table_name ADD COLUMN submission_count BIGINT(20) UNSIGNED DEFAULT 0 AFTER view_count;"
            );
        }
    }
}
