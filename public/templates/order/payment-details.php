<div class="tim_box tim-payment-box">
    <ul>
    	<li class="tim_clrx">
    		<!-- <div class="tim_col_5"> -->
    			<?php _e( 'Booking number', $plugin_name ); ?>:
    			<br>
    		<!-- </div> -->
    		<!-- <div class="tim_col_7"> -->
    			<b><?php echo $booking->booking_number; ?></b>
    		<!-- </div> -->
    	</li>
    	<li><hr></li>
    	<li class="tim_clrx">
    		<!-- <div class="tim_col_5"> -->
    			<?php _e( 'Status', $plugin_name ); ?>: 
    		<!-- </div> -->
    		<!-- <div class="tim_col_7"> -->
    			<?php
                switch( $booking->status ) {
                	case 'processing':
	                	?>
		                <span class="tim_label tim_label_primary"><?php _e( 'Pending', $plugin_name ); ?></span>
	            		<?php
	            	break;
	            	case 'confirmed':
	                	?>
		                <span class="tim_label tim_label_success"><?php _e( 'Confirmed', $plugin_name ); ?></span>
	            		<?php
	            	break;
            	}
            	?>
    		<!-- </div> -->
    	</li>
    </ul>
    <div class="tim_align_center tim_text_uppercase">
        <?php
        switch( $booking->status ) {
        	case 'processing':
            	?>
                <br />
                <div class="tim_alert tim_alert_warning">
            		<b><?php _e( 'Payment pending', $plugin_name ); ?></b>
        		</div>
        		<?php
        	break;
        	case 'confirmed':
            	?>
                <br />
                <div class="tim_alert tim_alert_info">
            		<b><?php _e( 'Paid', $plugin_name ); ?></b>
        		</div>
        		<?php
        	break;
    	}
    	?>
	</div>

	<br />

	<?php
	if ( $booking->total_extra_prices || $booking->total_discount_prices || $booking->total_tax_prices ){
		?>
		<div class="tim-payment-box-subtotal">
			<?php
			if ( ! $taxesIncluded ) {
				?>
				<dl class="tim-dl">
        			<dt>
	        			<?php _e( 'Subtotal', $plugin_name ); ?>
	        		</dt>
	        		<dd>
	        			<b><?php echo $booking->currency->symbol . $booking->total_net_prices; ?></b>
	        		</dd>
	        	</dl>
    			<?php
    		}

            if ( $booking->total_extra_prices ) {
                if ( ! $taxesIncluded ) {
                	?>
	        		<dl class="tim-dl">
	        			<dt>
		        			<?php _e( 'Extras', $plugin_name ); ?> (+)
		        		</dt>
		        		<dd>
		        			<?php echo $booking->currency->symbol . $booking->total_extra_prices; ?>
		        		</dd>
		        	<dt>
	                <?php
	            } else {
	            	?>
	        		<div class="tim_align_right">
	        			<?php _e( 'Extras', $plugin_name ); ?> (+)
	        		</div>
	                <?php
	            }
            }

            if ( $booking->total_discount_prices ) {
                ?>
                <dl class="tim-dl">
	        		<div>
	        			<?php _e( 'Discount', $plugin_name ); ?> (-)
	        		</div>
	        		<dd>
	        			<?php echo $booking->currency->symbol . $booking->total_discount_prices; ?>
	        		</dd>
	        	<dt>
                <?php
            }

            if ( ! $taxesIncluded && $booking->total_tax_prices ) {
            	$total_net_tax_prices = $booking->total_net_tax_prices > 0 ? $booking->total_net_tax_prices : $booking->total_tax_prices;
                ?>
                <dl class="tim-dl">
	        		<dt>
	        			<?php _e( 'Taxes', $plugin_name ); ?> (+)
	        		</dt>
	        		<dd>
	        			<?php echo $booking->currency->symbol . $total_net_tax_prices; ?>
	        		</dd>
	        	<dt>
                <?php
            }
        ?>
        </div>
        <?php
	}
	?>
	<dl class="tim-dl tim-payment-box-total">
		<dt>
			<?php echo strtoupper( __( 'Total price', $plugin_name ) ); ?>
		</dt>
		<dd class="tim_align_right">
			<?php echo $booking->currency->code; ?> <b><?php echo $booking->currency->symbol . $booking->total_price; ?></b>
		</dd>
	</dl>
</div>