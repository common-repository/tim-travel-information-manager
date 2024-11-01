<?php
/*
Plugin Name: TIM - Travel Information Manager
Plugin URI:  http://timtravel.app/plugins/tim-travel-information-manager/
Description: Tim plugin. Travel Information Manager
Version:     1.5.5
Author:      Nativo
Author URI:  https://timtravel.app
Text Domain: tim-travel-information-manager
Domain Path: /lang/
Tags:        Travel, Tours, Hotels, Transportation, Vacation Packages

Copyright (C) Nativo LLC
*/

// If this file is called directly, abort
if ( !defined('WPINC') ) {
    die;
}

$env = 'production'; // development, qa, staging, production
switch ( $env ) {
	case 'qa':
		$timFrontEndUrl = 'https://qa.timtravel.app';
		$timBackEndUrl  = 'https://api-qa.timtravel.app';
	break;

	case 'staging':
		$timFrontEndUrl = 'https://app.timtravel.app';
		$timBackEndUrl  = 'https://api-prod.timtravel.app'; // Please be carefully
	break;

	case 'production':
		$timFrontEndUrl = 'https://app.timtravel.app';
		$timBackEndUrl  = 'https://api-prod.timtravel.app';
	break;
	
	default:
		$timFrontEndUrl = 'http://localhost:4200';
		$timBackEndUrl  = 'http://localhost:5000';
	break;
}

define('TIM_TRAVEL_MANAGER_PLUGIN_VERSION', '1.5.5'); // IMPORTANT !!!!

define('TIM_TRAVEL_MANAGER_ENV', $env);

define('TIM_TRAVEL_MANAGER_FRONTEND_URL', $timFrontEndUrl .'/#');
define('TIM_TRAVEL_MANAGER_BACKEND_URL', $timBackEndUrl .'/api');

define('TIM_TRAVEL_MANAGER_PLUGIN_TITLE', 'TIM - Travel Information Manager');
define('TIM_TRAVEL_MANAGER_PLUGIN_NAME', 'tim-travel-information-manager');
define('TIM_TRAVEL_MANAGER_PLUGIN_SLUG', 'tim-travel-information-manager');

define('TIM_TRAVEL_MANAGER_CREDENTIALS', 'tim_travel_manager_credentials');
define('TIM_TRAVEL_MANAGER_GENERAL_OPTIONS', 'tim_travel_manager_general_options');

define('TIM_TRAVEL_MANAGER_PLUGIN_NONCE', 'tim_travel_manager_synchronize_api');

define('TIM_TRAVEL_MANAGER_POST_TYPE_COUNTRIES', 'tim_countries');
define('TIM_TRAVEL_MANAGER_POST_TYPE_LOCATIONS', 'tim_location');
define('TIM_TRAVEL_MANAGER_POST_TYPE_TOURS', 'tim_tour');
define('TIM_TRAVEL_MANAGER_POST_TYPE_TRANSPORTATIONS', 'tim_transportation');
define('TIM_TRAVEL_MANAGER_POST_TYPE_HOTELS', 'tim_hotel');
define('TIM_TRAVEL_MANAGER_POST_TYPE_PACKAGES', 'tim_package');
define('TIM_TRAVEL_MANAGER_POST_TYPE_CATEGORIES', 'tim_categories');
define('TIM_TRAVEL_MANAGER_POST_TYPE_FACILITIES', 'tim_facilities');
define('TIM_TRAVEL_MANAGER_POST_TYPE_WTOBRING', 'tim_wtobring');
define('TIM_TRAVEL_MANAGER_POST_TYPE_PICKUP_PLACES', 'tim_pickup_places');
define('TIM_TRAVEL_MANAGER_POST_TYPE_PRODUCT_CATEGORIES', 'tim_product_cat');
define('TIM_TRAVEL_MANAGER_POST_TYPE_CURRENCIES', 'tim_currencies');

define('TIM_TRAVEL_MANAGER_POST_TYPE_CART', 'tim_cart');
define('TIM_TRAVEL_MANAGER_POST_TYPE_CHECKOUT', 'tim_checkout');
define('TIM_TRAVEL_MANAGER_POST_TYPE_ORDER', 'tim_order');
define('TIM_TRAVEL_MANAGER_POST_TYPE_VERIFY_ORDER', 'tim_verify_order');
define('TIM_TRAVEL_MANAGER_POST_TYPE_MY_ACCOUNT', 'tim_my_account');

// You cannot create pages with this slugs
define('TIM_TRAVEL_MANAGER_SLUG_LOCATION', 'destination'); // location gives post type problems
define('TIM_TRAVEL_MANAGER_SLUG_TOUR', 'tour');
define('TIM_TRAVEL_MANAGER_SLUG_TRANSPORTATION', 'transport');
define('TIM_TRAVEL_MANAGER_SLUG_HOTEL', 'hotel');
define('TIM_TRAVEL_MANAGER_SLUG_PACKAGE', 'package');

define('TIM_TRAVEL_MANAGER_SLUG_CART', 'cart');
define('TIM_TRAVEL_MANAGER_SLUG_CHECKOUT', 'checkout');
define('TIM_TRAVEL_MANAGER_SLUG_ORDER', 'order');
define('TIM_TRAVEL_MANAGER_SLUG_VERIFY_ORDER', 'verify-order');
define('TIM_TRAVEL_MANAGER_SLUG_MY_ACCOUNT', 'my-account');

// BAC
// define('TIM_TRAVEL_MANAGER_POST_TYPE_PAYMENT_SUCCESS', 'tim_payment_success');
// define('TIM_TRAVEL_MANAGER_SLUG_PAYMENT_SUCCESS', 'payment-success');

require_once plugin_dir_path( __FILE__ ) . 'includes/class-tim-travel-information-manager.php';

function tim_travel_manager_start() {

    $plugin = new Tim_Travel_Manager();
    $plugin->init();

}

tim_travel_manager_start();

?>