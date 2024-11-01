<?php

class Tim_Travel_Manager_Public_Tour_Controller {

	protected $plugin_name;
	protected $post_type;
	protected $post_type_meta;

	protected $public_data;

	protected $content_language;
	protected $currency_code;
	protected $currency_symbol;

	public function __construct( $plugin_name, $public_data, $content_language, $currency_value, $post_type_tours ){

		$this->plugin_name = $plugin_name;

		$this->plugin_url = WP_PLUGIN_URL .'/'. $plugin_name;
		$this->plugin_dir = WP_PLUGIN_DIR .'/'. $plugin_name;

		$this->post_type      = $post_type_tours;
		$this->post_type_meta = $post_type_tours .'_meta';

		$this->public_data = $public_data;

		$this->content_language = $content_language;

		$this->currency_code   = ( $currency_value['code'] != '' )   ? $currency_value['code']   : $currency_value->symbol; // Session/Default
		$this->currency_symbol = ( $currency_value['symbol'] != '' ) ? $currency_value['symbol'] : $currency_value->symbol; // Session/Default

	    $this->init();

	}

	public function init(){

		add_shortcode( 'tour-list', array( $this, 'tour_list_display' ) );

	}

	public function tour_list_display( $atts ){

		extract( shortcode_atts( array(
			'view' => ''
		), $atts ) );

		ob_start();

		$this->render_tour_list( $view, 0 );

		return ob_get_clean();

	}

	public function render_tour_list( $view, $itemID, $search_LocationId = '', $related_item_ids = '' ) {

		$plugin_api = new Tim_Travel_Manager_Api( $this->plugin_name, '', $this->public_data );
		// $documentDateExchangeRate = $plugin_api->check_document_date_exchange_rate();

		$todayPrices = $plugin_api->get_product_list_today_prices( 'tour' );
		$productsPrices = $todayPrices->productsPrices;
		$documentDateExchangeRate = $todayPrices->documentDateExchangeRate;

		// echo '<pre>'; print_r($todayPrices); echo '</pre>';

		$content_language = $this->content_language;

		$theme_options = get_option( TIM_TRAVEL_MANAGER_GENERAL_OPTIONS );

		$themeLayoutIdDefault = 'travelo';
		$themeLayoutId = $theme_options['theme_layout_id'];

		// Sort results
		$order_by = 'title';
		$order = 'ASC';
		if ( isset( $_GET['sort'] ) ) {
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

		$locations = $this->public_data->get_locations( TIM_TRAVEL_MANAGER_POST_TYPE_LOCATIONS, $content_language );
		
		if ( $query->have_posts() ) :
			$list = array();

			$allTourLocations = array();
			// $toursLocationIds = array();

			while ( $query->have_posts() ) : $query->the_post();
				$original_post_id = $this->public_data->get_original_post_id( $this->post_type );
				$postmeta = get_post_meta( $original_post_id, $this->post_type_meta, true );

				// echo 'Id: '. get_the_ID();
				// echo '<br>';
				// echo 'original_post_id: '. $original_post_id;
				// var_dump($postmeta);
				// echo '<br><br>';

		        // $postmeta = get_post_meta( get_the_ID(), $this->post_type_meta, true );

		        $locationFound = true; // Show by default
				if ( ! empty( $_GET['loc'] ) && $_GET['loc'] !== 'all' ) {
					$locationFound = false;					

					$search_Location = $this->public_data->find_item_in_array($locations, 'id', $_GET['loc']);
					$search_LocationId = $search_Location['id'];

					if ( isset( $search_LocationId ) && isset( $postmeta['location']->id ) && ( $postmeta['location']->id === $search_LocationId ) ){ // Consider multiple selection
	        			$locationFound = true;
		        	}
				}

				$categoryFound = true; // Show by default
				if ( ! empty( $_GET['cat'] ) && $_GET['cat'] !== 'all' ) {
					$categoryFound = false;

					$get_cat = $_GET['cat'];
					$search_CategoryId = $this->public_data->get_post_type_by_name( $get_cat, 'tim_categories', 'multiple', $content_language );

					if ( isset( $postmeta['category_ids'] ) ){
						foreach ( $postmeta['category_ids'] as $category_id ) {
			        		if ( $category_id === $search_CategoryId ){
			        			$categoryFound = true;
			        			break;
			        		}
			        	}
					}
				}

	        	if ( $locationFound && $categoryFound ) { // && $rateRangeFound 
	        		// Exclude item in related items
	        		if ( $postmeta && $postmeta['id'] !== $itemID ) {
	        		//if ( ( $min_rate ) && ( get_the_ID() !== $itemID ) ){
						//$item = ''; Error on some phph versions

						$item['tour_id'] = $postmeta['tour_id'];

						$rate_from = $this->public_data->find_item_in_array_object( $productsPrices, 'id', $item['tour_id'])->price;

						$rateRangeFound = true;
			        	if ( ! empty( $_GET['rate'] ) && $_GET['rate'] !== 'all' ) {
				        	$rateRangeFound = false;
				        	
				        	$rate_range = explode( '-', $_GET['rate'] );
					    	$from = $rate_range[0];
					    	$to = $rate_range[1];

					    	// $rate_from = isset($postmeta['rate_from']) ? $postmeta['rate_from'] : '';

					    	$rateRangeFound = $this->public_data->is_value_between_range( $rate_from, $from, $to );
					    }

					    if ( $rateRangeFound ) {
							$item['id'] = $postmeta['id'];

							$tour_option = $postmeta['tour_options'][0];

							$schedules = '';
							foreach ( $tour_option->tour_option_schedules as $schedule ) {
								// array_push($schedules, $this->public_data->format_hour( $schedule->departure, $content_language ));

								$schedules .= $this->public_data->format_hour( $schedule->departure, $content_language ). ', ';
							}

							$item['departures'] = substr($schedules, 0, -2);

	
							// $item['departures'] = $postmeta['departures'];
							

							$item['duration'] = $postmeta['duration'] .' '. __( $postmeta['duration_time_unit'], $this->plugin_name );
							$item['location_id'] = $postmeta['location']->id;
							$item['location_name'] = $postmeta['location']->name->$content_language ? $postmeta['location']->name->$content_language : '';

							$item['name'] = $postmeta['name']->$content_language;
							$item['description'] = $postmeta['description']->$content_language;
		        			$item['link'] = get_permalink( get_the_ID() );
		        			$item['logo'] = ( $postmeta['logo'] ) ? $postmeta['logo'] : $this->plugin_url .'/public/img/item.jpg';
		        			$item['product_category_name'] = $postmeta['product_category']->name->$content_language;
		        			
		        			$item['url_video'] = $this->public_data->embed_video( $postmeta['url_video']->$content_language );
			        		
			        		$item['rate_from'] = $rate_from;
			        		
			        		// $item['rate_from'] = $this->public_data->find_item_in_array_object( $productsPrices, 'id', $item['tour_id'])->price;

			        		$item['featured'] = $postmeta['featured'];

			        		// array_push( $toursLocationIds, $postmeta['location']->id);

							array_push( $list, $item );

							$totalActivePosts++;
						}
					}
	        	}

	        	if ( $postmeta && $postmeta['id'] !== $itemID ) {
	        		$itemAll['location_id'] = $postmeta['location']->id;
					$itemAll['location_name'] = $postmeta['location']->name->$content_language ? $postmeta['location']->name->$content_language : '';

	        		array_push( $allTourLocations, $itemAll );
	        	}
			endwhile;
			wp_reset_postdata();
		endif;



		// echo $totalActivePosts;
		// echo '<hr>';
		// var_dump($list);

		if ( $get_view !== 'related' ) {
			$search_widget = $this->plugin_dir .'/public/widgets/searchs/tim-tour-list-search.php';
			require_once $search_widget;

			$view_mode = ( $get_view !== 'grid' && $get_view !== 'related' ) ? 'tim_list_view' : '';

			echo '<div class="tim_wrapper">';
				if ( $totalActivePosts > 0 ) {
					echo '<div class="tim_list '. $view_mode .'">';
				}
		} else { // Related items
			$totalActivePosts = 0;
			$relatedItems = array();
			foreach ( $related_item_ids as $item_id) {
				foreach ( $list as $key => $item) {
					if ( $item['tour_id'] === $item_id ){
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
				echo 'featured tours';
			} else {
				// Plugin theme layout selected
				if ( $themeLayoutId ) {
					$layour_dir = $this->plugin_dir .'/public/layouts/';
					
					$themeLayout = $layour_dir . $themeLayoutId .'/tours/list.php';
					$themeLayout = file_exists($themeLayout) ? $themeLayout : $layour_dir . $themeLayoutIdDefault .'/list.php';
				} else {
					$themeLayout = get_stylesheet_directory() .'/tim-tour-list.php'; // child theme
				}
				
				require_once $themeLayout;
	
				if ( $order_by === 'rate' ) {
					$list = $this->public_data->sort_rates_by( $list, $order );
				}
				
				foreach ( $list as $key => $item) {
					$rate_from = $this->public_data->apply_exchange_rate_conversion( $item['rate_from'], $documentDateExchangeRate );
					$item['rate_from'] = $this->currency_symbol . $this->public_data->round_number($rate_from);

					render_tour_list_html( $item, $get_view, $this->plugin_name );
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
    		if ( $totalActivePosts > 0 ) {
    			echo '<div class="tim_gap"></div>';
				echo '</div>';
			}
	    	echo '</div>';
	    }
	 	    
    }

}



					//$themeLayout = get_template_directory() .'/tim-tour-list.php';


					// $item['rate_from'] = $this->currency_symbol . $item['rate_from'];
					// $rate_from         = $this->public_data->apply_exchange_rate_conversion( $item['rate_from'], $documentDateExchangeRate );
					// $item['rate_from'] = $this->currency_symbol . $rate_from;
	        	////$min_rate = $this->public_data->get_min_rate( $postmeta['tour_rates'], 'adult_rate' );

	        	/*$rateRangeFound = true;
	        	if ( ! empty( $_GET['rate'] ) && $_GET['rate'] !== 'all' ) {
		        	$rateRangeFound = false;
		        	
		        	$rate_range = explode( '-', $_GET['rate'] );
			    	$from = $rate_range[0];
			    	$to   = $rate_range[1];

			    	$rate_from = isset($postmeta['rate_from']) ? $postmeta['rate_from'] : '';

			    	$rateRangeFound = $this->public_data->is_value_between_range( $rate_from, $from, $to );
			    }*/


// $get_loc = $_GET['loc'];
// $search_LocationId = $this->public_data->get_post_type_by_name( $get_loc, 'tim_location', 'single', $content_language );

?>