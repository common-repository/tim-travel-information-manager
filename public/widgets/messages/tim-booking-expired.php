<div id="timBookingExpiredMsg">
	<div class="tim_alert tim_alert_danger tim_align_center" style="border-radius: 0; font-size: 14px;">
		<h2><?php _e( 'Booking deleted', $this->plugin_name ); ?></h2>
		<p><?php _e( 'Your booking has been deleted due to inactivity', $this->plugin_name ); ?></p>
		
		<a href="javascript:void(0);" onclick="timCloseAlert('timBookingExpiredMsg')">
			<b><?php _e( 'Dismiss', $this->plugin_name ); ?> (x)</b>
		</a>
	</div>
</div>