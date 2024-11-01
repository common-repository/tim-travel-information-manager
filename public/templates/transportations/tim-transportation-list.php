<?php

class Tim_Travel_Manager_Public_Transportation_Controller {

	protected $plugin_name;
	protected $post_type;
	protected $post_type_meta;

	protected $public_data;

	protected $content_language;
	protected $currency_code;
	protected $currency_symbol;

	public function __construct( $plugin_name, $public_data, $content_language, $currency_value, $post_type_transportations ){

		$this->plugin_name = $plugin_name;

		$this->plugin_url = WP_PLUGIN_URL .'/'. $plugin_name;
		$this->plugin_dir = WP_PLUGIN_DIR .'/'. $plugin_name;

		$this->post_type = $post_type_transportations;
		$this->post_type_meta = $post_type_transportations .'_meta';

		$this->public_data = $public_data;

		$this->content_language = $content_language;

		$this->currency_code = ( $currency_value['code'] != '' )   ? $currency_value['code']   : $currency_value->symbol; // Session/Default
		$this->currency_symbol = ( $currency_value['symbol'] != '' ) ? $currency_value['symbol'] : $currency_value->symbol; // Session/Default

	    $this->init();

	}

	public function init(){

		add_shortcode( 'transportation-list', array( $this, 'transportation_list_display' ) );

		/*
		$general_options = get_option( TIM_TRAVEL_MANAGER_GENERAL_OPTIONS );

		if ( $general_options['transportation_display_option'] === 'search' ){
			add_shortcode( 'transportation-search', array( $this, 'transportation_search_display' ) );
		}
		else{
			add_shortcode( 'transportation-list', array( $this, 'transportation_list_display' ) );
		}*/

	}

	public function transportation_list_display( $atts ){

		extract( shortcode_atts( array(
			'view' => ''
		), $atts ) );

		ob_start();

		$this->render_transportation_list( $view, 0 );

		return ob_get_clean();

	}

	public function render_transportation_list( $view, $itemID, $search_DepartureLocationId = '', $search_ArrivalLocationId = '', $related_item_ids = '' ){

		$plugin_api = new Tim_Travel_Manager_Api( $this->plugin_name, '', $this->public_data );

		$todayPrices = $plugin_api->get_product_list_today_prices( 'transportation' );
		$productsPrices = $todayPrices->productsPrices;
		$documentDateExchangeRate = $todayPrices->documentDateExchangeRate;

		$content_language = $this->content_language;

		$general_options = get_option( TIM_TRAVEL_MANAGER_GENERAL_OPTIONS );

		$themeLayoutIdDefault = 'travelo';
		$themeLayoutId = $general_options['theme_layout_id'];

		// Sort results
		$order_by = 'title';
		$order = 'ASC';
		if ( isset( $_GET['sort'] ) ){
			$sort = explode( '_', $_GET['sort']);
	    	$order_by = ( $sort[0] AND $sort[0] !== 'all' ) ? $sort[0] : $order_by;
	    	$order = ( $sort[1] AND $sort[1] !== 'all' ) ? strtoupper($sort[1]) : $order;
	    }

    	$get_view = isset( $_GET['view'] ) ? $_GET['view'] : $view;

		$args = array(
			'post_type' => $this->post_type,
			// 'meta_key'  => $this->post_type_meta,
			'orderby' => $order_by,
			'order' => $order,
			'posts_per_page' => -1 // no limit per page
		);

		$query = new WP_Query( $args );

		$totalActivePosts = 0;
		
		if ( $query->have_posts() ) :
			$list = array();

			while ( $query->have_posts() ) : $query->the_post();
		        $original_post_id = $this->public_data->get_original_post_id( $this->post_type );
				$postmeta = get_post_meta( $original_post_id, $this->post_type_meta, true );

				// echo get_the_ID();
				// echo '<br>';
				// var_dump($postmeta);

		        // $postmeta = get_post_meta( get_the_ID(), $this->post_type_meta, true );

	        	$productCategoryFound = true; // Show by default
	        	if ( ! empty( $_GET['cat'] ) && $_GET['cat'] !== 'all' ) {
	        		$productCategoryFound = false;

	        		$get_cat = $_GET['cat'];
					$search_ProductCategoryId = $this->public_data->get_post_type_by_name( $get_cat, 'tim_product_cat', 'multiple', $content_language );

	        		if ( ( $search_ProductCategoryId ) && ( $postmeta['product_category_id'] === $search_ProductCategoryId ) ){
	        			$productCategoryFound = true;
		        	}
	        	}

	        	$departureLocationFound = true; // Show by default
				if ( ! empty( $_GET['dep'] ) && $_GET['dep'] !== 'all' ) {
					$departureLocationFound = false;

					$get_dep = $_GET['dep'];
					$search_DepartureLocationId = $this->public_data->get_post_type_by_name( $get_dep, 'tim_location', 'single', $content_language );

					if ( ( $search_DepartureLocationId ) && ( $postmeta['departure_location']->id === $search_DepartureLocationId ) ){
	        			$departureLocationFound = true;
		        	}
				}

				$arrivalLocationFound = true; // Show by default
				if ( ! empty( $_GET['arr'] ) && $_GET['arr'] !== 'all' ) {
					$arrivalLocationFound = false;

					$get_arr = $_GET['arr'];
					$search_ArrivalLocationId = $this->public_data->get_post_type_by_name( $get_arr, 'tim_location', 'single', $content_language );

					if ( ( $search_ArrivalLocationId ) && ( $postmeta['arrival_location']->id === $search_ArrivalLocationId ) ){
	        			$arrivalLocationFound = true;
		        	}
				}

			    if ( $productCategoryFound && $departureLocationFound && $arrivalLocationFound ) {
	        		// Exclude item in related items
	        		if ( $postmeta && $postmeta['id'] !== $itemID ) {
						//$item = '';

						$item['transportation_id'] = $postmeta['transportation_id'];
						$item['id'] = $postmeta['id'];
						$item['schedule_type'] = $postmeta['schedule_type'];
						$item['duration'] = $postmeta['duration'] .' '. __( $postmeta['duration_time_unit'], $this->plugin_name );
						$item['distance'] = $postmeta['distance'];

						$transportationSchedules = [];
						foreach ( $postmeta['transportation_schedules'] as $schedule ) {
	                        array_push( $transportationSchedules, $this->public_data->format_hour( $schedule->departure, $content_language ) );
	                    }

	                    $item['transportation_schedules'] = $transportationSchedules;

	                    $item['departure_location_id'] = $postmeta['departure_location']->id;
						$item['departure_location_name'] = $postmeta['departure_location']->name->$content_language;
						
						$item['arrival_location_id'] = $postmeta['arrival_location']->id;
						$item['arrival_location_name'] = $postmeta['arrival_location']->name->$content_language;
						
						$item['product_category_name'] = $postmeta['product_category']->name->$content_language;

						$item['name'] = $postmeta['name']->$content_language;
						$item['description'] = $postmeta['description']->$content_language;
	        			$item['link'] = get_permalink( get_the_ID() );
	        			$item['logo'] = ( $postmeta['logo'] ) ? $postmeta['logo'] : $this->plugin_url .'/public/img/item.jpg';
	        			
	        			$item['url_video'] = $this->public_data->embed_video( $postmeta['url_video']->$content_language );
		        		
		        		// $item['rate_from'] = $postmeta['rate_from'];
	        			$item['rate_from'] = $this->public_data->find_item_in_array_object( $productsPrices, 'id', $item['transportation_id'])->price;

		        		$item['featured'] = $postmeta['featured'];

						array_push( $list, $item );

						$totalActivePosts++;
					}
	        	}
			endwhile;
			wp_reset_postdata();
		endif;


		// Show product search
		if ( ! $general_options['transportation_disable_search'] AND $get_view !== 'related' AND $totalActivePosts > 0 ) {
			require_once $this->plugin_dir .'/public/widgets/searchs/tim-transportation-search.php';
		}

		// Show product list
		if ( ! $general_options['transportation_hide_list'] ) {
			if ( $get_view !== 'related' ) {
				$view_mode = ( $get_view !== 'grid' && $get_view !== 'related' ) ? 'tim_list_view' : '';

				echo '<div class="tim_wrapper">';
					if ( $totalActivePosts > 0 ){
						echo '<div class="tim_list '. $view_mode .'">';
					}
			} else { // Related items
				$totalActivePosts = 0;
				$relatedItems = array();
				foreach ( $related_item_ids as $item_id) {
					foreach ( $list as $key => $item) {
						if ( $item['transportation_id'] === $item_id ){
							array_push( $relatedItems, $item );
							$totalActivePosts++;
						 	break;
						}
					}
				}

				$list = $relatedItems;
			}

		    if ( $totalActivePosts > 0 ) {
		    	if ($view === 'featured') {
					echo 'featured transportations';
				} else {
					// Plugin theme layout selected
					if ( $themeLayoutId ) {
						$layour_dir = $this->plugin_dir .'/public/layouts/';
						
						$themeLayout = $layour_dir . $themeLayoutId .'/transportations/list.php';
						$themeLayout = file_exists($themeLayout) ? $themeLayout : $layour_dir . $themeLayoutIdDefault .'/list.php';
					} else {
						$themeLayout = get_stylesheet_directory() .'/tim-transportation-list.php'; // child theme
					}
					
					require_once $themeLayout;
		
					if ( $order_by == 'rate' ) {
						$list = $this->public_data->sort_rates_by( $list, $order );
					}
					
					foreach ( $list as $key => $item) {
						$rate_from         = $this->public_data->apply_exchange_rate_conversion( $item['rate_from'], $documentDateExchangeRate );
						$item['rate_from'] = $this->currency_symbol . $this->public_data->round_number($rate_from);

						render_transportation_list_html( $item, $get_view, $this->plugin_name );
					}

					?>
					<script src="<?php echo plugin_dir_url(dirname( __DIR__ )); ?>libs/blazy.js"></script>
				    <script>
				        ;(function() {
				            var bLazy = new Blazy();
				        })();
				    </script>
					<?php
				}
		    } else {
		    	_e( 'Sorry. No items found', $plugin_name );
		    }

		    if ( $get_view !== 'related' ) {
	    		if ( $totalActivePosts > 0 ){
	    			echo '<div class="tim_gap"></div>';
					echo '</div>';
				}
		    	echo '</div>';
		    }
	    }
	 	    
    }

    /*public function transportation_search_display( $atts ){

    	extract( shortcode_atts( array(
			'view' => ''
		), $atts ) );

		ob_start();

		$content_language = $this->content_language;

		require_once $this->plugin_dir .'/public/widgets/searchs/tim-transportation-search.php';

		return ob_get_clean();

    }*/

}

?>