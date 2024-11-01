<dl class="tim-dl">
    <dt>
        <?php _e( 'Service date', $plugin_name ); ?>
    </dt>
    <dd>
        <b><?php echo $this->format_date( $item->start_date, '', $content_language ); ?></b>
    </dd>
    <dt>
        <?php _e( 'Service time', $plugin_name ); ?>
    </dt>
    <dd>
        <b><?php echo $this->format_hour( $item->departure_time, $content_language ); ?></b>
    </dd>
    <dt>
        <?php _e( 'Total pax', $plugin_name ); ?>
    </dt>
    <dd>
        <?php echo $totalPax; ?>
    </dd>
    <?php
    $defaultPickupPlaceId = $item->detail->default_pickup_place_id;
    $defaultDropoffPlaceId = $item->detail->default_dropoff_place_id;

    if ( ! $defaultPickupPlaceId) {
    // if (!$item->detail->default_pickup_place_id) {
       ?>
        <dt>
            <?php _e( 'Pick-up place', $plugin_name ); ?> <span class="tim_error_input_msg">*</span>
        </dt>
        <dd>
            <?php
            if ( $item->pickup_place_id ) {
                $pickupPlaceName = $item->pickup_place->name;

                // No translation
                if ( ! $pickupPlaceName ) {
                    $pickupPlace = $this->find_item_in_array( $pickupPlacesByLocation, 'id', $item->pickup_place_id );
                    $pickupPlaceName = $pickupPlace['name']->$default_content_lang;

                    // $pickupPlaceName = $this->get_postmeta_item_by_value( '', $findBy, $value );
                }

                echo '<em>'. $pickupPlaceName .'</em>';
            } else {                
                switch ($content_language) {
                    case 'de':
                        // $compareName = 'Später angeben'; // Noch nicht gebucht
                        $compareName = 'Noch nicht gebucht / nicht aufgeführt'; // Noch nicht gebucht
                        break;

                    case 'es':
                        $compareName = 'No reservado aún';
                        break;
                    
                    default:
                        // $compareName = 'Not booked yet';
                        $compareName = 'Not booked yet / Place not listed';
                        break;
                }

                // var_dump($pickupPlacesByLocation);
                // echo '<br>compareName: '. $compareName;
                // echo '<br>content_language: '. $content_language;
                // echo '<br>default_content_lang: '. $default_content_lang;

                // $pickupPlaceNotBooked = $this->find_place_by_name( $pickupPlacesByLocation, $compareName, $content_language, $default_content_lang );

                $allPickupPlaces = $this->get_postmeta_list( TIM_TRAVEL_MANAGER_POST_TYPE_PICKUP_PLACES );
                
                $pickupPlaceNotBooked = $this->find_place_by_name( $allPickupPlaces, $compareName, $content_language, $default_content_lang );
                
                $pickupPlaceNotBookedId = $pickupPlaceNotBooked['id'];
                ?>
                <select id="timPickupPlaceId_<?php echo $item->id ?>" onchange="timSelectPickupPlace('both', '<?php echo $item->id ?>')" required>
                    <option value="" disabled selected><?php _e( 'Select pickup place', $plugin_name ); ?></option>
                    <?php
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
                            <option value="<?php echo $pickupPlace['id']; ?>"><?php echo $pickupPlaceName; ?></option>
                            <?php
                        }
                    }
                    ?>
                </select>
                <script type="text/javascript">
                jQuery( function() {
                    var timPickupPlaceId_<?php echo $item->id ?> = jQuery('#timPickupPlaceId_<?php echo $item->id ?>');
                    timPickupPlaceId_<?php echo $item->id ?>.select2().on('change', function() {
                        timPickupPlaceId_<?php echo $item->id ?>.select2('close');
                    });
                });
                </script>
                <?php   
            }
            ?>
        </dd>
        <?php
    } else {
        //$item->pickup_place_id = $defaultPickupPlaceId;
        ?>
        <!-- <input type="hidden" id="timPickupPlaceId_<?php //echo $item->id ?>" value="<?php //echo $defaultPickupPlaceId; ?>" /> -->
        <?php
    }    
    
    if ( ! $defaultDropoffPlaceId) {
    // if (!$item->detail->default_dropoff_place_id) {
        ?>
        <dt>
            <?php _e( 'Drop-off place', $plugin_name ); ?> <span class="tim_error_input_msg">*</span>
        </dt>
        <dd>
           <?php
            if ( $item->dropoff_place_id ) {
                $dropoffPlaceName = $item->dropoff_place->name;

                // No translation
                if ( ! $dropoffPlaceName ) {
                    $dropoffPlace = $this->find_item_in_array( $dropoffPlacesByLocation, 'id', $item->dropoff_place_id );
                    $dropoffPlaceName = $dropoffPlace['name']->$default_content_lang;
                }

                echo '<em>'. $dropoffPlaceName .'</em>';
            } else {
                $dropoffPlaceNotBooked = $this->find_place_by_name( $allPickupPlaces, $compareName, $content_language, $default_content_lang );
                
                $dropoffPlaceNotBookedId = $dropoffPlaceNotBooked['id'];
                ?>
                <select id="timDropoffPlaceId_<?php echo $item->id ?>" onchange="timSelectPickupPlace('both', '<?php echo $item->id ?>')" required>
                    <option value="" disabled selected><?php _e( 'Select dropoff place', $plugin_name ); ?></option>
                    <?php
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
                            <option value="<?php echo $dropoffPlace['id']; ?>"><?php echo $dropoffPlaceName; ?></option>
                            <?php
                        }
                    }
                    ?>
                </select>
                <script type="text/javascript">
                jQuery( function() {
                    var timDropoffPlaceId_<?php echo $item->id ?> = jQuery('#timDropoffPlaceId_<?php echo $item->id ?>');
                    timDropoffPlaceId_<?php echo $item->id ?>.select2().on('change', function() {
                        timDropoffPlaceId_<?php echo $item->id ?>.select2('close');
                    });

                    // Open dropdown on focus
                    jQuery(document).on('focus', '.select2.select2-container', function (e) {
                        // only open on original attempt - close focus event should not fire open
                        if (e.originalEvent && jQuery(this).find(".select2-selection--single").length > 0) {
                            jQuery(this).siblings('select').select2('open');
                        } 
                    });
                });
                </script>
                <?php
            }
            ?>
        </dd>
        <?php
    } else {
        /*$item->dropoff_place_id = $defaultDropoffPlaceId;
        ?>
        <input type="hidden" id="timDropoffPlaceId_<?php echo $item->id ?>" value="<?php echo $defaultDropoffPlaceId; ?>" />
        <?php*/
    }
    ?>
</dl>
<?php
/*if ( ! $item->pickup_place_id || ! $item->dropoff_place_id ) {
    ?>
    <div class="tim-cart-item-button">
        <button type="button" id="addPlaces_<?php echo $item->id ?>" disabled onclick="timApplyPlacesToItemOrder('<?php echo $item->id ?>')">
            <?php _e( 'Apply', $plugin_name ); ?>
        </button>
    </div>
    <!-- <div class="tim-cart-item-notify">* <?php _e( 'Please select the pick-up and drop-off', $plugin_name ); ?></div> -->
    <?php
    $disabledCheckOut = ' disabled';
}*/