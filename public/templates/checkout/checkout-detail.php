<?php
/*
	Checkout Template
*/

$taxIdTypes	= array(
	['code' => '01', 'name' => 'Individual'], // 9
	['code' => '02', 'name' => 'Company'],    // 10
	['code' => '03', 'name' => 'Passport'],   // 11-12
	// ['code' => '03', 'name' => 'DIMEX'], // 12
	// ['code' => '04', 'name' => 'NITE'],  // 12
);

$guestName = '';
$guestLastName = '';
$guestCountryCode = '';
$guestPhoneNumber = '';
$guestPhoneCode = '';
$guestEmail = '';
$guestTaxIdCode = '';
$guestTaxIdNumber = '';
$guestNotes = '';

if ( $totalBookingItems > 0 ) {
	$validCart = true;
	$validCartClass = '';
	$invalidCartClass = ' class="tim_hide"';

	$disableInputs = '';
    
    foreach ( $bookingCart->booking_items as $item ) {
    	switch( $item->booking_type ) {
            case 'tour':
	    		if ( ( !$item->pickup_place_id || !$item->dropoff_place_id ) AND $validCart ) {
	    			$validCart = false;
	    			// $validCartClass = ' class="tim_hide"';
	    			// $invalidCartClass = '';

	    			// $validPickupData = false;

	    			// $disableInputs = 'disabled';
	    		}
    		break;
    	}
    }
    ?>

    <div id="timResult"></div>
    <form id="tim_checkout_form" action="#" method="post" class="tim-form" autocomplete="off">
		<div class="tim_clr">
		    <div class="tim_col_6">
		    	<div id="timCustomerResult">
		    		<div id="timCustomerBookingFormErrorMsg">
		    			<?php
		    			if ( $_SESSION['paymentErrorMsg'] ){
		    				?>
		    				<div class="tim_alert tim_alert_danger">
						        <?php echo $_SESSION['paymentErrorMsg']; ?>
						    </div><br />
		    				<?php
		    				unset($_SESSION['paymentErrorMsg']);

		    				// var_dump($_SESSION);

		    				$guestName = ' value="'. $_SESSION['guestData']['name'] .'"';
							$guestLastName = ' value="'. $_SESSION['guestData']['last_name'] .'"';
							$guestCountryCode = $_SESSION['guestData']['country_code'];
							$guestPhoneNumber = ' value="'. $_SESSION['guestData']['phone_number'] .'"';
							$guestPhoneCode = ' value="'. $_SESSION['guestData']['phone_code'] .'"';
							$guestEmail = ' value="'. $_SESSION['guestData']['email'] .'"';
							// $guestTaxIdCode = $_SESSION['guestData']['tax_id_code'];
							// $guestTaxIdNumber = ' value="'. $_SESSION['guestData']['tax_id_number'] .'"';
							$guestNotes = $_SESSION['guestData']['notes'];

		    				unset($_SESSION['guestData']);
		    			}
		    			?>
		    		</div>
			    	<?php
			    	if ( $_SESSION['tim_client_session'] ) { // Client logged
			    		?>
			    		<ul>
			    			<li>
			    				<b><?php _e( 'Name', $plugin_name ); ?>:</b> <?php echo $_SESSION['tim_client_session']['name'] .' '. $_SESSION['tim_client_session']['last_name']; ?>
			    			</li>
			    			<li>
			    				<b><?php _e( 'E-mail address', $plugin_name ); ?>:</b> <?php echo $_SESSION['tim_client_session']['email']; ?>
			    			</li>
			    		</ul>
			    		<input type="hidden" id="timClientId" value="<?php echo $_SESSION['tim_client_session']['id']; ?>" />
			    		<input type="hidden" id="timGuestName" value="<?php echo $_SESSION['tim_client_session']['name']; ?>" />
						<input type="hidden" id="timGuestLastName" value="<?php echo $_SESSION['tim_client_session']['last_name']; ?>" />
						<input type="hidden" id="timGuestEmail" value="<?php echo $_SESSION['tim_client_session']['email']; ?>" />
						<input type="hidden" id="timGuestPhone" value="<?php echo $_SESSION['tim_client_session']['main_phone']; ?>" />
						<input type="hidden" id="timGuestCountry" value="<?php echo $_SESSION['tim_client_session']['country_id']; ?>" />
						<input type="hidden" id="timGuestTaxIdCode" value="<?php echo $_SESSION['tim_client_session']['tax_id_code']; ?>" />
						<input type="hidden" id="timGuestTaxIdNumber" value="<?php echo $_SESSION['tim_client_session']['tax_id_number']; ?>" />
			    		<?php
			    	} else { // guest
			    		?>
			    		<input type="hidden" id="timClientId" value="" />
			            <div id="timCustomerFormSuccessMsg" class="tim_login_box"></div>

			            <div id="timCustomerDetails">
			            	<div style="margin-bottom: 20px;"><b><?php _e( 'Contact details', $plugin_name ); ?></b></div>

			            	<?php
			            	$general_options = get_option( TIM_TRAVEL_MANAGER_GENERAL_OPTIONS );
			            	if ( $general_options['secondary_price_list_enabled'] && ! isset( $_COOKIE['secondary_price_list_processed'] ) ) {
								$msg = $general_options['secondary_price_list_custom_msg_'. $content_language];
								?>

								<div class="tim-form-group">
									<div class="tim-payment-box">
										<?php echo $msg; ?>
										<span class="tim-secondary-buttons">
											<button 
												id="tim-accept-secondary"
												onclick="timProcessSecondaryPriceList('accepted')">
												<?php _e( 'Si', $this->plugin_name ); ?>
											</button>

											<button 
												id="tim-cancel-secondary"
												onclick="timProcessSecondaryPriceList('declined')">
												<?php _e( 'No', $this->plugin_name ); ?>
											</button>
										</span>
									</div>
								</div>
								<?php
			            	}
			            	?>

					    	<!-- Name -->
					    	<div class="tim-form-group">
				        		<input 
				        			type="text" 
				        			class="tim-form-control" 
				        			id="timGuestName" 
				        			name="timGuestName"<?php echo $guestName; ?>
				        			placeholder="<?php _e( 'Name', $plugin_name ); ?>*" 
				        			<?php echo $disableInputs ; ?> />
					        	<label class="tim-control-label" data-content="<?php _e( 'Name', $plugin_name ); ?>*"></label>
				        	</div>
		
					    	<!-- Last name -->
					    	<div class="tim-form-group">
				        		<input 
				        			type="text" 
				        			class="tim-form-control" 
				        			id="timGuestLastName" 
				        			name="timGuestLastName"<?php echo $guestLastName; ?> 
				        			placeholder="<?php _e( 'Last name', $plugin_name ); ?>" 
				        			<?php echo $disableInputs ; ?> />
					        	<label class="tim-control-label" data-content="<?php _e( 'Last name', $plugin_name ); ?>*"></label>
				        	</div>

				        	<!-- Email -->
							<div class="tim-form-group">
					        	<input 
					        		type="text" 
					        		class="tim-form-control" 
					        		id="timGuestEmail" 
					        		name="timGuestEmail"<?php echo $guestEmail; ?> 
					        		placeholder="<?php _e( 'E-mail address', $plugin_name ); ?>" 
					        		<?php echo $disableInputs ; ?> />
					        	<label class="tim-control-label" data-content="<?php _e( 'E-mail address', $plugin_name ); ?>*"></label>
								<input type="hidden" id="timValidEmail" value="1" />
							</div>

					    	<!-- Country -->
					    	<div class="tim-form-group">
				        		<select 
				        			class="tim-form-control" 
				        			id="timGuestCountry" 
				        			name="timGuestCountry" 
				        			onchange="timSelectCountry(this.value, 'Guest')" 
				        			required 
				        			<?php echo $disableInputs ; ?>>
		                        <?php
		                        echo '<option value="" disabled selected>'. __( 'Country', $plugin_name ) .'*</option>';
		                        for ($i = 0; $i < count($countries); $i++){
		                            $country = $countries[$i];

		                            $selected = $guestCountryCode === ($country['id'] .'-'. $country['phone_code']) ? 'selected="selected"' : '';

		                            echo '<option value="'. esc_attr( $country['id'] .'-'. $country['phone_code'] ) .'"'. $selected .'>'. $country['name']->$content_language .'</option>';
		                        }
		                        ?>
		                        </select>
		                        <label class="tim-control-label" data-content="<?php _e( 'Country', $plugin_name ); ?>"></label>
							</div>

					    	<!-- Phone -->
					    	<div class="tim-form-group">
				        		<input 
				        			type="text" 
				        			class="tim-form-control" 
				        			id="timGuestPhoneNumber" 
				        			name="timGuestPhoneNumber"<?php echo $guestPhoneNumber; ?> 
				        			placeholder="<?php _e( 'Phone', $plugin_name ); ?>" 
				        			disabled="disabled"<?php echo $guestPhoneNumber; ?> />
				        		<label class="tim-control-label" data-content="<?php _e( 'Phone', $plugin_name ); ?>*"></label>
					        	<input type="hidden" id="timGuestPhoneCode"<?php echo $_SESSION['guestData']['tax_id_code']; ?> />
					        </div>
			
					        <div class="tim_clr" style="display: none;">
								<div class="tim_col_6">
									<!-- Tax id type -->
									<div class="tim-form-group">
							        	<select 
							        		class="tim-form-control" 
							        		id="timGuestTaxIdCode" 
							        		name="timGuestTaxIdCode" 
							        		onchange="setTaxIdNumberMask(this.value)" 
							        		required 
							        		<?php echo $disableInputs ; ?>>
				                        <?php
				                        echo '<option value="" disabled selected>'. __( 'Tax id type', $plugin_name ) .'*</option>';
				                        for ($i = 0; $i < count($taxIdTypes); $i++){
				                            $taxIdType = $taxIdTypes[$i];
				                            
				                            $selected = $guestTaxIdCode === $taxIdType['code'] ? 'selected="selected"' : '';

				                            echo '<option value="'. esc_attr( $taxIdType['code'] ) .'"'. $selected .'>'. __( $taxIdType['name'], $plugin_name ) .'</option>';
				                        }
				                        ?>
				                        </select>
				                        <label class="tim-control-label" data-content="<?php _e( 'Tax id type', $plugin_name ); ?>"></label>
				                    </div>
						        </div>
						        <div class="tim_col_6">
						        	<!-- Tax id number -->
						        	<div class="tim-form-group">
							        	<input 
							        		type="text" 
							        		class="tim-form-control" 
							        		id="timGuestTaxIdNumber" 
							        		name="timGuestTaxIdNumber" 
							        		placeholder="<?php _e( 'Tax id number', $plugin_name ); ?>" 
							        		disabled="disabled"<?php echo $guestTaxIdNumber; ?> />
							        	<label class="tim-control-label" data-content="<?php _e( 'Tax id number', $plugin_name ); ?>*"></label>
							        </div>
						        </div>
					        </div>
						</div>
						<?php
					}
					?>
				</div>

				<!-- Notes -->
				<div class="tim-form-group">
		        	<textarea 
		        		class="tim-form-control desc" 
		        		id="timNotes" 
		        		name="timNotes" 
		        		rows="2" 
		        		placeholder="<?php _e( 'Special instructions', $plugin_name ); ?>" 
		        		<?php echo $disableInputs ; ?>><?php echo $guestNotes; ?></textarea>
	        	</div>
			</div>

			<div class="tim_col_6">
				<div style="margin-bottom: 20px;"><b><?php _e( 'Summary', $plugin_name ); ?></b></div>
				<div id="timCartDetail">
					<?php
				    $this->timCartDetail( $bookingCart, $content_language );
				    ?>
			    </div>

		    	<!-- <div style="margin-bottom: 20px;"><b><?php _e( 'Payment', $plugin_name ); ?></b></div> -->
		        <div id="timCheckoutTotals">
		        	<?php
		        	$this->timCheckoutTotals( $bookingCart );
		        	?>
			    </div>

			    <?php
			    $creditCarOption = '';
		        // if ( $validCart ) {
		        	?>
		        	<div id="paymentForm" <?php echo $validCartClass; ?>>
		        		<br>
				        <div class="tim-form-group">
				        	<b><?php _e( 'Payment method', $plugin_name ); ?></b><br><br>
				        	<?php
		                    if ( ($paymentGateways->bac_ecommerce_usd AND $bookingCurrency->code === 'USD') || ($paymentGateways->bac_ecommerce_crc AND $bookingCurrency->code === 'CRC') ) {

		                    	$creditCarOption = 'bac_ecommerce';
		                    	?>
		                    	<label>
						        	<input 
			                            type="radio" 
			                            name="timPaymentGateway" 
			                            value="bac_ecommerce" 
			                            checked 
			                            onclick="timSelectPaymentGateway(this.value, '<?php echo $content_language; ?>')" /> 
			                        &nbsp;<?php _e( 'Credit card', $plugin_name ); ?>
		                        </label>
		                    	<?php
		                    }

		                    if ( ($paymentGateways->bcr_ecommerce_usd AND $bookingCurrency->code === 'USD') || ($paymentGateways->bcr_ecommerce_crc AND $bookingCurrency->code === 'CRC') ) {
		                    	$creditCarOption = 'bcr_ecommerce';
		                    	?>
		                    	<label>
						        	<input 
			                            type="radio" 
			                            name="timPaymentGateway" 
			                            value="bcr_ecommerce" 
			                            checked 
			                            onclick="timSelectPaymentGateway(this.value, '<?php echo $content_language; ?>')" /> 
			                        &nbsp;<?php _e( 'Credit card', $plugin_name ); ?>
		                        </label>
		                    	<?php
		                    }

		                    // Paypal only supports some currencies
		                    // https://developer.paypal.com/docs/api/reference/currency-codes/
		                    if ( $paymentGateways->paypal AND $bookingCurrency->code === 'USD' ) {
		                    	?>
		                    	<label>
						        	<input 
			                            type="radio" 
			                            name="timPaymentGateway" 
			                            value="paypal" 
			                            onclick="timSelectPaymentGateway(this.value, '<?php echo $content_language; ?>')" /> 
			                        &nbsp;<?php _e( 'PayPal', $plugin_name ); ?>
		                        </label>
		                    	<?php
		                    }

		                    if ( $paymentGateways->pay_later ) {
		                    	?>
		                    	<label>
						        	<input 
			                            type="radio" 
			                            name="timPaymentGateway" 
			                            value="pay_later" 
			                            onclick="timSelectPaymentGateway(this.value, '<?php echo $content_language; ?>')" /> 
			                        &nbsp;<?php _e( 'Pay later', $plugin_name ); ?>
		                        </label>
		                    	<?php
		                    }
		                    ?>
		                </div>

		                <div style="margin-top: 20px;">
		                	<div class="tim_align_center"><div class="tim_travel_form_spinner"></div></div>
			            	<div id="timPaymentBox"></div>
			            </div>

			            <div class="tim-policies">
				        	<label>
		                        <input 
		                            type="checkbox" 
		                            onclick="timValidatePolicies(this)" /> 
		                            <?php _e( 'I agree to the', $plugin_name ); ?> <a href="javascript:void(0)" onclick="timAjaxContent('policies', '-')">
				                		<?php _e( 'Booking cancellation policies', $plugin_name ); ?>
				                	</a>
		                    </label>
		            	</div>

			            <!-- <hr> -->
		            </div>
		        	<?php
		    	// } else {
		    		?>
		            <div id="paymentFormInvalid" <?php echo $invalidCartClass; ?>>
			            <div class="tim_alert tim_alert_danger">
			                <b><?php _e( 'Please complete the cart form', $plugin_name ); ?></b>
			            </div>
			        </div>
		            <?php
		    	// }
		        ?>

		        <div id="timLoginFirstBox"></div>
		    </div>
		</div>

		<input type="hidden" id="timPaymentGateway" />

		<input type="hidden" id="timLabelErrors" value="<?php _e( 'Please check the errors below', $plugin_name ); ?>" />
		<input type="hidden" id="timIsClientLogged" value="<?php echo $isClientLogged; ?>" />		
		<input type="hidden" id="timApiUrl" value="<?php echo $this->backEndUrl; ?>/booking_payments" />
		<input type="hidden" id="timBookingId" value="<?php echo $bookingCart->id; ?>" />
		<input type="hidden" id="timLabelLoginOrRegister" value="<?php _e( 'Login first, or buy as guest', $plugin_name ); ?>" />
		<input type="hidden" id="timClientEmail" />

		<input type="hidden" id="timPoliciesAccepted" />
    </form>
    <?php
	// if ( $validCart ) {
		if ( $paymentGateways->paypal ) {
			$paypal_client_id = TIM_TRAVEL_MANAGER_ENV === 'production' ? $paymentGateways->paypal->cred->client_id : 'sb';
			?>
			<script src="https://www.paypal.com/sdk/js?client-id=<?php echo $paypal_client_id; ?>&currency=<?php echo $bookingCurrency->code; ?>&intent=capture"></script>
			<?php
		}
	// }

	if ( $creditCarOption ) {
		?>
		<script type="text/javascript">
			timSelectPaymentGateway('<?php echo $creditCarOption; ?>', '<?php echo $content_language; ?>')
		</script>
		<?php
	}
} else {
    ?>
    <a href="<?php echo $this->home_url; ?>" class="tim-btn"><?php _e( 'Continue shopping', $plugin_name ); ?></a>
    <?php
}


/*

<select 
	class="tim-form-control" 
	id="timPaymentGateway" 
	name="timPaymentGateway" 
	onchange="timSelectPaymentGateway(this.value, )" 
	required>
    <option value="" disabled selected><?php _e( 'Payment method', $plugin_name );</option>
    
    /*if ( ($paymentGateways->bac_ecommerce_usd AND $bookingCurrency->code === 'USD') || ($paymentGateways->bac_ecommerce_crc AND $bookingCurrency->code === 'CRC') ){
    	echo '<option value="bac_ecommerce">'. __( 'Credit card', $plugin_name ) .'</option>';
    }

    if ( ($paymentGateways->bcr_ecommerce_usd AND $bookingCurrency->code === 'USD') || ($paymentGateways->bcr_ecommerce_crc AND $bookingCurrency->code === 'CRC') ){
    	echo '<option value="bcr_ecommerce">'. __( 'Credit card', $plugin_name ) .'</option>';
    }

    // Paypal only supports some currencies
    // https://developer.paypal.com/docs/api/reference/currency-codes/
    if ( $paymentGateways->paypal AND $bookingCurrency->code === 'USD' ){
    	echo '<option value="paypal">PayPal</option>';
    }

    if ( $paymentGateways->pay_later ){
    	echo '<option value="pay_later">'. __( 'Pay later', $plugin_name ) .'</option>';
    }
    
</select>
<label class="tim-control-label" data-content="<?php _e( 'Payment method', $plugin_name );"></label>
*/

/*

<?php _e( 'By confirming your payment you agree to our', $plugin_name ); 
 <a href="javascript:void(0)" onclick="timAjaxContent('policies', '-')">
                		//_e( 'Booking cancellation policies', $plugin_name );
                	</a>


<div class="tim_col_4">
	<br />
	<h4><a href="javascript:void(0);" onclick="timToggleContent('tim-cart-summary')">
		_e( 'View Order summary', $plugin_name ); +</h4>
	</a>
	<!-- <div id="tim-cart-summary" style="display: none;"> -->
<!-- </div> -->


<!-- <div class="tim_clr"> -->
					<!-- <div class="tim_col_12"> -->
	        		<!-- </div> -->
				<!-- </div> -->

*/

?>