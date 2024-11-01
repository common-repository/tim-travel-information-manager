<?php

$PRODUCT_DISPLAY_PLUGIN_OPTIONS = array( 
	array(
		'name' => 'Transportation', 
    ), 
    // array(
		// 'name' => 'Tours', 
		// 'type'    => 'tours'
    // )
);

?>
<div class="tim_clr">
    <div class="tim_col_2">
        <h2><?php _e( 'Product display', $this->plugin_name ); ?></h2>
    </div>
    <div class="tim_col_10"> 	
		<table class="form-table tim-table">
			<?php
			foreach ($PRODUCT_DISPLAY_PLUGIN_OPTIONS as $product) { // key => $value
		        $productDisplayName = $product['name'];
		        $productDisplayType = strtolower( $product['name'] );

		        $productDisplayTypeDisableSearch = isset( $options[$productDisplayType .'_disable_search'] ) ? $options[$productDisplayType .'_disable_search'] : '';
		        $productDisplayTypeHideList = isset( $options[$productDisplayType .'_hide_list'] ) ? $options[$productDisplayType .'_hide_list']      : '';
		        ?>
		        <tr>
		        	<th><?php _e( $productDisplayName, $this->plugin_name ); ?></th>
		        	<td>
				        <label>
					        <input 
			                    type="checkbox" 
			                    name="<?php echo $settings_section; ?>[<?php echo $productDisplayType; ?>_disable_search]" 
			                    value="1" 
			                    <?php checked( $productDisplayTypeDisableSearch, true ); ?> /> 
			                <?php _e( 'Disable search', $this->plugin_name ); ?> 
					    </label> &nbsp; | &nbsp; 
					    <label>
					         <input 
			                    type="checkbox" 
			                    name="<?php echo $settings_section; ?>[<?php echo $productDisplayType; ?>_hide_list]" 
			                    value="1" 
			                    <?php checked( $productDisplayTypeHideList, true ); ?> /> 
			                <?php _e( 'Hide list', $this->plugin_name ); ?> 
					    </label>
				    </td>
				</tr>
		        <?php
			}
			?>
		</table>
	</div>
</div>

<!-- <td>
    <label>
        <input 
            type="radio" 
            name="<?php echo $settings_section ?>[<?php echo $productDisplayType; ?>_display_option]" 
            value="list"<?php echo checked( 'list', $options[$productDisplayType .'_display_option'], false ) ?> /> 
        <?php _e( 'List', $this->plugin_name ); ?>
    </label> &nbsp; | &nbsp; 
    <label>
        <input 
            type="radio" 
            name="<?php echo $settings_section ?>[<?php echo $productDisplayType; ?>_display_option]" 
            value="search"<?php echo checked( 'search', $options[$productDisplayType .'_display_option'], false ) ?> /> 
        <?php _e( 'Search', $this->plugin_name ); ?>
    </label> &nbsp; | &nbsp; 
</td>
 -->
<?php
		// 'type'    => 'transportation', 
		// 'options' => array(
    		// array('id' => 'list',   'name' => __( 'List', $this->plugin_name )), 
    		// array('id' => 'search', 'name' => __( 'Search', $this->plugin_name ))
    	// )
?>