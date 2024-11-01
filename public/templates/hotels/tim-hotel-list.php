<?php

class Tim_Travel_Manager_Public_Hotel_Controller {

	protected $plugin_name;
	protected $post_type;
	protected $post_type_meta;

	protected $public_data;

	protected $content_language;
	protected $currency_code;
	protected $currency_symbol;

	public function __construct( $plugin_name, $public_data, $content_language, $currency_value, $post_type_hotels ){

		$this->plugin_name = $plugin_name;

		$this->plugin_url = WP_PLUGIN_URL .'/'. $plugin_name;
		$this->plugin_dir = WP_PLUGIN_DIR .'/'. $plugin_name;

		$this->post_type      = $post_type_hotels;
		$this->post_type_meta = $post_type_hotels .'_meta';

		$this->public_data = $public_data;

		$this->content_language = $content_language;
	    
	    $this->currency_code   = ( $currency_value['code'] != '' )   ? $currency_value['code']   : $currency_value->symbol; // Session/Default
	    $this->currency_symbol = ( $currency_value['symbol'] != '' ) ? $currency_value['symbol'] : $currency_value->symbol; // Session/Default

	    $this->init();

	}

	public function init(){

		add_shortcode( 'hotel-list', array( $this, 'hotel_list_display' ) );

	}


	public function hotel_list_display( $atts ){

		extract( shortcode_atts( array(
			'view' => ''
		), $atts ) );

		ob_start();

		$this->render_hotel_list( $view, 0 );

		return ob_get_clean();

	}

	public function render_hotel_list( $view, $itemID, $search_LocationId = '', $related_item_ids = '' ){

		$plugin_api = new Tim_Travel_Manager_Api( $this->plugin_name, '', $this->public_data );

		$todayPrices              = $plugin_api->get_product_list_today_prices( 'hotel' );
		$productsPrices           = $todayPrices->productsPrices;
		$documentDateExchangeRate = $todayPrices->documentDateExchangeRate;

		$content_language = $this->content_language;

		$theme_options = get_option( TIM_TRAVEL_MANAGER_GENERAL_OPTIONS );

		$themeLayoutIdDefault = 'travelo';
		$themeLayoutId        = $theme_options['theme_layout_id'];

		// Sort results
		$sort     = explode('-', $_GET['sort']);
    	$order_by = ( ($sort[0]) AND ($sort[0] !== 'all')) ? $sort[0] : 'title';
    	$order    = ( ($sort[1]) AND ($sort[1] !== 'all') ) ? strtoupper($sort[1]) : 'ASC';

    	$get_view = isset( $_GET['view'] ) ? $_GET['view'] : $view;

		$args = array(
			'post_type' => $this->post_type,
			// 'meta_key'  => $this->post_type_meta,
			'orderby'   => $order_by,
			'order'     => $order,
			'posts_per_page' => -1 // no limit per page
			//'posts_per_page' => 3 // related?
		);

		$query = new WP_Query( $args );

		// Search location
		if ( ! empty( $_GET['loc'] ) && $_GET['loc'] !== 'all' ) {
			$get_loc = $_GET['loc'];
			$search_LocationId = $this->public_data->get_post_type_by_name( $get_loc, 'tim_location', 'single', $content_language );
		}

		// Search category
		if ( ! empty( $_GET['cat'] ) && $_GET['cat'] !== 'all' ) {
			$get_cat = $_GET['cat'];
			$search_CategoryId = $this->public_data->get_post_type_by_name( $get_cat, 'tim_categories', 'multiple', $content_language );
		}
		
		$totalActivePosts = 0;

		if ( $query->have_posts() ) :
			$list = array();

			while ( $query->have_posts() ) : $query->the_post();
				$original_post_id = $this->public_data->get_original_post_id( $this->post_type );
				$postmeta         = get_post_meta( $original_post_id, $this->post_type_meta, true );
				
		        // $postmeta = get_post_meta( get_the_ID(), $this->post_type_meta, true );

	        	$locationFound = true; // Show by default
				if ( ( $search_LocationId ) && ( $postmeta['location']->id !== $search_LocationId ) ){ // Consider multiple selection
        			$locationFound = false;
	        	}

	        	$categoryFound = true;
	        	if ( $search_CategoryId ){
	        		$categoryFound = false;
	        		foreach ( $postmeta['category_ids'] as $category_id ) {
		        		if ( $category_id === $search_CategoryId ){
		        			$categoryFound = true;
		        			break;
		        		}
		        	}
	        	}

	        	$rateRangeFound = true;
	        	if ( ! empty( $_GET['rate'] ) && $_GET['rate'] !== 'all' ) {
		        	$rateRangeFound = false;

		        	$rate_range = explode( '-', $_GET['rate'] );
			    	$from = $rate_range[0];
			    	$to   = $rate_range[1];

			    	$rate_from = $postmeta['rate_from'];

			    	$rateRangeFound = $this->public_data->is_value_between_range( $rate_from, $from, $to );
			    }

	        	if ( $locationFound && $categoryFound && $rateRangeFound ){
	        		// Exclude item in related items
	        		if ( $postmeta && $postmeta['id'] !== $itemID ){
						$item['hotel_id']          = $postmeta['hotel_id'];
						$item['id']                = $postmeta['id'];
						$item['location_name']     = $postmeta['location']->name->$content_language;

						$item['name']              = $postmeta['name']->$content_language;
						$item['description']       = $postmeta['description']->$content_language;
	        			$item['link']              = get_permalink( get_the_ID() );
	        			$item['logo']              = ( $postmeta['logo'] ) ? $postmeta['logo'] : $this->plugin_url .'/public/img/item.jpg';
	        			$item['stars_rating']      = $postmeta['stars_rating'];
	        			$item['product_category_name'] = $postmeta['product_category']->name->$content_language;

	        			$item['url_video']         = $this->public_data->embed_video( $postmeta['url_video']->$content_language );
		        		
		        		// $item['rate_from']         = $postmeta['rate_from'];

	        			$item['rate_from']     = $this->public_data->find_item_in_array_object( $productsPrices, 'id', $item['hotel_id'])->price;

		        		$item['featured']          = $postmeta['featured'];

						array_push( $list, $item );

						$totalActivePosts++;
					}
	        	}
			endwhile;
			wp_reset_postdata();
		endif;

		if ( $get_view !== 'related' ){
			$search_widget = $this->plugin_dir .'/public/widgets/searchs/tim-hotel-list-search.php';
			require_once( $search_widget );

			$view_mode = ( $get_view !== 'grid' && $get_view !== 'related' ) ? 'tim_list_view' : '';

			echo '<div class="tim_wrapper">';
				if ( $totalActivePosts > 0 ){
					echo '<div class="tim_list '. $view_mode .'">';
				}
		}
		else{ // Related items
			$totalActivePosts = 0;
			$relatedItems     = array();
			foreach ( $related_item_ids as $item_id ) {
				foreach ( $list as $key => $item) {
					if ( $item['hotel_id'] === $item_id ){
						array_push( $relatedItems, $item );
						$totalActivePosts++;
					 	break;
					}
				}
			}

			$list = $relatedItems;
		}

	    if ( $totalActivePosts > 0 ){
	    	if ($view === 'featured'){
				echo 'featured hotels';
			}
			else{	
				// Plugin theme layout selected
				if ( $themeLayoutId ){
					$layour_dir = $this->plugin_dir .'/public/layouts/';
					
					$themeLayout = $layour_dir . $themeLayoutId .'/hotels/list.php';
					$themeLayout = file_exists($themeLayout) ? $themeLayout : $layour_dir . $themeLayoutIdDefault .'/list.php';
				}
				else{
					$themeLayout = get_stylesheet_directory() .'/tim-hotel-list.php';
				}
				
				require_once( $themeLayout );
	
				if ( $order_by == 'rate_from' ){
					$list = $this->public_data->sort_rates_by( $list, $order );
				}
								
				foreach ( $list as $key => $item ) {
					$rate_from         = $this->public_data->apply_exchange_rate_conversion( $item['rate_from'], $documentDateExchangeRate );
					$item['rate_from'] = $this->currency_symbol . $this->public_data->round_number($rate_from);

					render_hotel_list_html( $item, $get_view, $this->plugin_name );
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
	    }
	    else{
	    	_e( 'Sorry. No items found', $plugin_name );
	    }

	    if ( $get_view !== 'related' ){
	    		if ( $totalActivePosts > 0 ){
	    			echo '<div class="tim_gap"></div>';
					echo '</div>';
				}
	    	echo '</div>';
	    }
	 	    
    }

}

?>