<?php
/*
	Tim Order Completed Template
*/

$taxesIncluded = $booking->price_list->taxes_included;

?>
<!-- <div class="tim_clr"> -->
	<!-- <div class="tim_col_8"> -->
		<div class="tim_box" style="text-align: center;">
			<i class="fa fa-check-circle-o fa-5x" style="color: #94b86e;"></i>

			<h2><?php _e( 'Order completed', $plugin_name ); ?></h2>
			
			<?php _e( 'Thank you', $plugin_name ); ?> <?php echo $booking->client->name; ?>!<br />
			
			<?php
            switch( $booking->status ){
            	case 'processing':
                	?>
                	<?php _e( 'Your booking has been processed', $plugin_name ); ?>.

                	<!-- <?php _e( 'Status', $plugin_name ); ?>: 
	                <span class="tim_label tim_label_primary"><?php _e( 'Pending', $plugin_name ); ?></span> 
	                -->
	                <?php _e( 'Awaiting staff response', $plugin_name ); ?>
            		<?php
            	break;
            	case 'confirmed':
                	?>
                	<?php _e( 'Your booking has been completed', $plugin_name ); ?>.

                	<!-- <?php _e( 'Status', $plugin_name ); ?>:
	                <span class="tim_label tim_label_success"><?php _e( 'Confirmed', $plugin_name ); ?></span> -->
            		<?php
            	break;
        	}

        	// ->booking_language
        	?>

        	<br /><br />
        	<?php _e( 'Booking number', $plugin_name ); ?>: <b><?php echo $booking->booking_number ?></b>
			<br /><br /><br />
			<!-- <hr /> -->

			<a href="<?php echo $order_url; ?>?act=view&amp;oid=<?php echo $booking->id; ?>&amp;onm=<?php echo $booking->booking_number; ?>&amp;lng=<?php echo $booking->language->lang; ?>" class="tim-btn tim-btn-lgx"><?php _e( 'View order', $plugin_name ); ?></a>
		</div>
	<!-- </div> -->
    <!-- <div class="tim_col_4"> -->
    	<?php
    	// $this->timPaymentDetails( $booking );
    	?>
	<!-- </div> -->
<!-- </div> -->