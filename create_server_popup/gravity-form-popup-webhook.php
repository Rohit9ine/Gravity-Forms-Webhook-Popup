<?php
/*
Plugin Name: Gravity Forms Webhook Popup
Description: Sends data to a webhook and displays a response in a popup after a Gravity Form submission.
Version: 1.0
Author: Rohit Kumar
Author URI: https://iamrohit.net/
*/

// Make sure Gravity Forms is active
add_action( 'admin_init', function() {
    if (!is_plugin_active('gravityforms/gravityforms.php')) {
        deactivate_plugins(plugin_basename(__FILE__));
        add_action('admin_notices', function() {
            echo '<div class="error"><p>Gravity Forms Webhook Popup requires Gravity Forms. Please install and activate Gravity Forms first.</p></div>';
        });
    }
});

// Enqueue necessary scripts and styles
add_action( 'wp_enqueue_scripts', 'gf_webhook_popup_enqueue_scripts' );
function gf_webhook_popup_enqueue_scripts() {
    wp_enqueue_style( 'gf-webhook-popup-style', plugins_url( '/css/popup-style.css', __FILE__ ) );
    wp_enqueue_script( 'gf-webhook-popup-script', plugins_url( '/js/popup-script.js', __FILE__ ), array('jquery'), '', true );

    // Localize the script with new data
    $script_data_array = array(
        'ajaxurl' => admin_url( 'admin-ajax.php' )
    );
    wp_localize_script( 'gf-webhook-popup-script', 'gf_webhook_popup_ajax', $script_data_array );
}

// Add AJAX action to handle the form submission
add_action( 'wp_ajax_gf_webhook_popup', 'gf_webhook_popup_handler' );
add_action( 'wp_ajax_nopriv_gf_webhook_popup', 'gf_webhook_popup_handler' );

function gf_webhook_popup_handler() {
    // Check if formData is set and not empty 
    if (isset($_POST['formData']) && !empty($_POST['formData'])) {
        // Parse the serialized form data into an associative array
        parse_str($_POST['formData'], $output);

        // Prepare the body to be JSON encoded
        $body = array();
        foreach ($output as $key => $value) {
            $body[$key] = $value;
        }

        // Send the request to the webhook
        $response = wp_remote_post('https://app.serverkade.com/webhook/capture/ZgmclKwCyd', array(
            'body' => json_encode($body),
            'headers' => array('Content-Type' => 'application/json'),
            'timeout' => 15,
        ));

        // Handle the response
        if (is_wp_error($response)) {
            $error_message = $response->get_error_message();
            wp_send_json_error($error_message);
        } else {
            $body = wp_remote_retrieve_body($response);
            wp_send_json_success($body);
        }
    } else {
        wp_send_json_error('No form data received.');
    }

    wp_die(); // Ensure we terminate and return a proper response
}

// Register the action that triggers the AJAX call on form submission
add_filter( 'gform_confirmation', 'custom_confirmation', 10, 4 );
function custom_confirmation( $confirmation, $form, $entry, $ajax ) {
    if (isset($form['cssClass']) && strpos($form['cssClass'], 'gf-webhook-popup-trigger') !== false) {
        $confirmation = array( 'redirect' => '#gf-webhook-popup' );
    }

    return $confirmation;
}