<table class="form-table">
    <tr>
    	<td>
            <!-- Locations -->
            <button 
                class="button" 
                id="tim_travel_sync_locations" 
                type="button" 
                onclick="timSincronize('locations', 'tim_travel_sync_locations')" 
            >
                <?php _e( 'Synchronize locations', $this->plugin_name ); ?>
            </button>
    	   
            <br><br>

            <!-- Tours -->
            <button 
                class="button" 
                id="tim_travel_sync_tours" 
                type="button" 
                onclick="timSincronize('tours', 'tim_travel_sync_tours')" 
            >
                <?php _e( 'Synchronize tours', $this->plugin_name ); ?>
            </button>
           
            <br><br>

            <!-- Transportation -->
            <button 
                class="button" 
                id="tim_travel_sync_transportation" 
                type="button" 
                onclick="timSincronize('transportation', 'tim_travel_sync_transportation')" 
            >
                <?php _e( 'Synchronize transportation', $this->plugin_name ); ?>
            </button>
           
            <br><br>

            <!-- Hotels -->
            <button 
                class="button" 
                id="tim_travel_sync_hotels" 
                type="button" 
                onclick="timSincronize('hotels', 'tim_travel_sync_hotels')" 
            >
                <?php _e( 'Synchronize hotels', $this->plugin_name ); ?>
            </button>
           
            <br><br>

            <!-- Packages -->
            <button 
                class="button" 
                id="tim_travel_sync_packages" 
                type="button" 
                onclick="timSincronize('packages', 'tim_travel_sync_packages')" 
            >
                <?php _e( 'Synchronize packages', $this->plugin_name ); ?>
            </button>

            <br><br>

            <!-- Miscellaneous -->
            <button 
                class="button" 
                id="tim_travel_sync_miscellaneous" 
                type="button" 
                onclick="timSincronize('miscellaneous', 'tim_travel_sync_miscellaneous')" 
            >
                <?php _e( 'Synchronize miscellaneous', $this->plugin_name ); ?>
            </button>
           
            <br><br><br>
            
    		<button 
                class="button" 
                id="tim_travel_sync" 
                type="button" 
            >
    			<?php _e( 'Synchronize all', $this->plugin_name ); ?>
    		</button><br>
            <small><?php _e( 'It may take some time', $this->plugin_name ); ?></small>
        </td>
    </tr>
</table>

<div id="tim_travel_sync_spinner" class="tim_travel_form_spinner"></div>