<?php

$booking_timeout = isset( $options['booking_timeout'] ) ? $options['booking_timeout'] : 5;

$secondary_price_list_enabled = isset( $options['secondary_price_list_enabled'] ) ? $options['secondary_price_list_enabled'] : '';
$secondaryPriceListDisplay = isset( $options['secondary_price_list_enabled'] ) ? '' : ' style="display: none;"';

$discount_coupon_enabled = isset( $options['discount_coupon_enabled'] ) ? $options['discount_coupon_enabled'] : '';
?>

<div class="tim_clr">
    <div class="tim_col_2">
        <h2><?php _e( 'Booking settings', $this->plugin_name ); ?></h2>
    </div>
    <div class="tim_col_10">
        <table class="form-table tim-table">
            <!-- Booking timeout -->
            <tr>
                <th>
                    <?php _e( 'Delete booking after', $this->plugin_name ); ?>
                </th>
                <td>
                    <input 
                        type="number" 
                        name="<?php echo $settings_section; ?>[booking_timeout]" 
                        value="<?php echo $booking_timeout; ?>" 
                        min="1" 
                        max="30" 
                        onkeypress="return timOnlyNumbers(event);" 
                        size="2" />

                    <?php _e( 'of user inactivity', $this->plugin_name ); ?><br />
                    <em style="font-size: 12px;"><?php _e( 'After this time a warning is shown to stating "Your booking will be deleted due to inactivity". The user can continue or cancel the booking', $this->plugin_name ); ?>.</em>
                </td>
            </tr>

            <!-- Secondary price list -->
            <tr>
                <th>
                    <?php _e( 'Secondary price list', $this->plugin_name ); ?>
                </th>
                <td>
                    <label>
                        <input 
                            type="checkbox" 
                            name="<?php echo $settings_section; ?>[secondary_price_list_enabled]" 
                            value="1" 
                            onclick="timDisplayContent(this, 'timSecondaryPriceListMsg')"
                            <?php checked( $secondary_price_list_enabled, true ); ?> /> 
                        <?php _e( 'Enable', $this->plugin_name ); ?> 
                    </label>
                    <em style="display: block; font-size: 12px; margin-top: 10px;">
                        <?php _e( 'Enable the option to activate a secondary price list for marketing & sales initiatives (i.e. customer segmentation).', $this->plugin_name ); ?>
                    </em>

                    <div id="timSecondaryPriceListMsg"<?php echo $secondaryPriceListDisplay; ?>>
                        <div style="margin-top: 20px;">
                            <label>
                                <input 
                                    type="checkbox" 
                                    name="<?php echo $settings_section; ?>[secondary_price_list_home_page_only]" 
                                    value="1" 
                                    <?php checked( $options['secondary_price_list_home_page_only'], true ); ?> /> 
                                <?php _e( 'Home page only', $this->plugin_name ); ?> 
                            </label> 
                            <label> &nbsp;
                                <input 
                                    type="checkbox" 
                                    name="<?php echo $settings_section; ?>[secondary_price_list_always_visible]" 
                                    value="1" 
                                    <?php checked( $options['secondary_price_list_always_visible'], true ); ?> /> 
                                <?php _e( 'Always visible', $this->plugin_name ); ?> 
                            </label>

                            <br /><br /><br />

                            <b><?php _e( 'Custom message', $this->plugin_name ); ?>:</b><br /><br />

                            <b><?php _e( 'English', $this->plugin_name ); ?>:</b><br />
                            <textarea 
                                name="<?php echo $settings_section ?>[secondary_price_list_custom_msg_en]" 
                                cols="80"><?php echo $options['secondary_price_list_custom_msg_en']; ?></textarea>
                            <br /><br />
                            <b><?php _e( 'Spanish', $this->plugin_name ); ?>:</b><br />
                            <textarea 
                                name="<?php echo $settings_section; ?>[secondary_price_list_custom_msg_es]" 
                                cols="80"><?php echo $options['secondary_price_list_custom_msg_es']; ?></textarea>

                            <!-- <br /><br />
                            <label>
                                <input 
                                    type="checkbox" 
                                    name="<?php echo $settings_section; ?>[secondary_price_list_custom_widget_enabled]" 
                                    value="1" 
                                    <?php checked( $options['secondary_price_list_custom_widget_enabled'], true ); ?> /> 
                                <?php _e( 'Use custom widget', $this->plugin_name ); ?> 
                            </label>
                            <em style="display: block; font-size: 12px; margin-top: 10px;">
                                <?php _e( 'Enable this option if you need custom style and position of this message in the website.', $this->plugin_name ); ?><br />
                                <?php _e( 'Use this short-code', $this->plugin_name ); ?>: <b>secondary-price-list</b>
                            </em> -->
                        </div>
                    </div>
                </td>
            </tr>

            <!-- Discount coupon -->
            <tr>
                <th>
                    <?php _e( 'Discount coupon', $this->plugin_name ); ?>
                </th>
                <td>
                    <label>
                        <input 
                            type="checkbox" 
                            name="<?php echo $settings_section; ?>[discount_coupon_enabled]" 
                            value="1" 
                            <?php checked( $discount_coupon_enabled, true ); ?> /> 
                        <?php _e( 'Enable', $this->plugin_name ); ?> 
                    </label>
                    <em style="display: block; font-size: 12px; margin-top: 10px;">
                        <?php _e( 'Enable the discount coupon for bookings', $this->plugin_name ); ?>
                    </em>
                </td>
            </tr>
        </table>
    </div>
</div>