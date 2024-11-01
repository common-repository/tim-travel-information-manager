<?php

class Tim_Travel_Manager_Public_Data {

    // get_content_language
    // get_original_post_id
    // get_currency_value
    // get_total_pax
    // get_total_nights

    // get_postmeta_list
    // get_postmeta_list_by_value
    // get_postmeta_item_by_value
    // get_post_type_by_name

    // get_hotels_by_location
    // get_locations
    // get_miscellaneous

    // is_value_between_range
    // get_min_or_max_array_value
    // get_min_rate
    // format_duration_time
    // format_hour
    // format_date
    // get_month_label
    // sort_rates_by
    // sort_array_by

    // embed_video

    // timCartDetail
    // timCartSummary
    // timCheckoutDetail
    // timOrderCompleted
    // timOrderDetail

    // timLoginForm
    // timSignupForm
    // timPasswordRecoveryForm
    // timEditPasswordForm

    // timMyAccount
    // timClientProfile
    // timListOrders

    // get_modal_data

    // get_payment_method_name
    // find_item_ids_in_array
    // find_item_in_array
    // find_item_in_array_object
    // get_taggings_by_language

    protected $plugin_name;

    public function __construct( $plugin_name ){

        $this->plugin_name = $plugin_name;

        $this->plugin_url = WP_PLUGIN_URL .'/'. $plugin_name;
        // $this->site_url   = get_option( 'siteurl' );
        
        $home_url = get_home_url();
        $this->home_url = rtrim( $home_url, '/' ) .'/'; // Important to avoid sitecart

        $this->backEndUrl  = TIM_TRAVEL_MANAGER_BACKEND_URL;
        $this->frontEndUrl = TIM_TRAVEL_MANAGER_FRONTEND_URL;

    }

    // Get content language - Must match translation plugin used
    public function get_content_language() {

        $currentLanguageCode = substr( get_locale(), 0, 2 );

        // global $sitepress;
        // $currentLanguageCode = $sitepress->get_current_language();

        return $currentLanguageCode;

    }

    public function get_original_post_id( $post_type, $general_options='' ){

        if ( $general_options == '' ){
            $general_options = get_option( TIM_TRAVEL_MANAGER_GENERAL_OPTIONS );
        }

        switch ( $general_options['translation_plugin_id'] ) {
            case 'wpml':
                $default_content_lang = $this->get_default_content_lang();
                $default_content_lang = $default_content_lang ? $default_content_lang : 'en';

                // $default_content_lang = get_option( 'tim_default_content_lang' );
                // $default_content_lang = 'de';

                $original_post_id = icl_object_id( get_the_ID(), $post_type, false, $default_content_lang );
            break;

            case 'qtranslate-x':
                $original_post_id = get_the_ID();
            break;
            
            // None
            default:
                $original_post_id = get_the_ID();
            break;
        }

        return $original_post_id;

    }

    public function get_default_content_lang( ) {
        return get_option( 'tim_default_content_lang' );

        // $default_content_lang = get_option( 'tim_default_content_lang' );
        // $default_content_lang = 'en';

        // return $default_content_lang;
    }

    // Get currency value - Default company currency FIX - sync api - TODO: select default currency for the price list (website)
    public function get_currency_value( $post_type ){

        if ( isset($_SESSION['tim_currency_value']) ){
            return $_SESSION['tim_currency_value'];
        }
        
        return $this->get_postmeta_item_by_value( $post_type, 'is_default', true, 'multiple' );

    }

    // Get total pax
    public function get_total_pax( $adults, $children, $infants, $seniors, $option = '' ) {

        $plugin_name = $this->plugin_name;

        $label_adults = ( $adults > 1 ) ? __( 'Adults', $plugin_name ) : __( 'Adult', $plugin_name );
        $title_adults = ( $option == 'short' ) ? '<span title="'. $label_adults .'">(A)</span>' : ' '. $label_adults;
        $total_pax    = '<b>'. $adults .'</b>'. $title_adults;

        if ( $children > 0 ){
            $label_children = ( $children > 1 ) ? __( 'Children', $plugin_name ) : __( 'Child', $plugin_name );
            $title_children = ( $option == 'short' ) ? '<span title="'. $label_children .'">(C)</span>' : ' '. $label_children;
            $total_pax    = $total_pax .', <b>'. $children .'</b>'. $title_children;
        }

        if ( $infants > 0 ){
            $label_infants = ( $infants > 1 ) ? __( 'Infants', $plugin_name ) : __( 'Infant', $plugin_name );
            $title_infants = ( $option == 'short' ) ? '<span title="'. $label_infants .'">(I)</span>' : ' '. $label_infants;
            $total_pax    = $total_pax .', <b>'. $infants .'</b>'. $title_infants;
        }

        if ( $seniors > 0 ){
            $label_seniors = ( $seniors > 1 ) ? __( 'Seniors', $plugin_name ) : __( 'Senior', $plugin_name );
            $title_seniors = ( $option == 'short' ) ? '<span title="'. $label_seniors .'">(S)</span>' : ' '. $label_seniors;
            $total_pax    = $total_pax .', <b>'. $seniors .'</b>'. $title_seniors;
        }

        return $total_pax;

    }

    public function get_total_nights( $start, $end ){

        $datediff = strtotime($end) - strtotime($start);

        return round( $datediff / (60 * 60 * 24) );

    }

    // ??
    function group_array_by( $array, $key ) {

        $return = array();
        
        foreach ( $array as $val ) {
            $return[$val->$key][] = $val;
        }

        return $return;

    }


    // Get postmeta list
    public function get_postmeta_list( $post_type ){

        $post_type_meta = $post_type .'_meta';

        $args = array(
            'post_type'      => $post_type,
            'meta_key'       => $post_type_meta,
            'posts_per_page' => -1
        );

        $query = new WP_Query( $args );

        $list = array();
        if ( $query->have_posts() ){
            $posts = get_posts( $args );

            foreach ( $posts as $post ) {
                $postmeta = get_post_meta( $post->ID, $post_type_meta, true );

                if ( $postmeta ){
                    foreach ( $postmeta as $meta ) {
                        array_push( $list, $meta );
                    }
                }
            }   
        }

        return $list;

    }

    // Get postmeta list - find by value
    public function get_postmeta_list_by_value( $post_type, $findBy, $value ){

        $post_type_meta = $post_type .'_meta';

        $args = array(
            'post_type'      => $post_type,
            'meta_key'       => $post_type_meta,
            'posts_per_page' => -1
        );

        $query = new WP_Query( $args );

        $list = array();
        if ( $query->have_posts() ){
            $posts = get_posts( $args );

            foreach ( $posts as $post ) {
                $postmeta = get_post_meta( $post->ID, $post_type_meta, true );

                if ( $postmeta ){
                    foreach ( $postmeta as $meta ) {
                        // if ( $meta{$findBy} === $value ){ // echo $meta[$findBy];
                        if ( $meta[$findBy] === $value ) {
                            array_push( $list, $meta );
                        }
                    }
                }
            }   
        }

        return $list;

    }

    // Get postmeta item - find by value
    public function get_postmeta_item_by_value( $post_type, $findBy, $value, $option = '' ){

        $post_type_meta = $post_type .'_meta';
        
        $args = array(
            'post_type' => $post_type,
            'meta_key' => $post_type_meta,
            'posts_per_page' => -1
        );

        $query = new WP_Query( $args );

        if ( $query->have_posts() ){
            $posts = get_posts( $args );

            foreach ( $posts as $post ) { // Loop posts found
                $postmeta = get_post_meta( $post->ID, $post_type_meta, true );

                if ( ! $option ) { // This is a post
                    if ( $postmeta[$findBy] === $value ) {     
                        return $postmeta; // Return the post and associated postmeta
                    }
                } else {
                    if ( $option === 'multiple' ) { // Multiple: Ex: tim_currencies, tim_countries
                        if ( $postmeta ){
                            foreach ( $postmeta as $meta ) { // Loop inside postmeta
                                if ( $meta[$findBy] === $value ) {
                                    return $meta; // Return the associated item
                                }
                            }
                        }
                    } else { // Array inside postmeta value: Ex hotel_rooms
                        foreach ( $postmeta[$option] as $meta ) { // Loop inside postmeta array
                            // if ( $meta->{$findBy} === $value ) {
                            if ( $meta[$findBy] === $value ) {
                                return $meta;
                            }
                        }
                    }
                }
            }
        }

        return null;

    }

    // Get post type by name
    public function get_post_type_by_name( $name, $post_type, $option, $content_language ){

        $post_type_meta = $post_type .'_meta';

        $args = array(
            'post_type' => $post_type,
            'meta_key' => $post_type_meta,
            'posts_per_page' => -1
        );

        $query = new WP_Query( $args );

        if ( $query->have_posts() ) {
            $posts = get_posts( $args );

            foreach ( $posts as $post ) { // Loop posts found
                $postmeta = get_post_meta( $post->ID, $post_type_meta, true);

                if ( $option === 'single' ) { // This is a post
                    if ( ( $postmeta['name']->$content_language ) && ( $postmeta['name']->$content_language === $name ) ) {
                        return $postmeta['id'];
                    }
                } else { // This belongs to a post - Array inside postmeta
                    foreach ( $postmeta as $meta) {
                        if ( ( $meta['name']->$content_language ) && ( $meta['name']->$content_language === $name ) ) {
                            return $meta['id'];
                        }
                    }
                }               
            }

            return false;
        }

        return false;

    }


    // Get active locations
    public function get_locations( $post_type, $content_language='' ) { // , $option=''

        $post_type_meta = $post_type .'_meta';

        $order_by = 'title';
        $order = 'ASC';

        $args = array(
            'post_type' => $post_type,
            // 'meta_key' => $post_type_meta, // not working for translations
            'posts_per_page' => -1, 
            'orderby' => $order_by,
            'order' => $order
        );

        $query = new WP_Query( $args );

        $list = array();

        if ( $query->have_posts() ) {
            while ( $query->have_posts() ) : $query->the_post();
                $original_post_id = $this->get_original_post_id( $post_type );
                $postmeta = get_post_meta( $original_post_id, $post_type_meta, true );

                if ( $postmeta && $postmeta['name'] ) {
                    $item = [
                        'id' => $postmeta['id'], 
                        'name' => $postmeta['name']->$content_language
                    ];

                    if ( $postmeta['status'] === 'active' && $postmeta['parentLocation_id'] ){
                        array_push( $list, $item );
                    }
                }
            endwhile;
        }

        /*if ( $query->have_posts() ){
            $posts = get_posts( $args );
            // var_dump($posts);

            foreach ( $posts as $post ) { // Loop posts found  
                $postmeta = get_post_meta( $post->ID, $post_type_meta, true);

                // if ( $option == 'basic' ){
                    $item = [
                        'id'   => $postmeta['id'], 
                        'name' => $postmeta['name']->$content_language
                    ];
                // }
 
                if ( $postmeta['status'] === 'active' && $postmeta['parentLocation_id'] ){
                    array_push( $list, $item );
                }
            }
        }*/

        return $list;

    }

    // Get active miscellaneous
    public function get_miscellaneous( $post_type, $option ) {

        $post_type_meta = $post_type .'_meta';

        $args = array(
            'post_type' => $post_type,
            'meta_key' => $post_type_meta,
            'posts_per_page' => -1
        );

        $query = new WP_Query( $args );

        $list = array();
        if ( $query->have_posts() ){
            $posts = get_posts( $args );
            $i = 1;
            foreach ( $posts as $post ) { // Loop posts found
                $postmeta = get_post_meta( $post->ID, $post_type_meta, true );

                foreach ( $postmeta as $meta ) {
                    // echo '<br>'. $meta['type'];
                    if ( isset($meta['type']->{$option}) AND $meta['type']->{$option} === true ){
                        array_push( $list, $meta );
                    }
                }
            }   
        }

        return $list;

    }

    // Check if value is between range
    public function is_value_between_range( $value, $min, $max ) {

        $isInt = ( ! is_int($min) ? ctype_digit($min) : true );

        // Not integer
        if ( ! $isInt ){
            return false;
        }

        if ( ($min <= $value) && ($value <= $max) ) return true;
        
        return false;

    }

    // Get min or max array value
    public function get_min_or_max_array_value( $array, $field, $option ) {

        $value = '';
        if ( $array ){
            $list = array();
            foreach ( $array as $item) {
                array_push( $list, $item[$field] );
            }

            return $value = ( $option === 'max' ) ? max( $list ) : min( $list );
        }

        return false;

    }

    // Get min adult rate - ???
    public function get_min_rate( $rates, $option ) {

        $min_rate = '';
        if ( $rates ){
            $list = array();
            foreach ( $rates as $rate) {
                array_push( $list, $rate->$option );
            }

            return $min_rate = min( $list ); // Min adult rate
        }

        return false;

    }

    // Format hour
    public function format_duration_time( $time ) {

        $time_range = explode(':', $time);
        $hours   = $time_range[0];
        $minutes = $time_range[1];

        // Substract first 0
        if ( $hours[0] === '0' ) {
            $hours = substr($hours, 1);
        }

        if ( $minutes === '00' ) {
            $minutes = '';
        } elseif ( $minutes === '30' ) {
            $minutes = '.5';
        } else {
            $minutes = '.'. $minutes;
        }

        $value = $hours . $minutes .' '. __( 'hours', $this->plugin_name );

        return $value;

    }

    // Format hour
    public function format_hour( $hour, $lang ) {

        $hour_range = explode(':', $hour);
        $hours = $hour_range[0];
        $minutes = $hour_range[1];

        // Substract first 0
        /*if ( $hours[0] === '0' ){
            $hours = substr($hours, 1);
        }*/

        if ( $hours < '12' ) {
            $meridian = 'AM';
        } elseif ( $hours == '12' ) {
            $meridian = 'MD';
        } else {
            $meridian = 'PM';
        }

         if ( $lang === 'de' ) {
            $value = $hour .' Uhr'; // 02:00 Uhr
        } else {
            $hour = ( $hour == '00:00' ) ? '12:00' : $hour;

            $value = date("g:i A", strtotime($hour)); // EX: 2:00 PM
            // $value = $hour .' '. __( $meridian, $this->plugin_name );
        }

        return $value;

    }

    // Format date
    public function format_date( $from, $to = '', $lang = 'en' ) {

        $value = '-';

        if ( $from ) {
            $date_from = explode('-', $from);
            $year_from = $date_from[0];
            $month_from = $this->get_month_label( $date_from[1], $lang );
            $day_from = $date_from[2];

            if ( $lang === 'de' ) {
                $value = $day_from .'. '. $month_from .', '. $year_from;
            } else {
                $value = $month_from .' '. $day_from .', '. $year_from;
            }
        }

        if ( $to ) {
            $date_to = explode('-', $to);
            $year_to = $date_to[0];
            $month_to = $this->get_month_label( $date_to[1], $lang );
            $day_to = $date_from[2];
        
            // $year = '';
            // if ($year_from != $year_to){
            //  $year = ', '. $year_from;
            // }

            if ( $lang === 'de' ) {
                $value = $value .' / '. $day_to .'. '. $month_to .', '. $year_to;
            } else {
                $value = $value .' / '. $month_to .' '. $day_to .', '. $year_to;
            }
        }

        return $value;

    }

    // Get month
    public function get_month_label( $month, $lang ) {

        switch ( $lang ) {
            case 'de':
                $January = 'Januar';
                $February = 'Februar';
                $March = 'März';
                $April = 'April';
                $May = 'Mai';
                $June = 'Juni';
                $July = 'Juli';
                $August = 'August';
                $September = 'September';
                $October = 'Oktober';
                $November = 'November';
                $December = 'Dezember';
            break;

            case 'es':
                $January = 'Enero';
                $February = 'Febrero';
                $March = 'Marzo';
                $April = 'Abril';
                $May = 'Mayo';
                $June = 'Junio';
                $July = 'Julio';
                $August = 'Agosto';
                $September = 'Setiembre';
                $October = 'Octuber';
                $November = 'Noviembre';
                $December = 'Diciembre';
            break;

            default:
                $January = 'January';
                $February = 'February';
                $March = 'March';
                $April = 'April';
                $May = 'May';
                $June = 'June';
                $July = 'July';
                $August = 'August';
                $September = 'September';
                $October = 'October';
                $November = 'November';
                $December = 'December';
            break;
        }

        switch ( $month ) {
            case '01': $month = $January; break;
            case '02': $month = $February; break;
            case '03': $month = $March; break;
            case '04': $month = $April; break;
            case '05': $month = $May; break;
            case '06': $month = $June; break;
            case '07': $month = $July; break;
            case '08': $month = $August; break;
            case '09': $month = $September; break;
            case '10': $month = $October; break;
            case '11': $month = $November; break;
            case '12': $month = $December; break;
        }

        $month = __( $month, $this->plugin_name );

        return $month;

    }

    public function sort_rates_by( $list, $order ){

        if ( $order === 'DESC' ) {
            usort($list, function($a, $b) {
                return $a['rate_from'] - $b['rate_from'];
            });
        } else {
            usort($list, function($a, $b) {
                return $b['rate_from'] - $a['rate_from'];
            });
        }

        return $list;

    }

    public function sort_array_by( $data, $sort_by, $order_by ){

        $order_by = ( $order_by === 'ASC' ) ? SORT_ASC : SORT_DESC ;
        
        if (empty($data) or empty($sort_by)) return $data;

        $ordered = array();
        foreach ($data as $key => $value){
            $ordered[$value[$sort_by]] = $value;
        }

        ksort($ordered, $order_by);

        return array_values($ordered);

    }

    public function embed_video( $video ) {
        if ( !$video ){
            return '';
        }

        if ( strpos( $video, 'watch' ) == false) {
            return '';
        }

        return str_replace('watch?v=', 'embed/', $video); // v
    }

    public function timCartDetail( $bookingCart, $content_language, $option='' ){

        $plugin_name = $this->plugin_name;
        $plugin_url  = $this->plugin_url;

        $url_checkout = $this->home_url .'checkout/';

        $options = get_option( TIM_TRAVEL_MANAGER_CREDENTIALS );

        $bookingCurrency   = $bookingCart->currency;
        $totalBookingItems = isset($bookingCart->booking_items) ? count($bookingCart->booking_items) : 0;

        $template_path = WP_PLUGIN_DIR .'/'. $this->plugin_name .'/public/templates/cart/tim-cart-detail.php';

        require_once $template_path;
    }

    public function timCartSummary( $bookingCart ){

        $plugin_name = $this->plugin_name;
        $url_cart    = $this->home_url .'cart/';

        $options = get_option( TIM_TRAVEL_MANAGER_CREDENTIALS );

        $bookingCurrency   = $bookingCart->currency;
        $totalBookingItems = count($bookingCart->booking_items);

        $template_path = WP_PLUGIN_DIR .'/'. $this->plugin_name .'/public/templates/cart/tim-cart-summary.php';

        require_once $template_path;
    }

    public function timCheckoutDetail( $bookingCart, $content_language, $isClientLogged ){

        $plugin_name = $this->plugin_name;
        $plugin_url  = $this->plugin_url;
        $order_url   = $this->home_url .'order/';
        $url_cart    = $this->home_url .'cart/';

        // $options = get_option( TIM_TRAVEL_MANAGER_CREDENTIALS ); // check if needed

        $bookingCurrency   = $bookingCart->currency;
        $totalBookingItems = count($bookingCart->booking_items);

        $paymentGateways = $bookingCart->payment_gateways;

        $countries = $this->get_postmeta_list( TIM_TRAVEL_MANAGER_POST_TYPE_COUNTRIES );

        $template_path = WP_PLUGIN_DIR .'/'. $this->plugin_name .'/public/templates/checkout/checkout-detail.php';

        require_once $template_path;
    }

    public function timCheckoutTotals( $bookingCart ) {

        $plugin_name = $this->plugin_name;

        $options = get_option( TIM_TRAVEL_MANAGER_GENERAL_OPTIONS );

        $discount_coupon_enabled = $options['discount_coupon_enabled']; // isset ?

        $bookingCurrency = $bookingCart->currency;

        $template_path = WP_PLUGIN_DIR .'/'. $this->plugin_name .'/public/templates/checkout/checkout-totals.php';

        require_once $template_path;

    }

    public function timPaymentDetails( $booking ) {

        $plugin_name = $this->plugin_name;

        $template_path = WP_PLUGIN_DIR .'/'. $this->plugin_name .'/public/templates/order/payment-details.php';

        require_once $template_path;

    }

    public function timPaymentForm( $bookingCart, $content_language, $option, $policiesAccepted ) {

        if ( ! $option ) {
            return;
        }

        $options = get_option( TIM_TRAVEL_MANAGER_CREDENTIALS ); // for paypal data

        $plugin_name = $this->plugin_name;
        $plugin_url = $this->plugin_url;
        $url_cart = $this->home_url .'cart/';

        $template_path = WP_PLUGIN_DIR .'/'. $this->plugin_name .'/public/templates/checkout/payment-gateways/'. $option .'.php';

        require_once $template_path;

    }

    public function timOrderCompleted( $booking ){

        $plugin_name = $this->plugin_name;
        $order_url   = $this->home_url .'order/';

        $template_path = WP_PLUGIN_DIR .'/'. $this->plugin_name .'/public/templates/order/tim-order-completed.php';

        require_once $template_path;
    }

    public function timOrderDetail( $booking ){

        $plugin_name = $this->plugin_name;

        $options = get_option( TIM_TRAVEL_MANAGER_CREDENTIALS );

        $template_path = WP_PLUGIN_DIR .'/'. $plugin_name .'/public/templates/order/tim-order-detail.php';

        require_once $template_path;
    }

    public function timVerifyOrderForm(){

        $plugin_name = $this->plugin_name;
        $order_url   = $this->home_url .'order/';

        $template_path = WP_PLUGIN_DIR .'/'. $plugin_name .'/public/templates/order/tim-verify-order-form.php';

        require_once $template_path;
    }

    public function timLoginForm(){

        $plugin_name = $this->plugin_name;
        $order_url   = $this->home_url .'order/';

        $template_path = WP_PLUGIN_DIR .'/'. $plugin_name .'/public/templates/my-account/tim-login-form.php';

        require_once $template_path;
    }

    public function timSignupForm( $content_language ){

        $plugin_name  = $this->plugin_name;
        $countries    = $this->get_postmeta_list( TIM_TRAVEL_MANAGER_POST_TYPE_COUNTRIES );

        $template_path = WP_PLUGIN_DIR .'/'. $this->plugin_name .'/public/templates/my-account/tim-signup-form.php';

        require_once $template_path;
    }

    public function timPasswordRecoveryForm(){

        $plugin_name = $this->plugin_name;

        $template_path = WP_PLUGIN_DIR .'/'. $this->plugin_name .'/public/templates/my-account/tim-password-recovery-form.php';

        require_once $template_path;
    }

    public function timEditPasswordForm(){

        $plugin_name = $this->plugin_name;

        $template_path = WP_PLUGIN_DIR .'/'. $this->plugin_name .'/public/templates/my-account/tim-edit-password-form.php';

        require_once $template_path;
    }

    public function timMyAccount( $action, $content_language ){

        $plugin_name = $this->plugin_name;

        $template_path = WP_PLUGIN_DIR .'/'. $this->plugin_name .'/public/templates/my-account/tim-my-account.php';

        require_once $template_path;

    }

    public function timClientProfile( $clientSession, $content_language ){

        $plugin_name = $this->plugin_name;
        $countries   = $this->get_postmeta_list( TIM_TRAVEL_MANAGER_POST_TYPE_COUNTRIES );

        $template_path = WP_PLUGIN_DIR .'/'. $this->plugin_name .'/public/templates/my-account/tim-client-profile.php';

        require_once $template_path;

    }

    public function timListOrders( $bookings ){

        $plugin_name = $this->plugin_name;
        $order_url   = $this->home_url .'order/';

        $template_path = WP_PLUGIN_DIR .'/'. $this->plugin_name .'/public/templates/my-account/tim-list-orders.php';

        require_once $template_path;

    }

    public function get_modal_data( $option, $id, $type, $priceListId, $content_language ){

        $plugin_name = $this->plugin_name;
        $public_data = $this;

        $plugin_nonce = TIM_TRAVEL_MANAGER_PLUGIN_NONCE;
        $plugin_dir = WP_PLUGIN_DIR;

        $plugin_api = new Tim_Travel_Manager_Api( $plugin_name, $plugin_nonce, $public_data );

        $default_content_lang = $this->get_default_content_lang();

        switch ( $option ) {
            // case 'hotel-room':
            //     $post_type = 'tim_hotel';
            //     $postmeta  = $public_data->get_postmeta_item_by_value( $post_type, 'id', $id, 'hotel_rooms' );

            //     $file = 'templates/hotels/hotel-rooms/modals/tim-hotel-room-detail-modal.php';
            // break;

            case 'cart':
                $file = 'templates/cart/modals/tim-cart-item-'. $type .'.php';
            break;

            case 'booking-inactivity':
                $file = 'templates/cart/modals/tim-booking-inactivity.php';
            break;

            case 'policies':
                $file = 'templates/checkout/modals/tim-cancellation-policies.php';
            break;
            
            default:
                $post_type      = 'tim_'. $option;
                $post_type_meta = $post_type .'_meta';

                // $postmeta  = $public_data->get_postmeta_item_by_value( $post_type, $option .'_id', $id ); // problems with wpml plugin

                $postmeta = $this->find_post_meta_by_product_id( $post_type_meta, $option .'_id', $id );

                // var_dump($postmeta);

                $file = 'templates/'. $option .'s/modals/tim-'. $option .'-detail-modal.php';
            break;
        }

        session_start(); // !important to initialize session in modals
        
        // Session/Default
        $currency_value  = $public_data->get_currency_value( TIM_TRAVEL_MANAGER_POST_TYPE_CURRENCIES );
        $currency_id     = ( $currency_value['id'] != '' )     ? $currency_value['id']     : $currency_value->id;
        $currency_code   = ( $currency_value['code'] != '' )   ? $currency_value['code']   : $currency_value->code;   
        $currency_symbol = ( $currency_value['symbol'] != '' ) ? $currency_value['symbol'] : $currency_value->symbol;

        $file = $plugin_dir .'/'. $plugin_name .'/public/'. $file;

        require_once $file;

    }

    public function find_post_meta_by_product_id( $post_type_meta, $findBy, $id ){

        global $wpdb;
                
        $query = "SELECT * FROM wp_postmeta WHERE meta_key = '". $post_type_meta ."'";
        $items = $wpdb->get_results( $query, OBJECT );

        foreach ( $items as $item ) {
            $postmeta = get_post_meta( $item->post_id, $post_type_meta, true);

            if ( $postmeta[$findBy] === $id ){
                return $postmeta;
            }
        }

    }

    public function find_item_ids_in_array( $ids, $array, $field, $content_language ) {
        $data = '';
        if (is_array($ids) || is_object($ids)) {
            if ( count($ids) ) {
                $data = array();

                foreach ( $ids as $id ) {
                    foreach ( $array as $item ) {
                        if ( $id === $item['id'] ){
                            if (isset($item[$field]->$content_language)){
                                $name = $item[$field]->$content_language;
                            } else {
                                // Default lenguage
                                // Just in case not translation found
                                $name = $item[$field]->en;
                            }

                            array_push( $data, $name );

                            break;
                        }
                    }
                }
            }
        }

        return $data;

    }

    public function find_item_in_array( $array, $key, $value ) {
      
        foreach ( $array as $item ) {
            if ( $item[$key] === $value ) {
                return $item;
            }
        }

        return false;

    }

    public function find_item_in_array_object( $array, $key, $value ) {
        // if ( ! $array) {
            // return false;
        // }

        foreach ( $array as $item ) {
            if ( $item->$key === $value ){
                return $item;
            }
        }

        return false;

    }

    // Get only the name from taggings, if tag->lang = language
    public function get_taggings_by_language( $taggings, $language ){
      
        // $array = array_filter($taggings, function ($tag) use ($language) {
        //     return $tag->lang == $language;
        // });

        // return $array;

        $array = [];
        foreach ( $taggings as $tag ) {
            if ( $tag->lang && $tag->lang === $language ){
                array_push( $array, $tag->name );
            }
        }

        return $array;

    }

    public function apply_exchange_rate_conversion( $amount, $documentDateExchangeRate ){
    
        if ( $amount == 0 ){
            return 0;
        }

        $value = 0;

        switch ( $documentDateExchangeRate->conversion ) {
            case 'multiply':
                $value = $amount * $documentDateExchangeRate->value;
            break;
            case 'divide':
                $value = $amount / $documentDateExchangeRate->value;
            break;
            default:
                $value = $amount * $documentDateExchangeRate->value[0] / $documentDateExchangeRate->value[1];
            break;
        }

        return $value;

        // return (($number * 10**$decimals).round.to_f) / (10**$decimals) # 23.0087 => 23.01 Valid

        // return round( $datediff / (60 * 60 * 24) );

        // return _round_number($value)
    
    }

    public function round_number( $number, $decimals = 2 ){

        return round( $number, $decimals );

        // return (($number * 10**$decimals).round.to_f) / (10**$decimals); # 23.0087 => 23.01 Valid

    }

    // ??
    function group_product_locations_by_id( $list, $option = '' ) {

        $locations = array();
        
        if (is_array($list) || is_object($list)) {
            foreach ( $list as $item ) {
                $locationId = $item[$option . 'location_id'];

                if ( ! isset($locations[$locationId]) ) {
                    $name = $item[$option . 'location_name'];

                    $locations[$locationId] = array(
                        'id' => $locationId, 
                        'name' => $name,
                        // 'departure_location_id' => $item['departure_location_id'],
                        // 'arrival_location_id' => $item['arrival_location_id']
                    );
                }
            }
        }

        return $locations;

    }

    function find_place_by_name( $array, $value, $content_language, $default_content_lang ) {
      
        foreach ( $array as $item ) {
            if ( $item['name']->$content_language === $value || $item['name']->$default_content_lang === $value  ) {
                return $item;
            }
        }

        return false;

    }

}


 // global $sitepress;
// echo $type = get_post_type(get_the_ID());
// $sitepress->get_default_languagex() - what about if default_content_lang is different than sitepress language ?   
// echo $sitepress->get_default_languagex();

/*if ( ! $original_post_id ){
    // $this->get_content_languagex();
    $language = $sitepress->get_default_languagex();

    $original_post_id = $wpdb->get_var("SELECT element_id FROM wp_icl_translations WHERE element_type = 'post_". $post_type ."' AND language_code = '". $default_content_lang ."' LIMIT 1");
}*/

// return icl_object_id( get_the_ID(), $post_type, false, ICL_LANGUAGE_CODE );

?>