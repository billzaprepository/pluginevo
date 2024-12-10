jQuery(document).ready(function($) {
    $('#evolution-api-license-connect').on('click', function() {
		var email = $('#evolution-api-license-email').val();
		var url = $('#evolution-api-license-url').val();
        var $button = $(this);
        $button.prop('disabled', true);
        $.ajax({
            url: evolutionAdminAjax.ajaxurl,
            method: 'POST',
            data: {
                action: 'evolution_api_connect_license',
                url: url,
                email: email
            },
            success: function(response) {
                $button.prop('disabled', false);
				location.reload(true);
            },
            error: function() {
                $button.prop('disabled', false);
				location.reload(true);
            }
        });
    });
	$('#evolution-api-license-remove').on('click', function() {
        $.ajax({
            url: evolutionAdminAjax.ajaxurl,
            method: 'POST',
            data: {
                action: 'evolution_api_remove_license'
            },
            success: function(response) {
				location.reload(true);
            },
            error: function() {
            }
        });
		return false;
	});
});
