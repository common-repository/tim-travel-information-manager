<?php

$bacEndpoint = 'https://credomatic.compassmerchantsolutions.com/api/transact.php';

// $credentials = $bookingCart->payment_gateways->bac_ecommerce->cred;

if ( $bookingCart->currency->code === 'USD' ) {
	$credentials = $bookingCart->payment_gateways->bac_ecommerce_usd->cred;
} elseif ($bookingCart->currency->code === 'CRC') {
	$credentials = $bookingCart->payment_gateways->bac_ecommerce_crc->cred;
} else {
	exit();
}


$processor_id = $credentials->processor_id;
$key_id = $credentials->key_id;
$key = $credentials->key;

$bookingNumber = $bookingCart->booking_number;
$amount = $_SERVER['HTTP_HOST'] !== 'localhost' ? $bookingCart->total_price : 1;
// $amount = $bookingCart->total_price;

$time = time();
$hash = MD5( $bookingNumber .'|'. $amount .'|'. $time .'|'. $key );

$disablePaymentBtn = $policiesAccepted == 'true' ? '' : ' disabled';

?>
<div class="tim_clr">
	<div class="tim_col_12">
		<div class="tim-form-group">
			<input 
				type="text" 
				class="tim-form-control" 
		    	id="ccnumber" 
		    	name="ccnumber" 
		    	maxlength="20" 
		    	onkeypress="return timOnlyNumbers(event);" 
		    	placeholder="<?php _e( 'Credit card number', $plugin_name ); ?>" />
		    <label class="tim-control-label" data-content="<?php _e( 'Credit card number', $plugin_name ); ?>"></label>
		    <!-- <input type="hidden" id="ccnumberError" value="<?php _e( 'Invalid Credit Card number', $plugin_name ); ?>" /> -->
		</div>
	</div>
</div>

<div class="tim_clr">
	<div class="tim_col_4">
    	<div class="tim-form-group">
        	<select 
        		class="tim-form-control" 
        		id="expMonth" 
        		required>
                <?php
                $MONTHS = array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'); 
                foreach ( $MONTHS as $month ){
					?>
					<option value="<?php echo $month; ?>"><?php echo $month; ?></option>
					<?php
				}
                ?>
        	</select>
        	<label class="tim-control-label" data-content="<?php _e( 'Exp. month', $plugin_name ); ?>"></label>
        </div>
        <!-- <input type="hidden" id="expDateError" value="<?php _e( 'Invalid date', $plugin_name ); ?>" /> -->
    </div>
    <div class="tim_col_4">
    	<div class="tim-form-group">
        	<select 
        		class="tim-form-control" 
        		id="expYear" 
        		required>
				<?php
				$year = date('Y'); // Y = yyyy - y = yy
				for ( $i = 0; $i < 15; $i++ ){
					?>
					<option value="<?php echo $year; ?>"><?php echo $year; ?></option>
					<?php	
					$year = $year + 1;
				}
				?>
			</select>
			<label class="tim-control-label" data-content="<?php _e( 'Exp. year', $plugin_name ); ?>"></label>
        </div>
    </div>
    <div class="tim_col_4">
    	<div class="tim-form-group">
	    	<input 
				type="text" 
				class="tim-form-control" 
				id="cvv" 
				name="cvv" 
				maxlength="5" 
				onkeypress="return timOnlyNumbers(event);" 
				placeholder="<?php _e( 'CCV code', $plugin_name ); ?>" />
			<label class="tim-control-label" data-content="<?php _e( 'CCV code', $plugin_name ); ?>"></label>
	    	<!-- <input type="hidden" id="cvvError" value="<?php _e( 'Invalid CCV', $plugin_name ); ?>" /> -->
	    </div>
    </div>
</div>

<div class="tim_clr">
	<div class="tim_col_12">
		<div class="tim-form-group">
			<input 
				type="text" 
				class="tim-form-control" 
		    	id="ccName" 
		    	name="ccName" 
		    	maxlength="128" 
		    	placeholder="<?php _e( 'Name on card', $plugin_name ); ?>" />
		    	<label class="tim-control-label" data-content="<?php _e( 'Name on card', $plugin_name ); ?>"></label>
		    	<!-- <input type="hidden" id="ccNameError" value="<?php _e( 'Invalid Name on card', $plugin_name ); ?>" /> -->
		</div>
	</div>
</div>

<input type="hidden" id="ccexp" name="ccexp" />
<input type="hidden" id="ccType" />

<input type="hidden" name="type" value="sale" />
<input type="hidden" name="processor_id" value="<?php echo $processor_id; ?>" />
<input type="hidden" name="key_id" value="<?php echo $key_id; ?>" />
<input type="hidden" name="hash" value="<?php echo $hash; ?>" />
<input type="hidden" name="time" value="<?php echo $time; ?>" />
<input type="hidden" name="amount" value="<?php echo $amount; ?>" id="timPaymentGatewayAmount" />
<input type="hidden" name="orderid" value="<?php echo $bookingNumber; ?>" />
<input type="hidden" id="redirect" name="redirect" />
<input type="hidden" id="bac_endpoint" value="<?php echo $bacEndpoint; ?>" />

<input type="hidden" id="timCurrencyCode" value="<?php echo $bookingCart->currency->code; ?>" />

<button 
	type="button" 
	onclick="timProcessBacEcommercePayment()" 
	class="tim-btn tim-btn-lg tim-btn-block timSendButton" 
	id="timPaymentBtn"
	<?php echo $disablePaymentBtn; ?>>
	<?php _e( 'Complete order', $plugin_name ); ?>
</button>

<br /><br />
<img src="<?php echo $plugin_url; ?>/public/img/bac-payments.png" alt="<?php _e( 'Credit card', $plugin_name ); ?>" />