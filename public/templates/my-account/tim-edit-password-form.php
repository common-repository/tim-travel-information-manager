<?php
/*
	Tim Update Password Form Template
*/
?>
<div class="tim_login_box" autocomplete="off">
	<form action="#" name="tim_update_password_form" class="tim-form">
		<legend><?php _e( 'Reset your Password', $plugin_name ); ?></legend>
		
		<div class="tim_alert tim_alert_success">
			<?php _e( 'We have sent a code to your email address. Enter the code in the field below so you can set a new password', $plugin_name ); ?>
		</div>
		<br />

		<label>
	    	<?php _e( 'Code', $plugin_name ); ?>:
	    	<input type="text" id="timCustomerCode" name="timCustomerCode" />
	    </label>

		<label>
	    	<?php _e( 'New Password', $plugin_name ); ?>:
	    	<input type="password" id="timCustomerPassword" name="timCustomerPassword" />
	    </label>

	    <label>
		    <button type="button" onclick="timCheckUpdatePassword()" class="tim-btn timSendButton">
				<?php _e( 'Update password', $plugin_name ); ?>
			</button>
		</label>

		<div id="timCustomerFormErrorMsg"></div>

		<input type="hidden" id="timLabelErrorPassword" value="<?php _e( 'Enter the password', $plugin_name ); ?>" />
		<input type="hidden" id="timLabelErrorPasswordInvalid" value="<?php _e( 'Invalid password', $plugin_name ); ?>" />

		<input type="hidden" id="timLabelPasswordUpdated" value="<?php _e( 'Password has been reset. You can authenticate now', $plugin_name ); ?>" />
	</form>
</div>