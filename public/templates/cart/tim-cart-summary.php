<?php
/*
    Tim Cart Summary Template
*/

if ( $totalBookingItems > 0 ){
    foreach ( $bookingCart->booking_items as $item ) {
        $extraPricesLabel = '';
        if ( $item->pickup_price || $item->dropoff_price ) {
            $extraPrices = ( $item->pickup_price + $item->dropoff_price );

            $extraPricesLabel = '<div title="'. __( 'Transportation', $plugin_name ) .'">+ '. $bookingCurrency->symbol . $extraPrices .'<small></div>';
        }

        $itemDetailName   = $item->detail->content->name;
        $itemDetailOption = $item->detail->option ? $item->detail->option : '';
        switch( $item->booking_type ){
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
            <div class="tim-cart-item-left">
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
                    <span><?php echo $itemDetailOption; ?></span>
                </div>
            </div>

            <div class="tim-cart-item-right">
                <div class="tim-cart-item-detail">
                    <?php
                    if ( $item->booking_type === 'tour' || $item->booking_type === 'transportation') {
                        require 'products/tour.php'; // tour/transportation
                    } elseif ( $item->booking_type === 'hotel' ) {
                        require 'products/hotel.php';
                    }
                    ?>
                </div>

                <div class="tim-cart-item-price">
                    <?php echo $bookingCurrency->symbol . $item->item_price; ?>
                    <?php echo $extraPricesLabel; ?>
                </div>
            </div>
        </div><!--  /tim-cart-item -->
        <?php
    } //.foreach
}
else{
    _e( 'Empty order', $plugin_name );
}

?>