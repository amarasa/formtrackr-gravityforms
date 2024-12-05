<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Class FormTrackr_Admin
 * Handles admin UI integration for FormTrackr.
 */
class FormTrackr_Admin
{

    /**
     * Initialize admin functionality.
     */
    public static function init()
    {
        add_filter('gform_form_actions', [__CLASS__, 'add_pageviews_link'], 10, 2);
        add_action('admin_menu', [__CLASS__, 'register_pageviews_page']);
    }

    public static function add_pageviews_link($actions, $form)
    {
        if (!is_array($actions)) {
            $actions = [];
        }

        $form_id = is_array($form) && isset($form['id']) ? absint($form['id']) : absint($form);

        if ($form_id) {
            $url = admin_url("admin.php?page=formtrackr-pageviews&form_id={$form_id}");
            $actions['pageviews'] = sprintf(
                '<a href="%s">%s</a>',
                esc_url($url),
                esc_html__('Pageviews', 'formtrackr-gravityforms')
            );
        }

        return $actions;
    }

    public static function register_pageviews_page()
    {
        add_submenu_page(
            null,
            __('Form Activity', 'formtrackr-gravityforms'),
            __('Form Activity', 'formtrackr-gravityforms'),
            'manage_options',
            'formtrackr-pageviews',
            [__CLASS__, 'render_pageviews_page']
        );
    }

    public static function render_pageviews_page()
    {
        if (!current_user_can('manage_options')) {
            return;
        }

        if (empty($_GET['form_id'])) {
            wp_die(__('Invalid form ID.', 'formtrackr-gravityforms'));
        }

        $form_id = intval($_GET['form_id']);

        require_once FORMTRACKR_PLUGIN_DIR . 'includes/class-formtrackr-database.php';
        $views = FormTrackr_Database::get_form_views($form_id);

        echo '<div class="wrap">';
        echo '<h1>' . esc_html(sprintf(__('Activity for Form #%d', 'formtrackr-gravityforms'), $form_id)) . '</h1>';

        if (!$views) {
            echo '<div class="notice notice-info"><p>' . esc_html__('No activity recorded for this form.', 'formtrackr-gravityforms') . '</p></div>';
        } else {
            echo '<table class="widefat fixed striped">';
            echo '<thead>
                    <tr>
                        <th>' . esc_html__('Page URL', 'formtrackr-gravityforms') . '</th>
                        <th>' . esc_html__('View Count', 'formtrackr-gravityforms') . '</th>
                        <th>' . esc_html__('Submission Count', 'formtrackr-gravityforms') . '</th>
                        <th>' . esc_html__('Last Activity', 'formtrackr-gravityforms') . '</th>
                    </tr>
                  </thead>';
            echo '<tbody>';
            foreach ($views as $view) {
                $last_activity = $view['last_viewed'] ? date_i18n(
                    'l, M j, Y \a\t g:i A',
                    strtotime($view['last_viewed']),
                    true
                ) : 'N/A';

                echo '<tr>';
                echo '<td><a href="' . esc_url($view['page_url']) . '" target="_blank">' . esc_html($view['page_url']) . '</a></td>';
                echo '<td>' . intval($view['view_count']) . '</td>';
                echo '<td>' . intval($view['submission_count']) . '</td>';
                echo '<td>' . esc_html($last_activity) . '</td>';
                echo '</tr>';
            }
            echo '</tbody>';
            echo '</table>';
        }

        echo '<p><a href="' . esc_url(admin_url('admin.php?page=gf_edit_forms')) . '" class="button">' . esc_html__('Back to Forms', 'formtrackr-gravityforms') . '</a></p>';
        echo '</div>';
    }
}
