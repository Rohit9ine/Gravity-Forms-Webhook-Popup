jQuery(document).ready(function($) {
    // Bind to Gravity Forms submission event
    $(document).on('submit', 'form#gform_1', function(e) { // Replace '#gform_1' with your specific form ID
        e.preventDefault();
        var formData = $(this).serialize();

        // AJAX request to perform the server-side operation
        $.ajax({
            url: gf_webhook_popup_ajax.ajaxurl,
            method: 'POST',
            data: {
                action: 'gf_webhook_popup',
                formData: formData
            },
            timeout: 60000, // 5 seconds timeout
            beforeSend: function() {
                // Optionally, display a loading indication
            },
            success: function(response) {
                if(response.success) {
                    // Redirect to the new page with the response data in the query string
                    window.location.href = '/created-server/?data=' + encodeURIComponent(response.data);
                }
            },
            error: function(xhr, status, error) {
                // On error, redirect with an error message instead
                window.location.href = '/created-server/?error=' + encodeURIComponent(error);
            }
        });
    });
});