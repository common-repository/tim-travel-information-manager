<?php
/*
	Tim Order Page
*/

// For payment gateway redirect
if ( isset( $_GET['act'] ) AND $_GET['act'] === 'paid' ) {
	$pg = isset( $_GET['pg'] ) ? $_GET['pg'] : $_SESSION['tim_booking_client_session']['pg']; // bac, bcr

	require_once 'payment/'. $pg .'_process.php';
	exit;
}

$error = false;
if ( ! isset( $_GET['oid'] ) && ! isset( $_GET['onm'] ) ) {
	$error = true;
} else if ( ! isset($_SESSION['tim_verify_order']) && ! isset($_SESSION['tim_client_session']) && ! isset($_SESSION['tim_guest_session']) ){
	$error = true;
}

if ( $error ) {
	header( 'Location: '. $this->home_url );
	return false;
}

get_header();
?>

<div class="container">
	<div class="tim_wrapper">
		<!-- <div class="tim_clr">
			<div class="tim_col_8">
				<h1><?php _e( 'Order detail', $this->plugin_name ); ?></h1>
			</div>
			<?php
			if ( $_SESSION['tim_client_session'] ) {
				?>
				<div class="tim_col_4 tim_align_right">
					<a href="<?php echo $my_account_url; ?>?act=orders"><?php _e( 'View orders', $plugin_name ); ?></a>
				</div>
				<?php
			}
			?>
		</div>
 		-->
		
		<?php
		if ( $action == 'done' ){
			$this->public_data->timOrderCompleted( $this->booking );
		} else { // View order detail
			$this->public_data->timOrderDetail( $this->booking );
		}
		?>
	</div>
</div>

<div class="tim_spinner"></div>
<?php

wp_reset_query();
get_footer();

?>