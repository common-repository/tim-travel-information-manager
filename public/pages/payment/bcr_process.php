<?php

if ( ! isset( $_POST ) || ! $_POST['x_response_code'] ){
    exit;
}

$resp = $_POST;

$chargeApproved = false;

// Pay button from authorizer
if ( $resp['x_response_code'] == 1 ) {
    if ( $resp['x_response_reason_code'] == 100 ) {
        /*if ( $bookingCart->currency->code === 'USD' ){
            $credentials = $bookingCart->payment_gateways->bcr_ecommerce_usd->cred;
        }
        elseif ($bookingCart->currency->code === 'CRC'){
            $credentials = $bookingCart->payment_gateways->bcr_ecommerce_crc->cred;
        }
        else{
            exit();
        }

        $hash_value = 'CoMeRciO'; // Production ??
        $transaction_key = $credentials->transaction_key;

        // $hash = hash_hmac( 'md5', $hash_value . $resp['x_login'] . $resp['x_trans_id'] . $resp['x_amount'], $transaction_key );
        $hash = $hash_value . $resp['x_login'] . $resp['x_trans_id'] . $resp['x_amount'];
        
        // Validate hash in response with authorizer hash - Not working
        if ( $resp['x_MD5_hash'] == $hash ){
            $chargeApproved = true;
        }
        else{
            $transactionErrorMsg = 'Invalid payment';
        }*/

        $chargeApproved = true;
    } else {
        $transactionErrorMsg = $resp['x_response_reason_text'];
    }
} elseif ( $resp['x_response_code'] == 3 ) { 
    // Cancel button from authorizer
    $transactionErrorMsg = 'Canceled. Try again';
} else {
    $transactionErrorMsg = 'Error';
}

$booking_client_session = $_SESSION['tim_booking_client_session'];

if ( $chargeApproved ) {
    $params = array(
        'id' => $booking_client_session['id'], 
        'name' => $booking_client_session['name'], 
        'last_name' => $booking_client_session['last_name'], 
        'email' => $booking_client_session['email'], 
        'country_id' => $booking_client_session['country_id'], 
        'phone_number' => $booking_client_session['phone_number'], 
        'phone_code' => $booking_client_session['phone_code'], 
        // 'tax_id_code' => $booking_client_session['tax_id_code'], 
        // 'tax_id_number' => $booking_client_session['tax_id_number'], 
        'notes' => $booking_client_session['notes'], 
        'client_id' => $booking_client_session['client_id'], 
        'lang' => $booking_client_session['lang'], // lng
        'payment_gateway' => 'BCR Ecommerce',
        'transactionid' => $resp['x_trans_id'],
        'authcode' => $resp['authcode']
    );

    if ( $this->plugin_api->complete_order_payment( $params ) ){
        unset( $_SESSION['tim_booking_client_session'] );

        header( 'Location: '. $this->site_url .'/order/?act=done&oid='. $booking_client_session['id'] .'&onm='. $resp['x_invoice_num'] .'&lng='. $booking_client_session['lang'] );
    }
} else {
    $_SESSION['paymentErrorMsg'] = $transactionErrorMsg;

    // Here we can send an email to the company to log the error

    $_SESSION['guestData'] = array(
        'name' => $booking_client_session['name'], 
        'last_name' => $booking_client_session['last_name'], 
        'email' => $booking_client_session['email'], 
        'country_code' => $booking_client_session['country_id'] .'-'. $booking_client_session['phone_code'], 
        'phone_number' => $booking_client_session['phone_number'], 
        'phone_code' => $booking_client_session['phone_code'], 
        // 'tax_id_code' => $booking_client_session['tax_id_code'], 
        // 'tax_id_number' => $booking_client_session['tax_id_number'], 
        'notes' => $booking_client_session['notes']
    );

    unset( $_SESSION['tim_booking_client_session'] );

    header( 'Location: '. $this->site_url .'/checkout/' );
}