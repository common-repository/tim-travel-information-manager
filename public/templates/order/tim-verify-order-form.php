<?php
/*
	Tim Verfy Order Template
*/
?>
<div class="tim_login_box" autocomplete="off">
	<form action="#" name="tim_verify_order_form" class="tim-form">
		<legend><?php _e( 'Enter your Booking number and e-mail address', $plugin_name ); ?></legend>

		<label>
	    	<?php _e( 'Booking number', $plugin_name ); ?>:
	    	<input type="text" id="timOrderId" name="timOrderId" />
	    </label>

	    <label>
	    	<?php _e( 'E-mail address', $plugin_name ); ?>:
	    	<input type="text" id="timCustomerEmail" name="timCustomerEmail" />
	    </label>

		<button type="button" onclick="timVerifyOrderForm()" class="tim-btn timSendButton">
			<?php _e( 'Verify order', $plugin_name ); ?>
		</button>

		<div id="timCustomerFormErrorMsg"></div>
		
		<input type="hidden" id="timLabelErrorOrderID" value="<?php _e( 'Enter your Booking number', $plugin_name ); ?>" />
		<input type="hidden" id="timLabelErrorEmail" value="<?php _e( 'Enter your e-mail', $plugin_name ); ?>" />
		<input type="hidden" id="timLabelErrorEmailInvalid" value="<?php _e( 'Invalid e-mail', $plugin_name ); ?>" />
	</form>
</div>