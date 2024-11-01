<?php
/*
	Tim Client Profile Template
*/
?>
<!-- <div id="timCustomerFormSuccessMsg"></div> -->

<div id="timClientProfile" class="tim_tab_content active">
	<h5><?php _e( 'Profile', $plugin_name ); ?></h5>
	<div class="tim_clr">
	    <div class="tim_col_6">
        	<?php _e( 'Name', $plugin_name ); ?>: 
        	<b><?php echo $clientSession['name']; ?></b><br />

        	<?php _e( 'Last name', $plugin_name ); ?>: 
        	<b><?php echo $clientSession['last_name']; ?></b><br /><br />

        	<?php _e( 'Country', $plugin_name ); ?>: 
        	<?php
            for ($i = 0; $i < count($countries); $i++){
                $country = $countries[$i];
                if ( $clientSession['country_id'] == $country['id'] ){
                	?><b><?php echo $country['name']->$content_language; ?></b><?php
                	break;
                };
            }
            ?><br />

            <?php _e( 'Phone', $plugin_name ); ?>: 
        	<b>(<?php echo $clientSession['phone_code'] ?>) <?php echo $clientSession['phone_number']; ?></b>
	    </div>
	    <div class="tim_col_6">
	    	<!-- <?php _e( 'Tax id type', $plugin_name ); ?>: 
        	<b><?php echo $clientSession['tax_id_code']; ?></b><br />

        	<?php _e( 'Tax id number', $plugin_name ); ?>: 
        	<b><?php echo $clientSession['tax_id_number']; ?></b><br /> -->
        	
        	<?php _e( 'E-mail address', $plugin_name ); ?>: 
        	<b><?php echo $clientSession['email']; ?></b>
	    </div>
	</div>
</div>

<div id="timClientProfileForm" class="tim_tab_content">
	<form action="#" name="tim_update_profile_form" class="tim-form">
	    <legend><?php _e( 'Edit profile', $plugin_name ); ?></legend>
	    <div class="tim_clr">
		    <div class="tim_col_6">
		    	<!-- Name -->
		        <label>
		        	<?php _e( 'Name', $plugin_name ); ?>:
		        	<input type="text" id="timCustomerName" name="timCustomerName" value="<?php echo $clientSession['name']; ?>" />
		        </label>

		        <!-- Last name -->
		        <label>
		        	<?php _e( 'Last name', $plugin_name ); ?>:
		        	<input type="text" id="timCustomerLastName" name="timCustomerLastName" value="<?php echo $clientSession['last_name']; ?>" />
		        </label>

		        <!-- Country -->
		        <label>
			    	<?php _e( 'Country', $plugin_name ); ?>:
			    	<select id="timCustomerCountry" name="timCustomerCountry" onchange="timSelectCountry(this.value, 'Customer')">
			            <?php
			            echo '<option value="">- '. __( 'Select', $plugin_name ) .'- </option>';
			            for ($i = 0; $i < count($countries); $i++){
			                $country = $countries[$i];

			                ?>
			                <option<?php selected( $clientSession['country_id'], $country['id'] ); ?> value="<?php echo $country['id'] .'-'. $country['phone_code']; ?>">
			                	<?php echo $country['name']->$content_language; ?>
			                </option>
			                <?php

			                // echo '<option'. selected( $clientSession['country_id'], $country['id'] ) .' value="'. $country['id'] .'-'. $country['phone_code'] .'">'. $country['name']->$content_language .'</option>';
			            }
			            ?>
			        </select>
			    </label>

			    <!-- Phone -->
			    <label>
		        	<?php _e( 'Phone', $plugin_name ); ?>:
		        	<div class="tim-input-group">
		        		<div id="timCustomerPhoneCodeHtml" class="tim-input-group-addon"><?php echo $clientSession['phone_code']; ?></div>
		        		<input type="text" id="timCustomerPhoneNumber" name="timCustomerPhoneNumber" value="<?php echo $clientSession['phone_number']; ?>" />
		        	</div>
		        	<input type="hidden" id="timCustomerPhoneCode" value="<?php echo $clientSession['phone_code']; ?>">
		        </label>
		    </div>
		    <div class="tim_col_6">
		    	<!-- Email -->
		    	<label>
		        	<?php _e( 'E-mail address', $plugin_name ); ?>:
		        	<input type="text" id="timCustomerEmail" name="timCustomerEmail" value="<?php echo $clientSession['email']; ?>" />
		        </label>

		        <button type="button" onclick="timCheckUpdateClientProfile()" class="tim-btn timSendButton">
					<?php _e( 'Update profile', $plugin_name ); ?>
				</button>
				<div class="tim_pull_right">
			        <a href="javascript:void(0);" onclick="timTabContent(event, 'timClientProfile', 1); timLoadData('load_client_profile');">
	                    <?php _e( 'Cancel', $plugin_name ); ?>
	                </a>
				</div>
		    </div>
		</div>

		<div id="timClientProfileFormErrorMsg"></div>

		<input type="hidden" id="timCustomerId" value="<?php echo $clientSession['id']; ?>" />

		<input type="hidden" id="timLabelErrorEmailInvalid" value="<?php _e( 'Invalid e-mail', $plugin_name ); ?>" />
		<input type="hidden" id="timLabelErrorPhoneInvalid" value="<?php _e( 'Invalid phone number', $plugin_name ); ?>" />

		<input type="hidden" id="timLabelProfileUpdated" value="<?php _e( 'Your profile has been updated', $plugin_name ); ?>" />
	</form>
</div>

<div id="timClientPasswordForm" class="tim_tab_content">
	<form action="#" name="tim_update_password_form" class="tim-form">
	    <legend><?php _e( 'Edit password', $plugin_name ); ?></legend>
	    <div class="tim_clr">
		    <div class="tim_col_6">
		        <label>
		        	<?php _e( 'Password', $plugin_name ); ?>:
		        	<input type="password" id="timCustomerPassword" name="timCustomerPassword" />
		        </label>

		        <label>
		        	<?php _e( 'Confirm password', $plugin_name ); ?>:
		        	<input type="password" id="timCustomerPasswordConfirmation" name="timCustomerPasswordConfirmation" />
		        </label>
		    </div>
		</div>
		
		<div class="tim_clr">
		    <div class="tim_col_6">
				<button type="button" onclick="timCheckUpdateClientPassword()" class="tim-btn timSendButton">
					<?php _e( 'Update password', $plugin_name ); ?>
				</button>   
			    <div class="tim_pull_right">
			        <a href="javascript:void(0);" onclick="timTabContent(event, 'timClientProfile', 1); timLoadData('load_client_profile');">
	                    <?php _e( 'Cancel', $plugin_name ); ?>
	                </a>
				</div>
			</div>
		</div>

		<div id="timClientPasswordFormErrorMsg"></div>

		<!-- <input type="hidden" id="timCustomerId" value="<?php echo $clientSession['id']; ?>" /> -->

		<input type="hidden" id="timLabelErrorPasswordInvalid" value="<?php _e( 'Invalid password', $plugin_name ); ?>" />
		<input type="hidden" id="timLabelErrorPasswordsDoNotMatch" value="<?php _e( 'Passwords do not match', $plugin_name ); ?>" />
		<input type="hidden" id="timLabelPasswordUpdated" value="<?php _e( 'Your password has been updated', $plugin_name ); ?>" />
	</form>
</div>

<hr />
<a href="javascript:void(0);" onclick="timTabContent(event, 'timClientProfile', 1); timLoadData('load_client_profile');">
    <?php _e( 'View profile', $plugin_name ); ?>
</a> | 
<a href="javascript:void(0);" onclick="timTabContent(event, 'timClientProfileForm', 1);">
    <?php _e( 'Edit profile', $plugin_name ); ?>
</a> | 
<a href="javascript:void(0);" onclick="timTabContent(event, 'timClientPasswordForm', 1);">
    <?php _e( 'Edit password', $plugin_name ); ?>
</a>