<?php
/*
	Tim Order Page
*/

get_header();

?>

<div class="container">
	<div class="tim_wrapper">
		<h1><?php _e( 'Order detail', $this->plugin_name ); ?></h1>
		
		<div id="timCartDetail">
		    <?php
		    $this->public_data->timCartDetail( $this->bookingCart, $this->content_language );
		    ?>
		</div>
	</div>
</div>

<div class="tim_spinner"></div>
<?php

wp_reset_query();
get_footer();

?>