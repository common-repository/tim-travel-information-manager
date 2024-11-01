<?php

if ( ! isset( $_GET ) || ! $_GET['response'] ) {
	exit;
}

$resp = $_GET;

$booking_client_session = $_SESSION['tim_booking_client_session'];

if ( $resp['response'] == 1 || $resp['response_code'] == 100 ) {
    //$hash = MD5( $resp['orderid'] .'|'. $resp['amount'] .'|'. $resp['response'] .'|'. $resp['transactionid'] .'|'. $resp['avsresponse'] .'|'. $resp['cvvresponse'] .'|'. $resp['time'] .'|'. $resp['bc'] );
    
    // Validate hash in response with authorizer hash - Not working
    /*if ( $resp['hash'] == $hash ){
        $chargeApproved = true;
    }
    else{
        $transactionErrorMsg = 'Invalid payment';
    }*/

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
        'payment_gateway' => 'BAC Ecommerce', 
        'transactionid' => $resp['transactionid'],
        'authcode' => $resp['authcode']
    );

    if ( $this->plugin_api->complete_order_payment( $params ) ) {
        unset( $_SESSION['tim_booking_client_session'] );

        header( 'Location: '. $this->site_url .'/order/?act=done&oid='. $booking_client_session['id'] .'&onm='. $resp['orderid'] .'&lng='. $booking_client_session['lang'] );
    }
} else {
    $_SESSION['paymentErrorMsg'] = $resp['responsetext'];

    // Send an email to the company to log the error    
    $plugin_api = new Tim_Travel_Manager_Api( $this->plugin_name, '', $this->public_data );

    $url = '/sync/send_bac_payment_error';

    $params = array(
        'bookingNumber' => $resp['orderid'], 
        'clientName' => $booking_client_session['name'], 
        'clientEmail' => $booking_client_session['email'], 
        'error' => $_SESSION['paymentErrorMsg']
    );

    $plugin_api->authenticate_api_key( $url, $params, 'GET' );

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

    header( 'Location: '. $this->site_url .'/checkout/' ); // ?err=1
}



// $_SESSION['guestData'] = array(
//     'name'          => $resp['name'], 
//     'last_name'     => $resp['last_name'], 
//     'email'         => $resp['email'], 
//     'country_code'  => $resp['country_id'] .'-'. $resp['phone_code'], 
//     'phone_number'  => $resp['phone_number'], 
//     'phone_code'    => $resp['phone_code'], 
//     'tax_id_code'   => $resp['tax_id_code'], 
//     'tax_id_number' => $resp['tax_id_number'], 
//     'notes'         => $resp['notes']
// );



// header( 'Location: '. $this->site_url .'/order/?act=done&oid='. $resp['id'] .'&onm='. $resp['orderid'] .'&lng='. $resp['lng'] );


// $chargeApproved = false;

        // 'id'              => $resp['id'], 
        // 'name'            => $resp['name'], 
        // 'last_name'       => $resp['last_name'], 
        // 'country_id'      => $resp['country_id'], 
        // 'phone_number'    => $resp['phone_number'], 
        // 'email'           => $resp['email'], 
        // 'phone_code'      => $resp['phone_code'], 
        // 'tax_id_code'     => $resp['tax_id_code'], 
        // 'tax_id_number'   => $resp['tax_id_number'], 
        // 'notes'           => $resp['notes'], 
        // 'client_id'       => $resp['client_id'], 
        // 'lng'             => $resp['lng'], 
        
        // 'ccType'          => $resp['ccType'], 
        // 'ccName'          => $resp['ccName'],  
        // 'orderid'         => $resp['orderid'], 



//     $chargeApproved = true;
// }
// else{
//     $transactionErrorMsg = $resp['responsetext'];
// }

// $_SESSION['paymentErrorMsg'] = $transactionErrorMsg;



// if ( $chargeApproved ) {

?>