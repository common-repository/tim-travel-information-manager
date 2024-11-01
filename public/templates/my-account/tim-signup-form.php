<?php
/*
	Tim Signup Form Template
*/

$taxIdTypes	= array(
	[code => '01', name => 'Individual'], // 9
	[code => '02', name => 'Company'],    // 10
	[code => '03', name => 'Passport'],   // 11-12

	// [code => '03', name => 'DIMEX'], // 12
	// [code => '04', name => 'NITE'],  // 12
);

?>
<form action="#" name="tim_signup_form" class="tim-form" autocomplete="off">
    <legend><?php _e( 'Create account', $plugin_name ); ?></legend>
    <div class="tim_clr">
	    <div class="tim_col_6">
	    	<!-- Name -->
	    	<div class="tim-form-group">
	        	<label><?php _e( 'Name', $plugin_name ); ?>:</label>
	        	<input type="text" id="timCustomerName" name="timCustomerName" />
	        </div>

	        <!-- lastName -->
	        <div class="tim-form-group">
	        	<label><?php _e( 'Last name', $plugin_name ); ?>:</label>
	        	<input type="text" id="timCustomerLastName" name="timCustomerLastName" />
	        </div>

	        <!-- Country -->
	        <div class="tim-form-group">
	        	<label><?php _e( 'Country', $plugin_name ); ?>:</label>
		    	<select id="timCustomerCountry" name="timCustomerCountry" onchange="timSelectCountry(this.value, 'Customer')">
		            <?php
		            echo '<option value="">- '. __( 'Select', $plugin_name ) .'- </option>';
		            for ($i = 0; $i < count($countries); $i++){
		                $country = $countries[$i];
		                echo '<option value="'. esc_attr( $country['id'] .'-'. $country['phone_code'] ) .'">'. $country['name']->$content_language .'</option>';
		            }
		            ?>
		        </select>
		    </div>

		    <!-- Phone -->
		    <div class="tim-form-group">
	       		<label><?php _e( 'Phone', $plugin_name ); ?>:</label>
	        	<div class="tim-input-group">
	        		<div id="timCustomerPhoneCodeHtml" class="tim-input-group-addon">-</div>
	        		<input type="text" id="timCustomerPhoneNumber" name="timCustomerPhoneNumber" disabled />
	        	</div>
	        	<input type="hidden" id="timCustomerPhoneCode">
	        </div>
	    </div>
	    <div class="tim_col_6">
	    	<!-- Tax id type -->
	    	<div class="tim-form-group">
				<label><?php _e( 'Tax id type', $plugin_name ); ?>:</label>
	        	<select id="timGuestTaxIdCode" name="timGuestTaxIdCode" onchange="setTaxIdNumberMask(this.value)">
                <?php
                echo '<option value="">- '. __( 'Select', $plugin_name ) .'- </option>';
                for ($i = 0; $i < count($taxIdTypes); $i++){
                    $taxIdType = $taxIdTypes[$i];
                    echo '<option value="'. $taxIdType['code'] .'">'. __( $taxIdType['name'], $plugin_name ) .'</option>';
                }
                ?>
                </select>
	        </div>
   	
   			<!-- Tax id number -->
   			<div class="tim-form-group">
				<label><?php _e( 'Tax id number', $plugin_name ); ?>:</label>
	        	<input type="text" id="timGuestTaxIdNumber" name="timGuestTaxIdNumber" disabled="disabled" />
	        </div>

	        <!-- Email -->
	        <div class="tim-form-group">
	    		<label><?php _e( 'E-mail address', $plugin_name ); ?>:</label>
	        	<input type="text" id="timCustomerEmail" name="timCustomerEmail" />
	        </div>
	        
	        <!-- Password -->
	        <div class="tim-form-group">
				<label><?php _e( 'Password', $plugin_name ); ?>:</label>
		    	<input type="password" id="timCustomerPassword" name="timCustomerPassword" />
		    </div>		    

		    <br />
		    <div class="tim_row">
				<div class="tim_col_7">
					<button type="button" onclick="timCheckSignupForm()" class="tim-btn timSendButton">
						<?php _e( 'Sign up', $plugin_name ); ?>
					</button>
				</div>
				<div class="tim_col_5 tim_align_right">
					<a href="javascript:void(0);" onclick="timLoadData('load_login_form');">
						<small><?php _e( 'Back to login', $plugin_name ); ?></small>
					</a>
				</div>
			</div>

			<div id="timCustomerFormErrorMsg"></div>
	    </div>
	</div>

	<input type="hidden" id="timLabelHello" value="<?php _e( 'Hello', $plugin_name ); ?>" />
	<input type="hidden" id="timLabelLogout" value="<?php _e( 'Logout', $plugin_name ); ?>" />
	<input type="hidden" id="timLabelName" value="<?php _e( 'Name', $plugin_name ); ?>" />
	<input type="hidden" id="timLabelEmail" value="<?php _e( 'E-mail address', $plugin_name ); ?>" />

	<input type="hidden" id="timLabelErrorEmailInvalid" value="<?php _e( 'Invalid e-mail', $plugin_name ); ?>" />
	<input type="hidden" id="timLabelErrorPhoneInvalid" value="<?php _e( 'Invalid phone number', $plugin_name ); ?>" />
	<input type="hidden" id="timLabelErrorCountry" value="<?php _e( 'Select your country', $plugin_name ); ?>" />
	<input type="hidden" id="timLabelErrorTaxIdCode" value="<?php _e( 'Select your tax id code', $plugin_name ); ?>" />
	<input type="hidden" id="timLabelErrorTaxIdNumber" value="<?php _e( 'Enter your tax id number', $plugin_name )
	; ?>" />
	<input type="hidden" id="timLabelErrorTaxIdNumberInvalid" value="<?php _e( 'Invalid tax id number', $plugin_name ); ?>" />

	<input type="hidden" id="timLabelErrorPassword" value="<?php _e( 'Enter your password', $plugin_name ); ?>" />
	<input type="hidden" id="timLabelErrorPasswordInvalid" value="<?php _e( 'Invalid password', $plugin_name ); ?>" />
</form>