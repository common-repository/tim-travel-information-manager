<?php

// Copyright (C) Nativo LLC

class Tim_Travel_Manager_Api {

	// authenticate_api_key
	// _response

	// sync_tim_api
	// save_multiple_data
	// save_single_data
	// get_meta_args_countries
	// get_meta_args_locations
	// get_meta_args_tours
	// get_meta_args_transportations
	// get_meta_args_hotels
	// get_meta_args_packages
	// get_meta_args_miscellaneous
	// get_meta_args_pickup_places
	// get_meta_args_product_categories
	// get_meta_args_currencies

	// edit_post
	// set_post_meta_array
	// get_post_by_meta

	// check_tour_rates_api

	// search_transportations_rates_api
	// check_transportation_rates_api
	// load_transportation_to_locations

	// check_hotel_availability_api
	// check_hotel_room_rates_api
	// check_package_request_api
	// check_document_date_exchange_rate

	// get_cart_session
	// get_booking
	// get_booking_item
	// delete_booking
	// get_cancellation_policies
	
	// add_item_to_order_api
	// update_item_from_order_api
	// apply_places_to_order_api
	// remove_item_from_order_api

	// apply_discount_coupon_to_order_api
	// delete_discount_coupon_to_order_api

	// load_payment_form
	// process_ecommerce_payment
	// process_pay_later_payment
	// complete_order_payment
	// complete_order_api
	// paypal_order_completed
	// order_completed

	// load_pickup_places_to_order_item
	// verify_order_api

	// list_orders_api

	// load_login_form
	// create_client_login_api
	// client_logout
	// load_signup_form
	// create_client_signup_api
	// load_password_recovery_form
	// create_client_password_api
	// load_edit_password_form
	// update_client_password_api

	// load_client_profile
	// update_client_profile_api
	// update_client_profile_password_api
	
	// register_client_session_data
	// register_guest_session_data

	// set_currency_value
	// start_session

	protected $plugin_name;
	protected $plugin_nonce;
	protected $public_data;

	public function __construct( $plugin_name, $plugin_nonce, $public_data ) {

		$this->plugin_name = $plugin_name;
		$this->plugin_nonce = $plugin_nonce;
 
		$this->public_data = $public_data;

		$this->backEndUrl = TIM_TRAVEL_MANAGER_BACKEND_URL;

	}

	public function authenticate_api_key( $url, $params, $method, $accountApi = 0 ) { // $method = 'POST'

		$options = get_option( TIM_TRAVEL_MANAGER_CREDENTIALS );

		$backEndUrl = $this->backEndUrl;

		$url = esc_url_raw( $backEndUrl . $url );

		$credentials = array(
		    'sub' => $options['subdomain'],
		    'dak' => $options['domain_api_key'], 
		    'cak' => $options['company_api_key']
		);
		
		/*if ( $accountApi ){
			$account_api_key = array(
	        	'apk' => $options['account_api_key']
		    );

		    $credentials = array_merge( $credentials, $account_api_key );
		}
		*/
		
		$body = array_merge( $credentials, $params );

		$args = array(
			'method' => $method, 
		    'body' => $body,
		    'timeout' => 45,
			'redirection' => 5,
			'httpversion' => '1.0',
			'blocking' => true,
			'headers' => array(),
			'cookies' => array(), 
			'sslverify' => false
		);

		$response = wp_remote_request( $url, $args );

		$data = $response['body'];

		if ( $data !== 'Invalid Access' ) {
	     	return json_decode( $data );
	    }

	    return false;

	}

	private function _response( $data, $status = 200 ) {
    	
    	return json_encode( $data );

  	}


  	// -- SYNCHRONIZATION functions --//

	// Sync api data
	public function sync_tim_api() {

		check_ajax_referer( $this->plugin_nonce, 'nonce' );

		$url_option = '';

		if ( isset($_POST['option']) ) {
			// echo 'cool';
			// exit;
			$url_option = '/?option='. $_POST['option'];
		}

		$url = '/sync/synchronize_account_api'. $url_option;

		$params = array();
	    
	    $data = $this->authenticate_api_key( $url, $params, 'GET', 1 ); // POST

		if ( ! $data) {
	    	echo 'Not Authorized';
	    } else {
	    	echo 'Data Synchronized';
    		// echo '<pre>'; print_r($data); echo '</pre>';
			
			$general_options = get_option( TIM_TRAVEL_MANAGER_GENERAL_OPTIONS );
			$translation_plugin_id = $general_options['translation_plugin_id'];

			$default_content_lang = $data->default_content_lang;

			// echo 'default_content_lang: '. $default_content_lang;
			// exit;

			$this->set_default_content_lang( $default_content_lang ); // not saving the value
    		
    		if ( ! isset($_POST['option']) ) {
				$this->delete_posts_by_post_type();

				if ($translation_plugin_id === 'wpml' ) {
					$this->delete_wpml_icl_translations();
				}
				
				// $this->set_default_content_lang( $default_content_lang ); // not saving the value

		    	$this->_response( $this->save_multiple_data( $translation_plugin_id, 'location', $data->locations, $default_content_lang ) );
		    	$this->_response( $this->save_multiple_data( $translation_plugin_id, 'tour', $data->tours, $default_content_lang ) );
		    	$this->_response( $this->save_multiple_data( $translation_plugin_id, 'transportation', $data->transportations, $default_content_lang ) );
		    	$this->_response( $this->save_multiple_data( $translation_plugin_id, 'hotel', $data->hotels, $default_content_lang ) );
		    	$this->_response( $this->save_multiple_data( $translation_plugin_id, 'package', $data->packages, $default_content_lang ) );

		    	$this->_response( $this->save_single_data( 'countries', $data->countries ) );
		    	$this->_response( $this->save_single_data( 'categories', $data->categories ) );
		    	$this->_response( $this->save_single_data( 'facilities', $data->facilities ) );
		    	$this->_response( $this->save_single_data( 'wtobring', $data->what_to_bring ) );
		    	$this->_response( $this->save_single_data( 'pickup_places', $data->pickup_places ) );
		    	$this->_response( $this->save_single_data( 'product_cat', $data->product_categories ) );
		    	
		    	$this->_response( $this->save_single_data( 'currencies', $data->website_currencies ) );
		    	// $this->_response( $this->save_single_data( 'currencies', $data->currencies ) );

		    	// Check if data was stored on WP DB, otherwise, save it again
    		} else {
    			switch ($_POST['option']) {
    				case 'locations':
    					$post_types = array(
							'location'
				    	);
    				break;

    				case 'tours':
    					$post_types = array(
							'tour'
				    	);
    				break;

    				case 'transportation':
    					$post_types = array(
							'transportation'
				    	);
    				break;

    				case 'hotels':
    					$post_types = array(
							'hotel'
				    	);
    				break;

    				case 'packages':
    					$post_types = array(
							'package'
				    	);
    				break;

    				case 'miscellaneous':
    					$post_types = array(
							'countries', 
							'categories', 
							'facilities', 
							'wtobring', 
							'pickup_places', 
							'product_cat', 
							'currencies'
				    	);
    				break;
    			}

				$this->delete_posts_by_post_type( $post_types );

    			if ( $translation_plugin_id === 'wpml' AND $_POST['option'] !== 'miscellaneous' ) {
					$this->delete_wpml_icl_translations($post_types);
				}
    			
    			switch ($_POST['option']) {
    				case 'locations':
				    	$this->_response( $this->save_multiple_data( $translation_plugin_id, 'location', $data->locations, $default_content_lang ) );
    				break;

    				case 'tours':
    					// echo 'translation_plugin_id: '. $translation_plugin_id;
    					// echo '<br>default_content_lang: '. $default_content_lang;
    					// echo '<br>';
    					// var_dump($data->tours);

				    	$this->_response( $this->save_multiple_data( $translation_plugin_id, 'tour', $data->tours, $default_content_lang ) );
    				break;

    				case 'transportation':
				    	$this->_response( $this->save_multiple_data( $translation_plugin_id, 'transportation', $data->transportations, $default_content_lang ) );
    				break;

    				case 'hotels':
				    	$this->_response( $this->save_multiple_data( $translation_plugin_id, 'hotel', $data->hotels, $default_content_lang ) );
    				break;

    				case 'packages':
				    	$this->_response( $this->save_multiple_data( $translation_plugin_id, 'package', $data->packages, $default_content_lang ) );
    				break;

    				case 'miscellaneous':
				    	$this->_response( $this->save_single_data( 'countries', $data->countries ) );
				    	$this->_response( $this->save_single_data( 'categories', $data->categories ) );
				    	$this->_response( $this->save_single_data( 'facilities', $data->facilities ) );
				    	$this->_response( $this->save_single_data( 'wtobring', $data->what_to_bring ) );
				    	$this->_response( $this->save_single_data( 'pickup_places', $data->pickup_places ) );
				    	$this->_response( $this->save_single_data( 'product_cat', $data->product_categories ) );
				    	
				    	$this->_response( $this->save_single_data( 'currencies', $data->website_currencies ) );
    				break;
    			}
    		}
	    }
	  
		wp_die(); // this is required to return a proper result
		
	}

	protected function set_default_content_lang( $default_content_lang ) {

		$option = 'tim_default_content_lang';

		if ( ! $this->option_exists($option) ) {
		    add_option( $option, $default_content_lang );
		} else {
			update_option( $option, $default_content_lang );
		}
	}

	protected function option_exists($name, $site_wide=false){
    	global $wpdb;

    	return $wpdb->query("SELECT * FROM ". ($site_wide ? $wpdb->base_prefix : $wpdb->prefix). "options WHERE option_name ='$name' LIMIT 1");
	}

	// Save multiple data - one post per item
	protected function save_multiple_data( $translation_plugin_id, $option, $data, $default_content_lang ) {

		// There is data
		if ( $data ) {
			$post_type = 'tim_'. $option; // Under 20 chars
			$post_type_meta = 'tim_'. $option .'_meta';

			foreach ( $data as $item ) {
				$post_meta_args = '';

				switch ( $option ) {
					case 'location':
						$post_meta_args = $this->get_meta_args_locations( $item );
						break;

					case 'tour':
						$post_meta_args = $this->get_meta_args_tours( $item );
						break;

					case 'transportation':
						$post_meta_args = $this->get_meta_args_transportations( $item );
						break;

					case 'hotel':
						$post_meta_args = $this->get_meta_args_hotels( $item );
						break;

					case 'package':
						$post_meta_args = $this->get_meta_args_packages( $item );
						break;
				}

				$post_args = array(
		        	'post_title' => $item->name->$default_content_lang, 
		        	'post_name' => $item->url->$default_content_lang, 
		        	'post_type' => $post_type, 
		        	'post_status' => 'publish', 
		        	'post_author' => 1
			    );

				// Insert post and retrive id
	        	$post_id = $this->edit_post( 'insert', $post_args );
	        	add_post_meta( $post_id, $post_type_meta, $post_meta_args );

				switch ( $translation_plugin_id ) {
		            case 'wpml':
		            	// Save the translations for the default post
		              	foreach ( $item->url as $key=>$value ) {
						    if ( $key != $default_content_lang ) {
						    	$secondary_post_id = wp_insert_post( array(
						    		'post_title' => $item->name->$key, 
						    		'post_name' => $value, 
								    'post_type'=> $post_type, 
						        	'post_status' => 'publish', 
						        	'post_author' => 1
								) );

								$wpml_element_type = apply_filters( 'wpml_element_type', $post_type );
	         
						        // get the language info of the original post
						        // https://wpml.org/wpml-hook/wpml_element_language_details/
						        $get_language_args = array('element_id' => $post_id, 'element_type' => $post_type );
						        $original_post_language_info = apply_filters( 'wpml_element_language_details', null, $get_language_args );

						        $wpml_default_language = apply_filters( 'wpml_default_language', NULL );

						        // echo 'wpml_default_language: '. $wpml_default_language;
						        // echo ' - default_content_lang: '. $default_content_lang;
						        // echo '<br><br>';

								// When wpml default language is different than TIMS' default content language
								if ( $wpml_default_language !== $default_content_lang ) {
							        $set_language_args = array(
							            'element_id' => $secondary_post_id,
							            'element_type' => $wpml_element_type,
							            'trid' => $original_post_language_info->trid, 
							            'language_code' => $key,
							            'source_language_code' => $original_post_language_info->language_code
							        );

							        // echo 'diff';

							        // ??
							        do_action( 'wpml_set_element_language_details', $set_language_args );
								} else {
									// ??
							        global $wpdb;

							        $wpml_translations = $wpdb->prefix . 'icl_translations'; // wp_icl_translations
									
									// update de language_code
							        $query = "UPDATE ". $wpml_translations ." set language_code = '". $default_content_lang ."' WHERE element_id = '". $post_id ."'";
							        $wpdb->query( $query );

							        $query = "UPDATE ". $wpml_translations ." 
							        	set trid = '". $original_post_language_info->trid ."', 
							        	    language_code = '". $key ."', 
							        	    source_language_code = '". $default_content_lang ."' 
							        	WHERE element_id = '". $secondary_post_id ."'";
							        $wpdb->query( $query );

							        // if ($option === 'tour') {
						        	// 	echo '<br>';
							        // 	echo 'post_id: '. $post_id .' - default_content_lang: '. $default_content_lang .' - key: '. $key .' - secondary_post_id: '. $secondary_post_id;
							        // }

							        // echo 'same';
						        }
						    }
						}
		            break;

		            case 'qtranslate-x':
		            	// Add translation slugs for the post
		                foreach ( $item->url as $key=>$value ) {
			        		if ( $key != $default_content_lang ) { // 'en'
			        			add_post_meta( $post_id, '_qts_slug_'. $key, $value );
						    }
						}
		            break;
		                
		            // None
		            default:
		            	// Do nothing
		            break;
		        }
	    	}
	    }
	}

	// Save single data - only one post - REMOVE DUPLICATES OR OLD POST TYPES - ex tim_tour
	protected function save_single_data( $option, $data ) {

		// There is data
		if ( $data ){
			$post_type      = 'tim_'. $option; // Under 20 chars
			$post_type_meta = 'tim_'. $option .'_meta';

			$list = array();

        	foreach ($data as $item) {
        		$post_meta_args = '';
				switch ( $option ) {
					case 'countries':
						$post_meta_args = $this->get_meta_args_countries( $item );
						break;
					
					case 'categories':
						$post_meta_args = $this->get_meta_args_miscellaneous( $item );
						break;

					case 'facilities':
						$post_meta_args = $this->get_meta_args_miscellaneous( $item );
						break;

					case 'wtobring':
						$post_meta_args = $this->get_meta_args_miscellaneous( $item );
						break;

					case 'pickup_places':
						$post_meta_args = $this->get_meta_args_pickup_places( $item );
						break;

					case 'product_cat': // product_categories - keep it under 20
						$post_meta_args = $this->get_meta_args_product_categories( $item );
						break;

					case 'currencies':
						$post_meta_args = $this->get_meta_args_currencies( $item );
						break;
				}

        		array_push( $list, $post_meta_args );
        	}

			$post_args = array(
	        	'post_title'  => $post_type, 
	        	'post_type'   => $post_type,
	        	'post_status' => 'publish', 
	        	'post_author' => 1
		    );

			// Insert post and retrive id
        	$post_id = $this->edit_post( 'insert', $post_args );
        	add_post_meta( $post_id, $post_type_meta, $list );

	    }

	}

	protected function get_meta_args_countries( $item ) {

		$post_meta_args = array(
		    'id'         => $item->_id,
		    'name'       => $item->name,
		    'code'       => $item->geo_zoom, 
		    'phone_code' => $item->phone_code,
		    'status'     => $item->status
		);

		return $post_meta_args;

	}

	protected function get_meta_args_locations( $item ) {

		$post_meta_args = array(
		    'id'                => $item->_id,
		    'code'              => $item->code,
		    'name'              => $item->name,
		    'url'               => $item->url,
		    'short_description' => $item->short_description,
		    'long_description'  => $item->long_description,
		    'highlights'        => $item->highlights,
		    'url_video'         => $item->url_video,

		    'geo_lat'           => $item->geo_lat, 
		    'geo_lng'           => $item->geo_lng, 
		    'geo_zoom'          => $item->geo_zoom, 
		    'status'            => $item->status, 
		    'logo'              => $item->logo,
		    'country_id'        => $item->country_id, 
		    'category_ids'      => $item->category_ids,
		    'parentLocation_id' => $item->parentLocation_id, 
		    'photos'            => $item->photos
		);

		return $post_meta_args;

	}

	protected function get_meta_args_tours( $item ) {

		$post_meta_args = array(
		    'tour_id'             => $item->tour_id,
		    'tour_code'           => $item->tour_code, 
		    'geo_lat'             => $item->geo_lat, 
		    'geo_lng'             => $item->geo_lng, 
		    'geo_zoom'            => $item->geo_zoom, 
		    'departures'          => $item->departures, 
		    'arrivals'            => $item->arrivals, 
		    'duration'            => $item->duration, 
		    'duration_time_unit'  => $item->duration_time_unit, 
		    'min_pax_required'    => $item->min_pax_required, 
		    'max_pax_allowed'     => $item->max_pax_allowed, 
		    'min_children_age'    => $item->min_children_age, 
		    'max_children_age'    => $item->max_children_age, 

		    'tour_options'        => $item->tour_options, 
		    'taggings'            => $item->taggings,
		    'location'            => $item->location,
		    'zone'                => $item->zone, 
		    'category_ids'        => $item->category_ids, 
		    'facility_ids'        => $item->facility_ids, 
		    'what_to_bring_ids'   => $item->what_to_bring_ids,
		    'related_tour_ids'    => $item->related_tour_ids, 
		    'product_category_id' => $item->product_category_id, 
		    'product_category'    => $item->product_category, 
		    'provider_id'         => $item->provider_id, 
		    'default_pickup_place_id' => $item->default_pickup_place_id, 
		    'default_dropoff_place_id' => $item->default_dropoff_place_id, 

		    'id'                  => $item->_id,
		    'code'                => $item->code,
		    'name'                => $item->name,
		    'url'                 => $item->url,
		    'description'         => $item->description,
		    'itinerary'           => $item->itinerary,
		    'address'             => $item->address,
		    'url_video'           => $item->url_video,
		    'notes'               => $item->notes,
		    'policies'            => $item->policies, 
		    'seo_title'           => $item->seo_title,
		    'seo_description'     => $item->seo_description, 
		    'rate_from'           => $item->rate_from, 
		    'order'               => $item->order, 
		    'featured'            => $item->featured, 
		    // 'status'              => $item->status, 
		    'logo'                => $item->logo, 		    
		    'photos'              => $item->photos   
		);

		return $post_meta_args;

	}

	protected function get_meta_args_transportations( $item ) {

		$post_meta_args = array(
		    'transportation_id'           => $item->transportation_id,
		    'transportation_code'         => $item->transportation_code, 
		    'schedule_type'               => $item->schedule_type, 
		    'distance'                    => $item->distance, 
		    'duration'                    => $item->duration, 
		    'duration_time_unit'          => $item->duration_time_unit, 
		    'min_pax_required'            => $item->min_pax_required, 
		    'max_pax_allowed'             => $item->max_pax_allowed, 
		    'min_children_age'            => $item->min_children_age, 
		    'max_children_age'            => $item->max_children_age, 

		    'transportation_schedules'    => $item->transportation_schedules, 
		    'transportation_route_points' => $item->transportation_route_points,
		    'taggings'                    => $item->taggings,
		    'facility_ids'                => $item->facility_ids, 
		    'what_to_bring_ids'           => $item->what_to_bring_ids,
		    'departure_location'          => $item->departure_location,
		    'arrival_location'            => $item->arrival_location, 
		    'related_transportation_ids'  => $item->related_transportation_ids,
		    'product_category_id'         => $item->product_category_id, 
		    'product_category'            => $item->product_category, 
		    'provider_id'                 => $item->provider_id, 

		    'id'                          => $item->_id,
		    'code'                        => $item->code,
		    'name'                        => $item->name,
		    'url'                         => $item->url,
		    'description'                 => $item->description,
		    'itinerary'                   => $item->itinerary,
		    'address'                     => $item->address,
		    'url_video'                   => $item->url_video,
		    'notes'                       => $item->notes,
		    'policies'                    => $item->policies, 
		    'seo_title'                   => $item->seo_title,
		    'seo_description'             => $item->seo_description, 
		    'rate_from'                   => $item->rate_from, 
		    'order'                       => $item->order, 
		    'featured'                    => $item->featured, 
		    // 'status'                      => $item->status, 
		    'logo'                        => $item->logo,
		    'photos'                      => $item->photos   
		);

		return $post_meta_args;

	}

	protected function get_meta_args_hotels( $item ) {

		$post_meta_args = array(
		    'hotel_id'            => $item->hotel_id,
		    'hotel_code'          => $item->hotel_code, 
		    'geo_lat'             => $item->geo_lat, 
		    'geo_lng'             => $item->geo_lng, 
		    'geo_zoom'            => $item->geo_zoom, 
		    'stars_rating'        => $item->stars_rating, 
		    'total_rooms'         => $item->total_rooms, 
		    'check_in'            => $item->check_in, 
		    'check_out'           => $item->check_out,
		    'has_allotments'      => $item->has_allotments,
		    'min_children_age'    => $item->min_children_age,
		    'max_children_age'    => $item->max_children_age,

		    'hotel_rooms'         => $item->hotel_rooms, 
		    'taggings'            => $item->taggings,
		    'location'            => $item->location,
		    'zone'                => $item->zone, 
		    'category_ids'        => $item->category_ids, 
		    'facility_ids'        => $item->facility_ids, 
		    'related_hotel_ids'   => $item->related_hotel_ids,
		    'product_category_id' => $item->product_category_id, 
		    'product_category'    => $item->product_category, 
		    'provider_id'         => $item->provider_id, 

		    'id'                  => $item->_id,
		    'code'                => $item->code,
		    'name'                => $item->name,
		    'url'                 => $item->url,
		    'description'         => $item->description,
		    'itinerary'           => $item->itinerary,
		    'address'             => $item->address,
		    'url_video'           => $item->url_video,
		    'notes'               => $item->notes,
		    'policies'            => $item->policies, 
		    'seo_title'           => $item->seo_title,
		    'seo_description'     => $item->seo_description, 
		    'rate_from'           => $item->rate_from, 
		    'order'               => $item->order, 
		    'featured'            => $item->featured, 
		    // 'status'              => $item->status, 
		    'logo'                => $item->logo, 		    
		    'photos'              => $item->photos   
		);

		return $post_meta_args;

	}

	protected function get_meta_args_packages( $item ) {

		$post_meta_args = array(
		    'package_id'          => $item->package_id,
		    'package_code'        => $item->hotel_code, 

		    'min_pax_required'    => $item->min_pax_required, 
		    'max_pax_allowed'     => $item->max_pax_allowed, 
		    'min_children_age'    => $item->min_children_age,
		    'max_children_age'    => $item->max_children_age,

		    'package_days'        => $item->package_days, 
		    'taggings'            => $item->taggings,
		    'departure_location'  => $item->departure_location,
		    'arrival_location'    => $item->arrival_location, 
		    'category_ids'        => $item->category_ids, 
		    'facility_ids'        => $item->facility_ids, 
		    'what_to_bring_ids'   => $item->what_to_bring_ids, 
		    'related_package_ids' => $item->related_package_ids,
		    'product_category_id' => $item->product_category_id, 
		    'product_category'    => $item->product_category, 
		    'provider_id'         => $item->provider_id, 

		    'id'                  => $item->_id,
		    'code'                => $item->code,
		    'name'                => $item->name,
		    'url'                 => $item->url,
		    'description'         => $item->description,
		    'itinerary'           => $item->itinerary,
		    'url_video'           => $item->url_video,
		    'notes'               => $item->notes,
		    'policies'            => $item->policies, 
		    'seo_title'           => $item->seo_title,
		    'seo_description'     => $item->seo_description, 
		    'rate_from'           => $item->rate_from, 
		    'order'               => $item->order, 
		    'featured'            => $item->featured, 
		    'logo'                => $item->logo, 		    
		    'photos'              => $item->photos   
		);

		return $post_meta_args;

	}

	protected function get_meta_args_miscellaneous( $item ) {

		$post_meta_args = array(
		    'id'          => $item->_id, 
		    'name'        => $item->name, 
		    'description' => $item->description, 
		    'type'        => $item->type
		);

		return $post_meta_args;

	}

	protected function get_meta_args_pickup_places( $item ) {

		$post_meta_args = array(
		    'id'          => $item->_id,
		    'name'        => $item->name,
		    'address'     => $item->address,
		    'type'        => $item->type,
		    'type_id'     => $item->type_id,
		    'geo_lat'     => $item->geo_lat, 
		    'geo_lng'     => $item->geo_lng, 
		    'geo_zoom'    => $item->geo_zoom, 
		    'status'      => $item->status, 
		    'logo'        => $item->logo, 
		    'location_id' => $item->location_id,
		    'zone_id'     => $item->zone_id,
		    'zone'        => $item->the_zone
		);

		return $post_meta_args;

	}

	protected function get_meta_args_product_categories( $item ) {

		$post_meta_args = array(
		    'id'           => $item->_id,
		    'name'         => $item->name,
		    'description'  => $item->description,
		    'product_type' => $item->product_type
		);

		return $post_meta_args;

	}

	protected function get_meta_args_currencies( $item ) {

		$post_meta_args = array(
		    'id'             => $item->_id,
		    'name'           => $item->name,
		    'code'           => $item->code,
		    'symbol'         => $item->symbol, 
		    'decimal_places' => $item->decimal_places,
		    'is_default'     => $item->is_default
		);

		return $post_meta_args;

	}


	// Create post
	protected function edit_post( $option, $args ){

		$post_id = ($option === 'insert') ? wp_insert_post( $args ) : wp_update_post( $args );

		return $post_id;

	}

	// Set post meta array
	protected function set_post_meta_array( $array ) {

		if ( is_array( $array ) ) {
			$array_data = array();
			foreach ( $array as $key ) {
				array_push( $array_data, $key );
			}
		}

		return $array_data;

	}

	// Check if post meta exists
	protected function get_post_by_meta( $id, $post_type, $post_type_meta ){
		
		$args = array(
			'post_type'      => $post_type,
			'meta_key'       => $post_type_meta,
        	'posts_per_page' => -1
		);

		$query = new WP_Query( $args );

		if ( $query->have_posts() ){
			$posts = get_posts( $args );
		    foreach ( $posts as $post ) {
		        $postmeta = get_post_meta( $post->ID, $post_type_meta, true);

		        // Post exists
		        if ( $postmeta['id'] === $id){
		        	return array( get_post( $post->ID ), $postmeta ); // Return the post and associated postmeta
		        }
		    }
		}

		return null;

	}

	// Delete all post by post_type and associated post_meta
	protected function delete_posts_by_post_type( $post_types = '' ) {

		if ( $post_types == '' ) {
			$post_types = array(
				'location', 
				'tour', 
				'transportation', 
				'package', 
				'hotel',
				'countries', 
				'categories', 
				'facilities', 
				'wtobring', 
				'pickup_places', 
				'product_cat', 
				'currencies'
	    	);
		}

		foreach ( $post_types as $option ) {		
			$post_type = 'tim_'. $option; // Under 20 chars
			$post_type_meta = 'tim_'. $option .'_meta';

			$args = array(
				'post_type' => $post_type, 
				'post_status' => 'any', 
	        	'posts_per_page' => -1
			);

			$query = new WP_Query( $args );

			if ( $query->have_posts() ){
				$posts = get_posts( $args );
			    
			    foreach ( $posts as $post ) {
			        if ( wp_delete_post( $post->ID, true ) ){
			        	//delete_post_meta( $post->ID, $post_type_meta );
			        	delete_post_meta_by_key( $post_type_meta );
			        }
			    }
			}
		}

	}

	// Delete wpml icl_translation
	protected function delete_wpml_icl_translations( $post_types = '' ) {

		global $wpdb;

		if ( $post_types == '' ) {
			$post_types = array(
				'location', 
				'tour', 
				'transportation', 
				'package', 
				'hotel'
	    	);
		}

		foreach ( $post_types as $option ) {		
			$post_type = 'tim_'. $option;

			$wpml_translations = $wpdb->prefix . 'icl_translations'; // wp_icl_translations

			// Delete items that belongs to tim_
			$query = "DELETE FROM ". $wpml_translations ." WHERE element_type = 'post_". $post_type ."'";
			$wpdb->query( $query );
		}

	}


	public function open_modal() {

		check_ajax_referer( $this->plugin_nonce, 'f_nonce' );

		$option      = isset( $_POST['option'] ) ? sanitize_text_field( $_POST['option'] ) : '';
		$id          = isset( $_POST['id'] )     ? sanitize_text_field( $_POST['id'] )     : '';
		$type        = isset( $_POST['type'] )   ? sanitize_text_field( $_POST['type'] )   : '';
		$priceListId = isset( $_POST['pLId'] )   ? sanitize_text_field( $_POST['pLId'] )   : ''; // price list id for booking
		$lang        = isset( $_POST['lang'] )   ? sanitize_text_field( $_POST['lang'] )   : '';

	    $public_data = $this->get_public_data();
	    echo $public_data->get_modal_data( $option, $id, $type, $priceListId, $lang );

		wp_die();

	}

	// save cookie 30 days
	// '/' cookie is available in all website
	public function accept_secondary_price_list() {

		// If there are products in the shopping cart
		// call the API and recalculate prices, based on the secondary price list
	    $bookingId = $this->get_cart_session()['booking_id'];

	    if ( $bookingId ) {
			$url = '/bookings_client/'. $bookingId;

			$params = array(
				// 'lang' => $content_language
				'booking' => array(
		        	'id' => $bookingId
		        ),
				'chance_price_list' => true
			);

			if ( $data = $this->authenticate_api_key( $url, $params, 'PUT' ) ) {
				$currency_id = $data->currency_id;

			    $post_type_currencies = TIM_TRAVEL_MANAGER_POST_TYPE_CURRENCIES;

			    $public_data = $this->get_public_data();

			    $currency_value = $public_data->get_postmeta_item_by_value( $post_type_currencies, 'id', $currency_id, 'multiple' ); // id

				$this->start_session();
				$_SESSION['tim_currency_value'] = $currency_value;
			}
	    } else {
	    	$option = isset( $_POST['option'] ) ? sanitize_text_field( $_POST['option'] ) : '';
	    	
	    	if ( $option === 'accepted' ) {
		    	$url = '/sync/get_secondary_price_list_currency_code';

				$params = array();

		    	if ( $data = $this->authenticate_api_key( $url, $params, 'GET' ) ) {
		    		$currency_code = $data->currency_code;

				    $post_type_currencies = TIM_TRAVEL_MANAGER_POST_TYPE_CURRENCIES;

				    $public_data = $this->get_public_data();

				    $currency_value = $public_data->get_postmeta_item_by_value( $post_type_currencies, 'code', $currency_code, 'multiple' ); // code

				    // var_dump($currency_value);
				    // echo $currency_value['code'];

				    $this->start_session();
				    $_SESSION['tim_currency_value'] = $currency_value;
		    	}
	    	}
	    }

	    $option = sanitize_text_field( $_POST['option'] );

	    // This change the price list, no matter if the previous chance_price_list response is false
		setcookie( 'secondary_price_list_processed', $option, time() + (86400 * 30), '/' ); // 86400 = 1 day

		wp_die();

	}


	// BOOKING functions
	// ------------------------------------

	// Check tour rates
	public function check_tour_rates_api() {

		check_ajax_referer( $this->plugin_nonce, 'f_nonce' );

		$url = '/sync/check_tour_availability';

		if ( ! $_POST['bookingItemData']['start_date'] ){ return; }

		$params = array(
			'currentUTCDate' => sanitize_text_field( $_POST['currentUTCDate'] ), 
			'start_date' => sanitize_text_field( $_POST['bookingItemData']['start_date'] ), 
			'adults' => sanitize_text_field( $_POST['bookingItemData']['adults'] ), 
			'children' => sanitize_text_field( $_POST['bookingItemData']['children'] ), 
			'infants' => sanitize_text_field( $_POST['bookingItemData']['infants'] ), 
			'seniors' => sanitize_text_field( $_POST['bookingItemData']['seniors'] ), 
			'tour_id' => sanitize_text_field( $_POST['bookingItemData']['tour_id'] ), 
		    'tour_content_id' => sanitize_text_field( $_POST['bookingItemData']['tour_content_id'] ), 
			'lang' => sanitize_text_field( $_POST['lang'] ), 
			'currency_id' => sanitize_text_field( $_POST['currency_id'] ), 
			// 'price_list_id' => sanitize_text_field( $_POST['price_list_id'] )
		);

		if ( isset( $_POST['spl_accepted'] ) ){
			$params['spl_accepted'] = true;
		}

		if ( $_POST['bookingItemData']['booking_item_id'] ){ // edit mode
			$params['booking_id']    = sanitize_text_field( $_POST['booking_id'] );
			$params['price_list_id'] = sanitize_text_field( $_POST['price_list_id'] );

			$params['booking_item_id'] = sanitize_text_field( $_POST['bookingItemData']['booking_item_id'] );
			$params['tour_option_id'] = sanitize_text_field( $_POST['bookingItemData']['tour_option_id']);
			$params['tour_option_schedule_id'] = sanitize_text_field( $_POST['bookingItemData']['tour_option_schedule_id']);
			$params['pickup_place_id'] = sanitize_text_field( $_POST['bookingItemData']['pickup_place_id'] );
			$params['dropoff_place_id'] = sanitize_text_field( $_POST['bookingItemData']['dropoff_place_id'] );
		}

		$data = $this->authenticate_api_key( $url, $params, 'GET' );

	    echo $this->_response( $data );

	    wp_die(); // this is required to return a proper result

	}

	// Check transportation rates
	public function check_transportation_rates_api() {

		check_ajax_referer( $this->plugin_nonce, 'f_nonce' );

		$url = '/sync/check_transportation_availability';

		if ( ! $_POST['bookingItemData']['start_date'] ){ return; }

		$params = array(
			'currentUTCDate'            => sanitize_text_field( $_POST['currentUTCDate'] ), 
			'start_date'                => sanitize_text_field( $_POST['bookingItemData']['start_date'] ), 
			'adults'                    => sanitize_text_field( $_POST['bookingItemData']['adults'] ), 
			'children'                  => sanitize_text_field( $_POST['bookingItemData']['children'] ), 
			'infants'                   => sanitize_text_field( $_POST['bookingItemData']['infants'] ), 
			'seniors'                   => sanitize_text_field( $_POST['bookingItemData']['seniors'] ), 
			'transportation_id'         => sanitize_text_field( $_POST['bookingItemData']['transportation_id'] ), 
		    'transportation_content_id' => sanitize_text_field( $_POST['bookingItemData']['transportation_content_id'] ), 
			'lang'                      => sanitize_text_field( $_POST['lang'] ), 
			'currency_id'               => sanitize_text_field( $_POST['currency_id'] )
		);

		if ( isset( $_POST['spl_accepted'] ) ){
			$params['spl_accepted'] = true;
		}

		if ( $_POST['bookingItemData']['booking_item_id'] ){ // edit mode
			$params['booking_id']    = sanitize_text_field( $_POST['booking_id'] );
			$params['price_list_id'] = sanitize_text_field( $_POST['price_list_id'] );

			$params['booking_item_id']            = sanitize_text_field( $_POST['bookingItemData']['booking_item_id'] );
			$params['transportation_schedule_id'] = sanitize_text_field( $_POST['bookingItemData']['transportation_schedule_id']);
			$params['pickup_place_id']            = sanitize_text_field( $_POST['bookingItemData']['pickup_place_id'] );
			$params['dropoff_place_id']           = sanitize_text_field( $_POST['bookingItemData']['dropoff_place_id'] );
		}

		$data = $this->authenticate_api_key( $url, $params, 'GET' );

	    echo $this->_response( $data );

	    wp_die(); // this is required to return a proper result

	}

	/*public function load_transportation_to_locations() {
		check_ajax_referer( $this->plugin_nonce, 'f_nonce' );

		echo 'cool';

		$args = array(
			'post_type' => 'tim_transportation',
			'orderby' => 'title',
			'order' => 'ASC',
			'posts_per_page' => -1 // no limit per page
		);

		$query = new WP_Query( $args );

		$totalActivePosts = 0;
		
		if ( $query->have_posts() ) :
			$list = array();

			while ( $query->have_posts() ) : $query->the_post();

		// $option = sanitize_text_field( $_POST['option'] );
		// $lang = sanitize_text_field( $_POST['lang'] );
		// $policiesAccepted = sanitize_text_field( $_POST['policiesAccepted'] );

		// $bookingCart = $this->get_booking( 'draft', $lang );

		// $public_data = $this->get_public_data();
	    // echo $public_data->timPaymentForm( $bookingCart, $lang, $option, $policiesAccepted );

	    wp_die(); // this is required to return a proper result
	}*/

	// For multiple items
	public function search_transportations_rates_api(){
		check_ajax_referer( $this->plugin_nonce, 'f_nonce' );

		$url = '/sync/search_transportations_rates';

		if ( ! $_POST['start_date'] ){ return; }

		$params = array(
			'currentUTCDate'        => sanitize_text_field( $_POST['currentUTCDate'] ), 
			'departure_location_id' => sanitize_text_field( $_POST['departureLocationId'] ), 
		    'arrival_location_id'   => sanitize_text_field( $_POST['arrivalLocationId'] ), 
			'start_date'            => sanitize_text_field( $_POST['start_date'] ), 
			'departure_time'        => sanitize_text_field( $_POST['timDepartureTime'] ), 
			'adults'                => sanitize_text_field( $_POST['adults'] ), 
			'children'              => sanitize_text_field( $_POST['children'] ), 
			'infants'               => sanitize_text_field( $_POST['infants'] ), 
			'seniors'               => sanitize_text_field( $_POST['seniors'] ), 
			'lang'                  => sanitize_text_field( $_POST['lang'] ), 
			'currency_id'           => sanitize_text_field( $_POST['currency_id'] )
		);

		if ( isset( $_POST['spl_accepted'] ) ){
			$params['spl_accepted'] = true;
		}

		$data = $this->authenticate_api_key( $url, $params, 'GET' );

		$post_type = TIM_TRAVEL_MANAGER_POST_TYPE_TRANSPORTATIONS;
		$post_type_meta = $post_type .'_meta';

		$public_data = $this->get_public_data();

		$args = array(
			'post_type' => $post_type,
			'order' => $order,
			'posts_per_page' => -1 // no limit per page
		);

		$query = new WP_Query( $args );

		// We need to add the url to the transportation
		foreach ( $data->transportations as $item ) {

			while ( $query->have_posts() ) : $query->the_post();
				$original_post_id = $public_data->get_original_post_id( $post_type );
				$postmeta = get_post_meta( $original_post_id, $post_type_meta, true); // Get item post meta
		
				if ( $postmeta && $postmeta['transportation_id'] == $item->transportation_id ) {
					$item->link = get_permalink( get_the_ID() );
				}
			endwhile;

			// $item->link = 'testing';
		}

	    echo $this->_response( $data );

	    wp_die(); // this is required to return a proper result
	}

	// Check hotel rates
	public function check_hotel_availability_api() {

		check_ajax_referer( $this->plugin_nonce, 'f_nonce' );

		$url = '/sync/check_hotel_availability';

		if ( ! $_POST['bookingItemData']['start_date'] ){ return; }

		$params = array(
			'currentUTCDate'   => sanitize_text_field( $_POST['currentUTCDate'] ), 
			'start_date'       => sanitize_text_field( $_POST['bookingItemData']['start_date'] ), 
			'end_date'         => sanitize_text_field( $_POST['bookingItemData']['end_date'] ),
			'hotel_id'         => sanitize_text_field( $_POST['bookingItemData']['hotel_id'] ), 
		    'hotel_content_id' => sanitize_text_field( $_POST['bookingItemData']['hotel_content_id'] ), 
			'lang'             => sanitize_text_field( $_POST['lang'] ), 
			'currency_id'      => sanitize_text_field( $_POST['currency_id'] )
		);

		if ( isset( $_POST['spl_accepted'] ) ){
			$params['spl_accepted'] = true;
		}

		if ( $_POST['bookingItemData']['booking_item_id'] ){ // edit mode
			$params['booking_id']    = sanitize_text_field( $_POST['booking_id'] );
			$params['price_list_id'] = sanitize_text_field( $_POST['price_list_id'] );

			$params['booking_item_id'] = sanitize_text_field( $_POST['bookingItemData']['booking_item_id'] );
		}

		$data = $this->authenticate_api_key( $url, $params, 'GET' );

	    echo $this->_response( $data );

	    wp_die(); // this is required to return a proper result

	}

	public function check_package_request_api() {

		check_ajax_referer( $this->plugin_nonce, 'f_nonce' );

		$url = '/sync/check_package_request';

		if ( ! $_POST['package_id'] ){ return; }

		$options = get_option( TIM_TRAVEL_MANAGER_GENERAL_OPTIONS );
		// $emil_to = $options['package_email_notification'] ? $options['package_email_notification'] : '';

		$params = array(
			'package_id' => sanitize_text_field( $_POST['package_id'] ), 
			'package_date' => sanitize_text_field( $_POST['package_date'] ), 
			'adults' => sanitize_text_field( $_POST['adults'] ), 
		    'children' => sanitize_text_field( $_POST['children'] ), 
		    'infants' => sanitize_text_field( $_POST['infants'] ), 
		    'comments' => sanitize_text_field( $_POST['comments'] ), 
			'name' => sanitize_text_field( $_POST['name'] ),
			'email' => sanitize_email( $_POST['email'] ), 
			'phone' => sanitize_text_field( $_POST['phone'] ), 
			'email_to' => $options['package_email_notification'], 
			'lang' => sanitize_text_field( $_POST['lang'] ) 
		);

		if ($_POST['children'] > 0) {
			$params['children_ages'] = sanitize_text_field( $_POST['children_ages'] );
		}

		$data = $this->authenticate_api_key( $url, $params, 'GET' );

	    echo $this->_response( $data );

	    wp_die(); // this is required to return a proper result

	}

	// call the api only if the secondary price list was selected
	public function get_product_list_today_prices( $product_type, $product_content_id = '' ) {

		// unset( $_SESSION[$product_type] );

		// store in sessions to avoid calling the api again
		// NO, beacuse prices can be changed
		// if ( isset( $_SESSION[$product_type] ) ){
		// 	return $_SESSION[$product_type];
		// }

		$public_data = $this->get_public_data();

		$currency    = $public_data->get_currency_value( TIM_TRAVEL_MANAGER_POST_TYPE_CURRENCIES );
		$currency_id = $currency['id'];

		$params = array(
			'product_type' => $product_type, 
			'currency_id'  => $currency_id, 
			// 'spl_accepted' => $spl_accepted
		);

		$general_options = get_option( TIM_TRAVEL_MANAGER_GENERAL_OPTIONS );
			
		// $spl_accepted = false;
		if ( $general_options['secondary_price_list_enabled'] && isset( $_COOKIE['secondary_price_list_processed']) && $_COOKIE['secondary_price_list_processed'] === 'accepted' ){
			// $spl_accepted = true;
			$params['spl_accepted'] = true;
		}

		# product detail only
		if ( $product_content_id ){
			$params['product_content_id'] = $product_content_id;
		}

		$url = '/sync/get_product_list_today_prices';

		$data = $this->authenticate_api_key( $url, $params, 'GET' );

		// $_SESSION[$product_type] = $data;

	    return $data;
	    // return $this->_response( $data );
	}

	//
	public function check_document_date_exchange_rate() {

		// $bookingId = $this->get_cart_session()['booking_id'];

		// Consider storing the exchange rate in session,

		// if ( ! $_SESSION['tim_document_date_exchange'] ){

			// $booking_id = $this->get_cart_session()['booking_id'];

			$public_data = $this->get_public_data();

			$currency    = $public_data->get_currency_value( TIM_TRAVEL_MANAGER_POST_TYPE_CURRENCIES );
			$currency_id = $currency['id'];

			$currency       = $public_data->get_postmeta_item_by_value( TIM_TRAVEL_MANAGER_POST_TYPE_CURRENCIES, 'code', 'USD', 'multiple' );
			$usd_currency_id = $currency['id'];

			// $general_options = get_option( TIM_TRAVEL_MANAGER_GENERAL_OPTIONS );
			
			/*$spl_accepted = false;
			if ( $general_options['secondary_price_list_enabled'] && isset( $_COOKIE['secondary_price_list_processed']) && $_COOKIE['secondary_price_list_processed'] === 'accepted' ){
				$spl_accepted = true;
			}*/

			$url = '/sync/check_document_date_exchange_rate';

			$params = array(
				'currency_id'     => $currency_id, 
				'usd_currency_id' => $usd_currency_id, 
				// 'booking_id'      => $booking_id, 
				// 'spl_accepted'    => $spl_accepted 
			);

			$data = $this->authenticate_api_key( $url, $params, 'GET' );

			// $this->start_session();
			// $_SESSION['tim_document_date_exchange'] = $data;

		    return $data;
		// }

		// return false;

	}

	public function get_cart_session(){

		$this->start_session();
		return isset($_SESSION['tim_cart_session']) ? $_SESSION['tim_cart_session'] : array();

	}

	// Get booking
	public function get_booking( $status = 'draft', $content_language, $bookingId = '', $bookingNumber = '' ) {

		if ( $status == 'draft' ) {
			$bookingId = $this->get_cart_session()['booking_id'];

			if ( $bookingId ) {
				$url = '/bookings_client/'. $bookingId .'/show_draft_booking';

				$params = array(
					'show' => 'pm', 
					'lang' => $content_language
				);
			} else {
				// Clear the session - this is a fix beacuse the unset after the order complete is not working remotely
				unset( $_SESSION['tim_cart_session'] );
				return false;
			}
		} else {
			$url = '/bookings_client/'. $bookingId; // check json data, limit only client info

			$params = array(
			    'booking_number' => $bookingNumber, 
			    'lang'           => $content_language
			);

			if ( $_SESSION['tim_client_session'] ){
				$params['logged'] = true;
			}
		}

		$data = $this->authenticate_api_key( $url, $params, 'GET' );
		if ( $data->id ) {
	    	return $data;
	    }

	    if ( $bookingId ){
	    	sleep(5); // In case of server restart, wait x sec and re-try
	    	
	    	$data = $this->authenticate_api_key( $url, $params, 'GET' );
	    	if ( $data->id ) {
	    		return $data;
	    	}
	    }

	    // if ( $action !== 'paid' ){
	    	// Clear the session unless act=pay
			unset( $_SESSION['tim_cart_session'] );
		// }

		return false;

	}

	public function delete_booking_api() {

		$bookingId = sanitize_text_field( $_POST['bookingId'] );

		$url = '/bookings_client/'. $bookingId;

		$params = array(
			'lang' => sanitize_text_field( $_POST['lang'] ), 
		);

	    if ( $this->authenticate_api_key( $url, $params, 'DELETE' ) ){
	    	$this->start_session(); // !important in ajax response
	    	unset( $_SESSION['tim_cart_session'] );

	    	// maybe if the warning was not responded, we show and alert in the top header
	    	if ( $_POST['deletedBySystem'] ){
	    		$_SESSION['tim_booking_deleted'] = true;
	    	}

	    	return true;
	    }

		return false;

	}

	// Get booking item
	public function get_booking_item( $bookingItemId, $content_language ) {

		$bookingId = $this->get_cart_session()['booking_id'];

		if ( $bookingId && $bookingItemId ){
			$url = '/bookings_client/'. $bookingId .'/booking_items_client/'. $bookingItemId;

			$params = array('lang' => $content_language);

			$data = $this->authenticate_api_key( $url, $params, 'GET' );

		    return $data;
		}

		return false;

	}

	// Add item to order
	public function add_item_to_order_api() {

		check_ajax_referer( $this->plugin_nonce, 'f_nonce' );

		if ( ! $_POST['bookingItemData']['start_date'] ){ return; }

		$bookingId = $this->get_cart_session()['booking_id'];

		if ( !$bookingId ){ // Create the booking and add first item
			$url = '/bookings_client';
		} else { // Add item to booking and update booking totals
			$url = '/bookings_client/'. $bookingId .'/booking_items_client';
		}

		$bookingType = sanitize_text_field( $_POST['bookingItemData']['booking_type'] );

		$bookingItemParams = array(
			'booking_type' => $bookingType, 
			'start_date' => sanitize_text_field( $_POST['bookingItemData']['start_date'] ), 
			'adults' => sanitize_text_field( $_POST['bookingItemData']['adults'] ), 
			'quantity' => sanitize_text_field( $_POST['bookingItemData']['quantity'] ), 
			'children' => sanitize_text_field( $_POST['bookingItemData']['children'] ), 
			'infants' => sanitize_text_field( $_POST['bookingItemData']['infants'] ), 
			'seniors' => sanitize_text_field( $_POST['bookingItemData']['seniors'] ), 
			'provider_id' => sanitize_text_field( $_POST['bookingItemData']['provider_id'] ),
			'price_list_id' => sanitize_text_field( $_POST['bookingItemData']['priceListIdByProvider'] ), 

			'item_price' => sanitize_text_field( $_POST['bookingItemData']['item_price'] ),
			'base_price' => sanitize_text_field( $_POST['bookingItemData']['base_price'] ),
			'commission_price_percentage' => sanitize_text_field( $_POST['bookingItemData']['commission_price_percentage'] ),
			'commission_price' => sanitize_text_field( $_POST['bookingItemData']['commission_price'] ),
			'net_price' => sanitize_text_field( $_POST['bookingItemData']['net_price'] ),
			// 'discount_price_percentage'        => sanitize_text_field( $_POST['bookingItemData']['discount_price_percentage'] ),
			'discount_price' => sanitize_text_field( $_POST['bookingItemData']['discount_price'] ),
			'subtotal_price' => sanitize_text_field( $_POST['bookingItemData']['subtotal_price'] ),
			'tax_price_percentage' => sanitize_text_field( $_POST['bookingItemData']['tax_price_percentage'] ),
			'tax_price' => sanitize_text_field( $_POST['bookingItemData']['tax_price'] ), 
			'tax_exoneration_price_percentage' => sanitize_text_field( $_POST['bookingItemData']['tax_exoneration_price_percentage'] ),
			'tax_exoneration_price' => sanitize_text_field( $_POST['bookingItemData']['tax_exoneration_price'] ), 
			'net_tax_price_percentage' => sanitize_text_field( $_POST['bookingItemData']['net_tax_price_percentage'] ),
			'net_tax_price' => sanitize_text_field( $_POST['bookingItemData']['net_tax_price'] ), 
			'total_price' => sanitize_text_field( $_POST['bookingItemData']['total_price'] ), 
			'status' => 'oncart'
		);

		if ( isset( $_POST['bookingItemData']['sale_taxes'] ) ){
			$sale_taxes = [];
			foreach ( $_POST['bookingItemData']['sale_taxes'] as $tax ) {
				array_push( $sale_taxes, $tax );
			}

			$bookingItemParams['transaction_taxes'] = $sale_taxes;
		}

		switch ( $bookingType ) {
			case 'tour':
				$bookingTypeParams = array(
				    'tour_id' => sanitize_text_field( $_POST['bookingItemData']['tour_id'] ), 
					'tour_content_id' => sanitize_text_field( $_POST['bookingItemData']['tour_content_id'] ), 
					'tour_option_id' => sanitize_text_field( $_POST['bookingItemData']['tour_option_id'] ), 
					'tour_option_schedule_id' => sanitize_text_field( $_POST['bookingItemData']['tour_option_schedule_id'] ), 
					'departure_time' => sanitize_text_field( $_POST['bookingItemData']['departure_time'] ),
					'children_ages' => sanitize_text_field( $_POST['bookingItemData']['children_ages'] )
				);

				if ( isset( $_POST['bookingItemData']['pickup_place_id'] ) ){
					$bookingTypeParams['pickup_place_id'] = sanitize_text_field( $_POST['bookingItemData']['pickup_place_id'] );
				}

				if ( isset( $_POST['bookingItemData']['dropoff_place_id'] ) ){
					$bookingTypeParams['dropoff_place_id'] = sanitize_text_field( $_POST['bookingItemData']['dropoff_place_id'] );
				}
			break;

			case 'transportation':
				$bookingTypeParams = array(
				    'transportation_id' => sanitize_text_field( $_POST['bookingItemData']['transportation_id'] ), 
					'transportation_content_id' => sanitize_text_field( $_POST['bookingItemData']['transportation_content_id'] ), 
					'transportation_schedule_id' => sanitize_text_field( $_POST['bookingItemData']['transportation_schedule_id'] ), 
					'departure_time' => sanitize_text_field( $_POST['bookingItemData']['departure_time'] )
				);
			break;

			case 'hotel':
				$bookingItemHotelDates = array();
				
				foreach ( $_POST['bookingItemData']['booking_item_hotel_dates'] as $item ) {
					array_push( $bookingItemHotelDates, $item );
				}

				$bookingTypeParams = array(
				    'hotel_id' => sanitize_text_field( $_POST['bookingItemData']['hotel_id'] ), 
					'hotel_content_id' => sanitize_text_field( $_POST['bookingItemData']['hotel_content_id'] ), 
					'end_date' => sanitize_text_field( $_POST['bookingItemData']['end_date'] ), 
					'total_rooms' => sanitize_text_field( $_POST['bookingItemData']['total_rooms'] ), 
					'booking_item_hotel_dates' => $bookingItemHotelDates
				);
			break;
		}

		$bookingItem = array_merge( $bookingItemParams, $bookingTypeParams );

		$params = array(
	        'booking' => array(
	        	// 'booking_language' => # remove when removed from api # consider saving 'en' in the booking model
	        	'currency_id' => sanitize_text_field( $_POST['currency_id'] ),
	        	'exchange_rate_value' => sanitize_text_field( $_POST['bookingItemData']['exchange_rate_value'] ), 
	        	// 'spl_accepted' => sanitize_text_field( $_POST['spl_accepted'] ),
	        	'status' => 'oncart'
	        ), 
	        'booking_item' => $bookingItem, //'booking_items' => array($bookingItem), // send as array
			'lang' => sanitize_text_field( $_POST['lang'] )
		);

		// if ( isset( $_POST['spl_accepted'] ) ){
		if ( isset( $_POST['bookingItemData']['spl_accepted'] ) ) {
			$params['spl_accepted'] = true;
		}

		$data = $this->authenticate_api_key( $url, $params, 'POST' ); //$method

    	if ( $data->id ) {
	    	$this->start_session();
	    	
	    	$_SESSION['tim_cart_session'] = array(
				'booking_id' => $data->id,
		        'total_items' => count($data->booking_items)
			);
    	}

		wp_die(); // this is required to return a proper result

	}

	// Update item from order
	public function update_item_from_order_api() {

		check_ajax_referer( $this->plugin_nonce, 'f_nonce' );

		if ( ! $_POST['bookingItemData']['start_date'] ){ return; }

		$bookingId     = $this->get_cart_session()['booking_id'];
		$bookingItemId = sanitize_text_field( $_POST['bookingItemData']['booking_item_id'] );

		$url = '/bookings_client/'. $bookingId .'/booking_items_client/'. $bookingItemId;

		$bookingType = sanitize_text_field( $_POST['bookingItemData']['booking_type'] );

		$bookingItemParams = array(
			'booking_type' => $bookingType, 
			'start_date' => sanitize_text_field( $_POST['bookingItemData']['start_date']), 
			'adults' => sanitize_text_field( $_POST['bookingItemData']['adults']), 
			'children' => sanitize_text_field( $_POST['bookingItemData']['children']), 
			'infants' => sanitize_text_field( $_POST['bookingItemData']['infants']), 
			'seniors' => sanitize_text_field( $_POST['bookingItemData']['seniors']), 

			'item_price' => sanitize_text_field( $_POST['bookingItemData']['item_price'] ),
			'base_price' => sanitize_text_field( $_POST['bookingItemData']['base_price'] ),
			'commission_price_percentage'      => sanitize_text_field( $_POST['bookingItemData']['commission_price_percentage'] ),
			'commission_price' => sanitize_text_field( $_POST['bookingItemData']['commission_price'] ),
			'net_price' => sanitize_text_field( $_POST['bookingItemData']['net_price'] ),
			// 'discount_price_percentage' => sanitize_text_field( $_POST['bookingItemData']['discount_price_percentage'] ),
			'discount_price' => sanitize_text_field( $_POST['bookingItemData']['discount_price'] ),
			'subtotal_price' => sanitize_text_field( $_POST['bookingItemData']['subtotal_price'] ),
			'tax_price_percentage' => sanitize_text_field( $_POST['bookingItemData']['tax_price_percentage'] ),
			'tax_price' => sanitize_text_field( $_POST['bookingItemData']['tax_price'] ), 
			'tax_exoneration_price_percentage' => sanitize_text_field( $_POST['bookingItemData']['tax_exoneration_price_percentage'] ),
			'tax_exoneration_price' => sanitize_text_field( $_POST['bookingItemData']['tax_exoneration_price'] ), 
			'net_tax_price_percentage' => sanitize_text_field( $_POST['bookingItemData']['net_tax_price_percentage'] ),
			'net_tax_price' => sanitize_text_field( $_POST['bookingItemData']['net_tax_price'] ), 
			'total_price' => sanitize_text_field( $_POST['bookingItemData']['total_price'] )
		);

		if ( $_POST['bookingItemData']['pickup_place_id'] ){
			$bookingItemParams['pickup_place_id'] = $_POST['bookingItemData']['pickup_place_id'];
			$bookingItemParams['pickup_price'] = sanitize_text_field( $_POST['bookingItemData']['pickup_price']);
		}

		if ( $_POST['bookingItemData']['dropoff_place_id'] ){
			$bookingItemParams['dropoff_place_id'] = $_POST['bookingItemData']['dropoff_place_id'];
			$bookingItemParams['dropoff_price'] = sanitize_text_field( $_POST['bookingItemData']['dropoff_price']);
		}

		$bookingTypeParams = array();

		switch ( $bookingType ) {
			case 'tour':
				$bookingTypeParams['tour_id'] = sanitize_text_field( $_POST['bookingItemData']['tour_id']);
				$bookingTypeParams['tour_content_id'] = sanitize_text_field( $_POST['bookingItemData']['tour_content_id']);
				$bookingTypeParams['tour_option_id'] = sanitize_text_field( $_POST['bookingItemData']['tour_option_id']);
				$bookingTypeParams['tour_option_schedule_id'] = sanitize_text_field( $_POST['bookingItemData']['tour_option_schedule_id']);
				$bookingTypeParams['departure_time'] = sanitize_text_field( $_POST['bookingItemData']['departure_time']);
				// $bookingTypeParams['children_ages'] = $_POST['children_ages'] ? sanitize_text_field( $_POST['bookingItemData']['children_ages'] ) : '';
				$bookingTypeParams['children_ages'] = sanitize_text_field( $_POST['bookingItemData']['children_ages'] );
			break;

			case 'transportation':
				$bookingTypeParams['transportation_id'] = sanitize_text_field( $_POST['bookingItemData']['transportation_id']);
				$bookingTypeParams['transportation_content_id'] = sanitize_text_field( $_POST['bookingItemData']['transportation_content_id']);
				$bookingTypeParams['transportation_schedule_id'] = sanitize_text_field( $_POST['bookingItemData']['transportation_schedule_id']);
				$bookingTypeParams['departure_time'] = sanitize_text_field( $_POST['bookingItemData']['departure_time']);
			break;

			case 'hotel':
				$bookingItemHotelDates = array();
				foreach ( $_POST['bookingItemData']['booking_item_hotel_dates'] as $item ) {
					array_push( $bookingItemHotelDates, $item );
				}

				$bookingTypeParams = array(
				    'hotel_id' => sanitize_text_field( $_POST['bookingItemData']['hotel_id'] ), 
					'hotel_content_id' => sanitize_text_field( $_POST['bookingItemData']['hotel_content_id'] ), 
					'end_date' => sanitize_text_field( $_POST['bookingItemData']['end_date'] ), 
					'total_rooms' => sanitize_text_field( $_POST['bookingItemData']['total_rooms'] ), 
					'booking_item_hotel_dates' => $bookingItemHotelDates
				);
			break;
		}

		$bookingItem = array_merge( $bookingItemParams, $bookingTypeParams );

		$lang = sanitize_text_field( $_POST['lang'] );

		$params = array(
	        'booking_item' => $bookingItem, // send as object
			'lang' => $lang
		);

		if ( isset( $_POST['bookingItemData']['spl_accepted'] ) ){
			$params['spl_accepted'] = true;
		}

		$data = $this->authenticate_api_key( $url, $params, 'PUT' );

    	$public_data = $this->get_public_data();
    	echo $public_data->timCartDetail( $data, $lang );

		wp_die(); // this is required to return a proper result

	}

	// Apply places to item order
	public function apply_places_to_order_api() {

		check_ajax_referer( $this->plugin_nonce, 'f_nonce' );

		$bookingId     = $this->get_cart_session()['booking_id'];
		$bookingItemId = sanitize_text_field( $_POST['bookingItemData']['booking_item_id'] );

		$url = '/bookings_client/'. $bookingId .'/booking_items_client/'. $bookingItemId .'/apply_places';

		$bookingItemParams = array(
			'pickup_place_id'  => sanitize_text_field( $_POST['bookingItemData']['pickup_place_id']), 
			'dropoff_place_id' => sanitize_text_field( $_POST['bookingItemData']['dropoff_place_id'])
		);

		$lang = sanitize_text_field( $_POST['lang'] );

		$params = array(
	        'booking_item' => $bookingItemParams,
			'lang' => $lang
		);

		$data = $this->authenticate_api_key( $url, $params, 'PUT' );

    	$public_data = $this->get_public_data();
    	echo $public_data->timCartDetail( $data, $lang );

		wp_die(); // this is required to return a proper result

	}

	// Remove item from order
	public function remove_item_from_order_api() {

		check_ajax_referer( $this->plugin_nonce, 'f_nonce' );

		$bookingId = $this->get_cart_session()['booking_id'];
		$bookingItemId = sanitize_text_field( $_POST['bookingItemId'] );

		$url = '/bookings_client/'. $bookingId .'/booking_items_client/'. $bookingItemId;

		$lang = sanitize_text_field( $_POST['lang'] );

		$params = array(
			'lang' => $lang
		);

		$data = $this->authenticate_api_key( $url, $params, 'DELETE' );

	    $_SESSION['tim_cart_session']['total_items'] = count($data->booking_items);

	    $public_data = $this->get_public_data();
	    echo $public_data->timCartDetail( $data, $lang );

		wp_die(); // this is required to return a proper result

	}

	// Apply discount coupon to order
	public function apply_discount_coupon_to_order_api() {

		check_ajax_referer( $this->plugin_nonce, 'f_nonce' );

		$booking_id  = sanitize_text_field( $_POST['booking_id'] );
		$coupon_code = sanitize_text_field( $_POST['coupon_code'] );
		$lang        = sanitize_text_field( $_POST['lang'] );

		$url = '/bookings_client/'. $booking_id .'/apply_discount_coupon';

		$params = array(
			'coupon_code' => $coupon_code, 
			'lang' => $lang
		);

		$data = $this->authenticate_api_key( $url, $params, 'PUT' );

		if ( $data->id ){
			$public_data = $this->get_public_data();
    		echo $public_data->timCheckoutTotals( $data );
		} else {
			echo 'error';
		}

		wp_die(); // this is required to return a proper result

	}

	public function delete_discount_coupon_to_order_api() {

		check_ajax_referer( $this->plugin_nonce, 'f_nonce' );

		$booking_id = sanitize_text_field( $_POST['booking_id'] );
		$lang       = sanitize_text_field( $_POST['lang'] );

		$url = '/bookings_client/'. $booking_id .'/delete_discount_coupon';

		$params = array(
			'lang' => $lang
		);

		$data = $this->authenticate_api_key( $url, $params, 'PUT' );

		if ( $data->id ) {
			$public_data = $this->get_public_data();
    		echo $public_data->timCheckoutTotals( $data );
		} else {
			echo 'error';
		}

		wp_die(); // this is required to return a proper result

	}

	// Apply places to item order
	public function get_booking_totals_api() {

		check_ajax_referer( $this->plugin_nonce, 'f_nonce' );

		// $this->bookingCart = $this->plugin_api->get_booking( 'draft', $this->content_language );

		$booking_id = sanitize_text_field( $_POST['booking_id'] );
		$lang = sanitize_text_field( $_POST['lang'] );

		// $bookingId     = $this->get_cart_session()['booking_id'];

		$url = '/bookings_client/'. $booking_id .'/show_draft_booking';

		$params = array(
			'lang' => $lang
		);

		$data = $this->authenticate_api_key( $url, $params, 'GET' );

    	$public_data = $this->get_public_data();
    	echo $public_data->timCheckoutTotals( $data, $lang );

		wp_die(); // this is required to return a proper result

	}

	// load_payment_form
	public function load_payment_form() {

		check_ajax_referer( $this->plugin_nonce, 'f_nonce' );

		$option = sanitize_text_field( $_POST['option'] );
		$lang = sanitize_text_field( $_POST['lang'] );
		$policiesAccepted = sanitize_text_field( $_POST['policiesAccepted'] );

		$bookingCart = $this->get_booking( 'draft', $lang );

		$public_data = $this->get_public_data();
	    echo $public_data->timPaymentForm( $bookingCart, $lang, $option, $policiesAccepted );

	    wp_die(); // this is required to return a proper result

	}	

	// For BAC and BCR
	public function process_ecommerce_payment() {

		$this->start_session();

		$_SESSION['tim_booking_client_session'] = $this->set_client_detail_params();
		$_SESSION['tim_booking_client_session']['payment_gateway'] = sanitize_text_field( $_POST['payment_gateway'] );
		$_SESSION['tim_booking_client_session']['pg']              = sanitize_text_field( $_POST['pg'] );

		if ( $_POST['client_id'] AND $_POST['client_id'] !== '' ){ // Logged user made the booking
			$_SESSION['tim_booking_client_session']['client_id'] = $_POST['client_id'];
		}

		return true;

	}

	// Pay later only - at the moment
	public function process_pay_later_payment() {

		check_ajax_referer( $this->plugin_nonce, 'f_nonce' );

		$params                    = $this->set_client_detail_params();
		$params['payment_gateway'] = sanitize_text_field( $_POST['payment_gateway'] );
		$params['status']          = 'processing';

		$url = '/booking_payments/complete_order';

		$data = $this->authenticate_api_key( $url, $params, 'POST' );

		if ( ! $data->errors ){
			$this->order_completed( $data );
		}

		echo $this->_response( $data );

		wp_die(); // this is required to return a proper result

	}

	public function complete_order_payment( $params ){

		$params['status'] = 'confirmed';

		$url = '/booking_payments/complete_order';

		$data = $this->authenticate_api_key( $url, $params, 'POST' );

		if ( ! $data->errors ){
			$this->order_completed( $data );

			return true;
		}

		return false;

		// wp_die(); // this is required to return a proper result

	}

	// Paypal order competed - Sessions not working here *** only for ssl in server
	public function paypal_order_completed() {

		check_ajax_referer( $this->plugin_nonce, 'f_nonce' );

		$this->order_completed( $_POST['bookingItemData'] );

	    wp_die(); // this is required to return a proper result
	}

	// Order completed
	private function order_completed( $data ) { // public

		$this->register_guest_session_data( $data );

		// Clear the session - not working remotely
		unset( $_SESSION['tim_cart_session'] );

	}

	public function verify_guest_email_api(){

		check_ajax_referer( $this->plugin_nonce, 'f_nonce' );

		if ( ! $_POST['email'] ){ return; }

		$params = array(
	        'email' => sanitize_email( $_POST['email'] )
		);

		$url = '/bookings_client/verify_guest_email';

		$data = $this->authenticate_api_key( $url, $params, 'GET' );

		echo $this->_response( $data );

		wp_die(); // this is required to return a proper result

	}

	public function set_client_detail_params(){

		$params = array(
			'id'            => sanitize_text_field( $_POST['id'] ),
			'name'          => sanitize_text_field( $_POST['name'] ),
			'last_name'     => sanitize_text_field( $_POST['last_name'] ),
	        'email'         => sanitize_email( $_POST['email'] ), 
	        'country_id'    => sanitize_text_field( $_POST['country_id'] ), 
	        'phone_number'  => sanitize_text_field( $_POST['phone_number'] ),
	        'phone_code'    => sanitize_text_field( $_POST['phone_code'] ), 
	        // 'tax_id_code'   => sanitize_text_field( $_POST['tax_id_code'] ), 
	        // 'tax_id_number' => sanitize_text_field( $_POST['tax_id_number'] ), 
	        'notes'         => sanitize_text_field( $_POST['notes'] ), 
	        'lang'          => sanitize_text_field( $_POST['lang'] )
		);

		if ( $_POST['client_id'] AND $_POST['client_id'] !== '' ){ // Logged user made the booking
			$params['client_id'] = $_POST['client_id'];
		}

		return $params;

	}

	// Load pickup places to item order
	public function load_pickup_places_to_order_item(){

		check_ajax_referer( $this->plugin_nonce, 'f_nonce' );

		$public_data  = $this->get_public_data();
		$pickupPlaces = $public_data->get_postmeta_list( TIM_TRAVEL_MANAGER_POST_TYPE_PICKUP_PLACES );

		# Load pickup places onle for the zone_ids

		echo $this->_response( $pickupPlaces );

		wp_die(); // this is required to return a proper result

	}

	// Verify booking by ID and number
	public function verify_order_api( ){
		
		check_ajax_referer( $this->plugin_nonce, 'f_nonce' );

		$url = '/bookings_client/verify_booking';

		if ( ! $_POST['bookingNumber'] ){ return; }
		if ( ! $_POST['clientEmail'] ){ return; }

		$params = array(
			'booking_number'=> sanitize_text_field( $_POST['bookingNumber'] ),
			'client_email' => sanitize_email( $_POST['clientEmail'] ),
			'lang' => sanitize_text_field( $_POST['lang'] )
		);

		$data = $this->authenticate_api_key( $url, $params, 'POST' );

		if ( ! $data->errors ){
    		$this->register_guest_session_data( $data );
    	}

		echo $this->_response( $data );

		wp_die(); // this is required to return a proper result

	}

	public function get_cancellation_policies( $content_language ) {
		$url = '/sync/cancellation_policies';

		$params = array(
		    'lang' => $content_language
		);

		$data = $this->authenticate_api_key( $url, $params, 'GET' );

		return $data;
	}


	// -- LOGIN / SIGNUP functions --//

	// Load login form
	public function load_login_form(){

		check_ajax_referer( $this->plugin_nonce, 'f_nonce' );

	    $public_data = $this->get_public_data();
	    echo $public_data->timLoginForm();

		wp_die(); // this is required to return a proper result

	}

	// Create client login api
	public function create_client_login_api() {

		check_ajax_referer( $this->plugin_nonce, 'f_nonce' );

		$url = '/sessions/create_client_login';

		if ( ! $_POST['email'] ){ return; }
		if ( ! $_POST['password'] ){ return; }

		$params = array(
	        'email'    => sanitize_email( $_POST['email'] ),
	        'password' => sanitize_text_field( $_POST['password'] ),
			'lang'     => sanitize_text_field( $_POST['lang'] )
		);

		$data = $this->authenticate_api_key( $url, $params, 'POST' );

		if ( !$data->errors ){
    		$this->register_client_session_data( $data );
    	}

    	echo $this->_response( $data );

		wp_die(); // this is required to return a proper result

	}

	// Client logout
	public function client_logout() {

		check_ajax_referer( $this->plugin_nonce, 'f_nonce' );

		$this->start_session(); # why ?

		$option = ( $_SESSION['tim_client_session'] ) ? 'client' : 'guest' ;

		// Clear the session
		unset( $_SESSION['tim_client_session'] );
		unset( $_SESSION['tim_guest_session'] );
		unset( $_SESSION['tim_verify_order'] );

		echo true;

		wp_die(); // this is required to return a proper result

	}

	// Load signup form
	public function load_signup_form(){

		check_ajax_referer( $this->plugin_nonce, 'f_nonce' );

		$lang = sanitize_text_field( $_POST['lang'] );

		$public_data = $this->get_public_data();
	    echo $public_data->timSignupForm( $lang );

		wp_die(); // this is required to return a proper result

	}

	// Create client signup api
	public function create_client_signup_api() {

		check_ajax_referer( $this->plugin_nonce, 'f_nonce' );

		$url = '/sessions/create_client_signup';

		if ( ! $_POST['name'] ){ return; }
		if ( ! $_POST['email'] ){ return; }

		$clientParams = array(
	        'name'          => sanitize_text_field( $_POST['name'] ),
	        'last_name'     => sanitize_text_field( $_POST['last_name'] ),
	        'tax_id_code'   => sanitize_text_field( $_POST['tax_id_code'] ), 
			'tax_id_number' => sanitize_text_field( $_POST['tax_id_number'] ), 
	        'email'         => sanitize_email( $_POST['email'] ),
			'phone_number'  => sanitize_text_field( $_POST['phone_number'] ), 
			'phone_code'    => sanitize_text_field( $_POST['phone_code'] ), 
			'country_id'    => sanitize_text_field( $_POST['country_id'] ), 
			'password'      => sanitize_text_field( $_POST['password'] )
		);

		$params = array(
	        'client' => $clientParams,
			'lang'   => sanitize_text_field( $_POST['lang'] )
		);

		$data = $this->authenticate_api_key( $url, $params, 'POST' );

    	echo $this->_response( $data );

    	if ( !$data->errors ){
    		$this->register_client_session_data( $data );
    	}

	    wp_die(); // this is required to return a proper result

	}

	// Load password recovery form
	public function load_password_recovery_form(){

		check_ajax_referer( $this->plugin_nonce, 'f_nonce' );

	    $public_data = $this->get_public_data();
	    echo $public_data->timPasswordRecoveryForm();

		wp_die(); // this is required to return a proper result

	}

	// Create client password recovery api
	public function create_client_password_api() {

		check_ajax_referer( $this->plugin_nonce, 'f_nonce' );

		$url = '/password_recovery/create_client_password';

		if ( ! $_POST['email'] ){ return; }

		$params = array(
	        'email' => sanitize_email( $_POST['email'] ),
			'lang'  => sanitize_text_field( $_POST['lang'] )
		);

		$data = $this->authenticate_api_key( $url, $params, 'POST' );

	    echo $this->_response( $data );

		wp_die(); // this is required to return a proper result

	}

	public function load_edit_password_form(){

		check_ajax_referer( $this->plugin_nonce, 'f_nonce' );

	    $public_data = $this->get_public_data();
	    echo $public_data->timEditPasswordForm();

		wp_die(); // this is required to return a proper result

	}

	// Update client password recovery api
	public function update_client_password_api() {

		check_ajax_referer( $this->plugin_nonce, 'f_nonce' );

		$url = '/password_recovery/update_client_password';

		if ( ! $_POST['email'] ){ return; }

		$params = array(
			'email'    => sanitize_email( $_POST['email'] ),
	        'code'     => sanitize_text_field( $_POST['code'] ),
	        'password' => sanitize_text_field( $_POST['password'] ),
			'lang'     => sanitize_text_field( $_POST['lang'] )
		);

		$data = $this->authenticate_api_key( $url, $params, 'PUT' );

	    echo $this->_response( $data );

		wp_die(); // this is required to return a proper result

	}

	// Load edit profile
	public function load_client_profile(){

		check_ajax_referer( $this->plugin_nonce, 'f_nonce' );

		$lang = sanitize_text_field( $_POST['lang'] );

		$this->start_session();
		$clientSession = $_SESSION['tim_client_session'];

		$public_data = $this->get_public_data();
		
	    echo $public_data->timClientProfile( $clientSession, $lang );
	    
		wp_die(); // this is required to return a proper result

	}

	// Update client profile recovery api
	public function update_client_profile_api() {

		check_ajax_referer( $this->plugin_nonce, 'f_nonce' );

		$url = '/sessions/update_client_profile';

		if ( ! $_POST['name'] ){ return; }
		if ( ! $_POST['email'] ){ return; }

		$clientParams = array(
			'name'          => sanitize_text_field( $_POST['name'] ),
			'last_name'     => sanitize_text_field( $_POST['last_name'] ),
			'tax_id_code'   => sanitize_text_field( $_POST['tax_id_code'] ), 
			'tax_id_number' => sanitize_text_field( $_POST['tax_id_number'] ),
			'email'         => sanitize_email( $_POST['email'] ),
	        'phone_number'  => sanitize_text_field( $_POST['phone_number'] ), 
			'phone_code'    => sanitize_text_field( $_POST['phone_code'] ), 
			'country_id'    => sanitize_text_field( $_POST['country_id'] )
		);

		$params = array(
			'id'     => sanitize_text_field( $_POST['id'] ),
	        'client' => $clientParams,
			'lang'   => sanitize_text_field( $_POST['lang'] )
		);

		$data = $this->authenticate_api_key( $url, $params, 'PUT' );

	    echo $this->_response( $data );

	    if ( !$data->errors ){
			$this->register_client_session_data( $data );
		}

		wp_die(); // this is required to return a proper result

	}

	// Update client password api
	public function update_client_profile_password_api() {

		check_ajax_referer( $this->plugin_nonce, 'f_nonce' );

		$url = '/sessions/update_client_password';

		if ( ! $_POST['password'] ){ return; }

		$params = array(
			'id'       => sanitize_text_field( $_POST['id'] ),
	        'password' => sanitize_text_field( $_POST['password'] ),
			'lang'     => sanitize_text_field( $_POST['lang'] )
		);

		$data = $this->authenticate_api_key( $url, $params, 'PUT' );

	    echo $this->_response( $data );

		wp_die(); // this is required to return a proper result

	}

	// Load list orders
	public function list_orders_api(){

		check_ajax_referer( $this->plugin_nonce, 'f_nonce' );

		$url = '/bookings_client';

		$this->start_session();
		$clientSession = $_SESSION['tim_client_session'];		

		$params = array(
			'client_id'    => $clientSession['id'],
			'client_email' => $clientSession['email'],
			'lang'         => sanitize_text_field( $_POST['lang'] )
		);

		$data = $this->authenticate_api_key( $url, $params, 'GET' );

	    $public_data = $this->get_public_data();
	    echo $public_data->timListOrders( $data );

		wp_die(); // this is required to return a proper result

	}

	// Register client session data
	public function register_client_session_data( $data ) {

		$this->start_session();
		
		$_SESSION['tim_client_session'] = array(
			'id'            => $data->id,
	        'name'          => $data->name,
	        'last_name'     => $data->last_name, 
	        'tax_id_code'   => $data->tax_id_code, 
			'tax_id_number' => $data->tax_id_number, 
	        'email'         => $data->email,
			'phone_number'  => $data->phone_number, 
			'phone_code'    => $data->country->phone_code, 
			'country_id'    => $data->country_id
		);
		
	}

	// Register guest session data
	public function register_guest_session_data( $data ) {

		$this->start_session();

		if ( isset($_SESSION['tim_client_session']) ){
			return;
		}
		
		$_SESSION['tim_guest_session'] = array(
	        'name'      => $data->name || 'Name',
	        'last_name' => $data->last_name || 'Last name',
	        'email'     => $data->email || 'Email'
		);
		
	}

	// Set currency value after on change option - Local data or Api call
	public function set_currency_value() {

		$currency_code = sanitize_text_field( $_POST['currency_code'] );

	    $post_type_currencies = TIM_TRAVEL_MANAGER_POST_TYPE_CURRENCIES;

	    $public_data = $this->get_public_data();

	    $currency_value = $public_data->get_postmeta_item_by_value( $post_type_currencies, 'code', $currency_code, 'multiple' );

	    $this->start_session();
	    $_SESSION['tim_currency_value'] = $currency_value;

	    // If there is a booking, call the API and apply the exchange rate
	    $bookingId = $this->get_cart_session()['booking_id'];

	    if ( $bookingId ) {
			$url = '/bookings_client/'. $bookingId;

			$params = array(
				// 'lang'         => $content_language
				'booking' => array(
		        	'currency_id' => $currency_value['id']
		        ),
				'apply_conversion' => true
			);

			$this->authenticate_api_key( $url, $params, 'PUT' );
	    }

		wp_die(); // this is required to return a proper result

	}


	// -- AJAX Call functions --//

	// Start the session
	private function start_session(){

		// if ( ! session_id() ){ // problems with ajax sometimes
			session_start(); // problems in qa after payment - PHP Warning:  session_start():
		// }

	}

	// Get public name
	private function get_plugin_name() {

		$plugin_name = TIM_TRAVEL_MANAGER_PLUGIN_NAME;

		return $plugin_name;

	}

	// Get public class
	private function get_public_data() {

		$plugin_name = $this->get_plugin_name();

		$public_data = new Tim_Travel_Manager_Public_Data( $plugin_name );

		return $public_data;
	}

}


	/*public function sync_tim_tours_api() {

		check_ajax_referer( $this->plugin_nonce, 'nonce' );

		$url = '/sync/synchronize_account_api?opt=tours';

		$params = array();
	    
	    $data = $this->authenticate_api_key( $url, $params, 'GET', 1 );

		if ( ! $data) {
	    	echo 'Not Authorized';
	    } else {
	    	echo 'Data Synchronized';
    		echo '<pre>'; print_r($data); echo '</pre>';

    		$post_types = array(
				'tour'
	    	);

			$this->delete_posts_by_post_type( $post_types );

			$general_options = get_option( TIM_TRAVEL_MANAGER_GENERAL_OPTIONS );
			$translation_plugin_id = $general_options['translation_plugin_id'];

			$default_content_lang = $data->default_content_lang;

			$this->set_default_content_lang( $default_content_lang ); // not saving the value

	    	$this->_response( $this->save_multiple_data( $translation_plugin_id, 'tour', $data->tours, $default_content_lang ) );
	    }
	  
		wp_die(); // this is required to return a proper result

	}*/


				// if (get_option($option) === '') {
			// 	echo 'empty';
			// } else {
			// 	echo 'replace';
			// }
		// $lang_option = get_option( 'tim_default_content_lang' );

		// if (get_option('tim_default_content_lang')) {
		// 	if (get_option('tim_default_content_lang') == '') {
		// 		echo ' - new';
		// 	} else {
		// 		echo ' - update';
		// 	}
        // 	// update_option('some_option', 'value_we_want_to_add');
	    // } else {
	    // 	echo ' - add';
	    //  	// add_option('some_option', 'value_we_want_to_add');
	    // }

		// if ( ! $lang_option ) {
		// 	echo ' - not evaluated';
		// 	// add_option( 'tim_default_content_lang', $default_content_lang ); // not working-saving
		// } else {
		// 	if ( $lang_option !== $default_content_lang ) {
		// 		echo 'update';
		// 		// update_option( 'tim_default_content_lang', $default_content_lang );
		// 	} else {
		// 		echo 'same';
		// 	}
		// }
		

?>