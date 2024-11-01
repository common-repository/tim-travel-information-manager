<?php

function render_check_rate_widget( $item, $checkRateLayoutId = '') {

    global $plugin_name, $content_language;

    ?>
    <div class="tim_detail_booking_form">
        <h4><?php _e( 'Request more information', $plugin_name ); ?></h4>

        <form action="#" name="tim_package_form" class="tim_check_rate_form" autocomplete="off">
            <p>
                
                <input type="hidden" id="timFromDB" />
                <input type="text" id="timFrom" name="timFrom" readonly placeholder="<?php _e( 'Arrival date', $plugin_name ); ?>" class="calendar"<?php echo $startDate; ?> />
            </p>

            <div class="tim_clr">
                <div class="tim_col_4">
                    <p>
                        <select id="timAdults" name="timAdults" onchange="timCheckPackageRates();">
                        <?php
                        echo '<option value="" selected>'. __( 'Adults', $plugin_name ) .'</option>';
                        for ( $i = $item['min_pax_required']; $i <= $item['max_pax_allowed']; $i++ ) {
                            echo '<option value="'. $i .'">'. $i .'</option>';
                        }
                        ?>
                        </select>
                    </p>
                </div>

                <div class="tim_col_4">
                    <p>
                        <select id="timChildren" name="timChildren" onchange="timCheckPackageRates();">
                        <?php
                        echo '<option value="0" selected>'. __( 'Children', $plugin_name ) .'</option>';
                        for ( $i = 0; $i <= $item['max_pax_allowed']; $i++ ) {
                            echo '<option value="'. $i .'">'. $i .'</option>';
                        }
                        ?>
                        </select>
                    </p>

                </div>

                <div class="tim_col_4">
                    <p>
                        <select id="timInfants" name="timInfants" onchange="timCheckPackageRates();">
                        <?php
                        echo '<option value="0" selected>'. __( 'Infants', $plugin_name ) .'</option>';
                        for ( $i = 0; $i <= $item['max_pax_allowed']; $i++ ) {
                            echo '<option value="'. $i .'">'. $i .'</option>';
                        }
                        ?>
                        </select>
                    </p>
                </div>
            </div>
            
            <div id="timChildrenAgesContent" style="clear: both; font-size: 12px; line-height: 26px;"></div>

            <!-- Name -->
            <p>
                <input type="text" id="timCustomerName" name="timCustomerName" placeholder="<?php _e( 'Name', $plugin_name ); ?>" />    
            </p>

            <!-- Email -->
            <p>
                <input type="text" id="timCustomerEmail" name="timCustomerEmail" placeholder="<?php _e( 'E-mail address', $plugin_name ); ?>" />
            </p>

            <!-- Phone -->
            <p>
                <input type="text" id="timCustomerPhoneNumber" name="timCustomerPhoneNumber" placeholder="<?php _e( 'Whatsapp / Phone number to contact you?', $plugin_name ); ?>" />    
            </p>

            <!-- Comments -->
            <p>
                <textarea 
                    id="timCustomerComments" 
                    name="timCustomerComments" 
                    rows="4" 
                    placeholder="<?php _e( 'Comments', $plugin_name ); ?>"></textarea>
            </p>

            <button type="button" onclick="timCheckPackageForm()" class="tim-btn timSendButton">
                <?php _e( 'Accept', $plugin_name ); ?>
            </button>

            <div id="timRequestPackageFormMsg"></div>

            <input type="hidden" id="timLabelErrorCheckIn" value="<?php _e( 'Select date', $plugin_name ); ?>" />
            <input type="hidden" id="timLabelErrorAdults" value="<?php _e( 'Select adults', $plugin_name ); ?>" />
            <input type="hidden" id="timLabelErrorName" value="<?php _e( 'Enter your name', $plugin_name ); ?>" />
            <input type="hidden" id="timLabelErrorEmailInvalid" value="<?php _e( 'Invalid e-mail', $plugin_name ); ?>" />

            <input type="hidden" id="timLabelRequestPackageSent" value="<?php _e( 'Request has been sent. We will reply your inquiry as soon as possible.', $plugin_name ); ?>" />

            <input type="hidden" id="timMinChildrenAge" value="<?php echo $item['min_children_age']; ?>" />
            <input type="hidden" id="timMaxChildrenAge" value="<?php echo $item['max_children_age']; ?>" />
            <input type="hidden" id="timChildrenAges" />
        </form>
    </div>

    <script type="text/javascript">
    var timLabels = {
        specifyAgeChildren: '<?php _e( 'Specify the age of the children', $plugin_name ); ?>', 
        child: '<?php _e( 'Child', $plugin_name ); ?>', 
    }

    // var timLabels = {
    //     timLabelErrorCheckIn: '<?php _e( 'Select date', $plugin_name ); ?>', 
    //     timLabelErrorName: '<?php _e( 'Enter your name', $plugin_name ); ?>', 
    //     timLabelErrorEmailInvalid: '<?php _e( 'Invalid e-mail', $plugin_name ); ?>', 
    //     timLabelErrorAdults: '<?php _e( 'Select adults', $plugin_name ); ?>',

        

    //     departure: '<?php _e( 'Departure', $plugin_name ); ?>', 
    //     duration: '<?php _e( 'Duration', $plugin_name ); ?>', 
    //     price: '<?php _e( 'Price', $plugin_name ); ?>', 
    //     book: '<?php _e( 'Book', $plugin_name ); ?>', 
    //     taxes: '<?php _e( 'Taxes', $plugin_name ); ?>', 
    //     subtotal: '<?php _e( 'Subtotal', $plugin_name ); ?>', 
    //     totalPrice: '<?php _e( 'Total price', $plugin_name ); ?>', 
    //     selectTime: '<?php _e( 'Select time', $plugin_name ); ?>', 

    //     errorMinPaxRequired: '<?php _e( 'Min pax required', $plugin_name ); ?>', 
    //     errorSelectEarlierDate: '<?php _e( 'Select an earlier date', $plugin_name ); ?>', 
    //     errorNotAvailable: '<?php _e( 'Not available', $plugin_name ); ?>', 
    //     errorSelectPickupPlace: '<?php _e( 'Select the pick-up place', $plugin_name ); ?>', 
    //     errorSelectSelectTime: '<?php _e( 'Select the pick-up time', $plugin_name ); ?>'
    // };

    jQuery( function() {
        timProductDatePicker('package', 1, '<?php echo $content_language; ?>');
    });
    </script>
    <?php

}