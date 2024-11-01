<?php
/*
	Tim Verify Order Page
*/

get_header();

?>

<div class="container">
	<div class="tim_wrapper">
		<h1><?php _e( 'Verify order', $this->plugin_name ); ?></h1>

		<div class="tim_content_area">
			<?php
			$this->public_data->timVerifyOrderForm();
			?>
		</div>
	</div>
</div>

<div class="tim_spinner"></div>
<?php

wp_reset_query();
get_footer();

?>