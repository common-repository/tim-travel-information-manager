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

$children_ages = $bookingItem->children_ages;
$ageContent = '';

$tourId = $bookingItem->tour_id;
$tourOptionOptionId = $bookingItem->tour_option_id;
$tourOptionScheduleId = $bookingItem->tour_option_schedule_id;
$tourContentId = $bookingItem->tour_content_id;

$providerId = $bookingItem->provider_id;

$pickupPlacesByLocation = $public_data->get_postmeta_list_by_value( TIM_TRAVEL_MANAGER_POST_TYPE_PICKUP_PLACES, 'location_id', $bookingItem->detail->location_id );
$dropoffPlacesByLocation = $pickupPlacesByLocation;

$post_type = TIM_TRAVEL_MANAGER_POST_TYPE_TOURS;
$post_type_meta = $post_type .'_meta';

$postmeta = $public_data->find_post_meta_by_product_id( $post_type_meta, 'tour_id', $tourId );

// $postmeta  = $public_data->get_postmeta_item_by_value( $post_type, 'tour_id', $tourId ); // problems with wpml plugin

$minChildrenAge = $postmeta['min_children_age'];
$maxChildrenAge = $postmeta['max_children_age'];

$defaultPickupPlaceId = $postmeta['default_pickup_place_id'];
$defaultDropoffPlaceId = $postmeta['default_dropoff_place_id'];

$default_content_lang = 'en'; // hack

// var_dump($postmeta);

// $bookingItem->detail->name
?>
<div class="tim_wrapper">
    <form action="#" name="tim_update_cart" class="tim_check_rate_form">
        <h3 style="margin-top: 0;"><?php echo $bookingItem->detail->content->name; ?></h3>
        <div class="tim_clr">
            <div class="tim_col_4">
                <div class="tim_check_rate_form_title"><?php _e( 'Service date', $plugin_name ); ?></div>
                <div class="timDepartureDateField">
                    <input type="hidden" id="timFromDB" />
                    <div id="timFrom" class="tim-datepicker"></div>

                    <!-- <span class="timAvailableDates" style="background: #4289cc; color: #fff; padding: 1px 10px; border-radius: 4px; margin-right: 5px;"></span>
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
                        <select id="timChildren" name="timChildren" onchange="selectChildrenAges('edit', 'children', this.value);">
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

                    <div id="timChildrenAgesContent" style="clear: both; font-size: 12px; line-height: 26px;">
                        <?php
                        if ($children > 0 && $children_ages && $children_ages != '') {
                            // $myArray = explode('/', $children_ages);
                            // print_r($myArray);
                            // echo '<br>';
                            // echo count($myArray);
                            // echo '<br>';

                            ?>
                            <b><?php echo _e( 'Specify the age of the children', $plugin_name ); ?></b>
                            <br>

                            <?php
                            if ($children == 1) {
                                $myArray = [$children_ages];
                            } else {
                                $myArray = explode('/', $children_ages);
                            }

                            $i = 1;
                            foreach ($myArray as $item) {
                                $age = substr(trim($item), -1);
                                $age = intval($age);

                                ?>
                                <?php _e( 'Child', $plugin_name ); ?> <?php echo $i; ?>: <select id="timChildAge_<?php echo $i; ?>" name="timChildAge_<?php echo $i; ?>" onchange="addChildAge('tour', 'edit')">
                                    <?php
                                    for ($x = $minChildrenAge; $x <= $maxChildrenAge; $x++ ) {
                                        ?>
                                        <option value="<?php echo $x; ?>"<?php selected( $age, $x ); ?>><?php echo $x; ?></option>
                                        <?php
                                    }
                                    ?>
                                </select><br>
                                <?php

                                $ageContent .= __( 'Child', $plugin_name ) .' '. $i . ': '. $age . ' / ';

                                $i++;
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>

            <div class="tim_col_4">
                <div class="tim_check_rate_form_title"><?php _e( 'Choose one option below', $plugin_name ); ?></div>
                <?php
                foreach ( $postmeta['tour_options'] as $tourOption ) {
                    ?>
                    <div class="tim_availability_table_wrapper">
                        <h5><?php echo $tourOption->name->$content_language; ?></h5>
                        <table class="tim_availability_table">
                            <thead>
                                <tr>
                                    <th><?php _e( 'Departures', $plugin_name ); ?></th>
                                    <th><?php _e( 'Duration', $plugin_name ); ?></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tboby>
                                <?php
                                foreach ( $tourOption->tour_option_schedules as $schedule ) {
                                    $radioCheckedOptionScheduleId = ($schedule->id == $tourOptionScheduleId) ? ' checked="checked"' : '';
                                    ?>
                                    <tr>
                                        <td style="width: 30%;">
                                            <?php echo $public_data->format_hour( $schedule->departure, $content_language ); ?>
                                        </td>
                                        <td style="width: 30%;">
                                            <?php echo $schedule->duration; ?> <?php _e( $schedule->duration_time_unit, $plugin_name ); ?>
                                        </td>
                                        <td style="text-align:right;">
                                            <div class="tim_label_controls">
                                                <div class="tim_label_radio">
                                                    <input type="radio" name="schedule" id="radio_<?php echo $schedule->id; ?>" value="<?php echo $tourOption->id .'-'. $schedule->id .'-'. $schedule->departure; ?>"<?php echo $radioCheckedOptionScheduleId; ?>><label for="radio_<?php echo $schedule->id; ?>"></label>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            ?>
                            </tboby>
                        </table>
                    </div>
                    <?php
                }
                ?>
            </div>
            
            <div class="tim_col_4">
                <?php
                $classPickupDropoff = '';

                if (!$defaultPickupPlaceId && !$defaultDropoffPlaceId) {
                    ?>
                    <div class="tim_check_rate_form_title"><?php _e( 'Pick-up / Drop-off', $plugin_name ); ?></div>
                    <?php

                    $classPickupDropoff = ' class="timPickUpPlacesField"';
                }

                ?>
                <div<?php echo $classPickupDropoff; ?>>
                    <?php
                    if ( ! $defaultPickupPlaceId) {
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
                        ?>
                        <p>
                            <?php _e( 'Pickup place', $plugin_name ); ?>:<br />
                            <select id="timPickupPlaceId" style="width: 100%;">
                            <?php
                            echo '<option value="" disabled selected>'. __( 'Select pickup place', $plugin_name ) .'</option>';
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
                        <?php
                    } else {
                        ?>
                        <input type="hidden" id="timPickupPlaceId" name="timPickupPlaceId" value="<?php echo $defaultPickupPlaceId; ?>" />
                        <?php
                    }

                    if (!$defaultDropoffPlaceId) {
                        $dropoffPlaceNotBooked = $this->find_place_by_name( $allPickupPlaces, $compareName, $content_language, $default_content_lang ); // dropoffPlacesByLocation
                
                        $dropoffPlaceNotBookedId = $dropoffPlaceNotBooked['id'];
                        ?>
                        <p>
                            <?php _e( 'Dropoff place', $plugin_name ); ?>:<br />
                            <select id="timDropOffPlaceId" style="width: 100%;">
                            <?php
                            echo '<option value="" disabled selected>'. __( 'Select dropoff place', $plugin_name ) .'</option>';
                            // '. selected( $dropoffPlaceId, '' ) .'

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
                        <?php
                    } else {
                        ?>
                        <input type="hidden" id="timDropOffPlaceId" name="timDropOffPlaceId" value="<?php echo $defaultDropoffPlaceId; ?>" />
                        <?php
                    }
                    ?>
                </div>

                <button type="button" id="timCheckRates" onclick="timCheckTourRates('', '<?php echo $id; ?>');" class="tim-btn"><?php _e( 'Check rates', $plugin_name ); ?></button> 
                   
                <br />
                <div id="timAvailabilityResult"></div>
            </div>
        </div>
        <input type="hidden" id="timUserCurrency" value="<?php echo $currency_id; ?>" />
        <input type="hidden" id="timUserCurrencyCode" value="<?php echo $currency_code; ?>" />
        <input type="hidden" id="timUserCurrencySymbol" value="<?php echo $currency_symbol; ?>" />

        <input type="hidden" id="timProviderId" value="<?php echo $providerId; ?>" />
        <input type="hidden" id="timTourId" value="<?php echo $tourId; ?>" />
        <input type="hidden" id="timTourContentId" value="<?php echo $tourContentId; ?>" />

        <input type="hidden" id="timBookingId" value="<?php echo $bookingItem->booking_id; ?>" />
        <input type="hidden" id="timBookingPriceListId" value="<?php echo $priceListId; ?>" />

        <input type="hidden" id="timMinChildrenAge" value="<?php echo $minChildrenAge; ?>" />
        <input type="hidden" id="timMaxChildrenAge" value="<?php echo $maxChildrenAge; ?>" />
        <input type="hidden" id="timChildrenAges" value="<?php echo $ageContent; ?>" />
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
        specifyAgeChildren: '<?php _e( 'Specify the age of the children', $plugin_name ); ?>', 
        child: '<?php _e( 'Child', $plugin_name ); ?>', 

        errorMinPaxRequired: '<?php _e( 'Min pax required', $plugin_name ); ?>', 
        errorSelectEarlierDate: '<?php _e( 'Select an earlier date', $plugin_name ); ?>', 
        errorNotAvailable: '<?php _e( 'Not available', $plugin_name ); ?>', 
        errorSelectPickupPlace: '<?php _e( 'Select the pick-up place', $plugin_name ); ?>',
        errorNotOptionsAvailable: '<?php _e( 'No options available. Try another date!', $plugin_name ); ?>'
    };

    //var unavailableDates = ["2017-05-15","2017-05-23"];
    jQuery( function() {
        timProductDatePicker('tour', 1, '<?php echo $content_language; ?>', '<?php echo $startDate; ?>');

        <?php
        if (!$defaultPickupPlaceId) {
            ?>
            var timPickupPlaceId = jQuery('#timPickupPlaceId');
            timPickupPlaceId.select2().on('change', function() {
                timPickupPlaceId.select2('close');
            });
            <?php
        }

        if (!$defaultDropoffPlaceId) {
            ?>
            var timDropOffPlaceId = jQuery('#timDropOffPlaceId');
            timDropOffPlaceId.select2().on('change', function() {
                timDropOffPlaceId.select2('close');
            });
            <?php
        }
        ?>

        // Open dropdown on focus
        jQuery(document).on('focus', '.select2.select2-container', function (e) {
            // only open on original attempt - close focus event should not fire open
            if (e.originalEvent && jQuery(this).find('.select2-selection--single').length > 0) {
                jQuery(this).siblings('select').select2('open');
            } 
        });
    });
    </script>
</div>