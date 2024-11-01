<?php
/*
Template Name: Tim Transportation Detail Template
*/

$plugin_name = TIM_TRAVEL_MANAGER_PLUGIN_NAME;

$plugin_url = WP_PLUGIN_URL .'/'. $plugin_name;
$plugin_dir = WP_PLUGIN_DIR .'/'. $plugin_name;
$layour_dir = $plugin_dir .'/public/layouts/';

$post_type = TIM_TRAVEL_MANAGER_POST_TYPE_TRANSPORTATIONS;
$post_type_meta = $post_type .'_meta';

$general_options = get_option( TIM_TRAVEL_MANAGER_GENERAL_OPTIONS );

$themeLayoutIdDefault = 1;
$themeLayoutId = $general_options['theme_layout_id'];

$checkRateLayoutId = $general_options['transportation_check_rate_layout_id'];

// Plugin theme selected
if ( $themeLayoutId ) {
	$themeLayout = $layour_dir . $themeLayoutId .'/transportations/detail.php';
	$themeLayout = file_exists($themeLayout) ? $themeLayout : $layour_dir . $themeLayoutIdDefault .'/detail.php';
} else {
	$themeLayout = get_stylesheet_directory() .'/tim-transportation-detail.php';
}

$public_data = new Tim_Travel_Manager_Public_Data( $plugin_name );

$original_post_id = $public_data->get_original_post_id( $post_type );
$postmeta = get_post_meta( $original_post_id, $post_type_meta, true); // Get item post meta

$transportation_facilities = $public_data->get_miscellaneous( TIM_TRAVEL_MANAGER_POST_TYPE_FACILITIES, 'transportation' );
$transportation_wtobrings = $public_data->get_miscellaneous( TIM_TRAVEL_MANAGER_POST_TYPE_WTOBRING, 'transportation' );

$content_language = $public_data->get_content_language();

$currency_value = $public_data->get_currency_value( TIM_TRAVEL_MANAGER_POST_TYPE_CURRENCIES );
$currency_id = ( $currency_value['id'] !== '' ) ? $currency_value['id'] : $currency_value->id;    // Session/Default
$currency_code = ( $currency_value['code'] !== '' ) ? $currency_value['code'] : $currency_value->symbol; // Session/Default
$currency_symbol = ( $currency_value['symbol'] !== '' ) ? $currency_value['symbol'] : $currency_value->symbol; // Session/Default


// var_dump($transportation_facilities);

$facilities = $public_data->find_item_ids_in_array( $postmeta['facility_ids'], $transportation_facilities, 'name', $content_language );
$wtobrings = $public_data->find_item_ids_in_array( $postmeta['what_to_bring_ids'], $transportation_wtobrings, 'name', $content_language );

$transportationSchedules = [];
foreach ( $postmeta['transportation_schedules'] as $schedule ) {
    array_push( $transportationSchedules, $public_data->format_hour( $schedule->departure, $content_language ) );
}

$item['transportation_id'] = $postmeta['transportation_id'];
$item['schedule_type']     = $postmeta['schedule_type'];
$item['distance']          = $postmeta['distance'];
$item['duration']          = $postmeta['duration'] .' '. __( $postmeta['duration_time_unit'], $plugin_name );
$item['min_pax_required']  = $postmeta['min_pax_required'];
$item['max_pax_allowed']   = $postmeta['max_pax_allowed'];
$item['min_children_age']  = $postmeta['min_children_age'];
$item['max_children_age']  = $postmeta['max_children_age'];

$item['transportation_schedules']    = $transportationSchedules;
$item['transportation_route_points'] = $postmeta['transportation_route_points'];
$item['facilities']                  = $facilities;
$item['wtobrings']                   = $wtobrings;
$item['departure_location_name']     = $postmeta['departure_location']->name->$content_language;
$item['departure_location_geo_lat']  = $postmeta['departure_location']->geo_lat;
$item['departure_location_geo_lng']  = $postmeta['departure_location']->geo_lng;
$item['arrival_location_name']       = $postmeta['arrival_location']->name->$content_language;
$item['arrival_location_geo_lat']    = $postmeta['arrival_location']->geo_lat;
$item['arrival_location_geo_lng']    = $postmeta['arrival_location']->geo_lng;
$item['related_transportation_ids']  = $postmeta['related_transportation_ids'];
$item['product_category_name']       = $postmeta['product_category']->name->$content_language;
$item['provider_id']                 = $postmeta['provider_id'];

$item['id']          = $postmeta['id'];
$item['name']        = $postmeta['name']->$content_language;
$item['description'] = $postmeta['description']->$content_language;
$item['itinerary']   = $postmeta['itinerary']->$content_language;
$item['url_video']   = $public_data->embed_video( $postmeta['url_video']->$content_language );
$item['address']     = $postmeta['address']->$content_language;
$item['notes']       = $postmeta['notes']->$content_language;
$item['policies']    = $postmeta['policies']->$content_language;
$item['photos']      = $postmeta['photos'];

//$item['taggings'] = ( count($postmeta['taggings']) ) ? $public_data->get_taggings_by_language($postmeta['taggings'], $content_language) : '';
$item['taggings'] = '';
if (is_array($postmeta['taggings']) || is_object($postmeta['taggings'])) {
    $item['taggings'] = ( count($postmeta['taggings']) ) ? $public_data->get_taggings_by_language($postmeta['taggings'], $content_language) : '';
}

$plugin_api               = new Tim_Travel_Manager_Api( $plugin_name, '', $public_data );
$todayPrices              = $plugin_api->get_product_list_today_prices( 'transportation', $item['id'] );
$productsPrices           = $todayPrices->productsPrices[0]->price;
$documentDateExchangeRate = $todayPrices->documentDateExchangeRate;
$rate_from                = $public_data->apply_exchange_rate_conversion( $productsPrices, $documentDateExchangeRate );
$item['rate_from']        = $currency_symbol . $public_data->round_number($rate_from);

// For related items
$tim_travel_manager_transportation = new Tim_Travel_Manager_Public_Transportation_Controller( $plugin_name, $public_data, $content_language, $currency_value, $post_type );

$check_rate_widget = $plugin_dir .'/public/widgets/availability/tim-transportation-check-rate.php';
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

<input type="hidden" id="timMapType" value="waypoints" />  

<input type="hidden" id="timUserCurrency" value="<?php echo $currency_id; ?>" />
<input type="hidden" id="timUserCurrencyCode" value="<?php echo $currency_code; ?>" />
<input type="hidden" id="timUserCurrencySymbol" value="<?php echo $currency_symbol; ?>" />

<input type="hidden" id="timProviderId" value="<?php echo $item['provider_id']; ?>" />
<input type="hidden" id="timTransportationId" value="<?php echo $item['transportation_id']; ?>" />
<input type="hidden" id="timTransportationContentId" value="<?php echo $item['id']; ?>" />
<input type="hidden" id="timTransportationScheduleType" value="<?php echo $item['schedule_type']; ?>" />

<script type="text/javascript">
<?php
$departureLat = ( $item['departure_location_geo_lat'] ) ? $item['departure_location_geo_lat'] : 0;
$departureLng = ( $item['departure_location_geo_lng'] ) ? $item['departure_location_geo_lng'] : 0;
$arrivalLat   = ( $item['arrival_location_geo_lat'] ) ? $item['arrival_location_geo_lat'] : 0;
$arrivalLng   = ( $item['arrival_location_geo_lng'] ) ? $item['arrival_location_geo_lng'] : 0;
?>
var timMapLocations = {
	departure: { name: '<?php echo $item['departure_location_name']; ?>', lat: <?php echo $departureLat; ?>, lng: <?php echo $departureLng; ?> },
	arrival:   { name: '<?php echo $item['arrival_location_name']; ?>',   lat: <?php echo $arrivalLat; ?>,   lng: <?php echo $arrivalLng; ?> }
};
<?php
if ( count( $item['transportation_route_points'] ) ){
	?>
	var points = [
	<?php
	foreach ( $item['transportation_route_points'] as $route ) {
		$stopOnRoute = ( $route->stop_on_route ) ? 1 : 0;
		$locationLat = ( $route->location->geo_lat ) ? $route->location->geo_lat : 0;
		$locationLng = ( $route->location->geo_lng ) ? $route->location->geo_lng : 0;
		?>
		{
			description: '<?php echo $route->description->$content_language; ?>', stop_on_route: <?php echo $stopOnRoute; ?>, 
			location: { name: '<?php echo $route->location->name->$content_language; ?>', lat: <?php echo $locationLat; ?>, lng: <?php echo $locationLng; ?> }
		},
		<?php
		//name: echo $route->name->$content_language; 
	}
	?>
	];
	timMapLocations.waypoints = points;
	<?php
}
?>
timInitializeMap('', timMapLocations);
</script>
<?php

// Plugin theme selected
if ( $themeLayoutId ){
	?><script type="text/javascript">timLoadSwiper()</script><?php
}

wp_reset_query();
get_footer();

?>