<?php
/*
	Tim Checkout Page
*/

if ( !isset($_SESSION['tim_cart_session']) ) {
	header( 'Location: '. $this->home_url );
	return false;
}

get_header();


// echo $booking_url = get_permalink('33');
?>

<div class="container">
	<div class="tim_wrapper">
		<h1><?php _e( 'Order confirmation', $this->plugin_name ); ?></h1>
		<?php
		$this->public_data->timCheckoutDetail( $this->bookingCart, $this->content_language, $isClientLogged );
		?>

		<!-- <br><br>
		<div class="tim_align_center">
			<a href="<?php echo $this->home_url; ?>" class="tim-btn"><?php _e( 'Continue shopping', $this->plugin_name ); ?></a>
		</div> -->
	</div>
</div>

<div class="tim_spinner"></div>
<?php

wp_reset_query();
get_footer();

?>