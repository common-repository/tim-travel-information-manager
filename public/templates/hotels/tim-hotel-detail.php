<?php
/*
Template Name: Tim Hotel Detail Template
*/

$plugin_name = TIM_TRAVEL_MANAGER_PLUGIN_NAME;

$plugin_url = WP_PLUGIN_URL .'/'. $plugin_name;
$plugin_dir = WP_PLUGIN_DIR .'/'. $plugin_name;
$layour_dir = $plugin_dir .'/public/layouts/';

$post_type      = TIM_TRAVEL_MANAGER_POST_TYPE_HOTELS;
$post_type_meta = $post_type .'_meta';

$general_options = get_option( TIM_TRAVEL_MANAGER_GENERAL_OPTIONS );

$themeLayoutIdDefault = 1;
$themeLayoutId        = $general_options['theme_layout_id'];

// Plugin theme selected
if ( $themeLayoutId ){
	$themeLayout = $layour_dir . $themeLayoutId .'/hotels/detail.php';
	$themeLayout = file_exists($themeLayout) ? $themeLayout : $layour_dir . $themeLayoutIdDefault .'/detail.php';
}
else{
    $themeLayout = get_stylesheet_directory() .'/tim-hotel-detail.php';
}

$public_data = new Tim_Travel_Manager_Public_Data( $plugin_name );

$original_post_id = $public_data->get_original_post_id( $post_type );
$postmeta         = get_post_meta( $original_post_id, $post_type_meta, true); // Get item post meta

$hotel_categories      = $public_data->get_miscellaneous( TIM_TRAVEL_MANAGER_POST_TYPE_CATEGORIES, 'hotel' );
$hotel_facilities      = $public_data->get_miscellaneous( TIM_TRAVEL_MANAGER_POST_TYPE_FACILITIES, 'hotel' );
$hotel_room_facilities = $public_data->get_miscellaneous( TIM_TRAVEL_MANAGER_POST_TYPE_FACILITIES, 'hotel_room' );

$content_language = $public_data->get_content_language();

$currency_value  = $public_data->get_currency_value( TIM_TRAVEL_MANAGER_POST_TYPE_CURRENCIES );
$currency_id     = ( $currency_value['id'] !== '' )     ? $currency_value['id']      : $currency_value->id;    // Session/Default
$currency_code   = ( $currency_value['code'] !== '' )   ? $currency_value['code']   : $currency_value->symbol; // Session/Default
$currency_symbol = ( $currency_value['symbol'] !== '' ) ? $currency_value['symbol'] : $currency_value->symbol; // Session/Default

$categories = $public_data->find_item_ids_in_array( $postmeta['category_ids'], $hotel_categories, 'name', $content_language );
$facilities = $public_data->find_item_ids_in_array( $postmeta['facility_ids'], $hotel_facilities, 'name', $content_language );

$hotel_rooms = array();
foreach ( $postmeta['hotel_rooms'] as $hotel_room ) {
    // var_dump($hotel_room->photos);

    $room_facilities = $public_data->find_item_ids_in_array( $hotel_room->facility_ids, $hotel_room_facilities, 'name', $content_language );

    $room_photos = '';
    if ( count($hotel_room->photos) ){
        $room_photos = array();
        foreach ( $hotel_room->photos as $photo ) {
            $photo = array(
                'image' => $photo->thumb, 
                'title' => $photo->title->$content_language
            );

            array_push( $room_photos, $photo );
        }
    }

    $room = array(
        'id'                  => $hotel_room->id, 
        'logo'                => $hotel_room->logo, 
        'name'                => $hotel_room->name->$content_language, 
        'ideal_for'           => $hotel_room->ideal_for->$content_language, 
        'description'         => $hotel_room->description->$content_language, 
        'bed_configuration'   => $hotel_room->bed_configuration->$content_language, 
        'max_occupancy'       => $hotel_room->max_occupancy, 
        'children_allowed'    => $hotel_room->children_allowed, 
        'total_bedrooms'      => $hotel_room->total_bedrooms, 
        'size'                => $hotel_room->size, 
        'meals_included'      => $hotel_room->meals_included, 
        'wifi'                => $hotel_room->wifi, 
        'not_smoking'         => $hotel_room->not_smoking, 
        
        'room_type_name'      => $hotel_room->room_type->name->$content_language, 
        'occupancy_type_name' => $hotel_room->occupancy_type->name->$content_language, 
        'facilities'          => $room_facilities, 
        'photos'              => $room_photos, 
        'url_video'           => $public_data->embed_video( $hotel_room->url_video->$content_language )
    );

    array_push( $hotel_rooms, $room );
}

if ( count($postmeta['photos']) ){
    $photos = array();
    foreach ( $postmeta['photos'] as $photo ) {
        $photo = array(
            'image' => $photo->image, 
            'title' => $photo->title->$content_language
        );

        array_push( $photos, $photo );
    }
}

$item['hotel_id']           = $postmeta['hotel_id'];
$item['geo_lat']            = $postmeta['geo_lat'];
$item['geo_lng']            = $postmeta['geo_lng'];
$item['geo_zoom']           = $postmeta['geo_zoom'];
$item['stars_rating']       = $postmeta['stars_rating'];
$item['total_rooms']        = $postmeta['total_rooms'];
$item['check_in']           = $postmeta['check_in'];
$item['check_out']          = $postmeta['check_out'];
$item['has_allotments']     = ($postmeta['has_allotments']) ? $postmeta['has_allotments'] : 0 ;
$item['min_children_age']   = $postmeta['min_children_age'];
$item['max_children_age']   = $postmeta['max_children_age'];

$item['hotel_rooms']        = $hotel_rooms;
$item['location_name']      = $postmeta['location']->name->$content_language;
$item['categories']         = $categories;
$item['facilities']         = $facilities;
$item['related_hotel_ids']  = $postmeta['related_hotel_ids'];
$item['product_category_name'] = $postmeta['product_category']->name->$content_language;
$item['provider_id']        = $postmeta['provider_id'];

$item['id']                = $postmeta['id'];
$item['name']              = $postmeta['name']->$content_language;
$item['description']       = $postmeta['description']->$content_language;
$item['itinerary']         = $postmeta['itinerary']->$content_language;
$item['url_video']         = $public_data->embed_video( $postmeta['url_video']->$content_language );
$item['address']           = $postmeta['address']->$content_language;
$item['notes']             = $postmeta['notes']->$content_language;
$item['policies']          = $postmeta['policies']->$content_language;
$item['photos']            = $photos;

//$item['taggings'] = ( count($postmeta['taggings']) ) ? $public_data->get_taggings_by_language($postmeta['taggings'], $content_language) : '';
$item['taggings'] = '';
if (is_array($postmeta['taggings']) || is_object($postmeta['taggings'])) {
    $item['taggings'] = ( count($postmeta['taggings']) ) ? $public_data->get_taggings_by_language($postmeta['taggings'], $content_language) : '';
}

$plugin_api               = new Tim_Travel_Manager_Api( $plugin_name, '', $public_data );
$todayPrices              = $plugin_api->get_product_list_today_prices( 'hotel', $item['id'] );
$productsPrices           = $todayPrices->productsPrices[0]->price;
$documentDateExchangeRate = $todayPrices->documentDateExchangeRate;
$rate_from                = $public_data->apply_exchange_rate_conversion( $productsPrices, $documentDateExchangeRate );
$item['rate_from']        = $currency_symbol . $public_data->round_number($rate_from);

// $plugin_api = new Tim_Travel_Manager_Api( $plugin_name, '', $public_data );
// $documentDateExchangeRate = $plugin_api->check_document_date_exchange_rate();

// $rate_from         = $public_data->apply_exchange_rate_conversion( $postmeta['rate_from'], $documentDateExchangeRate );
// $item['rate_from'] = $currency_symbol . $rate_from;

$item['max_occupancy_rooms'] = $public_data->get_min_or_max_array_value( $hotel_rooms, 'max_occupancy', 'max' );


// For related items
$tim_travel_manager_hotel = new Tim_Travel_Manager_Public_Hotel_Controller(
    $plugin_name, $public_data, $content_language, $currency_value, $post_type
);

$check_rate_widget = $plugin_dir .'/public/widgets/availability/tim-hotel-check-rate.php';
require_once( $check_rate_widget );


// Add custom title to post
$timDetailTitle = $postmeta['seo_title']->$content_language ? 
$postmeta['seo_title']->$content_language : $postmeta['name']->$content_language;
function tim_custom_page_title() {
    global $timDetailTitle;
    return $timDetailTitle;
}
add_action( 'pre_get_document_title', 'tim_custom_page_title' );

get_header();

?>

<div class="tim_wrapper">
    <?php
    require_once $themeLayout;
    ?>
</div>
<div class="tim_spinner"></div>

<input type="hidden" id="timUserCurrency" value="<?php echo $currency_id; ?>" />
<input type="hidden" id="timUserCurrencyCode" value="<?php echo $currency_code; ?>" />
<input type="hidden" id="timUserCurrencySymbol" value="<?php echo $currency_symbol; ?>" />

<input type="hidden" id="timProviderId" value="<?php echo $item['provider_id']; ?>" />
<input type="hidden" id="timHotelId" value="<?php echo $item['hotel_id']; ?>" />
<input type="hidden" id="timHotelContentId" value="<?php echo $item['id']; ?>" />
<input type="hidden" id="timHotelHasAllotments" value="<?php echo $item['has_allotments']; ?>" />

<script type="text/javascript">timInitializeMap();</script>
<?php
// Plugin theme selected
if ( $themeLayoutId ){
    ?><script type="text/javascript">timLoadSwiper();</script><?php
}

wp_reset_query();
get_footer();

?>