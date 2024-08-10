# Gravity Forms Webhook Popup

## Description
The **Gravity Forms Webhook Popup** plugin sends form data to a webhook and displays the response in a popup after a Gravity Form submission.

## Features
- Sends Gravity Form data to a specified webhook URL.
- Displays the webhook response in a popup.
- Ensures integration with Gravity Forms, deactivating itself if Gravity Forms is not installed or active.

## Installation
1. Download the plugin files.
2. Upload the entire plugin folder to the `/wp-content/plugins/` directory.
3. Activate the plugin through the 'Plugins' menu in WordPress.
4. Make sure Gravity Forms is installed and activated.

## Usage
1. Add the custom CSS class `gf-webhook-popup-trigger` to the Gravity Form you want to trigger the popup.
2. After form submission, a popup will display the response from the webhook.

## Files
- `gravity-forms-webhook-popup.php`: Main plugin file that handles the Gravity Forms integration, AJAX requests, and popup functionality.
- `css/popup-style.css`: Custom styles for the popup.
- `js/popup-script.js`: JavaScript to handle the AJAX request and display the popup.

## Author
**Rohit Kumar**
- [Website](https://iamrohit.net/)

## Changelog
### Version 1.0
- Initial release.
