<?php
/*
Template Name: Tim Location Detail Template
*/

/*$plugin_name = TIM_TRAVEL_MANAGER_PLUGIN_NAME;

$plugin_url = WP_PLUGIN_URL .'/'. $plugin_name;
$plugin_dir = WP_PLUGIN_DIR .'/'. $plugin_name;
$layour_dir = $plugin_dir .'/public/layouts/';

$post_type      = TIM_TRAVEL_MANAGER_POST_TYPE_LOCATIONS;
$post_type_meta = $post_type .'_meta';

$theme_options = get_option( TIM_TRAVEL_MANAGER_GENERAL_OPTIONS );

$themeLayoutIdDefault = 1;
$themeLayoutId        = $theme_options['theme_layout_id'];

// Plugin theme selected
if ( $themeLayoutId ){
    $themeLayout = $layour_dir . $themeLayoutId .'/locations/detail.php';
    $themeLayout = file_exists($themeLayout) ? $themeLayout : $layour_dir . $themeLayoutIdDefault .'/detail.php';
}
else{
    //$themeLayout = get_template_directory() .'/tim-location-detail.php';
    $themeLayout = get_stylesheet_directory() .'/tim-location-detail.php';
}

// Get item post meta
$postmeta = get_post_meta( get_the_ID(), $post_type_meta, true);

$public_data = new Tim_Travel_Manager_Public_Data( $plugin_name );
$plugin_api  = new Tim_Travel_Manager_Api( $plugin_name, '', $public_data );

$booking_id = $_SESSION['tim_booking_id'];


$location_categories = $public_data->get_miscellaneous( TIM_TRAVEL_MANAGER_POST_TYPE_CATEGORIES, 'location' );

$content_language = $public_data->get_content_language();

$currency_value  = $public_data->get_currency_value( TIM_TRAVEL_MANAGER_POST_TYPE_CURRENCIES );
$currency_id     = ( $currency_value['id'] != '' )     ? $currency_value['id']     : $currency_value->id;     // Session/Default
$currency_code   = ( $currency_value['code'] != '' )   ? $currency_value['code']   : $currency_value->symbol; // Session/Default
$currency_symbol = ( $currency_value['symbol'] != '' ) ? $currency_value['symbol'] : $currency_value->symbol; // Session/Default

$categories = array();
foreach ( $postmeta['category_ids'] as $category_id ) {
    foreach ( $location_categories as $category ) {
        if ( $category_id === $category['id'] ){
            $name = $category['name']->$content_language;
            array_push( $categories, $name );

            break;
        }
    }
}

$get_country         = $public_data->get_postmeta_item_by_value( TIM_TRAVEL_MANAGER_POST_TYPE_COUNTRIES, 'id', $postmeta['country_id'], 'multiple' );
$get_parent_location = $public_data->get_postmeta_item_by_value( $post_type, 'id', $postmeta['parentLocation_id'] );
//$get_parent_location = $public_data->get_postmeta_by_id( $postmeta['parentLocation_id'], $post_type );


$item['id']                = $postmeta['id'];
$item['name']              = $postmeta['name']->$content_language;
$item['short_description'] = $postmeta['short_description']->$content_language;
$item['long_description']  = $postmeta['long_description']->$content_language;
$item['highlights']        = $postmeta['highlights']->$content_language;
$item['policies']          = $postmeta['policies']->$content_language;

if ( $postmeta['url_video']->$content_language != '' ){
    $item['url_video']     = str_replace('watch?v=', 'v/', $postmeta['url_video']->$content_language);
}

$item['country_name']        = $get_country['name']->$content_language;
$item['parentLocation_id']   = $postmeta['parentLocation_id'];
$item['parentLocation_name'] = $get_parent_location['name']->$content_language;

$item['geo_lat']           = $postmeta['geo_lat'];
$item['geo_lng']           = $postmeta['geo_lng'];
$item['geo_zoom']          = $postmeta['geo_zoom'];

$item['pictures']          = $postmeta['pictures'];
$item['categories']        = $categories;

// For related items
$hotelsByLocation = $public_data->get_hotels_by_location( TIM_TRAVEL_MANAGER_POST_TYPE_HOTELS, $item['id'] );
$tim_travel_manager_hotel = new Tim_Travel_Manager_Public_Hotel_Controller( $plugin_name, $public_data, $content_language, $currency_value, 
                                                                            TIM_TRAVEL_MANAGER_POST_TYPE_HOTELS );

// For related items
$tim_travel_manager_tour = new Tim_Travel_Manager_Public_Tour_Controller( $plugin_name, $public_data, $content_language, $currency_value, 
                                                                          TIM_TRAVEL_MANAGER_POST_TYPE_TOURS );


get_header();*/

?>
<div class="tim_wrapper">
    <?php
    if ( $postmeta['status'] === 'active' ){
        require_once $themeLayout;
    }
    else{
        echo "NO ITEM TO DISPLAY";
    }
    ?>
</div>
<div class="tim_spinner"></div>

<input type="hidden" id="timUserBooking" value="<?php echo $booking_id; ?>" />
<input type="hidden" id="timUserCurrency" value="<?php echo $currency_id; ?>" />

<input type="hidden" id="timLocationId" value="<?php echo $item['id']; ?>" />
<?php

wp_reset_query();
get_footer();
?>