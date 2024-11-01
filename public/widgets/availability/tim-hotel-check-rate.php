<?php

function render_check_availability_widget( $bookingItem ){

    global $plugin_name, $content_language;

    // $startDate = ($bookingItem) ? ' value="'. $bookingItem->start_date .'"' : '';
    // $endDate   = ($bookingItem) ? ' value="'. $bookingItem->end_date .'"' : '';

    // $chInDB  = ' value="2019-03-20"';
    // $chIn    = ' value="03/20/2019"';
    // $chOutDB = ' value="2019-03-23"';
    // $chOut   = ' value="03/23/2019"';

    $startDate = '';
    $endDate   = '';

    ?>
    <form action="#" name="tim_check_rates" class="tim_check_rate_form tim_check_rate_form_horizontal" autocomplete="off">
        <div class="tim_check_rate_box">
            <h4><?php _e( 'Check availability', $plugin_name ); ?></h4>

            <p>
                <?php _e( 'Check-in', $plugin_name ); ?>
                <input type="hidden" id="timFromDB"<?php echo $startDate; ?> />
                <input type="text" id="timFrom" name="timFrom" readonly class="calendar"<?php echo $startDate; ?> />
            </p>
            <p>
                <?php _e( 'Check-out', $plugin_name ); ?>
                <input type="hidden" id="timToDB"<?php echo $endDate; ?> />
                <input type="text" id="timTo" name="timTo" readonly class="calendar"<?php echo $endDate; ?> />
            </p>

            <p>
                <span class="tim_no_mob">&nbsp;</span>
                <button type="button" class="tim-btn" onclick="timCheckHotelAvailability('<?php echo $bookingItem->id; ?>');">
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
    </form>

    <script type="text/javascript">
    // Consider a file for the translations and pass the language
    // var timLabel getTimLabels('<?php echo $content_language; ?>');
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

    jQuery( function() {
        timProductDatePicker('hotel', 1, '<?php echo $content_language; ?>');
    });
    </script>
    <?php
}

?>