<?php

/**
 * [ Generate_PostTypes Adding the post types e.g. Tours, Transportations, Hotels, Packages ]
 */

class Tim_Travel_Manager_Post_Types {

	protected $plugin_name;
	protected $postTypesList;

	public function __construct( $plugin_name, $postTypesList){ // , $customPagesList

		$this->plugin_name = $plugin_name;

		$this->postTypesList   = $postTypesList;
		// $this->customPagesList = $customPagesList;
	}

	public function unregister_post_types() {
        
        global $wp_post_types;

		$currentPostTypes = array();

		foreach ( $wp_post_types as $post_type ) {                        
            $found = false;

			foreach ( $this->postTypesList as $key => $value ) {
                if ( $post_type->name == $value['post_type'] ){
                    $found = true;
                    break;
                }
        	}

            if (!$found){
                array_push( $currentPostTypes, $post_type );
            }
		}

        foreach ( $currentPostTypes as $currentPostType ) {
        	if ($currentPostType->rewrite) { // Added on Sept, 25- 2021
	            $slug      = $currentPostType->rewrite['slug'];
	            $query_var = $currentPostType->query_var;

	            foreach ( $this->postTypesList as $key => $value ) {
	                if ( ( $slug === $value['slug'] ) || ( $query_var === $value['slug'] ) ){
		            	unregister_post_type( $currentPostType->name );
	                    break;
		            }
	            }
            }
        }

    }

	public function register_post_types() {
		foreach ( $this->postTypesList as $key => $value ) {
			try {
				$labels = array(
					'name'          => __( $value['name'], $this->plugin_name ),
					'singular_name' => __( $value['singular_name'], $this->plugin_name )
				);

				$args = array(
				    'labels'              => $labels,
				    'public'              => true,
				    'has_archive'         => true, 
				    'rewrite'             => array('slug' => $value['slug'], 'with_front' => true), 
				    // 'rewrite' => array( 'slug' =>  _x($value['name'], $value['slug'])),
				    'show_in_menu'        => false,
				    'query_var'           => true,
					'exclude_from_search' => true,
					'hierarchical'        => true,
					'show_ui'             => false,
					'supports'            => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'trackbacks', 'custom-fields', 'comments', 'revisions', 'page-attributes')

					//'query_var'           => $value['slug'], // problems with admin pages
					// 'slug' => _x( 'post_type_name', 'URL slug', 'your_text_domain' ) // for translations
				);

				register_post_type( $value['post_type'], $args );

			} catch (Exception $e) {}
		}
	}

}

?>