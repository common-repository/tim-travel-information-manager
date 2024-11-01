<?php

function render_check_rate_widget( $item, $checkRateLayoutId = '', $datePickerOption = '' ) {

    global $plugin_name, $content_language;

    $adults = 2;
    $children = $infants = $seniors = 0;

    $max_pax_allowed = ( $item['max_pax_allowed'] ) ? $item['max_pax_allowed'] : 50;

    if ( ! $checkRateLayoutId ) {
        ?>
        <div class="tim_detail_booking_form">
            <h4><?php _e( 'Check availability', $plugin_name ); ?></h4>

            <form action="#" name="tim_check_rates" class="tim_check_rate_form" autocomplete="off">
                <div class="tim_check_rate_form_title"><?php _e( 'Choose a date', $plugin_name ); ?></div>
                <div class="timDepartureDateField">
                    <input type="hidden" id="timFromDB" />
                    <div id="timFrom" class="tim-datepicker"></div>   
                </div>
                <div id="timAvailabilityFor" class="timAvailabilityFor"></div>
                
                <div class="tim_check_rate_form_title"><?php _e( 'How many people?', $plugin_name ); ?></div>
                <div class="timGuestsField">
                    <p>
                        <?php _e( 'Adults', $plugin_name ); ?>
                        <select id="timAdults" name="timAdults" onchange="timCheckTransportationRates();">
                        <?php
                        for ($i = 1; $i <= $max_pax_allowed; $i++) {
                            echo '<option'. selected( $adults, $i ) .' value="'. $i .'">'. $i .'</option>';
                        }
                        ?>
                        </select>
                    </p>

                    <p>
                        <?php _e( 'Children', $plugin_name ); ?>:
                        <select id="timChildren" name="timChildren" onchange="timCheckTransportationRates();">
                        <?php
                        for ($i = 0; $i <= $max_pax_allowed; $i++) {
                            echo '<option'. selected( $children, $i ) .' value="'. $i .'">'. $i .'</option>';
                        }
                        ?>
                        </select>
                        <small><?php echo $item['min_children_age']; ?>-<?php echo $item['max_children_age']; ?> <?php _e( 'years', $plugin_name ); ?></small>
                    </p>

                    <p>
                        <?php _e( 'Infants', $plugin_name ); ?>:
                        <select id="timInfants" name="timInfants" onchange="timCheckTransportationRates();">
                        <?php
                        for ($i = 0; $i <= 50; $i++) {
                            echo '<option'. selected( $infants, $i ) .' value="'. $i .'">'. $i .'</option>';
                        }
                        ?>
                        </select>
                    </p>
                </div>

                <div class="tim_check_rate_form_title"><?php _e( 'Choose one option below', $plugin_name ); ?></div>
                <div id="timAvailabilityResult">
                    <div class="tim-inline-spinner"></div>
                    <!-- <div class="timOptionsField"> -->
                        <div id="timTransportationSchedules"></div>
                    <!-- </div> -->
                </div>
            </form>
        </div>
        <?php
    } else {
        ?>
        <div class="tim_detail_booking_form_content">
            <h4><?php _e( 'Check availability', $plugin_name ); ?></h4>
            
            <form action="#" name="tim_check_rates" class="tim_check_rate_form tim_check_rate_form_content" autocomplete="off">
                <div class="tim_clr">
                    <div class="tim_col_6">
                        <div class="timDepartureDateField">
                            <p>
                                <?php _e( 'Service date', $plugin_name ); ?>:
                                <input type="hidden" id="timFromDB" />
                                <input type="text" id="timFrom" name="timFrom" readonly class="calendar" />
                            </p>
                        </div>
                    </div>
                    
                    <div class="tim_col_6">
                        <div class="timGuestsField">
                            <p>
                                <?php _e( 'Adults', $plugin_name ); ?>:
                                <select id="timAdults" name="timAdults" onchange="timCheckTransportationRates();">
                                <?php
                                for ($i = 1; $i <= $max_pax_allowed; $i++) {
                                    echo '<option'. selected( $adults, $i ) .' value="'. $i .'">'. $i .'</option>';
                                }
                                ?>
                                </select>
                            </p>

                            <p>
                                <?php _e( 'Children', $plugin_name ); ?>:
                                <select id="timChildren" name="timChildren" onchange="timCheckTransportationRates();">
                                <?php
                                for ($i = 0; $i <= $max_pax_allowed; $i++) {
                                    echo '<option'. selected( $children, $i ) .' value="'. $i .'">'. $i .'</option>';
                                }
                                ?>
                                </select>
                                <small><?php echo $item['min_children_age']; ?>-<?php echo $item['max_children_age']; ?> <?php _e( 'years', $plugin_name ); ?></small>
                            </p>

                            <p>
                                <?php _e( 'Infants', $plugin_name ); ?>:
                                <select id="timInfants" name="timInfants" onchange="timCheckTransportationRates();">
                                <?php
                                for ($i = 0; $i <= 50; $i++) {
                                    echo '<option'. selected( $infants, $i ) .' value="'. $i .'">'. $i .'</option>';
                                }
                                ?>
                                </select>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="tim_check_rate_form_title tim_hide" id="timTourOptionsPrices"><?php _e( 'Choose one option below', $plugin_name ); ?></div>
                <div id="timAvailabilityResult">
                    <div class="tim-inline-spinner"></div>
                    <div id="timTransportationSchedules"></div>
                </div>
            </form>
        </div>
        <?php
    }
    ?>

    <input type="hidden" id="unavailableDates" value="[]" />
    <script type="text/javascript">
    var timLabels = {
        availabilityFor: '<?php _e( 'Availability for', $plugin_name ); ?>', 
        transportation: '<?php _e( 'Transportation', $plugin_name ); ?>', 
        updateOrder: '<?php _e( 'Update order', $plugin_name ); ?>', 
        departure: '<?php _e( 'Departure', $plugin_name ); ?>', 
        duration: '<?php _e( 'Duration', $plugin_name ); ?>', 
        hours: '<?php _e( 'hours', $plugin_name ); ?>', 
        price: '<?php _e( 'Price', $plugin_name ); ?>', 
        book: '<?php _e( 'Book', $plugin_name ); ?>', 
        taxes: '<?php _e( 'Taxes', $plugin_name ); ?>', 
        subtotal: '<?php _e( 'Subtotal', $plugin_name ); ?>', 
        totalPrice: '<?php _e( 'Total price', $plugin_name ); ?>', 
        selectTime: '<?php _e( 'Select time', $plugin_name ); ?>', 

        errorMinPaxRequired: '<?php _e( 'Min pax required', $plugin_name ); ?>', 
        errorSelectEarlierDate: '<?php _e( 'Select an earlier date', $plugin_name ); ?>', 
        errorNotAvailable: '<?php _e( 'Not available', $plugin_name ); ?>', 
        errorSelectPickupPlace: '<?php _e( 'Select the pick-up place', $plugin_name ); ?>', 
        errorSelectSelectTime: '<?php _e( 'Select the pick-up time', $plugin_name ); ?>',
        errorNotOptionsAvailable: '<?php _e( 'No options available. Try another date!', $plugin_name ); ?>'
    };

    jQuery( function() { // added only here to avoid issues with datepicker onSelect
        timProductDatePicker('transportation', 1, '<?php echo $content_language; ?>');

        <?php
        if ( ! $checkRateLayoutId ) {
            ?>
            timCheckTransportationRates();
            <?php
        }
        ?>
    });

    // $(function() { // added only here to avoid issues with datepicker onSelect
    // jQuery( function() {
    </script>
    <?php

}