<?php

$bcrEndpoint = 'https://evertecepstest.net/PayGate/Colector';

if ( TIM_TRAVEL_MANAGER_ENV === 'production' ) {
	$bcrEndpoint = 'https://dsec.athservices.net:443/PayGate/Colector';
}

if ( $bookingCart->currency->code === 'USD' ) {
	$credentials = $bookingCart->payment_gateways->bcr_ecommerce_usd->cred;
} elseif ($bookingCart->currency->code === 'CRC') {
	$credentials = $bookingCart->payment_gateways->bcr_ecommerce_crc->cred;
} else {
	exit();
}

$login_id = $credentials->login_id;
$transaction_key = $credentials->transaction_key;

$bookingNumber = $bookingCart->booking_number;
$amount = TIM_TRAVEL_MANAGER_ENV === 'production' ? $bookingCart->total_price : 1;

$time = time();
$hash = hash_hmac( 'md5', $login_id .'^'. $bookingNumber .'^'. $time .'^'. $amount .'^', $transaction_key );

$disablePaymentBtn = $policiesAccepted == 'true' ? '' : ' disabled';

?>
<input type="hidden" name="x_login" value="<?php echo $login_id; ?>" />
<input type="hidden" name="x_amount" value="<?php echo $amount; ?>" id="timPaymentGatewayAmount" />
<input type="hidden" name="x_type" value="AUTH_CAPTURE" />
<input type="hidden" name="x_invoice_num" value="<?php echo $bookingNumber; ?>" />
<input type="hidden" name="x_fp_sequence" value="<?php echo $bookingNumber; ?>" />
<input type="hidden" name="x_fp_timestamp" value="<?php echo $time; ?>" />
<input type="hidden" name="x_fp_hash" value="<?php echo $hash; ?>" />
<input type="hidden" name="x_test_request" value="false" />
<input type="hidden" name="x_show_form" value="PAYMENT_FORM" />
<input type="hidden" id="redirect" name="redirect" />
<input type="hidden" id="bcr_endpoint" value="<?php echo $bcrEndpoint; ?>" />

<input type="hidden" id="timCurrencyCode" value="<?php echo $bookingCart->currency->code; ?>" />
<button 
	type="button" 
	onclick="timProcessBcrEcommercePayment()" 
	class="tim-btn tim-btn-lg tim-btn-block timSendButton" 
	id="timPaymentBtn"
	<?php echo $disablePaymentBtn; ?>>
	<?php _e( 'Complete order', $plugin_name ); ?>
</button>

<br /><br />
<img src="<?php echo $plugin_url; ?>/public/img/bcr-payments.png" alt="<?php _e( 'Credit card', $plugin_name ); ?>" />


<?php
/* <input type="hidden" name="x_currency_code" value="$bookingCart->currency->code;" id="x_currency_code" /> */
?>