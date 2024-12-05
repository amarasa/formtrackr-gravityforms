<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Check user permissions.
formtrackr_check_admin_permissions();

// Get the form ID from the query string.
$form_id = isset($_GET['form_id']) ? formtrackr_sanitize_form_id($_GET['form_id']) : 0;

if (!$form_id) {
    wp_die(__('Invalid form ID.', 'formtrackr-gravityforms'));
}

// Fetch pageviews data.
require_once FORMTRACKR_PLUGIN_DIR . 'includes/class-formtrackr-database.php';
$pageviews = FormTrackr_Database::get_form_views($form_id);

?>

<div class="wrap">
    <h1><?php echo esc_html(sprintf(__('Pageviews for Form #%d', 'formtrackr-gravityforms'), $form_id)); ?></h1>

    <?php if (empty($pageviews)) : ?>
        <div class="notice notice-info">
            <p><?php esc_html_e('No pageviews have been recorded for this form yet.', 'formtrackr-gravityforms'); ?></p>
        </div>
    <?php else : ?>
        <table class="widefat fixed striped">
            <thead>
                <tr>
                    <th><?php esc_html_e('Page URL', 'formtrackr-gravityforms'); ?></th>
                    <th><?php esc_html_e('View Count', 'formtrackr-gravityforms'); ?></th>
                    <th><?php esc_html_e('Last Viewed', 'formtrackr-gravityforms'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pageviews as $view) : ?>
                    <tr>
                        <td>
                            <a href="<?php echo esc_url($view['page_url']); ?>" target="_blank">
                                <?php echo esc_html($view['page_url']); ?>
                            </a>
                        </td>
                        <td><?php echo intval($view['view_count']); ?></td>
                        <td><?php echo esc_html($view['last_viewed']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <p>
        <a href="<?php echo esc_url(admin_url('admin.php?page=gf_edit_forms')); ?>" class="button">
            <?php esc_html_e('Back to Forms', 'formtrackr-gravityforms'); ?>
        </a>
    </p>
</div>