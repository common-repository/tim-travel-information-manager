<?php

class Tim_Travel_Manager {

	protected $loader;
	protected $plugin_name;
	protected $plugin_slug;
	protected $plugin_version;
	protected $plugin_nonce;

	protected $public_data;
	// protected $content_language;

	public function __construct() {

		$this->plugin_name = TIM_TRAVEL_MANAGER_PLUGIN_NAME;
		$this->plugin_slug = TIM_TRAVEL_MANAGER_PLUGIN_SLUG;
		$this->plugin_version = TIM_TRAVEL_MANAGER_PLUGIN_VERSION;
		$this->plugin_nonce = TIM_TRAVEL_MANAGER_PLUGIN_NONCE;

		$this->post_type_currencies = TIM_TRAVEL_MANAGER_POST_TYPE_CURRENCIES;

		$this->post_type_locations = TIM_TRAVEL_MANAGER_POST_TYPE_LOCATIONS;
		$this->post_type_tours = TIM_TRAVEL_MANAGER_POST_TYPE_TOURS;
		$this->post_type_transportations = TIM_TRAVEL_MANAGER_POST_TYPE_TRANSPORTATIONS;
		$this->post_type_hotels = TIM_TRAVEL_MANAGER_POST_TYPE_HOTELS;
		$this->post_type_packages = TIM_TRAVEL_MANAGER_POST_TYPE_PACKAGES;
		$this->post_type_cart = TIM_TRAVEL_MANAGER_POST_TYPE_CART;
		$this->post_type_checkout = TIM_TRAVEL_MANAGER_POST_TYPE_CHECKOUT;
		$this->post_type_order = TIM_TRAVEL_MANAGER_POST_TYPE_ORDER;
		$this->post_type_verify_order = TIM_TRAVEL_MANAGER_POST_TYPE_VERIFY_ORDER;
		$this->post_type_my_account = TIM_TRAVEL_MANAGER_POST_TYPE_MY_ACCOUNT;

		$this->slug_location = TIM_TRAVEL_MANAGER_SLUG_LOCATION;
		$this->slug_tour = TIM_TRAVEL_MANAGER_SLUG_TOUR;
		$this->slug_transportation = TIM_TRAVEL_MANAGER_SLUG_TRANSPORTATION;
		$this->slug_hotel = TIM_TRAVEL_MANAGER_SLUG_HOTEL;
		$this->slug_package = TIM_TRAVEL_MANAGER_SLUG_PACKAGE;
		$this->slug_cart = TIM_TRAVEL_MANAGER_SLUG_CART;
		$this->slug_checkout = TIM_TRAVEL_MANAGER_SLUG_CHECKOUT;
		$this->slug_order = TIM_TRAVEL_MANAGER_SLUG_ORDER;
		$this->slug_verify_order = TIM_TRAVEL_MANAGER_SLUG_VERIFY_ORDER;
		$this->slug_my_account = TIM_TRAVEL_MANAGER_SLUG_MY_ACCOUNT;

		// $this->post_type_payment_success = TIM_TRAVEL_MANAGER_POST_TYPE_PAYMENT_SUCCESS;
		// $this->slug_payment_success      = TIM_TRAVEL_MANAGER_SLUG_PAYMENT_SUCCESS;

		$this->plugins_loaded();

		$this->load_dependencies();

		$this->register_custom_post_types();

		$this->initialize_api();

		if ( is_admin() ) {
			$this->define_admin_hooks();
		}
		else{
			$this->define_public_hooks();
		}
	}

	private function load_dependencies() {

		// The class responsible for orchestrating the actions and filters of the core plugin.
		require_once plugin_dir_path( dirname( __FILE__ ) ) .'includes/class-'. $this->get_plugin_name() .'-loader.php';

		// The class responsible for defining all actions that occur in the admin area.
		require_once plugin_dir_path( dirname( __FILE__ ) ) .'admin/class-'. $this->get_plugin_name() .'-admin.php';

		// The class responsible for defining all actions that occur in the public-facing side of the site.
		require_once plugin_dir_path( dirname( __FILE__ ) ) .'public/class-'. $this->get_plugin_name() .'-public.php';

		// The class responsible for defining the data
		require_once plugin_dir_path( dirname( __FILE__ ) ) .'public/includes/class-'. $this->get_plugin_name() .'-data.php';

		// The class responsible for defining the api
		require_once plugin_dir_path( __FILE__ ) .'class-'. $this->get_plugin_name() .'-api.php';

		$this->loader = new Tim_Travel_Manager_Loader();

	}

	// Register all of the hooks related to the admin area functionalityof the plugin.
	private function define_admin_hooks() {

		$plugin_admin = new Tim_Travel_Manager_Admin( $this->get_plugin_name(), $this->get_plugin_version(), $this->plugin_nonce );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_plugins_page' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'register_api_settings' );

	}

	// Register all of the hooks related to the public-facing functionality of the plugin.
	private function define_public_hooks() {

		$plugin_public = new Tim_Travel_Manager_Public(
			$this->get_plugin_name(), 
            $this->get_plugin_version(), 
            $this->plugin_nonce, 
            $this->get_public_data(), 
            $this->get_plugin_api(), 
            $this->post_type_currencies, 
            $this->post_type_locations, 
            $this->post_type_tours, 
            $this->post_type_transportations, 
            $this->post_type_hotels, 
            $this->post_type_packages, 
            $this->post_type_cart, 
            $this->post_type_checkout, 
            $this->post_type_order, 
            $this->post_type_verify_order, 
            $this->post_type_my_account//, 
            // $this->post_type_payment_success
        );

		$this->loader->add_action( 'wp_print_styles', $plugin_public, 'inspect_styles' );
		$this->loader->add_action( 'wp_print_scripts', $plugin_public, 'inspect_scripts' );

		$this->loader->add_action( 'init', $plugin_public, 'init' );

	}

	// Load translations
	private function plugins_loaded() {

		//add_action('plugins_loaded', array( $this, 'check_plugin_version') );
		add_action('plugins_loaded', array( $this, 'load_translation') );
		
	}

	// Check plugin version
	// public function check_plugin_version() {

	// 	if ( $this->plugin_version !== get_option(TIM_TRAVEL_MANAGER_VERSION) ){
	// 		my_awesome_plugin_activation();	
	// 	}

	// }

	// Load translations
	public function load_translation() {

		load_plugin_textdomain( $this->plugin_slug, false, $this->plugin_slug .'/lang' );

	}

	// Register custom post types
	private function register_custom_post_types() {

		$postTypesList = $this->set_post_types();

		require_once plugin_dir_path( dirname( __FILE__ ) ) .'includes/class-'. $this->get_plugin_name() .'-post-types.php';

		$plugin_post_types = new Tim_Travel_Manager_Post_Types( $this->get_plugin_name(), $postTypesList); // , $customPagesList

		$this->loader->add_action( 'init', $plugin_post_types, 'unregister_post_types', 20 ); // to avoid conflicts
		$this->loader->add_action( 'init', $plugin_post_types, 'register_post_types' );

	}

	private function set_post_types(){

		$list = array(
		    array(
				'name'          => 'Locations',
				'singular_name' => 'Location',
				'slug'          => $this->slug_location, 
				'post_type'     => $this->post_type_locations
			),
		    array(
				'name'          => 'Tours',
				'singular_name' => 'Tour',
				'slug'          => $this->slug_tour, 
				'post_type'     => $this->post_type_tours
			),
			array(
				'name'          => 'Transportations',
				'singular_name' => 'Transportation',
				'slug'          => $this->slug_transportation, 
				'post_type'     => $this->post_type_transportations
			),
		    array(
				'name'          => 'Hotels',
				'singular_name' => 'Hotel',
				'slug'          => $this->slug_hotel, 
				'post_type'     => $this->post_type_hotels
			),
			array(
				'name'          => 'Packages',
				'singular_name' => 'Package',
				'slug'          => $this->slug_package, 
				'post_type'     => $this->post_type_packages
			),
			array(
				'name'          => 'Cart summary',
				'singular_name' => 'Cart',
				'slug'          => $this->slug_cart, 
				'post_type'     => $this->post_type_cart
			),
			array(
				'name'          => 'Checkout',
				'singular_name' => 'Checkout',
				'slug'          => $this->slug_checkout, 
				'post_type'     => $this->post_type_checkout
			),
			array(
				'name'          => 'Order detail',
				'singular_name' => 'Order',
				'slug'          => $this->slug_order, 
				'post_type'     => $this->post_type_order
			),
			array(
				'name'          => 'Verify order',
				'singular_name' => 'Verify order',
				'slug'          => $this->slug_verify_order, 
				'post_type'     => $this->post_type_verify_order
			),
			array(
				'name'          => 'My Account',
				'singular_name' => 'My Account',
				'slug'          => $this->slug_my_account, 
				'post_type'     => $this->post_type_my_account
			)//,
			// array(
			// 	'name'          => 'Payment Success',
			// 	'singular_name' => 'Payment Success',
			// 	'slug'          => $this->slug_payment_success, 
			// 	'post_type'     => $this->post_type_payment_success
			// )
		);

		return $list;

	}

	// Load Api
	public function initialize_api( ) {

		$plugin_api = $this->get_plugin_api();

		if ( isset($_POST['f_nonce']) ) { // Front-end
	        $this->loader->add_action( 'wp_ajax_set_currency_value', $plugin_api, 'set_currency_value' ); // For logged users
	        	$this->loader->add_action( 'wp_ajax_nopriv_set_currency_value', $plugin_api, 'set_currency_value' );

	        $this->loader->add_action( 'wp_ajax_check_tour_rates_api', $plugin_api, 'check_tour_rates_api' ); // For logged users
	        	$this->loader->add_action( 'wp_ajax_nopriv_check_tour_rates_api', $plugin_api, 'check_tour_rates_api' );
	        
	        $this->loader->add_action( 'wp_ajax_search_transportations_rates_api', $plugin_api, 'search_transportations_rates_api' ); // For logged users
	        $this->loader->add_action( 'wp_ajax_check_transportation_rates_api', $plugin_api, 'check_transportation_rates_api' ); // For logged users
	        	$this->loader->add_action( 'wp_ajax_nopriv_search_transportations_rates_api', $plugin_api, 'search_transportations_rates_api' );
	        	$this->loader->add_action( 'wp_ajax_nopriv_check_transportation_rates_api', $plugin_api, 'check_transportation_rates_api' );
	        
	        $this->loader->add_action( 'wp_ajax_check_hotel_availability_api', $plugin_api, 'check_hotel_availability_api' ); // For logged users
	        $this->loader->add_action( 'wp_ajax_check_hotel_room_rates_api', $plugin_api, 'check_hotel_room_rates_api' ); // For logged users
	        $this->loader->add_action( 'wp_ajax_check_package_request_api', $plugin_api, 'check_package_request_api' ); // For logged users
	        	$this->loader->add_action( 'wp_ajax_nopriv_check_hotel_availability_api', $plugin_api, 'check_hotel_availability_api' );
	        	$this->loader->add_action( 'wp_ajax_nopriv_check_hotel_room_rates_api', $plugin_api, 'check_hotel_room_rates_api' );
	        	$this->loader->add_action( 'wp_ajax_nopriv_check_package_request_api', $plugin_api, 'check_package_request_api' );

	        $this->loader->add_action( 'wp_ajax_add_item_to_order_api', $plugin_api, 'add_item_to_order_api' ); // For logged users
	        $this->loader->add_action( 'wp_ajax_update_item_from_order_api', $plugin_api, 'update_item_from_order_api' ); // For logged users
	        $this->loader->add_action( 'wp_ajax_apply_places_to_order_api', $plugin_api, 'apply_places_to_order_api' ); // For logged users
	        $this->loader->add_action( 'wp_ajax_remove_item_from_order_api', $plugin_api, 'remove_item_from_order_api' ); // For logged users
	        $this->loader->add_action( 'wp_ajax_load_pickup_places_to_order_item', $plugin_api, 'load_pickup_places_to_order_item' ); // For logged users
	        $this->loader->add_action( 'wp_ajax_delete_booking_api', $plugin_api, 'delete_booking_api' ); // For logged users
		        $this->loader->add_action( 'wp_ajax_nopriv_add_item_to_order_api', $plugin_api, 'add_item_to_order_api' );
		        $this->loader->add_action( 'wp_ajax_nopriv_update_item_from_order_api', $plugin_api, 'update_item_from_order_api' );
		        $this->loader->add_action( 'wp_ajax_nopriv_apply_places_to_order_api', $plugin_api, 'apply_places_to_order_api' );
		        $this->loader->add_action( 'wp_ajax_nopriv_remove_item_from_order_api', $plugin_api, 'remove_item_from_order_api' );
		        $this->loader->add_action( 'wp_ajax_nopriv_load_pickup_places_to_order_item', $plugin_api, 'load_pickup_places_to_order_item' );
		        $this->loader->add_action( 'wp_ajax_nopriv_delete_booking_api', $plugin_api, 'delete_booking_api' );
	        
	        $this->loader->add_action( 'wp_ajax_load_payment_form', $plugin_api, 'load_payment_form' ); // For logged users
	        	$this->loader->add_action( 'wp_ajax_nopriv_load_payment_form', $plugin_api, 'load_payment_form' );
	        
	        // $this->loader->add_action( 'wp_ajax_nopriv_process_bac_ecommerce_payment_api', $plugin_api, 'process_bac_ecommerce_payment_api' );
	        
	        $this->loader->add_action( 'wp_ajax_process_ecommerce_payment', $plugin_api, 'process_ecommerce_payment' ); // For logged users
	        $this->loader->add_action( 'wp_ajax_process_pay_later_payment', $plugin_api, 'process_pay_later_payment' ); // For logged users
	        	$this->loader->add_action( 'wp_ajax_nopriv_process_ecommerce_payment', $plugin_api, 'process_ecommerce_payment' );
	        	$this->loader->add_action( 'wp_ajax_nopriv_process_pay_later_payment', $plugin_api, 'process_pay_later_payment' );

	        $this->loader->add_action( 'wp_ajax_paypal_order_completed', $plugin_api, 'paypal_order_completed' ); // For logged users
	        $this->loader->add_action( 'wp_ajax_verify_guest_email_api', $plugin_api, 'verify_guest_email_api' ); // For logged users
	        	$this->loader->add_action( 'wp_ajax_nopriv_paypal_order_completed', $plugin_api, 'paypal_order_completed' );
	        	$this->loader->add_action( 'wp_ajax_nopriv_verify_guest_email_api', $plugin_api, 'verify_guest_email_api' );
	        
	        $this->loader->add_action( 'wp_ajax_verify_order_api', $plugin_api, 'verify_order_api' ); // For logged users
	        $this->loader->add_action( 'wp_ajax_list_orders_api', $plugin_api, 'list_orders_api' ); // For logged users
	        	$this->loader->add_action( 'wp_ajax_nopriv_verify_order_api', $plugin_api, 'verify_order_api' );
	        	$this->loader->add_action( 'wp_ajax_nopriv_list_orders_api', $plugin_api, 'list_orders_api' );

	        $this->loader->add_action( 'wp_ajax_load_login_form', $plugin_api, 'load_login_form' ); // For logged users
	        $this->loader->add_action( 'wp_ajax_create_client_login_api', $plugin_api, 'create_client_login_api' ); // For logged users
	        $this->loader->add_action( 'wp_ajax_client_logout', $plugin_api, 'client_logout' ); // For logged users
	        	$this->loader->add_action( 'wp_ajax_nopriv_load_login_form', $plugin_api, 'load_login_form' );
	        	$this->loader->add_action( 'wp_ajax_nopriv_create_client_login_api', $plugin_api, 'create_client_login_api' );
	        	$this->loader->add_action( 'wp_ajax_nopriv_client_logout', $plugin_api, 'client_logout' );
	        
	        $this->loader->add_action( 'wp_ajax_load_signup_form', $plugin_api, 'load_signup_form' ); // For logged users
	        $this->loader->add_action( 'wp_ajax_create_client_signup_api', $plugin_api, 'create_client_signup_api' ); // For logged users
	        	$this->loader->add_action( 'wp_ajax_nopriv_load_signup_form', $plugin_api, 'load_signup_form' );
	        	$this->loader->add_action( 'wp_ajax_nopriv_create_client_signup_api', $plugin_api, 'create_client_signup_api' );

	        $this->loader->add_action( 'wp_ajax_load_password_recovery_form', $plugin_api, 'load_password_recovery_form' ); // For logged users
	        $this->loader->add_action( 'wp_ajax_create_client_password_api', $plugin_api, 'create_client_password_api' ); // For logged users
	        $this->loader->add_action( 'wp_ajax_load_edit_password_form', $plugin_api, 'load_edit_password_form' ); // For logged users
	        $this->loader->add_action( 'wp_ajax_update_client_password_api', $plugin_api, 'update_client_password_api' ); // For logged users
		        $this->loader->add_action( 'wp_ajax_nopriv_load_password_recovery_form', $plugin_api, 'load_password_recovery_form' );
		        $this->loader->add_action( 'wp_ajax_nopriv_create_client_password_api', $plugin_api, 'create_client_password_api' );
		        $this->loader->add_action( 'wp_ajax_nopriv_load_edit_password_form', $plugin_api, 'load_edit_password_form' );
		        $this->loader->add_action( 'wp_ajax_nopriv_update_client_password_api', $plugin_api, 'update_client_password_api' );

		    $this->loader->add_action( 'wp_ajax_load_client_profile', $plugin_api, 'load_client_profile' ); // For logged users
	        $this->loader->add_action( 'wp_ajax_update_client_profile_api', $plugin_api, 'update_client_profile_api' ); // For logged users
	        $this->loader->add_action( 'wp_ajax_update_client_profile_password_api', $plugin_api, 'update_client_profile_password_api' ); // For logged users
	        $this->loader->add_action( 'wp_ajax_nopriv_load_client_profile', $plugin_api, 'load_client_profile' );
	        $this->loader->add_action( 'wp_ajax_nopriv_update_client_profile_api', $plugin_api, 'update_client_profile_api' );
	        $this->loader->add_action( 'wp_ajax_nopriv_update_client_profile_password_api', $plugin_api, 'update_client_profile_password_api' );

	        $this->loader->add_action( 'wp_ajax_open_modal', $plugin_api, 'open_modal' ); // For logged users
	        	$this->loader->add_action( 'wp_ajax_nopriv_open_modal', $plugin_api, 'open_modal' );

	        $this->loader->add_action( 'wp_ajax_accept_secondary_price_list', $plugin_api, 'accept_secondary_price_list' ); // For logged users
	        	$this->loader->add_action( 'wp_ajax_nopriv_accept_secondary_price_list', $plugin_api, 'accept_secondary_price_list' );
	        
	        $this->loader->add_action( 'wp_ajax_apply_discount_coupon_to_order_api', $plugin_api, 'apply_discount_coupon_to_order_api' ); // For logged users
	        $this->loader->add_action( 'wp_ajax_delete_discount_coupon_to_order_api', $plugin_api, 'delete_discount_coupon_to_order_api' ); // For logged users
	        	$this->loader->add_action( 'wp_ajax_nopriv_apply_discount_coupon_to_order_api', $plugin_api, 'apply_discount_coupon_to_order_api' );
	        	$this->loader->add_action( 'wp_ajax_nopriv_delete_discount_coupon_to_order_api', $plugin_api, 'delete_discount_coupon_to_order_api' );

	        // $this->loader->add_action( 'wp_ajax_load_transportation_to_locations', $plugin_api, 'load_transportation_to_locations' ); // For logged users
	        // 	$this->loader->add_action( 'wp_ajax_nopriv_load_transportation_to_locations', $plugin_api, 'load_transportation_to_locations' );

	        $this->loader->add_action( 'wp_ajax_get_booking_totals_api', $plugin_api, 'get_booking_totals_api' ); // For logged users
	        $this->loader->add_action( 'wp_ajax_nopriv_get_booking_totals_api', $plugin_api, 'get_booking_totals_api' );
	    } else { // Back-end
	        $this->loader->add_action( 'wp_ajax_sync_tim_api', $plugin_api, 'sync_tim_api' );
	    }

	}
	
	public function init() {
		$this->loader->run();
	}

	// The reference to the class that orchestrates the hooks with the plugin.
	public function get_loader() {
		return $this->loader;
	}

	// The name of the plugin used to uniquely identify it within the context of
	// WordPress and to define internationalization functionality.
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	// Retrieve the version number of the plugin.
	public function get_plugin_version() {
		return $this->plugin_version;
	}

	public function get_public_data() {
		$this->public_data = new Tim_Travel_Manager_Public_Data( $this->plugin_name );

		return $this->public_data;
	}

	public function get_plugin_api() {
		$this->plugin_api = new Tim_Travel_Manager_Api( $this->plugin_name, $this->plugin_nonce, $this->get_public_data() );

		return $this->plugin_api;
	}

}

?>