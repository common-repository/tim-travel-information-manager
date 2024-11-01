<?php

$bookingItem = $plugin_api->get_booking_item( $id, $content_language );

$startDate = $bookingItem->start_date;
$departureTime = $bookingItem->departure_time;
$adults = $bookingItem->adults;
$children = $bookingItem->children;
$infants = $bookingItem->infants;
$seniors = $bookingItem->seniors;
$pickupPlaceId = $bookingItem->pickup_place_id; // ? $bookingItem->pickup_place_id : '';
$dropoffPlaceId = $bookingItem->dropoff_place_id;

$transportationId = $bookingItem->transportation_id;
$transportatioScheduleId = $bookingItem->transportation_schedule_id;
$transportationContentId = $bookingItem->transportation_content_id;

$providerId = $bookingItem->provider_id;

$pickupPlacesByLocation  = $public_data->get_postmeta_list_by_value( TIM_TRAVEL_MANAGER_POST_TYPE_PICKUP_PLACES, 'location_id', $bookingItem->detail->departure_location_id );
$dropoffPlacesByLocation = $public_data->get_postmeta_list_by_value( TIM_TRAVEL_MANAGER_POST_TYPE_PICKUP_PLACES, 'location_id', $bookingItem->detail->arrival_location_id );

$post_type = TIM_TRAVEL_MANAGER_POST_TYPE_TRANSPORTATIONS;
$post_type_meta = $post_type .'_meta';

$postmeta = $public_data->find_post_meta_by_product_id( $post_type_meta, 'transportation_id', $transportationId );

// $postmeta = $public_data->get_postmeta_item_by_value( $post_type, 'transportation_id', $transportationId );

$minChildrenAge = $postmeta['min_children_age'];
$maxChildrenAge = $postmeta['max_children_age'];

$showSchedules = true;
$labelDepartures = 'Departures';
if ( $postmeta['schedule_type'] === 'open' ){
    $showSchedules = false;
    $labelDepartures = 'Select time';
}

$default_content_lang = 'en'; // hack

//var_dump($bookingItem);
?>
<div class="tim_wrapper">
    <form action="#" name="tim_update_cart" class="tim_check_rate_form">
        <h3 style="margin-top: 0;"><?php echo $bookingItem->detail->name; ?></h3>
        <div class="tim_clr">
            <div class="tim_col_4">
                <div class="tim_check_rate_form_title"><?php _e( 'Service date', $plugin_name ); ?></div>
                <div class="timDepartureDateField">
                    <input type="hidden" id="timFromDB" />
                    <div id="timFrom" class="tim-datepicker"></div>

                   <!--  <span class="timAvailableDates" style="background: #4289cc; color: #fff; padding: 1px 10px; border-radius: 4px; margin-right: 5px;"></span>
                    <?php
                    _e( 'Available', $plugin_name );
                    ?>

                    <span class="timUnAvailableDates" style="background: #ff5400; color: #fff; padding: 1px 10px; border-radius: 4px; margin: 0 5px 0 10px;"></span>
                    <?php
                    _e( 'Full', $plugin_name );
                    ?> -->
                </div>
                <div id="timAvailabilityFor" class="timAvailabilityFor"></div>

                <div class="tim_check_rate_form_title"><?php _e( 'How many people?', $plugin_name ); ?></div>
                <div class="timGuestsField">
                    <p>
                        <?php _e( 'Adults', $plugin_name ); ?>:
                        <select id="timAdults" name="timAdults">
                        <?php
                        for ($i = 1; $i <= 50; $i++){
                            echo '<option'. selected( $adults, $i ) .' value="'. $i .'">'. $i .'</option>';
                        }
                        ?>
                        </select>
                    </p>

                    <p>
                        <?php _e( 'Children', $plugin_name ); ?>:
                        <select id="timChildren" name="timChildren">
                        <?php
                        for ($i = 0; $i <= 50; $i++){
                            echo '<option'. selected( $children, $i ) .' value="'. $i .'">'. $i .'</option>';
                        }
                        ?>
                        </select>
                        <small><?php echo $minChildrenAge; ?>-<?php echo $maxChildrenAge; ?> <?php _e( 'years', $plugin_name ); ?></small>
                    </p>

                    <p>
                        <?php _e( 'Infants', $plugin_name ); ?>:
                        <select id="timInfants" name="timInfants">
                        <?php
                        for ($i = 0; $i <= 50; $i++){
                            echo '<option'. selected( $infants, $i ) .' value="'. $i .'">'. $i .'</option>';
                        }
                        ?>
                        </select>
                    </p>
                </div>
            </div>

            <div class="tim_col_4">
                <div class="tim_check_rate_form_title"><?php _e( 'Choose one option below', $plugin_name ); ?></div>
                <div class="tim_availability_table_wrapper">
                    <h5><?php echo $tourOption->name->$content_language; ?></h5>
                    <table class="tim_availability_table">
                        <thead>
                            <tr>
                                <th><?php _e( $labelDepartures, $plugin_name ); ?></th>
                                <th><?php _e( 'Duration', $plugin_name ); ?></th>
                                <?php
                                if ( $showSchedules ) {
                                    ?><th></th><?php
                                }
                                ?>
                            </tr>
                        </thead>
                        <tboby>
                            <?php
                            foreach ( $postmeta['transportation_schedules'] as $schedule ) {
                                ?>
                                <tr>
                                    <td style="width: 30%;">
                                        <?php
                                        if ( $showSchedules ) {
                                            echo $public_data->format_hour( $schedule->departure, $content_language );
                                        } else {
                                            ?>
                                            <p>
                                                <input type="text" id="departureTime" name="departureTime" class="timepicker" />
                                            </p>
                                            <input type="hidden" id="timScheduleId" value="<?php echo $transportatioScheduleId; ?>" />
                                            <?php
                                        }
                                        ?>
                                    </td>
                                    <td style="width: 30%;">
                                        <?php echo $schedule->duration; ?> <small><?php _e( $schedule->duration_time_unit, $plugin_name ); ?></small>
                                    </td>
                                    <?php
                                    if ( $showSchedules ) {
                                        $radioCheckedScheduleId = ($schedule->id == $transportatioScheduleId) ? ' checked="checked"' : '';
                                        ?>
                                        <td style="text-align:right;" class="tim_label_controls">
                                            <div class="tim_label_radio">
                                                <input type="radio" name="schedule" id="radio_<?php echo $schedule->id; ?>" value="<?php echo $schedule->id .'-'. $schedule->departure; ?>"<?php echo $radioCheckedScheduleId; ?>><label for="radio_<?php echo $schedule->id; ?>"></label>
                                            </div>
                                        </td>
                                        <?php
                                    }
                                    ?>
                                </tr>
                                <?php
                            }
                        ?>
                        </tboby>
                    </table>
                </div>
            </div>

            <div class="tim_col_4">
                <div class="tim_check_rate_form_title"><?php _e( 'Pick-up / Drop-off', $plugin_name ); ?></div>
                <div class="timPickUpPlacesField">
                    <?php

                    switch ($content_language) {
                        case 'de':
                            $compareName = 'Noch nicht gebucht / nicht aufgeführt'; // Noch nicht gebucht
                            break;

                        case 'es':
                            $compareName = 'No reservado aún';
                            break;
                        
                        default:
                            $compareName = 'Not booked yet / Place not listed';
                            break;
                    }

                    $allPickupPlaces = $this->get_postmeta_list( TIM_TRAVEL_MANAGER_POST_TYPE_PICKUP_PLACES );
                    $pickupPlaceNotBooked = $this->find_place_by_name( $allPickupPlaces, $compareName, $content_language, $default_content_lang ); // pickupPlacesByLocation
            
                    $pickupPlaceNotBookedId = $pickupPlaceNotBooked['id'];

                    // var_dump($pickupPlacesByLocation);
                    // echo '<br>compareName: '. $compareName;
                    // echo '<br>content_language: '. $content_language;
                    // echo '<br>default_content_lang: '. $default_content_lang;
                    ?>

                    <p>
                        <?php _e( 'Pickup place', $plugin_name ); ?>:
                        <select id="timPickupPlaceId" style="width: 100%;">
                        <?php
                        echo '<option value="" disabled selected>- '. __( 'Select pickup place', $plugin_name ) .'- </option>';
                        // '. selected( $pickupPlaceId, '' ) .'

                        if ( $pickupPlaceNotBookedId ) {
                            $pickupPlaceNotBookedName = $pickupPlaceNotBooked['name']->$content_language ? $pickupPlaceNotBooked['name']->$content_language : $pickupPlaceNotBooked['name']->$default_content_lang;
                            ?>
                            <option value="<?php echo $pickupPlaceNotBookedId; ?>"><?php echo $pickupPlaceNotBookedName; ?></option>
                            <?php
                        }

                        foreach ( $pickupPlacesByLocation as $pickupPlace ) {
                            $pickupPlaceName = $pickupPlace['name']->$content_language ? $pickupPlace['name']->$content_language : $pickupPlace['name']->$default_content_lang;
                            
                            if ( $pickupPlaceName ) {
                                ?>
                                <option value="<?php echo $pickupPlace['id']; ?>"<?php echo selected( $pickupPlaceId, $pickupPlace['id'] ); ?>><?php echo $pickupPlaceName; ?></option>
                                <?php
                            }
                        }
                        ?>
                        </select>
                    </p>
                    <p>
                        <?php _e( 'Dropoff place', $plugin_name ); ?>:
                        <select id="timDropOffPlaceId" style="width: 100%;">
                        <?php
                        echo '<option value="" disabled selected>- '. __( 'Select dropoff place', $plugin_name ) .'- </option>';
                        // '. selected( $dropoffPlaceId, '' ) .'

                        $dropoffPlaceNotBooked = $this->find_place_by_name( $allPickupPlaces, $compareName, $content_language, $default_content_lang ); // dropoffPlacesByLocation
                
                        $dropoffPlaceNotBookedId = $dropoffPlaceNotBooked['id'];

                        if ( $dropoffPlaceNotBookedId ) {
                            $dropoffPlaceNotBookedName = $dropoffPlaceNotBooked['name']->$content_language ? $dropoffPlaceNotBooked['name']->$content_language : $dropoffPlaceNotBooked['name']->$default_content_lang;
                            ?>
                            <option value="<?php echo $dropoffPlaceNotBookedId; ?>"><?php echo $dropoffPlaceNotBookedName; ?></option>
                            <?php
                        }

                        foreach ( $dropoffPlacesByLocation as $dropoffPlace ) {
                            $dropoffPlaceName = $dropoffPlace['name']->$content_language ? $dropoffPlace['name']->$content_language : $dropoffPlace['name']->$default_content_lang;

                            if ( $dropoffPlaceName ) {
                                ?>
                                <option value="<?php echo $dropoffPlace['id']; ?>"<?php echo selected( $dropoffPlaceId, $dropoffPlace['id'] ); ?>><?php echo $dropoffPlaceName; ?></option>
                                <?php
                            }
                        }
                        ?>
                        </select>
                    </p>
                </div>

                <button type="button" id="timCheckRates" onclick="timCheckTransportationRates('<?php echo $id; ?>');" class="tim-btn"><?php _e( 'Check rates', $plugin_name ); ?></button> 
                   
                <br />
                <div id="timAvailabilityResult"></div>
            </div>
        </div>
        

        <input type="hidden" id="timUserCurrency" value="<?php echo $currency_id; ?>" />
        <input type="hidden" id="timUserCurrencyCode" value="<?php echo $currency_code; ?>" />
        <input type="hidden" id="timUserCurrencySymbol" value="<?php echo $currency_symbol; ?>" />

        <input type="hidden" id="timProviderId" value="<?php echo $providerId; ?>" />
        <input type="hidden" id="timTransportationId" value="<?php echo $transportationId; ?>" />
        <input type="hidden" id="timTransportationContentId" value="<?php echo $transportationContentId; ?>" />
        <input type="hidden" id="timTransportationScheduleType" value="<?php echo $postmeta['schedule_type']; ?>" />

        <input type="hidden" id="timBookingId" value="<?php echo $bookingItem->booking_id; ?>" />
        <input type="hidden" id="timBookingPriceListId" value="<?php echo $priceListId; ?>" />
    </form>
    <script type="text/javascript">
    var timLabels = {
        availabilityFor: '<?php _e( 'Availability for', $plugin_name ); ?>', 
        transportation: '<?php _e( 'Transportation', $plugin_name ); ?>', 
        updateOrder: '<?php _e( 'Update order', $plugin_name ); ?>', 
        departure: '<?php _e( 'Departure', $plugin_name ); ?>', 
        duration: '<?php _e( 'Duration', $plugin_name ); ?>', 
        price: '<?php _e( 'Price', $plugin_name ); ?>', 
        book: '<?php _e( 'Book', $plugin_name ); ?>', 
        taxes: '<?php _e( 'Taxes', $plugin_name ); ?>', 
        subtotal: '<?php _e( 'Subtotal', $plugin_name ); ?>', 
        totalPrice: '<?php _e( 'Total price', $plugin_name ); ?>', 

        errorMinPaxRequired: '<?php _e( 'Min pax required', $plugin_name ); ?>', 
        errorSelectEarlierDate: '<?php _e( 'Select an earlier date', $plugin_name ); ?>', 
        errorNotAvailable: '<?php _e( 'Not available', $plugin_name ); ?>', 
        errorSelectPickupPlace: '<?php _e( 'Select the pick-up place', $plugin_name ); ?>',
        errorNotOptionsAvailable: '<?php _e( 'No options available. Try another date!', $plugin_name ); ?>'
    };

    jQuery( function() {
        timProductDatePicker('transportation', 1, '<?php echo $content_language; ?>', '<?php echo $startDate; ?>');

        var timPickupPlaceId = jQuery('#timPickupPlaceId');
        timPickupPlaceId.select2().on('change', function() {
            timPickupPlaceId.select2('close');
        });

        var timDropOffPlaceId = jQuery('#timDropOffPlaceId');
        timDropOffPlaceId.select2().on('change', function() {
            timDropOffPlaceId.select2('close');
        });

        // Open dropdown on focus
        jQuery(document).on('focus', '.select2.select2-container', function (e) {
            // only open on original attempt - close focus event should not fire open
            if (e.originalEvent && jQuery(this).find(".select2-selection--single").length > 0) {
                jQuery(this).siblings('select').select2('open');
            } 
        });
        
        <?php
        // Open departures
        if ( ! $showSchedules ){
            ?>
            setTimePicker('timepicker', '<?php echo $departureTime; ?>');
            <?php
        }
        ?>
    });
    </script>
</div>