<?php

class Tim_Travel_Manager_Public {

	// inspect_styles
	// inspect_scripts
	// register_nonce

	// load_custom_meta
	// load_widgets
	// add_cart_widget
	
	// load_shortcodes
	// cart_widget_sortcode

	// get_custom_post_type_template
	// get_custom_page_template
	// start_session

	protected $plugin_name;
	protected $plugin_version;
	protected $plugin_nonce;

	protected $post_type_currencies;
	protected $post_type_locations;
	protected $post_type_tours;
	protected $post_type_transportations;
	protected $post_type_hotels;
	protected $post_type_packages;

	protected $post_type_cart;
	protected $post_type_checkout;
	protected $post_type_order;
	protected $post_type_verify_order;

	protected $currency_value;

	public function __construct(
		$plugin_name, 
		$plugin_version, 
		$plugin_nonce, 
		$public_data, 
		$plugin_api, 
		$post_type_currencies, 
		$post_type_locations, 
		$post_type_tours, 
		$post_type_transportations, 
		$post_type_hotels, 
		$post_type_packages, 
		$post_type_cart, 
		$post_type_checkout, 
		$post_type_order, 
		$post_type_verify_order, 
		$post_type_my_account  ) { // , $post_type_payment_success 

		$this->plugin_name = $plugin_name;
		$this->plugin_version = $plugin_version;
		$this->plugin_nonce = $plugin_nonce;

		$this->post_type_currencies = $post_type_currencies;

		$this->post_type_locations = $post_type_locations;
		$this->post_type_tours = $post_type_tours;
		$this->post_type_transportations = $post_type_transportations;
		$this->post_type_hotels = $post_type_hotels;
		$this->post_type_packages = $post_type_packages;

		$this->post_type_cart = $post_type_cart;
		$this->post_type_checkout = $post_type_checkout;
		$this->post_type_order = $post_type_order;
		$this->post_type_verify_order = $post_type_verify_order;
		$this->post_type_my_account = $post_type_my_account;

		// $this->post_type_payment_success = $post_type_payment_success;

		$this->public_data = $public_data;
		$this->plugin_api = $plugin_api;
		
		// $this->site_url    = get_option( 'siteurl' );
		$home_url = get_home_url();
		$this->home_url = rtrim( $home_url, '/' ) .'/'; // Important to avoid sitecart

		$this->plugin_dir = WP_PLUGIN_DIR .'/'. $plugin_name;
		$this->layour_dir = $this->plugin_dir .'/public/layouts/';

	}

	public function init() {

		// Report all errors except E_NOTICE
		error_reporting(E_ALL & ~E_NOTICE);

		$this->start_session();

		$this->currency_value   = $this->public_data->get_currency_value( $this->post_type_currencies );
		$this->content_language = $this->public_data->get_content_language();

		$this->cartSession = $this->plugin_api->get_cart_session();

		$action = isset( $_GET['act'] ) ? $_GET['act'] : '';
		if ( $this->cartSession && $action == 'done' ) { // Just in case the car is not empty and order is completed
			unset( $_SESSION['tim_cart_session'] );
			$this->cartSession = null;
		}

		$this->general_options = get_option( TIM_TRAVEL_MANAGER_GENERAL_OPTIONS );

		$this->load_custom_meta();
		$this->load_widgets();
		$this->load_shortcodes();
		$this->set_footer();
		
	}

	// Inspect registered styles for the public area, to avoid duplicate declaration
	function inspect_styles() {
		
		global $wp_styles;

		$srcs = array_map('basename', (array) wp_list_pluck($wp_styles->registered, 'src') );

		// $general_options = get_option( TIM_TRAVEL_MANAGER_GENERAL_OPTIONS );

		$themeLayoutCssFile = '';
		$themeLayoutColorCssFile = '';
		
		$themeLayouId = $this->general_options['theme_layout_id'];
	    // Plugin theme layout selected
		if ( $themeLayouId ){
			$themeLayoutCssFile = $this->layour_dir . $themeLayouId .'/css/layout.css';
			$themeLayoutCss     = file_exists($themeLayoutCssFile) ? plugin_dir_url( __FILE__ ) .'layouts/'. $themeLayouId .'/css/layout.css' : '';

			$themeColorId = $this->general_options['theme_color_id'];
	    	// Plugin theme layout color selected
			if ( $themeColorId ){
				$themeLayoutColorCssFile = $this->layour_dir . $themeLayouId .'/css/themes/'. $themeColorId .'.css';
				$themeLayoutColorCss     = file_exists($themeLayoutColorCssFile) ? plugin_dir_url( __FILE__ ) .'layouts/'. $themeLayouId .'/css/themes/'. $themeColorId .'.css' : '';
			}
		} else {
			$themeLayoutCssFile = get_stylesheet_directory() .'/css/tim-theme-layout.css'; // get_template_directory
			$themeLayoutCss     = $themeLayoutCssFile;
		}

		// Public styles - If you change the responseive.less remember to save the tim.less to refresh te .css - then save the publi.css to minify to .min.css		
		$pulblic_css_min = ( $_SERVER['HTTP_HOST'] == 'localhost' ) ? '' : '.min';
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) .'css/tim-'. $this->plugin_version . $pulblic_css_min .'.css', array(), null, 'all' );

		if ( file_exists($themeLayoutCssFile) ) {
	    	// Theme layout
			wp_enqueue_style( 'tim_theme_layout', $themeLayoutCss, array(), null, 'all' );

			if ( file_exists($themeLayoutColorCssFile) ){
		    	// Theme layaout color
				wp_enqueue_style( 'tim_theme_color', $themeLayoutColorCss, array(), null, 'all' );	
			}
		}

		if ( $themeLayouId ) {
			// Swiper slider
			if ( ! in_array('swiper.css', $srcs) AND ! in_array('swiper.min.css', $srcs) ) {
				wp_enqueue_style( 'tim_swiper', plugin_dir_url( __FILE__ ) .'libs/swiper/swiper.min.css', array(), '4.4.6' );
			}
		}

		// Fancybox
		if ( ! in_array('fancybox.css', $srcs) AND ! in_array('jquery.fancybox.css', $srcs) ) {
			wp_enqueue_style( 'tim_fancybox', plugin_dir_url( __FILE__ ) .'libs/fancybox/jquery.fancybox.css', array(), '2.1.5' );
		}

		// JQuery datepicker
		wp_enqueue_style( 'tim_datepicker_core', plugin_dir_url( __FILE__ ) .'libs/datepicker/jquery-ui.min.css', array(), '1.10.3' );
		wp_enqueue_style( 'tim_datepicker', plugin_dir_url( __FILE__ ) .'libs/datepicker/themes/default.min.css', array(), '1.10.3' );

		// Timepicki
		/*if ( ! in_array('timepicki.css', $srcs) AND ! in_array('timepicki.min.css', $srcs) ) {
			wp_enqueue_style( 'tim_timepicki', plugin_dir_url( __FILE__ ) .'libs/timepicki/css/timepicki.min.css', array(), '2.0' );
		}*/

		// wickedpicker
		if ( ! in_array('wickedpicker.min.css', $srcs) ) {
			wp_enqueue_style( 'tim_wickedpicker', plugin_dir_url( __FILE__ ) .'libs/wickedpicker/css/wickedpicker.min.css', array(), '2.0' );
		}

		// select2 - problems in checkout form with some domains
		// if ( ! in_array('select2.css', $srcs) AND ! in_array('select2.min.css', $srcs) ) {
			wp_enqueue_style( 'tim_select2', plugin_dir_url( __FILE__ ) .'libs/select2/css/select2.min.css', array(), '4.0.3' );
		// }
		
		if ( ! in_array('font-awesome.css', $srcs) AND ! in_array('font-awesome.min.css', $srcs) ) {
			// Font awesome
			wp_enqueue_style( 'tim_awesome', plugin_dir_url( __FILE__ ) .'libs/font-awesome/css/font-awesome.min.css', array(), '4.5.0' );
		}

	}

	// Inspect registered scripts for the public area, to avoid duplicate declaration
	function inspect_scripts() {

		global $wp_scripts;

		$srcs = array_map('basename', (array) wp_list_pluck($wp_scripts->registered, 'src') );

		// $general_options = get_option( TIM_TRAVEL_MANAGER_GENERAL_OPTIONS );
		
		$themeLayouId = $this->general_options['theme_layout_id'];
		$googleMapEnabled = $this->general_options['google_map_enabled'];

	    //$public_min = ( $_SERVER['REMOTE_ADDR'] !== '127.0.0.1' ) ? '.min' : '';
	    //$public_min = ( $_SERVER['HTTP_HOST'] !== 'localhost' ) ? '.min' : '';

	    // Public scripts
	    // Local
	    if ( $_SERVER['HTTP_HOST'] == 'localhost' ) {
	    	wp_enqueue_script( $this->plugin_name .'-public', plugin_dir_url( __FILE__ ) .'js/public.js', array( 'jquery' ), null, false ); // instead of null if ver= -> $this->plugin_version (problems with cache)
	    	wp_enqueue_script( $this->plugin_name .'-order', plugin_dir_url( __FILE__ ) .'js/order.js', array( 'jquery' ), null, false );
	    	wp_enqueue_script( $this->plugin_name .'-payment', plugin_dir_url( __FILE__ ) .'js/payment.js', array( 'jquery' ), null, false );
	    	wp_enqueue_script( $this->plugin_name .'-timer', plugin_dir_url( __FILE__ ) .'js/timer.js', array( 'jquery' ), null, false );
	    } else { // QA/Production
	    	wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) .'js/tim-'. $this->plugin_version .'.min.js', array( 'jquery' ), null, false );
	    }

	    if ( $themeLayouId ) {
	    	// Swiper slider
	    	if ( ! in_array('swiper.js', $srcs) AND ! in_array('swiper.min.js', $srcs) ) {
	    		wp_enqueue_script( 'tim_swiper', plugin_dir_url( __FILE__ ) .'libs/swiper/swiper.min.js', array( 'jquery' ), '4.4.6', false );
	    	}
	    }

		// Fancybox
	    if ( ! in_array('fancybox.js', $srcs) AND ! in_array('jquery.fancybox.pack.js', $srcs) ) {
	    	wp_enqueue_script( 'tim_fancybox', plugin_dir_url( __FILE__ ) .'libs/fancybox/jquery.fancybox.pack.js', array( 'jquery' ), '2.1.5', true );
	    }

		// JQuery datepicker
	    if ( ! in_array('jquery.ui.datepicker.js', $srcs) AND ! in_array('jquery.ui.datepicker.min.js', $srcs) ) {
	    	wp_enqueue_script( 'tim_datepicker', plugin_dir_url( __FILE__ ) .'libs/datepicker/jquery.ui.datepicker.min.js', array( 'jquery' ), '1.10.3', false );
	    	wp_enqueue_script( 'tim_datepicker_lang', plugin_dir_url( __FILE__ ) .'libs/datepicker/lang/'. $this->content_language .'.min.js', array( 'jquery' ), '', false );
	    }

		// Timepicki
		/*if ( ! in_array('timepicki.js', $srcs) AND ! in_array('timepicki.min.js', $srcs) ) {
			wp_enqueue_script( 'tim_timepicki', plugin_dir_url( __FILE__ ) .'libs/timepicki/js/timepicki.min.js', array( 'jquery' ), '2.0', false );
		}*/

		// wickedpicker
		if ( ! in_array('wickedpicker.min.js', $srcs) ) {
			wp_enqueue_script( 'tim_wickedpicker', plugin_dir_url( __FILE__ ) .'libs/wickedpicker/js/wickedpicker.min.js', array( 'jquery' ), '1.0', false );
		}

		// select2
		if ( ! in_array('select2.min.js', $srcs) ) {
			wp_enqueue_script( 'tim_select2', plugin_dir_url( __FILE__ ) .'libs/select2/js/select2.min.js', array( 'jquery' ), '4.0.3', false );
		}

		// inputmask
		if ( ! in_array('inputmask.min.js', $srcs) ) {
			wp_enqueue_script( 'tim_inputmask', plugin_dir_url( __FILE__ ) .'libs/inputmask/inputmask.min.js', array( 'jquery' ), '1.0', false );
		}

		// Google map
		if ( $googleMapEnabled ) {
			if ( get_post_type() === $this->post_type_tours || get_post_type() === $this->post_type_transportations || get_post_type() === $this->post_type_hotels || get_post_type() === $this->post_type_packages || get_post_type() === $this->post_type_locations ) {
				
				$googleMapApiKey = $this->general_options['google_map_api_key'];
				wp_enqueue_script( 'google_maps', 'https://maps.googleapis.com/maps/api/js?key='. $googleMapApiKey .'', array(),'3.0', false ); //?callback=initialize true
			}
		}

		add_filter( 'script_loader_tag', array( $this, 'mind_defer_scripts'), 10, 3 );

		$this->register_nonce();

	}

	// This will add the data-cfasync="false" attribute to the scrit string to avoid conflicts with cache plugings != defer
	public function mind_defer_scripts( $tag, $handle, $src ) {

		if ( $_SERVER['HTTP_HOST'] == 'localhost' ){ // Local
			$defer = array( 
				$this->plugin_name .'-public', 
				$this->plugin_name .'-order', 
				$this->plugin_name .'-payment', 
				$this->plugin_name .'-timer'
			);
		}
		else{
			$defer = array( 
				$this->plugin_name
			);
		}
		
		if ( in_array( $handle, $defer ) ) {
			return str_replace(' src', ' data-cfasync="false" src', $tag);
		}

		return $tag;

	} 

	// Register data that will be printed in javaScript
	public function register_nonce() {	    

		$data = array( 
	        'ajaxurl' => admin_url( 'admin-ajax.php' ),         // URL to wp-admin/admin-ajax.php to process the request
	        'f_nonce' => wp_create_nonce( $this->plugin_nonce ) // Generate a nonce with a unique ID
	        												    // so that you can check it later when an AJAX request is sent
	    );


	    // Register script
	    wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) .'js/data.js' ); // wp_register_script => not working

	    // Send data to client
	    wp_localize_script( $this->plugin_name, 'timData', $data );

	}

	public function load_custom_meta() {
		add_action( 'wp_head', array( $this, 'custom_meta_description' ) );
	}

	public function load_widgets() {
		
		if ( isset( $_SESSION['tim_booking_deleted'] ) ) {
			add_filter( 'wp_head', array( $this, 'add_booking_expired_msg' ) );
		}

		// $general_options = get_option( TIM_TRAVEL_MANAGER_GENERAL_OPTIONS );

		// Cart widget
		/*if ( $general_options['cart_widget_enabled'] ){
			add_filter( 'wp_head', array( $this, 'add_cart_widget' ) ); // wp_footer
		}

		// Secondary price list widget
		if ( $general_options['secondary_price_list'] && $general_options['cart_widget_enabled'] ){
			// add_filter( 'wp_head', array( $this, 'secondary_price_list_custom_widget' ) );
		}*/

	}

	// Display msg top header if the booking was automatically deleted due inactivity
	public function add_booking_expired_msg( ) {

		require_once 'widgets/messages/tim-booking-expired.php';

		unset( $_SESSION['tim_booking_deleted'] );

	}

	public function custom_meta_description() {
		
		$post_id   = get_the_ID();
		$post_type = get_post_type($post_id);
		$array     = explode('_', $post_type);
		$value     = $array[0];

		// check if post type is any of tim: ej tim_tour
		if ( $value != 'tim' ) {
			return '';
		}
		
		$post_type_meta = $post_type .'_meta';
    	// $postmeta         = get_post_meta( $post_id, $post_type_meta, true);

		$original_post_id = $this->public_data->get_original_post_id( $post_type );
		$postmeta = get_post_meta( $original_post_id, $post_type_meta, true );
		$content_language = $this->content_language;

		$description = $postmeta['seo_description']->$content_language ? 
		$postmeta['seo_description']->$content_language : $postmeta['description']->$content_language;

		if ( ! empty( $description ) ) {
			// $description = esc_html(  $description );
			$description = strip_tags( $description );
			echo '<meta name="description" content="'. $description .'">';
		}

	}

	// Load all public shortcodes
	public function load_shortcodes() {

		global $wp_query;

		require_once 'templates/locations/tim-location-list.php';
		require_once 'templates/tours/tim-tour-list.php';
		require_once 'templates/transportations/tim-transportation-list.php';
		require_once 'templates/packages/tim-package-list.php';
		require_once 'templates/hotels/tim-hotel-list.php';

		$tim_travel_manager_location = new Tim_Travel_Manager_Public_Location_Controller(
			$this->plugin_name, 
			$this->public_data, 
			$this->content_language, 
			$this->currency_value, 
			$this->post_type_locations
		);

		$tim_travel_manager_tour = new Tim_Travel_Manager_Public_Tour_Controller(
			$this->plugin_name, 
			$this->public_data, 
			$this->content_language, 
			$this->currency_value, 
			$this->post_type_tours
		);

		$tim_travel_manager_transportation = new Tim_Travel_Manager_Public_Transportation_Controller(
			$this->plugin_name, 
			$this->public_data, 
			$this->content_language, 
			$this->currency_value, 
			$this->post_type_transportations
		);

		$tim_travel_manager_package = new Tim_Travel_Manager_Public_Package_Controller(
			$this->plugin_name, 
			$this->public_data, 
			$this->content_language, 
			$this->currency_value, 
			$this->post_type_packages
		);

		$tim_travel_manager_hotel = new Tim_Travel_Manager_Public_Hotel_Controller(
			$this->plugin_name, 
			$this->public_data, 
			$this->content_language, 
			$this->currency_value, 
			$this->post_type_hotels
		);
		
		add_filter( 'single_template', array( $this, 'get_custom_post_type_template') );
		add_action( 'template_redirect', array( $this, 'get_custom_page_template') );

		// add_action( 'wp', 'wpse_282498_wp' ); // to make is_front_page() work
		add_action( 'wp', array( $this, 'wpse_282498_wp') ); // to make is_front_page() work

		// Cart widget / shorcode
		if ( $this->general_options['cart_widget_enabled'] ) {
			add_filter( 'wp_head', array( $this, 'cart_widget' ) ); // wp_footer
		} else {
			add_shortcode( 'cart-widget', array( $this, 'cart_widget_sortcode' ) );
		}

	}

	public function wpse_282498_wp() {
		if ( $this->general_options['secondary_price_list_enabled'] ) {
			$isHomePage = is_front_page();

			if ( $this->general_options['secondary_price_list_always_visible'] ) {
				if ( $this->general_options['secondary_price_list_home_page_only'] ) {
					if ( $isHomePage ) {
						add_filter( 'wp_head', array( $this, 'secondary_price_list_widget' ) );
					}
				} else {
					add_filter( 'wp_head', array( $this, 'secondary_price_list_widget' ) );
				}
			} else {
				if ( ! isset( $_COOKIE['secondary_price_list_processed'] ) ) {
					if ( $this->general_options['secondary_price_list_home_page_only'] ) {
						if ( $isHomePage ) {
							add_filter( 'wp_head', array( $this, 'secondary_price_list_widget' ) );
						}
					} else {
						add_filter( 'wp_head', array( $this, 'secondary_price_list_widget' ) );
					}
				}
			}

			/*if ( ! isset( $_COOKIE['secondary_price_list_processed'] ) ) {
				// Secondary price list widget / shortcode
				if ( ! $this->general_options['secondary_price_list_custom_widget_enabled'] ) {
					add_filter( 'wp_head', array( $this, 'secondary_price_list_widget' ) );
				} else {
					add_shortcode( 'secondary-price-list', array( $this, 'secondary_price_list_sortcode' ) );
				}
			}*/			
		} else {
			// If secondary price list not enable but a cookie exists, we delete the cookie
			if ( isset( $_COOKIE['secondary_price_list_processed'] ) ){
		    	// empty value and expiration one hour before
				setcookie( 'secondary_price_list_processed' , '', time() - 3600, '/');
		    }
		}
	}

	// When price list enabled and custom widget not checked
	public function secondary_price_list_widget( $content, $msg = '' ) {

		$content_language = $this->content_language;

		$msg = $this->general_options['secondary_price_list_custom_msg_'. $content_language];

		require_once 'widgets/messages/tim-secondary-price-list.php';

	}

	// When price list enabled and custom widget checked
	public function secondary_price_list_sortcode( $atts ) {

		extract( shortcode_atts( array(
			// 'msg' => ''
		), $atts ) );

		ob_start();

		$this->add_secondary_price_list_widget( $content, $msg );

		return ob_get_clean();

	}

	// When cart widget enabled
	public function cart_widget( $content, $hideCart = false, $hideCurrency = false, $hideLanguage = false, $hideAccount = false ) {

		// TODO: Check when the session expires. Reload cartSession - Fix
		$totalBookingItems = isset( $this->cartSession['total_items'] ) ? $this->cartSession['total_items'] : 0 ;
		
		$content_language  = $this->content_language;

		require_once 'widgets/cart/tim-cart.php';

	}

	// When cart widget not enabled and shortcode exists
	public function cart_widget_sortcode( $atts ) {

		extract( shortcode_atts( array(
			'hide_cart'     => '',
			'hide_currency' => '', 
			'hide_language' => '', 
			'hide_account'  => ''
		), $atts ) );

		ob_start();

		$this->add_cart_widget( $content=null, $hide_cart, $hide_currency, $hide_language, $hide_account );

		return ob_get_clean();

	}

	private function set_translation_plugin_label_setting($translation_plugin_label_setting, $name, $flag) {
		if ($translation_plugin_label_setting) {
    		if ($translation_plugin_label_setting == 'name') {
    			$langLabel = $name;
    		} else {
    			$langLabel = '<img src="'. $flag .'" alt="'. $name .'">';
    		}
    	} else { // Both
    		$langLabel = '<img src="'. $flag .'" alt="'. $name .'"> '. $name;
    	}

    	return $langLabel;
	}

	private function set_footer() {
		add_filter( 'wp_footer', array( $this, 'load_footer_data' ), 5 );

		// add_filter( 'style_loader_src', array( $this, 'remove_css_js_version' ), 9999 );
		// add_filter( 'script_loader_src', array( $this, 'remove_css_js_version' ), 9999 );
	}

	// remove wp version number from scripts and styles
	// public function remove_css_js_version( $src ) {
	//     if ( strpos( $src, '?ver=' ) );
	    
	//     $src = remove_query_arg( 'ver', $src );
	    
	//     return $src;
	// }

	public function load_footer_data(){

		?>
		<input type="hidden" id="timCL" value="<?php echo $this->content_language; ?>" />
		<input type="hidden" id="timHomeUrl" value="<?php echo $this->home_url; ?>" />
		<?php

		if ( isset($this->cartSession) AND isset($this->cartSession['booking_id']) ) {
			$general_options = get_option( TIM_TRAVEL_MANAGER_GENERAL_OPTIONS );
			$timeout = isset($general_options['booking_timeout']) ? $general_options['booking_timeout'] : 5; // in minutes
			?>
			<input type="hidden" id="timTO" value="<?php echo $timeout; ?>" />
			<input type="hidden" id="timBID" value="<?php echo $this->cartSession['booking_id']; ?>" />
			<input type="hidden" id="timBTI" value="<?php echo $this->cartSession['total_items']; ?>" />
			<script>window.onload = timSetBookingTimers();</script>
			<?php
		}

		// Secondary price list enabled and cookie accepted
		if ( $this->general_options['secondary_price_list_enabled'] && isset( $_COOKIE['secondary_price_list_processed']) && $_COOKIE['secondary_price_list_processed'] === 'accepted' ) {
			?>
			<input type="hidden" id="timSPLAccepted" value="true" />
			<?php
		}

	}

	// Get custom post type template
	public function get_custom_post_type_template( $template ) {

		$post_id = get_the_ID();
		$array = explode('_', get_post_type($post_id));
		$value = $array[0];

		// check if post type is any of tim: ej tim_tour
		if ( $value != 'tim' ) { // return default theme template - or another plugin theme
			return $template;
		}

		$template = str_replace( 'tim_', '', get_post_type($post_id) );
		$template_path = WP_PLUGIN_DIR .'/'. $this->plugin_name .'/public/templates/'. $template .'s/tim-'. $template .'-detail.php';

		return $template_path;

	}

	// Get custom page template
	public function get_custom_page_template() {

		global $wp;

		$found = false;

		if ( isset($wp->query_vars['post_type']) ) {
			$query_var = $wp->query_vars['post_type']; // Fix
			
			if ( $query_var === $this->post_type_cart ) { // Cart page
				$template = $this->post_type_cart;
				$found    = true;

				$this->bookingCart = $this->plugin_api->get_booking( 'draft', $this->content_language );
			} elseif ( $query_var === $this->post_type_checkout ) { // Checkout page
				$template = $this->post_type_checkout;
				$found    = true;

				$this->bookingCart = $this->plugin_api->get_booking( 'draft', $this->content_language );
			} elseif ( $query_var === $this->post_type_order ) { // Order page
				$template = $this->post_type_order;
				$found    = true;

				$action          = isset( $_GET['act'] ) ? $_GET['act'] : '';
				$bookingId       = isset( $_GET['oid'] ) ? $_GET['oid'] : '';
				$bookingNumber   = isset( $_GET['onm'] ) ? $_GET['onm'] : '';
				// $bookingLanguage = isset( $_GET['lng'] ) ? $_GET['lng'] : '';

				$my_account_url = $this->home_url .'my-account/';

				if ( $action !== 'paid' ) {
					$this->booking = $this->plugin_api->get_booking( 'active', $this->content_language, $bookingId, $bookingNumber );
				}
			} elseif ( $query_var === $this->post_type_verify_order ) { // Verify order page
				$template = $this->post_type_verify_order;
				$found    = true;
			} elseif ( $query_var === $this->post_type_my_account ) {
				$action = isset( $_GET['act'] ) ? $_GET['act'] : '';

				$template = $this->post_type_my_account;
				$found    = true;
			}

			// elseif ( $query_var === $this->post_type_payment_success ){
			// 	$template = $this->post_type_payment_success;
			// 	$found    = true;
			// }
		}

		if ( ! $found ) {
			return false;
		}

		$template = str_replace( '_', '-', $template );
		$template_path = 'pages/'. $template .'.php';

		require_once $template_path;

	    die(); // Important

	}

	// Start the session
	public function start_session(){

		if ( ! session_id() ){
			session_start();
		}

	}

}

				// Secondary price list widget / shortcode
				/*if ( isset( $this->general_options['secondary_price_list_custom_widget_enabled'] ) && ! $this->general_options['secondary_price_list_custom_widget_enabled'] ) {
					echo 'one';
					add_filter( 'wp_head', array( $this, 'secondary_price_list_widget' ) );
				}
				else{
					echo 'two';
					add_shortcode( 'secondary-price-list', array( $this, 'secondary_price_list_sortcode' ) );
				}*/

?>