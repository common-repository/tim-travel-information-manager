<?php

$tourLocations = $this->public_data->group_product_locations_by_id($allTourLocations);

$categories = $this->public_data->get_miscellaneous( TIM_TRAVEL_MANAGER_POST_TYPE_CATEGORIES, 'tour' );

$rates = array(	
	array(
		'from' => 0,
		'to'   => 20
	), 
	array(
		'from' => 21,
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

$get_loc = isset( $_GET['loc'] )  ? $_GET['loc']  : '';
$get_cat = isset( $_GET['cat'] )  ? $_GET['cat']  : '';
$get_rate = isset( $_GET['rate'] ) ? $_GET['rate'] : '';
// $get_sort = isset( $_GET['sort'] ) ? $_GET['sort'] : '';
$get_view = isset( $_GET['view'] ) ? $_GET['view'] : '';

$full_url = explode('?', $_SERVER['REQUEST_URI'], 2); // Ex /travel_demo/model/
// $base_url = $_SERVER['HTTP_HOST'] . $full_url[0];
$base_url = $full_url[0];
$args_url = isset($full_url[1]) ? $full_url[1] : '';

$view_list = add_query_arg('view', 'list');
$view_grid = add_query_arg('view', 'grid');

?>
<div class="tim_list_search tim_row">
	<div class="tim_col_11">
		<?php _e( 'Filter', $this->plugin_name ); ?>:
		<form class="tim_list_search_filter_form">
		    <?php
		    if ( $tourLocations ) {
				?>
				<select name="loc">
					<option<?php selected( $get_loc, 'all' ); ?> value="all">- <?php _e( 'All locations', $this->plugin_name ); ?> -</option>
					<?php
					foreach ( $tourLocations as $location ) {
						$id = $location['id'];
						$name = $location['name'];
						if ( $id ) {
							?><option<?php selected( $get_loc, $id ); ?> value="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $name ); ?></option><?php
						}						
					}
					?>
				</select>
				<?php
			}
			if ( $categories ) {
				?>
				<select name="cat">
					<option<?php selected( $get_cat, 'all' ); ?> value="all">- <?php _e( 'All categories', $this->plugin_name ); ?> -</option>
					<?php
					foreach ( $categories as $category ) {
						$name = $category['name']->$content_language;
						if ( $name ) {
							?><option<?php selected( $get_cat, $name ); ?> value="<?php echo esc_attr( $name ); ?>"><?php echo esc_html( $name ); ?></option><?php
						}
					}
					?>
				</select>
				<?php
			}
			?>
			<select name="rate">
				<option<?php selected( $get_rate, 'all' ); ?> value="all">- <?php _e( 'Rate range', $this->plugin_name ); ?> -</option>
				<?php
				foreach ( $rates as $rate) {
					$value = $rate['from'] .'-'. $rate['to'];
					?><option<?php selected( $get_rate, $value ); ?> value="<?php echo esc_attr( $value ); ?>">$<?php echo esc_html( $rate['from'] ); ?> - $<?php echo esc_html( $rate['to'] ); ?></option><?php
				}
				?>
			</select>
			<?php

			if ( ! empty($get_view) ) {
				?><input type="hidden" name="view" value="<?php echo esc_attr( $get_view ); ?>" /><?php
			}
			?>
			<input type="submit" value="<?php _e( 'Search', $this->plugin_name ); ?>">
			<?php
			if ( $args_url !== '' ) {
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


<?php

/*

// $locations = $this->public_data->get_locations( TIM_TRAVEL_MANAGER_POST_TYPE_LOCATIONS, $content_language );
// $tourLocations = $this->public_data->group_product_locations_by_id($list);

// var_dump($tourLocations);

// Filter only locations assigned in tours
// $tourLocations = [];
// foreach ($locations as $location) {
// 	if ( in_array($location['id'], $toursLocationIds) ) {
// 		$tourLocations[] = $location;
// 	}
// }

$name = $location['name'];
if ( $name ) {
	<option selected( $get_loc, $name ); value="<?php echo esc_attr( $name );"><?php echo esc_html( $name );</option><?php
}

*/

?>