<?php

class Tim_Travel_Manager_Admin {

	// enqueue_styles
	// enqueue_scripts
	// register_nonce
	// add_plugins_page

	// travel_manager_admin_display
	// register_api_settings
	// initialize_credentials
	// initialize_theme_options
	// validate_credentials_inputs
	// sanitize_input_options

	protected $plugin_name;
	protected $plugin_version;
	protected $plugin_nonce;

	public function __construct( $plugin_name, $plugin_version, $plugin_nonce ) {

		$this->plugin_name    = $plugin_name;
		$this->plugin_version = $plugin_version;
		$this->plugin_nonce   = $plugin_nonce;

	}

	// Register the stylesheets for the admin area.
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) .'css/admin.css', array(), $this->plugin_version, 'all' );

		// Fancybox
		wp_enqueue_style( 'tim_fancybox', plugin_dir_url( __FILE__ ) .'libs/fancybox/jquery.fancybox.css', array(), '2.1.5' );
	
	}

	// Register the JavaScript for the admin area.
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) .'js/admin-'. $this->plugin_version .'.min.js', array( 'jquery' ), $this->plugin_version, false );

		// Fancybox
		wp_enqueue_script( 'tim_fancybox', plugin_dir_url( __FILE__ ) .'libs/fancybox/jquery.fancybox.pack.js', array( 'jquery' ), '2.1.5', true );

		$this->register_nonce();
   
	}

	// Register data that will be printed in javaScript
	public function register_nonce() {
		$data = array( 
	        'nonce' => wp_create_nonce( $this->plugin_nonce )
	    );
	    
	    // Register script
	    wp_register_script( $this->plugin_name, plugin_dir_url( __FILE__ ) .'js/data.js' );

	    // Send data to client
	    wp_localize_script( $this->plugin_name, 'timData', $data );
	}
	
	public function add_plugins_page() {

		$page_title = 'TIM - Travel Information Manager';
		$menu_title = 'TIM';
		$capability = 'manage_options';
		$menu_slug  = $this->plugin_name;
		$function   = array( $this, 'travel_manager_admin_display' );
		$icon_url   = plugin_dir_url( __FILE__ ) .'img/tim-logo-admin-icon.svg';
		
		add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, 65 );

	}

	public function travel_manager_admin_display() {

		// Only user with privileges
		if ( ! current_user_can( 'create_users' ) ){
			return;
		}

		require_once plugin_dir_path( __FILE__ ) . $this->plugin_name .'-admin-display.php';

	}

	// Register api settings
	public function register_api_settings() {

	    $this->initialize_credentials();
	    $this->initialize_theme_options();

	}

	public function initialize_credentials() { 
	
		// Register the settings with Validation callback
	    register_setting(
	    	TIM_TRAVEL_MANAGER_CREDENTIALS, 
	    	TIM_TRAVEL_MANAGER_CREDENTIALS, 
	    	array( $this, 'validate_credentials_inputs')
	    );

	}

	public function initialize_theme_options(){ 
	
		// Register the settings with Validation callback
	    register_setting(
	    	TIM_TRAVEL_MANAGER_GENERAL_OPTIONS, 
	    	TIM_TRAVEL_MANAGER_GENERAL_OPTIONS, 
	    	array( $this, 'validate_general_options_inputs') //sanitize_input_options
	    );

	}

	public function validate_credentials_inputs( $input ){

		// Only user with privileges
		if ( ! current_user_can( 'create_users' ) ){
			return;
		}

		$errors = array();

		$valid = array();
	    $valid['subdomain'] = sanitize_text_field( $input['subdomain'] );
	    $valid['domain_api_key'] = sanitize_text_field( $input['domain_api_key'] );
	    $valid['company_api_key'] = sanitize_text_field( $input['company_api_key'] );
	    
	    // $valid['endpoint']        = sanitize_text_field( $input['endpoint'] );
	    // $valid['account_api_key'] = sanitize_text_field( $input['account_api_key'] );

	    if ( $valid['subdomain'] === '' ) {
	        add_settings_error(
                'subdomain',                      // Setting title
                'subdomain_error',                // Error ID
                'Please enter a valid Subdomain', // Error message
                'error'                           // Type of message
	        );

	        // Set it to the default value
	        $valid['subdomain'] = '';

	        $errors['tim_travel_manager_credentials[subdomain]'] = __( 'Please enter a valid subdomain', $this->plugin_name );
	    }


	    if ( $valid['domain_api_key'] === '' ) {
	        add_settings_error(
                'domain_api_key',               // Setting title
                'domain_api_key_error',         // Error ID
                'Please enter a valid Api key', // Error message
                'error'                         // Type of message
	        );

	        // Set it to the default value
	        $valid['domain_api_key'] = '';

	        $errors['tim_travel_manager_credentials[domain_api_key]'] = __( 'Please enter a valid Domain Api key', $this->plugin_name );
	    }

	    if ( $valid['company_api_key'] === '' ) {
	        add_settings_error(
                'company_api_key',              // Setting title
                'company_api_key_error',        // Error ID
                'Please enter a valid Api key', // Error message
                'error'                         // Type of message
	        );

	        // Set it to the default value
	        $valid['company_api_key'] = '';

	        $errors['tim_travel_manager_credentials[company_api_key]'] = __( 'Please enter a valid Company Api key', $this->plugin_name );
	    }

	    /*if ( $valid['account_api_key'] === '' ) {
	        add_settings_error(
                'account_api_key',              // Setting title
                'account_api_key_error',        // Error ID
                'Please enter a valid Api key', // Error message
                'error'                         // Type of message
	        );

	        // Set it to the default value
	        $valid['account_api_key'] = '';

	        $errors['tim_travel_manager_credentials[account_api_key]'] = __( 'Please enter a valid Account Api key', $this->plugin_name );
	    }*/
	    

	    if ( empty($errors) === false ){
		    // This is for ajax requests:
	        if ( ! empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	            echo json_encode($errors);
	            exit;
	        }
	    }

	    return $valid;

	}

	public function validate_general_options_inputs( $input ){

		// Only user with privileges
		if ( ! current_user_can( 'create_users' ) ){
			return;
		}

		$valid = array();

		$valid['cart_widget_enabled'] = $input['cart_widget_enabled'];
	    $valid['google_map_enabled'] = $input['google_map_enabled']; // checked( $options['cart_widget_enabled'], 1 );
	    $valid['google_map_api_key'] = sanitize_text_field( $input['google_map_api_key'] );
	    
	    $valid['translation_plugin_id'] = $input['translation_plugin_id'];
	    $valid['translation_plugin_label_setting'] = $input['translation_plugin_label_setting'];

	    $valid['theme_layout_id'] = $input['theme_layout_id'];
	    $valid['theme_color_id'] = $input['theme_color_id'];

	    $valid['transportation_disable_search'] = $input['transportation_disable_search']; // !important
	    $valid['transportation_hide_list'] = $input['transportation_hide_list']; // !important

	    $valid['tour_check_rate_layout_id'] = $input['tour_check_rate_layout_id'];
	    $valid['transportation_check_rate_layout_id'] = $input['transportation_check_rate_layout_id'];

	    $valid['package_email_notification'] = sanitize_text_field( $input['package_email_notification'] );

	    $valid['booking_timeout'] = sanitize_text_field( $input['booking_timeout'] ); // <select> not working

	    $valid['secondary_price_list_enabled'] = $input['secondary_price_list_enabled']; // !important
	    
	    $valid['secondary_price_list_home_page_only'] = $input['secondary_price_list_home_page_only'];
	    $valid['secondary_price_list_always_visible'] = $input['secondary_price_list_always_visible'];

	    $valid['secondary_price_list_custom_msg_en'] = sanitize_textarea_field( $input['secondary_price_list_custom_msg_en'] );
	    $valid['secondary_price_list_custom_msg_es'] = sanitize_textarea_field( $input['secondary_price_list_custom_msg_es'] );
	    // $valid['secondary_price_list_custom_widget_enabled'] = $input['secondary_price_list_custom_widget_enabled']; // !important

	    $valid['discount_coupon_enabled'] = $input['discount_coupon_enabled']; // !important

		$errors = array();

	    // Validate car widget if enabled
	    if ( $valid['cart_widget_enabled'] && $valid['google_map_api_key'] === '' ) {
	        add_settings_error(
                'google_map_api_key',                        // Setting title
                'google_map_api_key_error',                  // Error ID
                'Please enter a valid valid Google map API', // Error message
                'error'                                      // Type of message
	        );

	        // Set it to the default value
	        $valid['google_map_api_key'] = '';

	        $errors['tim_travel_manager_general_options[google_map_api_key]'] = __( 'Please enter a valid Google map API key', $this->plugin_name );
	    }

	    if ( empty($errors) === false ){
		    // This is for ajax requests:
	        if ( ! empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	            echo json_encode($errors);
	            exit;
	        }
	    }

	    return $valid;

	}

	// Sanitize all input fields
	public function sanitize_input_options( $input ){
	
		// Define the array for the updated options
		$output = array();

		// Loop through each of the options sanitizing the data
		foreach ( $input as $key => $val ){
			$input[$key] = trim($val);
		
			if ( isset ( $input[$key] ) ){
				// Strip all HTML and PHP tags and properly handle quoted strings
				$output[$key] = strip_tags( stripslashes( $input[ $key ] ) );	
			}
		}
		
		// Return the new collection
		return apply_filters( 'sanitize_input_options', $output, $input );

	}

}

?>