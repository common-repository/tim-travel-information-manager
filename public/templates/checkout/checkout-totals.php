<?php

$taxesIncluded = $bookingCart->price_list->taxes_included;

?>

<div class="tim-payment-box">
	<?php
	if ( $bookingCart->total_extra_prices > 0 || $bookingCart->total_discount_prices > 0 || $bookingCart->total_tax_prices > 0 ) {
		?>
		<div class="tim-payment-box-subtotal">
			<dl class="tim-dl">
				<?php
				if ( ! $taxesIncluded ) {
					?>
		    		<dt>
		    			<?php _e( 'Subtotal', $plugin_name ); ?>
		    		</dt>
		    		<dd id="timNetPrice">
		    			<b><?php echo $bookingCurrency->symbol . $bookingCart->total_net_prices; ?></b>
		    		</dd>
	    			<?php
	    		}
	    		
	    		if ( $bookingCart->total_extra_prices ) {
		    		if ( ! $taxesIncluded ) {
		                ?>
		        		<dt>
		        			<?php _e( 'Extras', $plugin_name ); ?> (+)
		        		</dt>
		        		<dd id="timTotalExtraPrices">
		        			<?php echo $bookingCurrency->symbol . $bookingCart->total_extra_prices; ?>
		        		</dd>
		                <?php
		            } else {
		            	?>
		        		<dt></dt>
		        		<dd id="timTotalExtraPrices">
		        			<?php _e( 'Extras', $plugin_name ); ?> (+)
		        		</dd>
		                <?php
		            }
	            }

	    		if ( $bookingCart->total_discount_prices ) {
	                ?>
	        		<dt>
	        			<?php _e( 'Discount', $plugin_name ); ?> (-) <a href="javascript:void(0)" onclick="timDeleteDiscountCoupon()" title="<?php _e( 'Remove coupon', $plugin_name ); ?>">(x)</a>
	        		</dt>
	        		<dd id="timTotalDiscountPrices">
	        			<?php echo $bookingCurrency->symbol . $bookingCart->total_discount_prices; ?>
	        		</dd>
	                <?php
	            }				                

	            if ( ! $taxesIncluded && $bookingCart->total_tax_prices ) {
	                $total_net_tax_prices = $bookingCart->total_net_tax_prices > 0 ? $bookingCart->total_net_tax_prices : $bookingCart->total_tax_prices;
	                ?>
	        		<dt>
	        			<?php _e( 'Taxes', $plugin_name ); ?> (+)
	        		</dt>
	        		<dd id="timTotalTaxPrices">
	        			<?php echo $bookingCurrency->symbol . $total_net_tax_prices; ?>
	        		</dd>
	                <?php
	            }
	        ?>
	    	</dl>
	    </div>
	    <?php
	}
	?>
	<div class="tim-payment-box-total">
		<dl class="tim-dl">
			<dt>
				<?php echo __( 'Total price', $plugin_name ); ?>
			</dt>
			<dd id="timTotalPrice">
				<?php echo $bookingCurrency->code; ?> <b><?php echo $bookingCurrency->symbol . $bookingCart->total_price; ?></b>
			</dd>
		</dl>
	</div>
</div>

<?php
if ( $discount_coupon_enabled && ! $bookingCart->discount_coupon_id ) { //  && $allowCheckOut
	?>
	<!-- Discount coupon-->
	<div class="tim-form-group tim-coupon-code">
		<div class="tim_clr">
			<div class="tim_col_9">
	    		<input 
	        		type="text" 
	        		id="timCouponCode" 
	        		name="timCouponCode" 
	        		placeholder="<?php _e( 'Discount coupon', $plugin_name ); ?>" />
	        		<input type="hidden" id="timLabelErrorCoupon" value="<?php _e( 'Invalid coupon', $plugin_name ); ?>" />
	        </div>

	        <div class="tim_col_3">
	        	<button 
					type="button" 
					id="timApplyCodeBtn" 
					onclick="timApplyDiscountCoupon()" 
					class="timSendButton">
					<?php _e( 'Apply', $plugin_name ); ?>
				</button>
			</div>
		</div>
		<div id="timCouponCodeInvalid"></div>
	</div>
	<?php
}
?>