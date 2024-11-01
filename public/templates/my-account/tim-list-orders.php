<?php
/*
	Tim List Orders Template
*/
?>
<h5><?php _e( 'List orders', $plugin_name ); ?> (<?php echo count( $bookings ); ?>)</h5>
<?php
if ( count( $bookings ) ){
	?>
	<table class="tim_table">
	    <thead>
	        <tr>
	            <th style="width:120px;"><?php _e( 'Booking number', $plugin_name ); ?></th>
	            <th style="width:120px;"><?php _e( 'Status', $plugin_name ); ?></th>
	            <th><?php _e( 'Start date', $plugin_name ); ?></th>
	            <th><?php _e( 'End date', $plugin_name ); ?></th>
	            <th class="tim_align_right"><?php _e( 'Price', $plugin_name ); ?></th>
	            <th style="width:60px;"></th>
	        </tr>
	    </thead>
	    <tbody>
	        <?php
	        foreach ( $bookings as $item ) {
	            $url = $order_url .'?act=view&amp;oid='. $item->id .'&amp;onm='. $item->booking_number .'&amp;lng='. $item->language->lang; // booking_language
	            ?>
	            <tr">
	                <td data-th="<?php _e( 'Booking number', $plugin_name ); ?>:">
	                    <a href="<?php echo $url; ?>"><?php echo $item->booking_number; ?></a>
	                </td>
	                <td data-th="<?php _e( 'Status', $plugin_name ); ?>:">
	                    <?php
				        if ( $item->status == 'confirmed' ){
				        	?>
				  			<span class="tim_label tim_label_success"><?php _e( 'Confirmed', $plugin_name ); ?></span>
				        	<?php
				    	}
				    	else{
				    		?>
							<span class="tim_label tim_label_primary"><?php _e( 'Pending', $plugin_name ); ?></span>
				        	<?php
				        }
				        ?>
	                </td>
	                <td data-th="<?php _e( 'Start date', $plugin_name ); ?>:">
	                    <?php echo $this->format_date( $item->booking_start_date, '', $item->language->lang ); ?>
	                </td>
	                <td data-th="<?php _e( 'End date', $plugin_name ); ?>:">
	                    <?php echo $this->format_date( $item->end_date, '', $item->language->lang ); ?>
	                </td>
	                <td data-th="<?php _e( 'Price', $plugin_name ); ?>:" class="tim_align_right">
	                    <b><?php echo $item->currency->symbol . $item->total_price; ?></b>
	                </td>
	                <td class="tim_align_center">
	                    <a href="<?php echo $url; ?>" title="<?php _e( 'View order', $plugin_name ); ?>"><i class="fa fa-search"></i></a>
	                </td>
	            </tr>
	            <?php
	        } //.foreach
	        ?>
	    </tbody>
	</table>
	<?php
}
else{
    _e( 'No orders', $plugin_name );
}
?>