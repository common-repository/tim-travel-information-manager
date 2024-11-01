<?php

$disablePaymentBtn = $policiesAccepted == 'true' ? '' : ' disabled';

?>

<button 
	type="button" 
	onclick="timProcessPayLaterPayment()" 
	class="tim-btn tim-btn-lg tim-btn-block timSendButton" 
	id="timPaymentBtn"
	<?php echo $disablePaymentBtn; ?>>
	<?php _e( 'Complete order', $plugin_name ); ?>
</button>

<br /><br />
<div class="tim_alert tim_alert_info">
	<?php _e( 'Your order will be pending until confirming payment.', $plugin_name); ?>
</div>