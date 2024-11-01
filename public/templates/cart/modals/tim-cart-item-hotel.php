<?php

$bookingItem = $plugin_api->get_booking_item( $id, $content_language );

$startDate = $bookingItem->start_date;
$endDate = $bookingItem->end_date;
$adults = $bookingItem->adults;
$children = $bookingItem->children;
$infants = $bookingItem->infants;
$seniors = $bookingItem->seniors;

$hotelId = $bookingItem->hotel_id;
$hotelContentId = $bookingItem->hotel_content_id;

$post_type = TIM_TRAVEL_MANAGER_POST_TYPE_HOTELS;
$post_type_meta = $post_type .'_meta';

$postmeta = $public_data->find_post_meta_by_product_id( $post_type_meta, 'hotel_id', $hotelId );

// $postmeta  = $public_data->get_postmeta_item_by_value( $post_type, 'hotel_id', $hotelId );

// Group units per room type
$roomTypesSelected = array();
foreach ( $bookingItem->booking_item_hotel_dates as $hotel_date ) {
    $id = $hotel_date->hotel_room_id;
    if ( ! $roomTypesSelected[$id] ) {
        $roomTypesSelected[$id]['id'] = $hotel_date->hotel_room_id;
        $roomTypesSelected[$id]['name'] = $hotel_date->hotel_room->name;
        $roomTypesSelected[$id]['units'] = array();
    }
    
    $unit_number = $hotel_date->unit_number;
    $roomTypesSelected[$id]['units'][$unit_number]['hotel_date_id'] = $hotel_date->id;
    $roomTypesSelected[$id]['units'][$unit_number]['unit_number'] = $hotel_date->unit_number;
    $roomTypesSelected[$id]['units'][$unit_number]['adults'] = $hotel_date->adults;
    $roomTypesSelected[$id]['units'][$unit_number]['children'] = $hotel_date->children;
}

?>

<div class="tim_wrapper">
    <form action="#" name="tim_update_cart" class="tim_check_rate_form tim_check_rate_form_horizontal" autocomplete="off">
        <h3 style="margin-top: 0;"><?php echo $bookingItem->detail->content->name; ?></h3>
        <div class="tim_check_rate_box">
            <p>
                <?php _e( 'Check-in', $plugin_name ); ?>
                <input type="hidden" id="timFromDB" />
                <input type="text" id="timFrom" name="timFrom" readonly class="calendar" />
            </p>
            <p>
                <?php _e( 'Check-out', $plugin_name ); ?>
                <input type="hidden" id="timToDB" />
                <input type="text" id="timTo" name="timTo" readonly class="calendar" />
            </p>

            <p>
                <span class="tim_no_mob">&nbsp;</span>
                <button type="button" class="tim-btn" onclick="timCheckHotelAvailability('update');">
                    <?php _e( 'Check rates', $plugin_name ); ?>
                </button><span id="tim_travel_form_spinner" class="tim_travel_form_spinner"></span>
            </p>
        </div>

        <div id="timAvailabilityResult"></div>

        <div class="tim_alert tim_alert_warning tim_alert_lg tim_align_center" id="timAvailabilityResultMsg">
            <i class="fa fa-calendar fa-2x"></i>
            <div style="margin-top: 10px;">
                <?php _e( 'Please select the check-in and check-out dates', $plugin_name ); ?>
            </div>
        </div>

        <input type="hidden" id="timBookingItemId" value="<?php echo $bookingItem->id; ?>" />

        <input type="hidden" id="timUserCurrency" value="<?php echo $currency_id; ?>" />
        <input type="hidden" id="timUserCurrencyCode" value="<?php echo $currency_code; ?>" />
        <input type="hidden" id="timUserCurrencySymbol" value="<?php echo $currency_symbol; ?>" />

        <input type="hidden" id="timHotelId" value="<?php echo $hotelId; ?>" />
        <input type="hidden" id="timHotelContentId" value="<?php echo $hotelContentId; ?>" />

        <input type="hidden" id="timBookingId" value="<?php echo $bookingItem->booking_id; ?>" />
        <input type="hidden" id="timBookingPriceListId" value="<?php echo $priceListId; ?>" />
    </form>
    <script type="text/javascript">
    var timLabels = {
        accommodations: '<?php _e( 'Accommodations', $plugin_name ); ?>', 
        adults: '<?php _e( 'Adults', $plugin_name ); ?>', 
        bookNow: '<?php _e( 'Book now', $plugin_name ); ?>', 
        updateOrder: '<?php _e( 'Update order', $plugin_name ); ?>', 
        children: '<?php _e( 'Children', $plugin_name ); ?>', 
        details: '<?php _e( 'Details', $plugin_name ); ?>', 
        enterCheckInDate: '<?php _e( 'Enter check-in date', $plugin_name ); ?>', 
        enterOneRoom: '<?php _e( 'Please select at least one room', $plugin_name ); ?>', 
        left: '<?php _e( 'Left', $plugin_name ); ?>', 
        max: '<?php _e( 'Max', $plugin_name ); ?>', 
        notAvailable: '<?php _e( 'Not available', $plugin_name ); ?>', 
        notSmoking: '<?php _e( 'Not-smoking', $plugin_name ); ?>', 
        only: '<?php _e( 'Only', $plugin_name ); ?>', 
        priceFrom: '<?php _e( 'Price from', $plugin_name ); ?>', 
        rooms: '<?php _e( 'Rooms', $plugin_name ); ?>', 
        roomNights: '<?php _e( 'Room nights', $plugin_name ); ?>', 
        roomType: '<?php _e( 'Room type', $plugin_name ); ?>', 
        taxes: '<?php _e( 'Taxes', $plugin_name ); ?>', 
        subtotal: '<?php _e( 'Subtotal', $plugin_name ); ?>', 
        totalPrice: '<?php _e( 'Total price', $plugin_name ); ?>', 
        wifi: '<?php _e( 'Wi-fi', $plugin_name ); ?>', 

        errorCheckIn: '<?php _e( 'Check-in required', $plugin_name ); ?>', 
        errorCheckOut: '<?php _e( 'Check-out required', $plugin_name ); ?>'
    };

    var rooms = [];
    <?php
    foreach ( $roomTypesSelected as $roomTypeSelected ) {
        ?>
        var room = {
            id: '<?php echo $roomTypeSelected['id']; ?>', 
            name: '<?php echo $roomTypeSelected['name']; ?>', 
            unitsPerRoom: <?php echo count($roomTypeSelected['units']); ?>, 
            units: [] 
        };
        <?php
        foreach ( $roomTypeSelected['units'] as $unit ) {
            ?>
            room.units.push({
                hotel_date_id: '<?php echo $unit['hotel_date_id']; ?>', 
                unit_number: <?php echo $unit['unit_number']; ?>, 
                adults: <?php echo $unit['adults']; ?>, 
                children: <?php echo $unit['children']; ?>
            });
            <?php
        }
        ?>
        rooms.push(room);
        <?php
    }
    ?>

    var timBookedRooms = rooms;

    jQuery( function() {
        timProductDatePicker('hotel', 1, '<?php echo $content_language; ?>', '<?php echo $startDate; ?>', '<?php echo $endDate; ?>');

        timCheckHotelAvailability();
    });
    </script>
</div>