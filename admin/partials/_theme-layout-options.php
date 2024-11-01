<?php

$THEME_LAYOUT_PLUGIN_OPTIONS = array( 
    array('id' => 0, 'name' => __( 'Custom', $this->plugin_name )), 
    array('id' => 'argox', 'name' => 'Argox'), 
    // array('id' => 'travelo', 'name' => 'Travelo')
);

// Default layout options
$options['theme_layout_id'] = isset($options['theme_layout_id']) ? $options['theme_layout_id'] : 'argox';

?>

<div class="tim_clr">
    <div class="tim_col_2">
        <h2><?php _e( 'Theme options', $this->plugin_name ); ?></h2>
    </div>
    <div class="tim_col_10"> 	
		<table class="form-table tim-table">
		    <tr>
		        <th><?php _e( 'Theme layout', $this->plugin_name ); ?></th>
		        <td>
		        	<?php
		        	$count = count($THEME_LAYOUT_PLUGIN_OPTIONS);
					for ($i = 0; $i < $count; $i++) {
						$item = $THEME_LAYOUT_PLUGIN_OPTIONS[$i];

						$themeLayoutId   = $item['id'];
						$themeLayoutName = $item['name'];
						?>
						<input 
							type="radio" 
							id="theme_layout_<?php echo $themeLayoutId ?>" 
							name="<?php echo $settings_section ?>[theme_layout_id]" 
							value="<?php echo $themeLayoutId; ?>"<?php echo checked( $themeLayoutId, $options['theme_layout_id'], false ) ?> /> 
			        	<label for="theme_layout_<?php echo $themeLayoutId; ?>"><?php echo $themeLayoutName ?></label>
						<?php
						if ( $themeLayoutId ){
							$themeLayoutScreenshot = plugin_dir_url( '' ) . $this->plugin_name .'/public/layouts/'. $themeLayoutId .'/screenshot.png';
							?>
								<a class="tim_fancybox" href="<?php echo $themeLayoutScreenshot; ?>" title="<?php echo $themeLayoutName; ?>"><img src="<?php echo $themeLayoutScreenshot; ?>" alt="<?php echo $themeLayoutName; ?>" title="<?php echo $themeLayoutName; ?>" width="30" /></a>
							<?php
						}
						?> &nbsp; | &nbsp; <?php
					}
		        	?>
		        </td>
		    </tr>
		</table>

		<?php
		$THEME_COLOR_PLUGIN_OPTIONS = array( 
		    array('id' => 0,        'name' => __( 'Custom', $this->plugin_name )), 
		    array('id' => 'orange', 'name' => __( 'Orange', $this->plugin_name )), 
		    array('id' => 'blue',   'name' => __( 'Blue', $this->plugin_name ))
		);

		// Default color theme option
		$options['theme_color_id'] = isset($options['theme_color_id']) ? $options['theme_color_id'] : 'orange';

		?>
		<table class="form-table tim-table">
		    <tr>
		        <th><?php _e( 'Theme color', $this->plugin_name ); ?></th>
		        <td>
		        	<?php
		        	$count = count($THEME_COLOR_PLUGIN_OPTIONS);
					for ( $i = 0; $i < $count; $i++ ) {
						$item = $THEME_COLOR_PLUGIN_OPTIONS[$i];

						$themeColorId   = $item['id'];
						$themeColorName = $item['name'];
						?>
						<input 
							type="radio" 
		        	    	id="theme_color<?php echo $themeColorId ?>" 
		        	    	name="<?php echo $settings_section; ?>[theme_color_id]" 
		        	    	value="<?php echo $themeColorId; ?>"<?php echo checked( $themeColorId, $options['theme_color_id'], false ) ?> /> 
			        			<label for="theme_color<?php echo $themeColorId; ?>"><?php echo $themeColorName; ?></label>
						<?php
						?> &nbsp; | &nbsp; <?php
					}
		        	?>
		        </td>
		    </tr>
		</table>
	</div>
</div>
<?php

// $options['theme_color_id'] = ( count($options['theme_color_id']) ) ? $options['theme_color_id'] : 'orange';
// $options['theme_layout_id'] = ( count($options['theme_layout_id']) ) ? $options['theme_layout_id'] : 'argox';
?>