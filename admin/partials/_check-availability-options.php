<?php

$TOUR_CHECK_AVAILABILITY_OPTIONS = array( 
    array('id' => 0, 'name' => __( 'Sidebar', $this->plugin_name )), 
    array('id' => 1, 'name' => __( 'Content', $this->plugin_name ))
);

// Default layout tour
$options['tour_check_rate_layout_id'] = isset($options['tour_check_rate_layout_id']) ? $options['tour_check_rate_layout_id'] : 0;

$TRANSPORTATION_CHECK_AVAILABILITY_OPTIONS = array( 
    array('id' => 0, 'name' => __( 'Sidebar', $this->plugin_name )), 
    array('id' => 1, 'name' => __( 'Content', $this->plugin_name ))
);

// Default layout transportation
$options['transportation_check_rate_layout_id'] = isset($options['transportation_check_rate_layout_id']) ? $options['transportation_check_rate_layout_id'] : 0;
?>

<div class="tim_clr">
    <div class="tim_col_2">
        <h2><?php _e( 'Availability options', $this->plugin_name ); ?></h2>
    </div>
    <div class="tim_col_10"> 	
		<table class="form-table tim-table">
		    <tr>
		        <th><?php _e( 'Tour check rate form', $this->plugin_name ); ?></th>
		        <td>
		        	<select id="tour_check_rate_layout" name="<?php echo $settings_section ?>[tour_check_rate_layout_id]">
		        	<?php
					for ($i = 0; $i < count($TOUR_CHECK_AVAILABILITY_OPTIONS); $i++) {
						$item = $TOUR_CHECK_AVAILABILITY_OPTIONS[$i];

						$id = $item['id'];
						$name = $item['name'];
						?>
						<option value="<?php echo $id; ?>"<?php echo selected( $id, $options['tour_check_rate_layout_id'] ); ?>>
							<?php echo $name ?>
						</option>


			        	<?php
					}
		        	?>
		        	</select>
		        </td>
		    </tr>
		</table>

		<table class="form-table tim-table">
		    <tr>
		        <th><?php _e( 'Transportation check rate form', $this->plugin_name ); ?></th>
		        <td>
		        	<select id="transportation_check_rate_layout" name="<?php echo $settings_section ?>[transportation_check_rate_layout_id]">
		        	<?php
					for ($i = 0; $i < count($TRANSPORTATION_CHECK_AVAILABILITY_OPTIONS); $i++) {
						$item = $TRANSPORTATION_CHECK_AVAILABILITY_OPTIONS[$i];

						$id = $item['id'];
						$name = $item['name'];
	
						?>
						<option value="<?php echo $id; ?>"<?php echo selected( $id, $options['transportation_check_rate_layout_id'] ); ?>>
							<?php echo $name ?>
						</option>
						<?php
					}
		        	?>
		        	</select>
		        </td>
		    </tr>
		</table>
	</div>
</div>
<?php
/*

					// $themeLayoutScreenshot = plugin_dir_url( '' ) . $this->plugin_name .'/public/layouts/'. $id .'/screenshot.png';
						<!-- <option 
							type="radio" 
							id="tour_check_rate_layout_<?php echo $id ?>" 
							name="<?php echo $settings_section ?>[tour_check_rate_layout_id]" 
							value="<?php echo $id; ?>"<?php echo checked( $id, $options['tour_check_rate_layout_id'], false ) ?> 
						/> 
			        	<label for="tour_check_rate_layout_<?php echo $id; ?>">
			        		<?php echo $name ?>
			        	</label> -->
			        	<!-- <a class="tim_fancybox" href="<?php echo $themeLayoutScreenshot; ?>" title="<?php echo $name; ?>"><img src="<?php echo $themeLayoutScreenshot; ?>" alt="<?php echo $name; ?>" title="<?php echo $name; ?>" width="30" /></a>--> 
			        	<!-- &nbsp; | &nbsp; -->


			        							<!-- <input 
							type="radio" 
							id="transportation_check_rate_layout_<?php echo $id ?>" 
							name="<?php echo $settings_section ?>[transportation_check_rate_layout_id]" 
							value="<?php echo $id; ?>"<?php echo checked( $id, $options['transportation_check_rate_layout_id'], false ) ?> 
						/> 
			        	<label for="transportation_check_rate_layout_<?php echo $id; ?>">
			        		<?php echo $name ?>
			           	</label> -->
						<!-- <a class="tim_fancybox" href="<?php echo $themeLayoutScreenshot; ?>" title="<?php echo $name; ?>"><img src="<?php echo $themeLayoutScreenshot; ?>" alt="<?php echo $name; ?>" title="<?php echo $name; ?>" width="30" /></a> --> 
						<!-- &nbsp; | &nbsp; -->

*/

?>