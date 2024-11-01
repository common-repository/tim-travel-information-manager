(function( $ ) {
	'use_strict';

	// Document ready
    $(function () {

    	// Initialize the Lightbox for any links with the 'fancybox' class
    	$('a.tim_fancybox').fancybox();

    	var data = {};
    	
    	$('#tim_travel_admin_form').on('submit', function(e) {
			timTravelResetErrors();

			var form = '#tim_travel_admin_form';
			var spinner = '.tim_travel_form_spinner#tim_travel_admin_form_spinner';

			$(spinner).css('display', 'inline');

			var url = 'options.php';

			// Store all inputs data
			$.each($('form'+ form +' input, form'+ form +' input[type="radio"], form'+ form +' input[type="checkbox"], form'+ form +' textarea, form'+ form +' select'), function(i, v) {
				if (v.type !== 'submit') {
					if ((v.type === 'radio') || (v.type === 'checkbox')) {
						if (v.checked) {
							data[v.name] = v.value;
						}						
					} else {
						data[v.name] = v.value;	
					}
				}
			});

			$.ajax({
				dataType: 'json',
				type: 'POST',
				url: url,
				data: data,
				success: function(response) { // why is error here ?
					$.each(response, function(i, v) {
						//console.log(i + " => " + v); // view in console for error messages
						var msg = '<label class="tim_travel_error" for="'+ i +'">'+ v +'</label>';
						$(form +' input[name="' + i + '"]').addClass('tim_travel_error_input').after(msg);
					});

					var keys = Object.keys(response);
					$(form +' input[name="'+ keys[0] +'"]').focus();
					$(spinner).hide();

					return false;
				},
				error: function(response) {
					// Sometimes it does not refresh the input data correctly
					// $('.updated').show();
					// $('.updated p').html('Settings saved.');
					// $(spinner).hide();

					alert('General options saved');
					location.reload();
					// window.location.href = window.location.href + '&saved=1'; // it deletes the saved param
				}
			});

			e.preventDefault();

			return false;
		});

		$('#tim_travel_sync').click(function(event) {
	        var spinner = '.tim_travel_form_spinner#tim_travel_sync_spinner';

			$(spinner).css('display', 'inline');
	        
	        $.post(
	            ajaxurl, {
	                action: 'sync_tim_api', 
	                nonce:  timData.nonce
	            },
	            function(response) {
	               $('.updated').show();
	               $('.updated p').html(response);
	               $(spinner).hide();
	               
	               // console.log(response);
	            }
	        );		
	    });
	});
})( jQuery );

// reset form errors
function timTravelResetErrors() {
    jQuery('form#tim_travel_admin_form input').removeClass('tim_travel_error_input');
    jQuery('label.tim_travel_error').remove();
}

function timDisplayContent(element, id){
	var timGoogleMapApyKey = jQuery('#'+ id);

	if (element.checked){
		timGoogleMapApyKey.show();
	} else {
		timGoogleMapApyKey.hide();
	}
}

function timDisplayContentForRadios(element, id){
	var content = document.getElementById(id);

	if (element.value == 0) {
		content.style.display = 'none';
	} else {
		content.style.display = 'inline-block';
	}

	// var timGoogleMapApyKey = jQuery('#'+ id);

	// if (element.checked){
	// 	timGoogleMapApyKey.show();
	// } else {
	// 	timGoogleMapApyKey.hide();
	// }
}

// Only numbers "0123456789"
function timOnlyNumbers(e) {
	if (e.keyCode) code = e.keyCode;
	else if (e.which) code = e.which;
	if (code == 8 || code == 9 || code == 13) return true;
	var character = String.fromCharCode (code);
	return '0' <= character && character <= '9';
}


/*function timGoogleMapEnabled(element){
	var timGoogleMapApyKey = jQuery('#timGoogleMapApyKey');

	if (element.checked){
		timGoogleMapApyKey.show();
	}
	else{
		timGoogleMapApyKey.hide();
	}
}*/
