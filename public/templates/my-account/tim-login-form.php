<?php
/*
	Tim Login Form Template
*/
?>
<div class="tim_login_box" autocomplete="off">
	<form action="#" name="tim_login_form" class="tim-form">
		<legend><?php _e( 'Log in', $plugin_name ); ?></legend>

		<div class="tim-form-group">
	    	<label><?php _e( 'E-mail address', $plugin_name ); ?>:</label>
	    	<input type="text" id="timCustomerEmail" name="timCustomerEmail" />
		</div>

		<div class="tim-form-group">
			<label><?php _e( 'Password', $plugin_name ); ?>:</label>
	    	<input type="password" id="timCustomerPassword" name="timCustomerPassword" />
	    </label>

	    <br />
	    <div class="tim_row">
			<div class="tim_col_7">
				<button type="button" onclick="timCheckLoginForm();" class="tim-btn timSendButton">
					<?php _e( 'Log in', $plugin_name ); ?>
				</button>
			</div>
			<div class="tim_col_5 tim_align_right">
				<a href="javascript:void(0);" onclick="timLoadData('load_password_recovery_form');">
					<small><?php _e( 'Forgot password?', $plugin_name ); ?></small>
				</a>
			</div>
		</div>

		<div id="timCustomerFormErrorMsg"></div>

		<hr />
		<div class="tim_align_right">							
			<a href="javascript:void(0);" onclick="timLoadData('load_signup_form');">
				<?php _e( 'Create account', $plugin_name ); ?>
			</a>
		</div>

		<input type="hidden" id="timLabelHello" value="<?php _e( 'Hello', $plugin_name ); ?>" />
		<input type="hidden" id="timLabelLogout" value="<?php _e( 'Logout', $plugin_name ); ?>" />
		<input type="hidden" id="timLabelName" value="<?php _e( 'Name', $plugin_name ); ?>" />
		<input type="hidden" id="timLabelEmail" value="<?php _e( 'E-mail address', $plugin_name ); ?>" />
		
		<input type="hidden" id="timLabelErrorEmail" value="<?php _e( 'Enter your e-mail', $plugin_name ); ?>" />
		<input type="hidden" id="timLabelErrorEmailInvalid" value="<?php _e( 'Invalid e-mail', $plugin_name ); ?>" />
		<input type="hidden" id="timLabelErrorPassword" value="<?php _e( 'Enter your password', $plugin_name ); ?>" />
	</form>
</div>