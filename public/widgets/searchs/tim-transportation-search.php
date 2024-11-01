<?php

$public_data = new Tim_Travel_Manager_Public_Data( $plugin_name );

$currency_value  = $public_data->get_currency_value( TIM_TRAVEL_MANAGER_POST_TYPE_CURRENCIES );
$currency_id = ( $currency_value['id'] !== '' ) ? $currency_value['id'] : $currency_value->id;    // Session/Default
$currency_symbol = ( $currency_value['symbol'] !== '' ) ? $currency_value['symbol'] : $currency_value->symbol; // Session/Default

// $productCategories = $this->public_data->get_postmeta_list_by_value( TIM_TRAVEL_MANAGER_POST_TYPE_PRODUCT_CATEGORIES, 'product_type', 'transportation' );


// $locations  = $this->public_data->get_locations( TIM_TRAVEL_MANAGER_POST_TYPE_LOCATIONS, $content_language );
// var_dump($locations);

// echo '<br><br>--------------<br><br>';
// var_dump($list);


// ??
/*function group_product_locations_by_id( $list, $option ) {

    $locations = array();
    
    foreach ( $list as $item ) {
        $locationId = $item[$option .'_location_id'];

        if ( ! isset($locations[$locationId]) ) {
            $name = $item[$option .'_location_name'];

            $locations[$locationId] = array(
	        	'id' => $locationId, 
	        	'name' => $name
	        );
        }
    }

    // $locations = $this->public_data->sort_array_by( $locations, 'name', 'ASC' );

    return $locations;

}*/

$fromLocations = $this->public_data->group_product_locations_by_id( $list, 'departure_' );
// $toLocations = $this->public_data->group_product_locations_by_id( $list, 'arrival_' );

$toLocations = array();
if (is_array($list) || is_object($list)) {
    foreach ( $list as $item ) {
    	$object = new stdClass();
		$object->departure_location_id = $item['departure_location_id'];
		$object->arrival_location_id = $item['arrival_location_id'];
		$object->name = $item['arrival_location_name'];

    	array_push( $toLocations, $object );
    }
}


// echo '<pre>' , var_dump($fromLocations) , '</pre>';

// echo '<pre>' , var_dump($list) , '</pre>';
// echo '--------------';
// echo '<pre>' , var_dump($toLocations) , '</pre>';

// Convert to object in order to work with js

/*$locations = [];
foreach ( $fromLocations as $location ) {
	$object = new stdClass();
	$object->id = $location['id'];
	$object->name = $location['name'];

	// $locations[] = $object;
}
*/

/*$locations = [];
foreach ( $toLocations as $location ) {
	$object = new stdClass();
	$object->id = $location['id'];
	$object->name = $location['name'];

	$object->departure_location_id = $location['departure_location_id'];
	// $object->arrival_location_id = $location['arrival_location_id'];

	$locations[] = $object;
}*/

// echo '<pre>' , var_dump($locations) , '</pre>';
?>

<form action="#" name="dates" autocomplete="off" class="tim-form">
	<div class="tim-search-form">
		<div class="tim_clr tim-form-group">
			<div class="tim_col_4">
				<label><?php _e( 'From', $this->plugin_name ); ?></label>
				<select id="timDepartureLocation" onchange="timSelectTransportationDeparture(this.value)">
					<option value="">-- <?php _e( 'Select', $this->plugin_name ); ?> --</option>
					<?php
					foreach ( $fromLocations as $location ) {
						?>
						<option value="<?php echo esc_attr( $location['id'] ); ?>"><?php echo esc_html( $location['name'] ); ?></option>
						<?php
					}
					?>
				</select>
			</div>
			<div class="tim_col_4">
				<label><?php _e( 'To', $this->plugin_name ); ?></label>
				<select id="timArrivalLocation" disabled>
					<option value="">-- <?php _e( 'Select', $this->plugin_name ); ?> --</option>
				</select>
			</div>
			<div class="tim_col_4">
				<label><?php _e( 'Service date', $this->plugin_name ); ?></label>
				<input type="hidden" id="timFromDB" />
				<input 
					type="text" 
					class="calendar" 
					id="timFrom" 
					name="timFrom" 
					readonly />
			</div>
		</div>
		
		<div class="tim_clr tim-form-group">
			<div class="tim_col_4">
				<label><?php _e( 'Pick up time', $this->plugin_name ); ?></label>
				<input 
					type="text" 
					class="timepicker"
					id="timDepartureTime" 
					name="timDepartureTime" />
			</div>
			<div class="tim_col_4">
				<div class="tim_clr">
					<div class="tim_col_4 tim-search-pax">
						<label><?php _e( 'Adults', $this->plugin_name ); ?></label>
						<select id="timAdults">
							<?php
			                for ( $i = 1; $i <= 30; $i++ ){
			                    echo '<option value="'. esc_attr( $i ) .'">'. esc_html( $i ) .'</option>';
			                }
			                ?>
						</select>
					</div>
					<div class="tim_col_4 tim-search-pax">
						<label><?php _e( 'Children', $this->plugin_name ); ?></label>
						<select id="timChildren">
							<?php
			                for ( $i = 0; $i <= 20; $i++ ){
			                    echo '<option value="'. esc_attr( $i ) .'">'. esc_html( $i ) .'</option>';
			                }
			                ?>
						</select>
					</div>
					<div class="tim_col_4 tim-search-pax">
						<label><?php _e( 'Infants', $this->plugin_name ); ?></label>
						<select id="timInfants">
							<?php
			                for ( $i = 0; $i <= 10; $i++ ){
			                    echo '<option value="'. esc_attr( $i ) .'">'. esc_html( $i ) .'</option>';
			                }
			                ?>
						</select>
					</div>
				</div>
			</div>
			<div class="tim_col_4">
				<div class="tim-search-form-actions">
					<button 
						type="button" 
						id="sendbutton" 
						class="tim-btn tim-btn-bloc timSendButton" 
						onclick="timSearchTransportation();">
							<?php _e( 'Check rates', $this->plugin_name ); ?>
					</button>
				</div>
			</div>
		</div>
	</div>
</form>

<div id="timTransportations"></div>

<div class="tim_spinner"></div>
<input type="hidden" id="timUserCurrency" value="<?php echo $currency_id; ?>" />
<input type="hidden" id="timUserCurrencySymbol" value="<?php echo $currency_symbol; ?>" />

<script type="text/javascript">
var timLabels = {
	scheduleType: '<?php _e( 'Schedule type', $this->plugin_name ); ?>', 
	open: '<?php _e( 'Open', $this->plugin_name ); ?>',
	fixed: '<?php _e( 'Fixed', $this->plugin_name ); ?>', 
	duration: '<?php _e( 'Duration', $this->plugin_name ); ?>', 
	hours: '<?php _e( 'hours', $this->plugin_name ); ?>', 
    pickupTime: '<?php _e( 'Pick-up time', $this->plugin_name ); ?>', 
    price: '<?php _e( 'Price', $this->plugin_name ); ?>', 
    book: '<?php _e( 'Book', $this->plugin_name ); ?>', 
    details: '<?php _e( 'Details', $this->plugin_name ); ?>', 
    errorMinPaxRequired: '<?php _e( 'Min pax required', $this->plugin_name ); ?>', 
    errorNotAvailable: '<?php _e( 'Not available', $this->plugin_name ); ?>', 
    errorNotOptionsAvailable: '<?php _e( 'No options available. Try another date!', $plugin_name ); ?>',
    select: '<?php _e( 'Select', $this->plugin_name ); ?>'
};

var timToLocations = <?php echo json_encode($toLocations); ?>;

jQuery( function() {
    timProductDatePicker('transportation_search', 1, '<?php echo $content_language; ?>');
    setTimePicker('timepicker');
});
</script>

