<?php

//$positionStyle = ( $position) ? ' tim_cart_widget_relative' : '';
$positionStyle = '';

$content = '<div class="tim-top-nav-menu">'; // use position=relative, quitar top, right

// Cart
if ( ! $hideCart ) {
	$cart_url = 'javascript:void(0);';
	if ( $totalBookingItems > 0 ) {
		$cart_url = $this->home_url .'checkout/'; // cart Check with other translation plugins
	}

	$content .= '
	<span class="tim_cart_widget'. $positionStyle .'">
		<span class="tim-cursor" onclick="window.location=\''. $cart_url .'\'"><i class="fa fa-shopping-cart fa-lg"></i> <span id="timOTI" class="tim_cart_count">'. $totalBookingItems .'</span>
		</span>
	</span>';
}

// Currency selector
if ( ! $hideCurrency AND ! isset( $_GET['oid'] ) ) {
	$currencies = $this->public_data->get_postmeta_list( $this->post_type_currencies );

	if ( count($currencies) > 1 ) {
		$selectedSymbol = $this->currency_value['symbol'];
        $selectedCode = $this->currency_value['code'];

		if ( isset( $_SESSION['tim_currency_value'] ) ) {
			foreach ( $currencies as $currency ){
                if ( $currency['code'] === $this->currency_value['code'] ) {
                    $find = $currency;
                    break;
                }
            }

            $selectedSymbol = $find['symbol'];
            $selectedCode = $find['code'];
		}

		$content .= '
		<div class="tim-dropdown">
			<button class="tim-dropdown-dropbtn">'. $selectedSymbol .' '. $selectedCode .'</button>
			<div class="tim-dropdown-content">';
			foreach ( $currencies as $currency ) {
				//$title = __( $currency['name']->$content_language, $this->plugin_name );
				$code = $currency['code'];
				$symbol = $currency['symbol'];

				if ( $code != $selectedCode ) {
					$content .='
					<a href="javascript:void(0)" class="tim-currency-value" id="'. $code .'">
						'. $symbol .' '. $code .'
					</a>';
			    }
		  	}
			$content .= '
			</div>
		</div>'; // .tim-dropdown
	}
}

// Languages selector
if ( ! $hideLanguage ) {
	$general_options = get_option( 'TIM_TRAVEL_MANAGER_GENERAL_OPTIONS' );
	
	// Any translation plugin activated
	if ( $general_options['translation_plugin_id'] ) {
		$languageList = '';

		switch ( $general_options['translation_plugin_id'] ) {
			case 'wpml':
				$languages = icl_get_languages('skip_missing=1');
    			// $languages = apply_filters( 'wpml_active_languages', NULL, 'skip_missing=1' );
				
				// var_dump($languages);
				// echo '<br><br>';

				global $wp;
				$query_var = $wp->query_vars['post_type'];

				foreach ( $languages as $language ) {
					// var_dump($language);
		            if ( ! $language['active'] ){
		            	// echo $language['url'];
		            	$url = $language['url'];
		            	if ( isset($wp->query_vars['post_type']) ) {
		            		switch ( $query_var ) {
		            			// case $this->post_type_cart:
		            			// 	$section = 'cart/';
		            			// break;
		            			// case $this->post_type_checkout:
		            			// 	$section = 'checkout/';
		            			// break;
		            			// case $this->post_type_order:
		            			// 	$section = 'order/';
		            			// break;
		            			case $this->post_type_verify_order:
		            				$section = 'verify-order/';
		            			break;
		            			case $this->post_type_my_account:
		            				$section = 'my-account/';
		            			break;
		            		}

		            		$url = rtrim($language['url'], '/') .'/'. $section;
		            	}

		            	$flag = $language['country_flag_url'];
		            	$name = icl_disp_language( $language['native_name'] );

		            	$langLabel = $this->set_translation_plugin_label_setting($general_options['translation_plugin_label_setting'], $name, $flag);

		            	$languageList .= '
							<a href="'. $url .'" title="'. $name .'">
								'. $langLabel .'
							</a>';
		        	} else {
		        		$currentLanguage = array(
							'name' => $language['native_name'], 
							'flag' => $language['country_flag_url']
						);
		        	}
			    }
			break;
			
			case 'qtranslate-x':
				global $q_config;

				$flagsFolder = get_option('home') .'/wp-content/'. $q_config['flag_location'];

				$currentLanguage = array(
					'name' => $q_config['language_name'][$q_config['language']], // $q_config['language_name']
					'flag' => $flagsFolder . $q_config['flag'][$q_config['language']]
				);

				// $currentLanguageCode = $q_config['language']; // qtranxf_getLanguage()
				// $currentLanguageName = $q_config['language_name'][$currentLanguageCode];
				// $flag = $flagsFolder . $q_config['flag'][$currentLanguageCode];

				$languages = $q_config['enabled_languages']; // qtranxf_getSortedLanguages()
				// var_dump($this->languages);

				foreach ( $languages as $language ) {
				    if ( $language !== $q_config['language'] ) {
						$flag = $flagsFolder . $q_config['flag'][$language];
				    	$name = $q_config['language_name'][$language];

				    	$langLabel = $this->set_translation_plugin_label_setting($general_options['translation_plugin_label_setting'], $name, $flag);

					    $languageList .= '
							<a href="'. qtranxf_convertURL( $url, $language, false, true ) .'" title="'. $name .'">
								'. $langLabel .'
							</a>';
					}
				}
			break;

			// None
			default:
				// Do nothing
			break;
		}

		// var_dump($languageList);
		// echo '<br><br>';
		// var_dump($currentLanguage);

		$langLabel = $this->set_translation_plugin_label_setting($general_options['translation_plugin_label_setting'], $currentLanguage['name'], $currentLanguage['flag']);

		$content .= '
		<div class="tim-dropdown">
			<button class="tim-dropdown-dropbtn">'. $langLabel .'</button>
			<div class="tim-dropdown-content">';
				$content .= $languageList . '
			</div>
		</div>'; // .tim-dropdown

		// <img src="'. $currentLanguage['flag'] .'" alt="'. $currentLanguage['name'] .'"> '. $currentLanguage['name']
	}
}

if ( ! $hideAccount ) {
	// Account selector
	$content .= '
	<div class="tim-dropdown">
		<button class="tim-dropdown-dropbtn">'. __( 'Account', $this->plugin_name ) .'</button>
		<div class="tim-dropdown-content" style="min-width: 140px;">';

		$my_account_url = $this->home_url .'my-account/';
		if ( isset($_SESSION['tim_client_session']) ){ // fix
			$content .='
			<a href="'. $my_account_url .'">
				<i class="fa fa-user"></i> '. __( 'My profile', $this->plugin_name ) .'
			</a>
			<a href="'. $my_account_url .'?act=orders">
				<i class="fa fa-list-alt"></i> '. __( 'View orders', $this->plugin_name ) .'
			</a>
			<a href="javascript:void(0);" onclick="timLogout();">
				<i class="fa fa-unlock"></i> '.__( 'Logout', $this->plugin_name ) .'
			</a>';
		} else {
			// echo $home_url;
			$verify_order_url = $this->home_url .'verify-order/';
			
			$content .= '
			<a href="'. $verify_order_url .'">
				<i class="fa fa-search"></i> '. __( 'Verify order', $this->plugin_name ) .'
			</a>
			<a href="'. $my_account_url .'">
				<i class="fa fa-user"></i> '. __( 'Log in', $this->plugin_name ) .'
			</a>';
		}

		$content .= '
		</div>
	</div>'; // .tim-dropdown
}

$content .= '</div>'; // .tim_cart_widget

$content = preg_replace( "/\r|\n/", "", $content ); // Removes <br />

$content = preg_replace('#^<\/p>|$#', '', $content);

echo $content;

?>