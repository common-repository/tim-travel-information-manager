<?php
/*

Template Name: Tim Tour Detail Template

*/

$plugin_name = TIM_TRAVEL_MANAGER_PLUGIN_NAME;

$plugin_url = WP_PLUGIN_URL .'/'. $plugin_name;
$plugin_dir = WP_PLUGIN_DIR .'/'. $plugin_name;
$layour_dir = $plugin_dir .'/public/layouts/';

$post_type = TIM_TRAVEL_MANAGER_POST_TYPE_TOURS;
$post_type_meta = $post_type .'_meta';

$general_options = get_option( TIM_TRAVEL_MANAGER_GENERAL_OPTIONS );

$themeLayoutIdDefault = 1;
$themeLayoutId = $general_options['theme_layout_id'];

$checkRateLayoutId = $general_options['tour_check_rate_layout_id'];

// Plugin theme selected
if ( $themeLayoutId ) {
	$themeLayout = $layour_dir . $themeLayoutId .'/tours/detail.php';
	$themeLayout = file_exists($themeLayout) ? $themeLayout : $layour_dir . $themeLayoutIdDefault .'/detail.php';
} else {
	$themeLayout = get_stylesheet_directory() .'/tim-tour-detail.php';
}

// $general_options = get_option( TIM_TRAVEL_MANAGER_GENERAL_OPTIONS );
// $googleMap = $general_options['google_map'];

$public_data = new Tim_Travel_Manager_Public_Data( $plugin_name );

$original_post_id = $public_data->get_original_post_id( $post_type );
$postmeta = get_post_meta( $original_post_id, $post_type_meta, true); // Get item post meta

$tour_categories = $public_data->get_miscellaneous( TIM_TRAVEL_MANAGER_POST_TYPE_CATEGORIES, 'tour' );
$tour_facilities = $public_data->get_miscellaneous( TIM_TRAVEL_MANAGER_POST_TYPE_FACILITIES, 'tour' );
$tour_wtobrings = $public_data->get_miscellaneous( TIM_TRAVEL_MANAGER_POST_TYPE_WTOBRING, 'tour' );

$content_language = $public_data->get_content_language();

// Session/Default
$currency_value = $public_data->get_currency_value( TIM_TRAVEL_MANAGER_POST_TYPE_CURRENCIES );
$currency_id = ( $currency_value['id'] !== '' )     ? $currency_value['id']     : $currency_value->id;
$currency_code = ( $currency_value['code'] !== '' )   ? $currency_value['code']   : $currency_value->symbol;
$currency_symbol = ( $currency_value['symbol'] !== '' ) ? $currency_value['symbol'] : $currency_value->symbol; 

$categories = $public_data->find_item_ids_in_array( $postmeta['category_ids'], $tour_categories, 'name', $content_language );
$facilities = $public_data->find_item_ids_in_array( $postmeta['facility_ids'], $tour_facilities, 'name', $content_language );
$wtobrings = $public_data->find_item_ids_in_array( $postmeta['what_to_bring_ids'], $tour_wtobrings, 'name', $content_language );

$photos = array();
foreach ( $postmeta['photos'] as $photo ) {
    $photo = array(
        'image' => $photo->image, 
        'title' => $photo->title->$content_language
    );

    array_push( $photos, $photo );
}

$tour_option = $postmeta['tour_options'][0];

$schedules = '';
foreach ( $tour_option->tour_option_schedules as $schedule ) {
	$schedules .= $public_data->format_hour( $schedule->departure, $content_language ). ', ';
}

$item['tour_id'] = $postmeta['tour_id'];
$item['geo_lat'] = $postmeta['geo_lat'];
$item['geo_lng'] = $postmeta['geo_lng'];
$item['geo_zoom'] = $postmeta['geo_zoom'];
// $item['departures'] = $postmeta['departures'];
$item['departures'] = substr($schedules, 0, -2);
$item['duration'] = $postmeta['duration'] .' '. __( $postmeta['duration_time_unit'], $plugin_name );
$item['min_pax_required'] = $postmeta['min_pax_required'];
$item['max_pax_allowed']  = $postmeta['max_pax_allowed'];
$item['min_children_age'] = $postmeta['min_children_age'];
$item['max_children_age'] = $postmeta['max_children_age'];

$item['tour_options'] = $postmeta['tour_options'];
// $item['location_id'] = $postmeta['location']->id;
$item['location_name'] = $postmeta['location']->name->$content_language;
$item['categories'] = $categories;
$item['facilities'] = $facilities;
$item['wtobrings'] = $wtobrings;
$item['related_tour_ids'] = $postmeta['related_tour_ids'];
$item['provider_id'] = $postmeta['provider_id'];

$item['default_pickup_place_id'] = $postmeta['default_pickup_place_id'];
$item['default_dropoff_place_id'] = $postmeta['default_dropoff_place_id'];

$item['id'] = $postmeta['id'];
$item['name'] = $postmeta['name']->$content_language;
$item['description'] = $postmeta['description']->$content_language;
$item['itinerary'] = $postmeta['itinerary']->$content_language;
$item['url_video'] = $public_data->embed_video( $postmeta['url_video']->$content_language );
$item['address'] = $postmeta['address']->$content_language;
$item['notes'] = $postmeta['notes']->$content_language;
$item['policies'] = $postmeta['policies']->$content_language;
$item['photos'] = $photos;

// $item['taggings'] = ( count($postmeta['taggings']) ) ? $public_data->get_taggings_by_language($postmeta['taggings'], $content_language) : '';
$item['taggings'] = '';
if (is_array($postmeta['taggings']) || is_object($postmeta['taggings'])) {
	$item['taggings'] = ( count($postmeta['taggings']) ) ? $public_data->get_taggings_by_language($postmeta['taggings'], $content_language) : '';
}

$plugin_api = new Tim_Travel_Manager_Api( $plugin_name, '', $public_data );
$todayPrices = $plugin_api->get_product_list_today_prices( 'tour', $item['id'] );
$productsPrices = $todayPrices->productsPrices[0]->price;
$documentDateExchangeRate = $todayPrices->documentDateExchangeRate;
$rate_from = $public_data->apply_exchange_rate_conversion( $productsPrices, $documentDateExchangeRate );
$item['rate_from'] = $currency_symbol . $public_data->round_number($rate_from);

// $item['rate_from'] = '$100';

// For related items
$tim_travel_manager_tour = new Tim_Travel_Manager_Public_Tour_Controller( $plugin_name, $public_data, $content_language, $currency_value, $post_type );

$check_rate_widget = $plugin_dir .'/public/widgets/availability/tim-tour-check-rate.php';
require_once $check_rate_widget;


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
<input type="hidden" id="timTourId" value="<?php echo $item['tour_id']; ?>" />
<input type="hidden" id="timTourContentId" value="<?php echo $item['id']; ?>" />

<?php
if ($item['default_pickup_place_id']) {
	?>
	<input type="hidden" id="timTourDefaultPickupPlaceId" value="<?php echo $item['default_pickup_place_id']; ?>" />
	<?php
}

if ($item['default_dropoff_place_id']) {
	?>
	<input type="hidden" id="timTourDefaultDropoffPlaceId" value="<?php echo $item['default_dropoff_place_id']; ?>" />
	<?php
}
?>

<script type="text/javascript">timInitializeMap();</script>
<?php

// Plugin theme selected
if ( $themeLayoutId ) {
	?><script type="text/javascript">timLoadSwiper();</script><?php
}

wp_reset_query();
get_footer();


// $item['rate_from']        = $currency_symbol . $postmeta['rate_from'];
// $documentDateExchangeRate = $plugin_api->check_document_date_exchange_rate();
// $rate_from         = $public_data->apply_exchange_rate_conversion( $postmeta['rate_from'], $documentDateExchangeRate );
// $item['rate_from'] = $currency_symbol . $rate_from;

?>