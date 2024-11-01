<?php
/*
    Tim Cart Template
*/

// var_dump($bookingCart);
// echo $bookingLang = $bookingCart->language->lang;
// var_dump( $bookingCart->price_list );

$taxesIncluded = $bookingCart->price_list->taxes_included;

$priceListId = $bookingCart->price_list_id;

// $options = wp_load_alloptions();
// foreach ($options as $slug => $values) {
//     var_dump($slug);
//     // var_dump($values);
// }

// $default_content_lang = $this->get_default_content_lang();
$default_content_lang = 'en';

if ( $totalBookingItems > 0 ) {
    $validCart = true;
    
    $disabledCheckOut = '';

    foreach ( $bookingCart->booking_items as $item ) {
        $extraPricesLabel = '';
        if ( $item->pickup_price || $item->dropoff_price ) {
            $extraPrices = ( $item->pickup_price + $item->dropoff_price );

            $extraPricesLabel = '<div title="'. __( 'Transportation', $plugin_name ) .'">+ '. $bookingCurrency->symbol . $extraPrices .'</div>';
        }

        $itemDetailName = $item->detail->content->name;
        $itemDetailOption = $item->detail->option ? $item->detail->option : '';
        switch ( $item->booking_type ) {
            case 'tour':
                $pickupPlacesByLocation = $this->get_postmeta_list_by_value( TIM_TRAVEL_MANAGER_POST_TYPE_PICKUP_PLACES, 'location_id', $item->detail->location_id );
                $dropoffPlacesByLocation = $pickupPlacesByLocation;

                if ( ( !$item->pickup_place_id || !$item->dropoff_place_id ) AND $validCart ) {
                    $validCart = false;
                }
            break;

            case 'transportation':
                $pickupPlacesByLocation = $this->get_postmeta_list_by_value( TIM_TRAVEL_MANAGER_POST_TYPE_PICKUP_PLACES, 'location_id', $item->detail->departure_location_id );
                $dropoffPlacesByLocation = $this->get_postmeta_list_by_value( TIM_TRAVEL_MANAGER_POST_TYPE_PICKUP_PLACES, 'location_id', $item->detail->arrival_location_id );
            break;

            case 'hotel':
                $labelrooms = ( $item->total_rooms > 1 ) ? 'rooms' : 'room';
                $labelrooms = __( $labelrooms, $plugin_name ); 

                $labelNights = ( $item->total_nights > 1 ) ? 'nights' : 'night';
                $labelNights = __( $labelNights, $plugin_name ); 
            break;
        }

        $totalPax = $this->get_total_pax( $item->adults, $item->children, $item->infants, $item->seniors, 'long' );
        ?>
        <div class="tim-cart-item">
            <?php
            if ( ! $option ) { // Cart item buttons
                ?>
                <div class="tim-cart-item-buttons">
                    <a ref="javascript:void(0)" onclick="timOpenEditItemFromOrder('<?php echo $item->id; ?>', '<?php echo $item->booking_type; ?>', '<?php echo $priceListId; ?>')" title="<?php _e( 'Edit', $plugin_name ); ?>"><i class="fa fa-pencil-square-o"></i></a> 
                    <a href="javascript:void(0)" onclick="timRemoveItemFromOrder('<?php echo $item->id; ?>')" title="<?php _e( 'Remove', $plugin_name ); ?>"><i class="fa fa-trash"></i></a>
                </div>
                <?php
            }
            ?>
            <div class="tim-cart-item-header">
                <?php
                if ( $item->detail->content->logo ) {
                    ?>
                    <div class="tim-cart-item-img">
                        <img src="<?php echo $item->detail->content->logo; ?>" alt="<?php echo $itemDetailName; ?>" />
                    </div>
                    <?php
                }
                ?>
                <div class="tim-cart-item-name">
                    <div><?php echo $itemDetailName; ?></div>
                    <!-- <span><?php //echo $itemDetailOption; ?></span> -->

                    <div class="tim-cart-item-price">
                        <?php
                        if ( ! $taxesIncluded ) {
                            echo $bookingCurrency->symbol . $item->net_price;
                            echo $extraPricesLabel;
                        } else {
                            if ( $extraPricesLabel ) {
                                ?><div>+ <?php _e( 'Extras', $plugin_name ); ?></div><?php
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>

            <div class="tim-cart-item-content">
                <div class="tim-cart-item-detail">
                    <?php
                    if ( $item->booking_type === 'tour' || $item->booking_type === 'transportation') {
                        require 'products/tour.php'; // tour/transportation
                    } elseif ( $item->booking_type === 'hotel' ) {
                        require 'products/hotel.php';
                    }

                    // Print voucher
                    if ( $option === 'order' AND $bookingCart->status == 'confirmed' ){ // Order view
                        $voucherUrl = $this->frontEndUrl .'/'. $options['subdomain'] .'/tickets/voucher/'. $item->id .'/'. $options['company_api_key'] .'?lang='. $bookingCart->language->lang; // booking_language
                        ?>
                        <br />
                        <a href="<?php echo $voucherUrl; ?>" class="tim-btn" target="_blank">
                            <?php _e( 'Print voucher', $plugin_name ); ?>
                        </a> 
                        <?php
                    }
                    ?>
                </div>

                <!-- <div class="tim-cart-item-price">
                    <?php
                    if ( ! $taxesIncluded ) {
                        echo $bookingCurrency->symbol . $item->net_price;
                        echo $extraPricesLabel;
                    } else {
                        if ( $extraPricesLabel ) {
                            ?><div>+ <?php _e( 'Extras', $plugin_name ); ?></div><?php
                        }
                    }
                    ?>
                </div> -->
            </div>
        </div><!--  /tim-cart-item -->
        <?php
    } //.foreach
    ?>

    <div id="invalidCardMsg" class="tim_hide">
        <div class="tim_alert tim_alert_danger"><?php _e( 'Select the pick-up place', $plugin_name ); ?></div>
        <br>
    </div>

    <div class="tim-cart-totals" style="display: none;">
        <?php
        if ( ! $taxesIncluded ) {
            if ( $bookingCart->total_extra_prices || $bookingCart->total_discount_prices || $bookingCart->total_tax_prices ){
                
                // if ( ! $taxesIncluded ) {
                    ?>
                    <div>
                        <?php _e( 'Subtotal', $plugin_name ); ?> <?php echo $bookingCurrency->symbol . $bookingCart->total_net_prices; ?>
                    </div>
                    <?php
                // }
                
                if ( $bookingCart->total_discount_prices ) {
                    ?>
                    <div>
                        (-) <?php _e( 'Discount', $plugin_name ); ?> <?php echo $bookingCurrency->symbol . $bookingCart->total_discount_prices; ?>
                    </div>
                    <?php
                }

                if ( $bookingCart->total_extra_prices ) {
                    ?>
                    <div>
                        (+) <?php _e( 'Transportation', $plugin_name ); ?> <?php echo $bookingCurrency->symbol . $bookingCart->total_extra_prices; ?>
                    </div>
                    <?php
                }

                if ( $bookingCart->total_tax_prices ) {
                    $total_net_tax_prices = $bookingCart->total_net_tax_prices > 0 ? $bookingCart->total_net_tax_prices : $bookingCart->total_tax_prices;
                    ?>
                    <div>
                        <?php _e( 'Taxes', $plugin_name ); ?> <?php echo $bookingCurrency->symbol . $total_net_tax_prices; ?>
                    </div>
                    <?php
                }
            }
        }
        ?>
        <div class="tim-cart-total">
            <?php _e( 'Total price', $plugin_name ); ?> <span><?php echo $bookingCurrency->code; ?> <?php echo $bookingCurrency->symbol . $bookingCart->total_price; ?></span>
        </div>

        <?php
        if ( ! $option ) {
            ?>
            <button type="button" onclick="location.href='<?php echo $url_checkout; ?>'" class="tim-cart-complete"<?php echo $disabledCheckOut; ?>>
                <?php _e( 'Complete order', $plugin_name ); ?>
            </button>
            <?php
        }
        ?>
        <input type="hidden" id="timValidCart" value="<?php echo $validCart; ?>" />
    </div> <!-- ./tim-cart-total -->

    <input type="hidden" id="timBookingTotalAmount" value="<?php echo $bookingCart->total_price; ?>" />

    <?php
    if ( ! $option ) {
        ?>
        <form>
            <input type="hidden" id="timTotalBookingItems" value="<?php echo $totalBookingItems; ?>" />
            <input type="hidden" id="timLabelExtraCost" value="<?php _e( 'Extra cost', $plugin_name ); ?>" />
            <input type="hidden" id="timLabelApply" value="<?php _e( 'Apply', $plugin_name ); ?>" />
            <input type="hidden" id="timLabelSelectPickupPlace" value="<?php _e( 'Select the pick-up place', $plugin_name ); ?>" />
            <input type="hidden" id="timLabelSelectDropoffPlace" value="<?php _e( 'Select the drop-off place', $plugin_name ); ?>" />
        </form>
        <?php
    }
} else {
    _e( 'Empty order', $plugin_name );
}