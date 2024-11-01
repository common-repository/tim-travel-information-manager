<?php

/*$timPaypalEnv = 'sandbox';
if ( TIM_TRAVEL_MANAGER_ENV === 'production' ){
	$timPaypalEnv = 'production';
}*/
?>

<div id="paypal-button" class="tim_align_center"></div>
<!-- <input type="hidden" id="timPaypalEnv" value="<?php echo $timPaypalEnv; ?>" /> -->

<input type="hidden" id="timSubdomain" value="<?php echo $options['subdomain']; ?>" />
<input type="hidden" id="timDomainApiKey" value="<?php echo $options['domain_api_key']; ?>" />
<input type="hidden" id="timCompanyApiKey" value="<?php echo $options['company_api_key']; ?>" />