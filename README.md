
# FormTrackr for Gravity Forms

**FormTrackr** is a WordPress plugin that tracks the views and submissions of your Gravity Forms. Easily monitor which pages your forms are viewed on and where they are submitted, all from a user-friendly admin interface.

---

## Features

- Track **form views** (increments by 1 per page load).
- Track **form submissions** (increments by 1 per submission).
- Display detailed reports for each form:
  - Page URL
  - View count
  - Submission count
  - Last activity (viewed or submitted).
- Easy-to-use admin interface.
- Compatible with multisite installations.

---

## Installation

1. **Download and Upload**:
   - Download the plugin files and upload them to the `/wp-content/plugins/` directory.

2. **Activate the Plugin**:
   - Go to the WordPress admin dashboard.
   - Navigate to **Plugins > Installed Plugins**.
   - Find "FormTrackr for Gravity Forms" and click **Activate**.

3. **Enable Gravity Forms**:
   - Ensure the Gravity Forms plugin is installed and activated.

---

## Usage

1. **Tracking Views and Submissions**:
   - Embed a Gravity Form on any page or post.
   - Views and submissions will automatically be tracked.

2. **View Reports**:
   - Go to **Forms > Pageviews** in the WordPress admin.
   - Select the desired form to view its detailed activity report.

---

## Admin Features

- **Pageviews Report**:
  - Navigate to the Gravity Forms admin area.
  - Click on the "Pageviews" link under any form to view its activity.

- **Detailed Metrics**:
  - View and submission counts per page.
  - Last activity timestamp formatted for readability (e.g., `Thursday, Dec 12, 2024 at 9:16 AM`).

---

## Developer Notes

### Hooks Used
- **`gform_enqueue_scripts`**:
  - Tracks when a form is viewed.
- **`gform_after_submission`**:
  - Tracks when a form is submitted.

### Database Table
- A custom table (`wp_formtrackr_views`) is created to store:
  - `form_id`
  - `page_url`
  - `view_count`
  - `submission_count`
  - `last_viewed`

---

## Changelog

### Version 1.0.0
- Initial release with:
  - View and submission tracking.
  - Detailed admin reports.
  - Database schema updates for flexibility.

---

## Support

If you encounter any issues or have feature requests, feel free to [open an issue](https://github.com/amarasa/formtrackr-gravityforms/issues) on the GitHub repository.

---

## License

This plugin is licensed under the [GPL-2.0-or-later License](https://www.gnu.org/licenses/gpl-2.0.html).

---

### Example Screenshot of Admin Page
_Include an optional screenshot here showcasing the admin interface._

![FormTrackr Admin Interface](screenshot.png)
