<?php

// $bookingId = $_GET['bookingId'];

$bookingId = $id; // from data.php

?>
<div class="tim_wrapper tim_align_center">
    <h3><?php _e( 'Your booking will be deleted due to inactivity', $plugin_name ); ?></h3>

    <button 
        class="tim-btn" 
        onclick="timContinueWithBooking('<?php echo $bookingId ?>')">
        <?php _e( 'Continue with booking', $plugin_name ); ?>
    </button>

    <br /><br />
    <a href="javascript:void(0)" 
        onclick="timCancelBooking('<?php echo $bookingId ?>')">
        <?php _e( 'Cancel booking', $plugin_name ); ?>
    </button>

    <!-- <div id="booking-expired-msg" style="display: none;">Your booking has been deleted due inactivity</div> -->
</div>