<?php
/*
	Tim Password Recovery Form Template
*/
?>
<div class="tim_login_box" autocomplete="off">
	<form action="#" name="tim_password_recovery_form" class="tim-form">
		<legend><?php _e( 'Password recovery', $plugin_name); ?></legend>
		
		<div class="tim-form-group">
			<label><?php _e( 'E-mail address', $plugin_name); ?>:</label>
	    	<input type="text" id="timCustomerEmail" name="timCustomerEmail" />
	    </label>

	    <br />
	    <div class="tim_row">
			<div class="tim_col_7">
				<button type="button" onclick="timCheckPasswordRecoveryForm()" class="tim-btn timSendButton">
					<?php _e( 'Reset password', $plugin_name); ?>
				</button>
			</div>
			<div class="tim_col_5 tim_align_right">
				<a href="javascript:void(0);" onclick="timLoadData('load_login_form');">
					<small><?php _e( 'Back to login', $plugin_name); ?></small>
				</a>
			</div>
		</div>

		<div id="timCustomerFormErrorMsg"></div>

		<div class="tim_alert tim_alert_info">
			<?php _e( 'We will send a code to your email address so you can set a new password', $plugin_name); ?>.
		</div>

		<input type="hidden" id="timLabelErrorEmail" value="<?php _e( 'Enter you e-mail address', $plugin_name); ?>" />
		<input type="hidden" id="timLabelErrorEmailInvalid" value="<?php _e( 'Invalid e-mail', $plugin_name); ?>" />
	</form>
</div>