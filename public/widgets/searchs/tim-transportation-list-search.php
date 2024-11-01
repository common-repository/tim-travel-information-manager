<?php

$locations = $this->public_data->get_locations( TIM_TRAVEL_MANAGER_POST_TYPE_LOCATIONS, $content_language );

$transportationLocations = $this->public_data->group_product_locations_by_id($list);

$productCategories = $this->public_data->get_postmeta_list_by_value( TIM_TRAVEL_MANAGER_POST_TYPE_PRODUCT_CATEGORIES, 'product_type', 'transportation' );

$rates = array(	
	array(
		'from' => 20,
		'to'   => 50
	),
	array(
		'from' => 51,
		'to'   => 100
	),
	array(
		'from' => 101,
		'to'   => 200
	),
	array(
		'from' => 201,
		'to'   => 300
	)
);

$get_type = isset( $_GET['type'] ) ? $_GET['type'] : '';
$get_dep  = isset( $_GET['dep'] )  ? $_GET['dep']  : '';
$get_arr  = isset( $_GET['arr'] )  ? $_GET['arr']  : '';
$get_date = isset( $_GET['date'] ) ? $_GET['date'] : '';
$get_time = isset( $_GET['time'] ) ? $_GET['time'] : '';
$get_view = isset( $_GET['view'] ) ? $_GET['view'] : '';

$full_url = explode('?', $_SERVER['REQUEST_URI'], 2); //Ex /travel_demo/model/
$base_url = $full_url[0];
$args_url = isset($full_url[1]) ? $full_url[1] : '';

$view_list = add_query_arg('view', 'list');
$view_grid = add_query_arg('view', 'grid');
?>
<div class="tim_list_search tim_row">
	<div class="tim_col_11">
		<?php _e( 'Filter', $this->plugin_name ); ?>:
		<form class="tim_list_search_filter_form">
		    <select name="cat">
		    	<option<?php selected( $get_type, 'all' ); ?> value="all">- <?php _e( 'Service type', $this->plugin_name ); ?> -</option>
		    	<?php
				foreach ( $productCategories as $productCategory ) {
					$name = $productCategory['name']->$content_language;
					if ( $name ){
						?><option<?php selected( $get_type, $name ); ?> value="<?php echo esc_attr( $name ); ?>"><?php echo esc_html( $name ); ?></option><?php
					}
				}
				?>
			</select>
			<?php

		    if ( $transportationLocations ) {
				?>
				<select name="dep">
					<option<?php selected( $get_dep, 'all' ); ?> value="all">- <?php _e( 'Departing from', $this->plugin_name ); ?> -</option>
					<?php
					foreach ( $transportationLocations as $location ) {
						$name = $location['name'];
						if ( $name ){
							?><option<?php selected( $get_dep, $name ); ?> value="<?php echo esc_attr( $name ); ?>"><?php echo esc_html( $name ); ?></option><?php
						}
					}
					?>	
				</select> 
				<select name="arr">
					<option<?php selected( $get_arr, 'all' ); ?> value="all">- <?php _e( 'Arriving to', $this->plugin_name ); ?> -</option>
					<?php
					foreach ( $transportationLocations as $location ) {
						$name = $location['name']->$content_language;
						if ( $name ){
							?><option<?php selected( $get_arr, $name ); ?> value="<?php echo esc_attr( $name ); ?>"><?php echo esc_html( $name ); ?></option><?php
						}				
					}
					?>
				</select>
				<?php
			}

			if ( ! empty($get_view) ){
				?><input type="hidden" name="view" value="<?php echo esc_attr( $get_view ); ?>" /><?php
			}
			?>
			<input type="submit" value="<?php _e( 'Search', $this->plugin_name ); ?>">
			<?php
			if ( $args_url != '' ){
				?> <a href="<?php echo $base_url; ?>"><?php _e( 'Clear', $this->plugin_name ); ?></a><?php
			}
			?>
		</form>
	</div>
	<div class="tim_col_1">
		<div class="tim_list_search_view">
			<a href="<?php echo $view_list; ?>" title="<?php _e( 'List view', $this->plugin_name ); ?>"><i class="fa fa-list fa-lg"></i></a>
			<a href="<?php echo $view_grid; ?>" title="<?php _e( 'Gallery view', $this->plugin_name ); ?>"><i class="fa fa-th fa-lg"></i></a>
		</div>
	</div>
</div>