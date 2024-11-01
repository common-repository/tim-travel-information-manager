<?php
/*
Template Name: Tim Package Detail Template
*/

$plugin_name = TIM_TRAVEL_MANAGER_PLUGIN_NAME;

$plugin_url = WP_PLUGIN_URL .'/'. $plugin_name;
$plugin_dir = WP_PLUGIN_DIR .'/'. $plugin_name;
$layour_dir = $plugin_dir .'/public/layouts/';

$post_type = TIM_TRAVEL_MANAGER_POST_TYPE_PACKAGES;
$post_type_meta = $post_type .'_meta';

$general_options = get_option( TIM_TRAVEL_MANAGER_GENERAL_OPTIONS );

$themeLayoutIdDefault = 1;
$themeLayoutId = $general_options['theme_layout_id'];

// Plugin theme selected
if ( $themeLayoutId ) {
	$themeLayout = $layour_dir . $themeLayoutId .'/packages/detail.php';
	$themeLayout = file_exists($themeLayout) ? $themeLayout : $layour_dir . $themeLayoutIdDefault .'/detail.php';
} else {
	$themeLayout = get_stylesheet_directory() .'/tim-package-detail.php';
}

$public_data = new Tim_Travel_Manager_Public_Data( $plugin_name );

$original_post_id = $public_data->get_original_post_id( $post_type );
$postmeta = get_post_meta( $original_post_id, $post_type_meta, true); // Get item post meta

$package_categories = $public_data->get_miscellaneous( TIM_TRAVEL_MANAGER_POST_TYPE_CATEGORIES, 'package' );
$package_facilities = $public_data->get_miscellaneous( TIM_TRAVEL_MANAGER_POST_TYPE_FACILITIES, 'package' );
$package_wtobrings = $public_data->get_miscellaneous( TIM_TRAVEL_MANAGER_POST_TYPE_WTOBRING, 'package' );

$content_language = $public_data->get_content_language();

// Session/Default
$currency_value  = $public_data->get_currency_value( TIM_TRAVEL_MANAGER_POST_TYPE_CURRENCIES );
$currency_id = ( $currency_value['id'] != '' )     ? $currency_value['id']     : $currency_value->id;
$currency_code = ( $currency_value['code'] != '' )   ? $currency_value['code']   : $currency_value->symbol;
$currency_symbol = ( $currency_value['symbol'] != '' ) ? $currency_value['symbol'] : $currency_value->symbol;


$categories = $public_data->find_item_ids_in_array( $postmeta['category_ids'], $package_categories, 'name', $content_language );
$facilities = $public_data->find_item_ids_in_array( $postmeta['facility_ids'], $package_facilities, 'name', $content_language );
$wtobrings = $public_data->find_item_ids_in_array( $postmeta['what_to_bring_ids'], $package_wtobrings, 'name', $content_language );

$package_days = array();
foreach ( $postmeta['package_days'] as $package_day ) {
    $breakfast = '';
    $lunch = '';
    $dinner = '';
    $all_inclusive = '';
    
    foreach ( $package_day->meals_included as $meal ) {
        switch ( $meal ) {
            case 'B':
                $breakfast = str_replace('B', __( 'Breakfast', $plugin_name ), $meal) . ' - ';
                break;
            case 'L':
                $lunch = str_replace('L', __( 'Lunch', $plugin_name ), $meal) . ' - ';
                break;
            case 'D':
                $dinner = str_replace('D', __( 'Dinner', $plugin_name ), $meal) . ' - ';
                break;
            case 'All':
                $all_inclusive = str_replace('All', __( 'All-inclusive', $plugin_name ), $meal) . ' - ';
                break;
        } 
    }

    $meals_included = $breakfast . $lunch . $dinner . $all_inclusive;
    $meals_included = substr($meals_included, 0, -3); // Substract last ' - '

    // $location = $public_data->get_postmeta_item_by_value( TIM_TRAVEL_MANAGER_POST_TYPE_LOCATIONS, 'id', $package_day->location_id ); // not working in production

    // echo $package_day->location_id .'<br>';
    // var_dump($package_day);

	foreach ( $package_day->hotel_rooms as $hotel_room ) {
        $hotel_room->enableHotelModal = false;
		
        if ( $hotel_room->hotel_name->$content_language ) {
            $hotel_room->hotel_name = $hotel_room->hotel_name->$content_language;

            if ( $hotel_room->hotel_published ) {
                $hotel_room->enableHotelModal = true;
            }
        } else {
            $hotel_room->hotel_name = $hotel_room->hotel_name;
        }


        if ( $hotel_room->name->$content_language ) {
            $hotel_room->name = $hotel_room->name->$content_language;
        } else {
            // $hotel_room->name = 'two';
            $hotel_room->name = $hotel_room->name->en;
            // var_dump($hotel_room);
        }

        // $hotel_room->name = $hotel_room->name->$content_language; 
	}

	foreach ( $package_day->tour_schedules as $tour_schedule ) {
        $tour_schedule->enableTourModal = false;

		if ( $tour_schedule->tour_name->$content_language ) {
            $tour_schedule->tour_name = $tour_schedule->tour_name->$content_language;
            
            if ( $tour_schedule->tour_published ) {
                $tour_schedule->enableTourModal = true;
            }
        } else {
            $tour_schedule->tour_name = $tour_schedule->tour_name;
        }

        $tour_schedule->tour_option_name = $tour_schedule->tour_option_name->$content_language;
        $tour_schedule->departure = $public_data->format_hour( $tour_schedule->departure, $content_language );
	}

	$day_facilities = $public_data->find_item_ids_in_array( $package_day->facility_ids, $package_facilities, 'name', $content_language );

	$day = array(
        'id' => $package_day->id, 
        'logo' => $package_day->logo, 
        'day_number' => $package_day->day_number, 
        'meals_included' => $meals_included, 
        'location' => $package_day->location, 
        // 'location' => $location, 
        'hotel_rooms' => $package_day->hotel_rooms, 
        'tour_schedules' => $package_day->tour_schedules, 
        'facilities' => $day_facilities, 

        'name' => $package_day->name->$content_language, 
        'included' => $package_day->included->$content_language, 
        'description' => $package_day->description->$content_language, 
        'url_video' => $public_data->embed_video( $package_day->url_video->$content_language ), 
        'included' => $package_day->included->$content_language, 
        'excluded' => $package_day->excluded->$content_language, 
        'notes' => $package_day->notes->$content_language, 
        'photos' => $day_photos
    );

    array_push( $package_days, $day );
}

// foreach ( $package_days as $package_day ) {
    // var_dump($package_day);
    // echo 'geo_lat: '. $package_day['location']->geo_lat;
// }

# Order days by day_number ASC
$package_days = $public_data->sort_array_by($package_days, 'day_number', 'ASC');

if ( count($postmeta['photos']) ) {
    $photos = array();
    foreach ( $postmeta['photos'] as $photo ) {
        $photo = array(
            'image' => $photo->image, 
            'title' => $photo->title->$content_language
        );

        array_push( $photos, $photo );
    }
}

$item['package_id'] = $postmeta['package_id'];
$item['package_code'] = $postmeta['package_code'];
$item['min_pax_required'] = $postmeta['min_pax_required'];
$item['max_pax_allowed'] = $postmeta['max_pax_allowed'];
$item['min_children_age'] = $postmeta['min_children_age'];
$item['max_children_age'] = $postmeta['max_children_age'];
$item['package_days'] = $package_days;
$item['departure_location_name'] = $postmeta['departure_location']->name->$content_language;
$item['departure_location_geo_lat'] = $postmeta['departure_location']->geo_lat;
$item['departure_location_geo_lng'] = $postmeta['departure_location']->geo_lng;
$item['arrival_location_name'] = $postmeta['arrival_location']->name->$content_language;
$item['arrival_location_geo_lat'] = $postmeta['arrival_location']->geo_lat;
$item['arrival_location_geo_lng'] = $postmeta['arrival_location']->geo_lng;
$item['categories'] = $categories;
$item['facilities'] = $facilities;
$item['wtobrings'] = $wtobrings;
$item['related_package_ids'] = $postmeta['related_package_ids'];
$item['product_category_name'] = $postmeta['product_category']->name->$content_language;
$item['provider_id'] = $postmeta['provider_id'];

$item['id'] = $postmeta['id'];
$item['name'] = $postmeta['name']->$content_language;
// $item['ideal_for'] = $postmeta['ideal_for']->$content_language;
$item['description'] = $postmeta['description']->$content_language;
$item['itinerary'] = $postmeta['itinerary']->$content_language;
$item['url_video'] = $public_data->embed_video( $postmeta['url_video']->$content_language );
$item['notes'] = $postmeta['notes']->$content_language;
$item['policies'] = $postmeta['policies']->$content_language;

$item['photos'] = $photos;

// $item['taggings'] = ( count($postmeta['taggings']) ) ? $public_data->get_taggings_by_language($postmeta['taggings'], $content_language) : '';
$item['taggings'] = '';
if (is_array($postmeta['taggings']) || is_object($postmeta['taggings'])) {
    $item['taggings'] = ( count($postmeta['taggings']) ) ? $public_data->get_taggings_by_language($postmeta['taggings'], $content_language) : '';
}

$item['days'] = count($postmeta['package_days']);
$item['nights'] = ( $item['days'] - 1 );

$plugin_api = new Tim_Travel_Manager_Api( $plugin_name, '', $public_data );
$todayPrices = $plugin_api->get_product_list_today_prices( 'package', $item['id'] );
$productsPrices = $todayPrices->productsPrices[0]->price;
$documentDateExchangeRate = $todayPrices->documentDateExchangeRate;
$rate_from = $public_data->apply_exchange_rate_conversion( $productsPrices, $documentDateExchangeRate );
$item['rate_from'] = $currency_symbol . $public_data->round_number($rate_from);


// For related items
$tim_travel_manager_package = new Tim_Travel_Manager_Public_Package_Controller(
	$plugin_name, $public_data, $content_language, $currency_value, $post_type
);

$check_rate_widget = $plugin_dir .'/public/widgets/availability/tim-package-request.php';
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

<input type="hidden" id="timMapType" value="multiple" /> 

<input type="hidden" id="timUserCurrency" value="<?php echo $currency_id; ?>" />
<input type="hidden" id="timUserCurrencyCode" value="<?php echo $currency_code; ?>" />
<input type="hidden" id="timUserCurrencySymbol" value="<?php echo $currency_symbol; ?>" />

<input type="hidden" id="timProviderId" value="<?php echo $item['provider_id']; ?>" />
<input type="hidden" id="timPackageId" value="<?php echo $item['package_id']; ?>" />
<input type="hidden" id="timPackageContentId" value="<?php echo $item['id']; ?>" />

<script type="text/javascript">
var timMapLocations = [
<?php
foreach ( $item['package_days'] as $package_day ) {
    ?>
    { day_number: '<?php echo $package_day['day_number']; ?>', name: '<?php echo $package_day['name']; ?>', lat: '<?php echo $package_day['location']->geo_lat; ?>', lng: '<?php echo $package_day['location']->geo_lng; ?>' }, 
    <?php
}
?>
];

timInitializeMap('', timMapLocations);
</script>

<?php
if ( $themeLayoutId ) {
    ?><script type="text/javascript">timLoadSwiper();</script><?php
}

?>
<script src="<?php echo plugin_dir_url(dirname( __DIR__ )); ?>libs/blazy.js"></script>
<script>
    ;(function() {
        var bLazy = new Blazy();
    })();
</script>
<?php

wp_reset_query();
get_footer();

/*


// $plugin_api = new Tim_Travel_Manager_Api( $plugin_name, '', $public_data );
// $documentDateExchangeRate = $plugin_api->check_document_date_exchange_rate();

// $rate_from         = $public_data->apply_exchange_rate_conversion( $postmeta['rate_from'], $documentDateExchangeRate );
// $item['rate_from'] = $currency_symbol . $rate_from;

// $itineraries = $public_data->sort_array_by($itineraries, 'day_number', 'ASC');
// $item['min_rate']               = '$'. $public_data->get_min_rate( $postmeta['package_rates'], 'adult_rate' );


/*$package_rates = array();
foreach ( $postmeta['package_rates'] as $package_rate ) {
    $rate = '';
    $rate['season'] = $package_rate->season_id->name->$content_language;
    $rate['date']   = $public_data->format_date( $package_rate->season->from, $package_rate->season->to );
    $rate['price']  = $package_rate->currency->symbol . $package_rate->adult_rate;

    array_push( $package_rates, $rate );
}
$item['package_rates'] = $package_rates;

{ day_number: '<?php echo $package_day['day_number']; ?>', name: '<?php echo $package_day['name']; ?>', lat: '<?php echo $package_day['location']['geo_lat']; ?>', lng: '<?php echo $package_day['location']['geo_lng']; ?>' }, 




                <div class="tim_detail_line">
                    <h3><?php _e( 'Available Dates & Prices', $plugin_name ); ?></h3>
                    <table class="tim_table">
                        <thead>
                            <tr>
                                <th><?php _e( 'Availability', $plugin_name ); ?></th>
                                <th><?php _e( 'Season', $plugin_name ); ?></th>
                                <th><?php _e( 'Dates', $plugin_name ); ?></th>
                                <th><?php _e( 'Price per person', $plugin_name ); ?></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            /*foreach ( $item['package_rates'] as $rate ) {
                                ?>
                                <tr>
                                    <td data-th="<?php _e( 'Availability', $plugin_name ); ?>"><i class="fa fa-check-circle"></i></td>
                                    <td data-th="<?php _e( 'Season', $plugin_name ); ?>"><?php echo $rate['season']; ?></td>
                                    <td data-th="<?php _e( 'Dates', $plugin_name ); ?>"><?php echo $rate['date']; ?></td>
                                    <td data-th="<?php _e( 'Price per person', $plugin_name ); ?>" class="tim_table_total"><?php echo $rate['price']; ?></td>
                                    <td><a href="javascript:void(0)" class="tim-btn tim-btn-sm" onclick="timScrollTo('tim_detail_booking_form')"><?php _e( 'Check price', $plugin_name ); ?></a></td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>


*/

?>