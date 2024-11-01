<?php
/*
	Tim Order Template
*/
?>
<!-- <div class="tim_clr tim_box">
	<div class="tim_col_6">
		<b><?php _e( 'Status', $plugin_name ); ?>:</b> 
        <?php
        if ( $booking->status == 'confirmed' ){
        	?>
  			<span class="tim_label tim_label_success"><?php _e( 'Confirmed', $plugin_name ); ?></span>
        	<?php
    	} else {
    		?>
			<span class="tim_label tim_label_primary"><?php _e( 'Pending', $plugin_name ); ?></span> <?php _e( 'Awaiting staff response', $plugin_name ); ?>
        	<?php
        }
        ?>
	</div>
	<div class="tim_col_6 tim_align_right">
		<div style="font-size: 14px;">
    		<?php _e( 'Booking number', $plugin_name ); ?>: <b><?php echo $booking->booking_number ?></b>
    	</div>
	</div>
</div> -->

<h1><?php _e( 'Order detail', $this->plugin_name ); ?></h1>

<div class="tim_clr">
	<div class="tim_col_4">
		<div style="margin-bottom: 20px;"><b><?php _e( 'Summary', $plugin_name ); ?></b></div>
		<?php
		$this->timCartDetail( $booking, $booking->language->lang, 'order' );

		if ( $booking->status == 'confirmed' AND count($booking->booking_items) > 1 ){
			$invoiceId = $booking->invoice_ids[0];
			$invoiceUrl = $this->backEndUrl .'/invoices_client/'. $invoiceId .'/invoice_pdf/?sub='. $options['subdomain'] .'&cak='. $options['company_api_key'] .'&lang='. $booking->language->lang; # booking_language
			
			$itineraryUrl = $this->frontEndUrl .'/'. $options['subdomain'] .'/tickets/itinerary/'. $booking->id .'/'. $options['company_api_key'] .'?lang='. $booking->language->lang; // booking_language $options['domain_api_key']
			?>
			<br>
			<div class="tim_boxtim_align_center">
				<!-- <a href="<?php echo $invoiceUrl; ?>" target="_blank" class="tim-btn">
					<i class="fa fa-print"></i> <?php _e( 'Print invoice', $plugin_name ); ?>
				</a> &nbsp;  -->
				<a href="<?php echo $itineraryUrl; ?>" target="_blank" class="tim-btn">
					<i class="fa fa-print"></i> <?php _e( 'Print itinerary', $plugin_name ); ?>
				</a>
			</div>
			<?php
			echo '';
		}
		?>
	</div>
 	<div class="tim_col_4">
 		<div style="margin-bottom: 20px;"><b><?php _e( 'Contact details', $plugin_name ); ?></b></div>
		<table class="tim_table tim_table_details">
		    <tbody>
		        <tr>
		        	<td>
		            	<b><?php _e( 'Name', $plugin_name ); ?></b>
		            	<br>
		                <?php echo $booking->client->name; ?>
		            </td>
		        </tr>
		        <tr>
		            <td>
						<b><?php _e( 'Last name', $plugin_name ); ?></b>
		            	<br>
		                <?php echo $booking->client->last_name; ?>
		            </td>
		        </tr>
		        <tr>
		            <td>
						<b><?php _e( 'Country', $plugin_name ); ?></b>
		            	<br>
		                <?php echo $booking->client->country->name; ?>
		            </td>
		        </tr>
		    </tbody>
		</table>	
	</div>
    <div class="tim_col_4">
    	<div style="margin-bottom: 20px;"><b><?php _e( 'Payment', $plugin_name ); ?></b></div>
        <?php
    	$this->timPaymentDetails( $booking );
    	?>
    </div>
</div>

<?php
if ( $booking->invoice ) {
	?>
	<table class="tim_table tim_table_details">
	    <thead>
			<tr>
				<th colspan="2">
					<?php _e( 'Payment details', $plugin_name ); ?>
					<div style="float: right;">
						<a href="<?php echo $invoiceUrl; ?>" target="_blank">
							<i class="fa fa-print"></i> <?php _e( 'Print invoice', $plugin_name ); ?>
						</a>
					</div>
				</th>
			</tr>
		</thead>
	    <tbody>
	        <tr>
	            <td class="tim_table_label">
	                <?php _e( 'Payment method', $plugin_name ); ?>
	            </td>
	            <td>
	                <?php 
	                echo $booking->invoice->payment_method; ?>
	            </td>
	        </tr>
	        <?php
	        if ( $booking->status == 'confirmed' ){
	        	?>
	            <tr>
	                <td class="tim_table_label">
	                    <?php _e( 'Status', $plugin_name ); ?>
	                </td>
	                <td>
	                    <span class="tim_label tim_label_success"><span class="tim_text_uppercase"><?php _e( 'Paid', $plugin_name ); ?></span></span>
	                </td>
	            </tr>
	            <tr>
	                <td class="tim_table_label">
	                    <?php _e( 'Transaction ID', $plugin_name ); ?>
	                </td>
	                <td>
	                    <?php echo $booking->invoice->payment_trans_id; ?>
	                </td>
	            </tr>
	        	<?php
	    	} else {
	    		?>
	            <tr>
	                <td class="tim_table_label">
	                    <?php _e( 'Status', $plugin_name ); ?>
	                </td>
	                <td>
	                    <span class="tim_label tim_label_warning"><span class="tim_text_uppercase"><?php _e( 'Pending payment', $plugin_name ); ?></span></span>
	                </td>
	            </tr>
	        	<?php
	        }
	        ?>
	    </tbody>
	</table>
	<?php
}
?>