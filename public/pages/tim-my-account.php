<?php
/*
	Tim My Account Page
*/

get_header();

?>

<div class="container">
	<div class="tim_wrapper">
		<h1><?php _e( 'My Account', $this->plugin_name ); ?></h1>

		<div class="tim_content_area">
			<?php
			if ( $_SESSION['tim_client_session'] ){
				$this->public_data->timMyAccount( $action, $this->content_language );
			}
			else{
				?>
				<div id="timCustomerFormSuccessMsg" class="tim_login_box"></div>
				<div id="timLoadDataResult">
					<?php $this->public_data->timLoginForm(); ?>
				</div>
				<?php
			}
			?>
		</div>
	</div>
</div>

<form><input type="hidden" id="timClientEmail" /></form>
<div class="tim_spinner"></div>
<?php

wp_reset_query();
get_footer();

?>