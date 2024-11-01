<div class="tim-secondary">
	<?php echo $msg; ?>

	<span class="tim-secondary-buttons">
		<button 
			id="tim-accept-secondary"
			onclick="timProcessSecondaryPriceList('accepted')">
			<?php _e( 'Accept', $this->plugin_name ); ?>
		</button>

		<button 
			id="tim-cancel-secondary"
			onclick="timProcessSecondaryPriceList('declined')">
			<?php _e( 'Decline', $this->plugin_name ); ?>
		</button>
	</span>
</div>