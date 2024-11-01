<?php

class Tim_Travel_Manager_Public_Location_Controller {

    protected $plugin_name;
    protected $post_type;
    protected $post_type_meta;

    protected $public_data;

    protected $content_language;
    protected $currency_code;
    protected $currency_symbol;

    public function __construct( $plugin_name, $public_data, $content_language, $currency_value, $post_type_locations ){

        $this->plugin_name = $plugin_name;

        $this->plugin_url = WP_PLUGIN_URL .'/'. $plugin_name;
        $this->plugin_dir = WP_PLUGIN_DIR .'/'. $plugin_name;

        $this->post_type      = $post_type_locations;
        $this->post_type_meta = $post_type_locations .'_meta';

        $this->public_data = $public_data;

        $this->content_language = $content_language;

        $this->currency_code   = ( $currency_value['code'] != '' )   ? $currency_value['code']   : $currency_value->symbol; // Session/Default
        $this->currency_symbol = ( $currency_value['symbol'] != '' ) ? $currency_value['symbol'] : $currency_value->symbol; // Session/Default

        $this->init();

    }

    public function init(){

        add_shortcode( 'location-list', array( $this, 'location_list_display' ) );

    }


    public function location_list_display( $atts ){

        extract( shortcode_atts( array(
            'view' => ''
        ), $atts ) );

        ob_start();

        $this->render_location_list( $view, 0 );

        return ob_get_clean();

    }

    public function render_location_list( $view, $itemID ){

        $content_language = $this->content_language;

        $theme_options = get_option( TIM_TRAVEL_MANAGER_GENERAL_OPTIONS );

        $themeLayoutIdDefault = 'travelo';
        $themeLayoutId        = $theme_options['theme_layout_id'];

        // Sort results
        $sort     = explode( '_', $_GET['sort'] );
        $order_by = ( ($sort[0]) AND ($sort[0] !== 'all')) ? $sort[0] : 'title';
        $order    = ( ($sort[1]) AND ($sort[1] !== 'all') ) ? strtoupper($sort[1]) : 'ASC';

        $get_view = isset( $_GET['view'] ) ? $_GET['view'] : $view;

        $args = array(
            'post_type' => $this->post_type,
            'meta_key'  => $this->post_type_meta,
            'orderby'   => $order_by,
            'order'     => $order//,
            //'posts_per_page' => 3 // related?
        );

        $query = new WP_Query( $args );

        // Search category
        if ( ! empty( $_GET['cat'] ) && $_GET['cat'] !== 'all' ) {
            $get_cat = $_GET['cat'];

            $search_CategoryId = $this->public_data->get_post_type_by_name( $get_cat, 'tim_categories', 'multiple', $content_language );
        }

        $totalActivePosts = 0;
        
        if ( $query->have_posts() ) :
            $list = array();

        if ( is_single() ) {
            echo '<br><br>is_single';
        }

            while ( $query->have_posts() ) : $query->the_post();
                $postmeta = get_post_meta( get_the_ID(), $this->post_type_meta, true );

                if ( ( $postmeta['status'] === 'active' ) && ( $postmeta['parentLocation_id'] ) ){
                    
                    $category_found = true;

                    if ( $search_CategoryId ){
                        $category_found = false;
                        if ( $search_CategoryId ){
                            foreach ( $postmeta['category_ids'] as $category_id ) {
                                if ( $category_id === $search_CategoryId ){
                                    $category_found = true;
                                    break;
                                }
                            }
                        }
                    }

                    if ( $category_found ){
                        
                        // Exclude item in related items
                        if ( get_the_ID() !== $itemID ){
                            //$get_parent_location = $this->public_data->get_postmeta_by_id( $postmeta['parentLocation_id'], $this->post_type );
                            $get_parent_location = $this->public_data->get_postmeta_item_by_value( $this->post_type, 'id', $postmeta['parentLocation_id'] );

                            $item = '';

                            $item['name']                = $postmeta['name']->$content_language;
                            $item['short_description']   = $postmeta['short_description']->$content_language;
                            $item['highlights']          = $postmeta['highlights']->$content_language;
                            $item['parentLocation_name'] = $get_parent_location['name']->$content_language;
                            $item['link']                = get_permalink();
                            $item['logo']                = ( $postmeta['logo'] ) ? $postmeta['logo'] : $this->plugin_url .'/public/img/item.jpg';
                            
                            if ( $postmeta['url_video']->$content_language != '' ){
                                $item['url_video'] = str_replace('watch?v=', 'v/', $postmeta['url_video']->$content_language);
                            }

                            array_push( $list, $item );

                            $totalActivePosts++;
                        }
                    }                   
                }
            endwhile;
            wp_reset_postdata();
        endif;

        if ( $get_view !== 'related' ){
            $search_widget = $this->plugin_dir .'/public/widgets/searchs/tim-travel-location-list-search.php';
            require_once( $search_widget );

            $view_mode = ( $get_view !== 'grid' && $get_view !== 'related' ) ? 'tim_list_view' : '';

            echo '<div class="tim_wrapper">';
                if ( $totalActivePosts > 0 ){
                    echo '<div class="tim_list '. $view_mode .'">';
                }
        }

        if ( $totalActivePosts > 0 ){
            if ($view === 'featured'){
                echo "featured locations";
            }
            else{
                // Plugin theme layout selected
                if ( $themeLayoutId ){
                    $layour_dir = $this->plugin_dir .'/public/layouts/';
                    
                    $themeLayout = $layour_dir . $themeLayoutId .'/locations/list.php';
                    $themeLayout = file_exists($themeLayout) ? $themeLayout : $layour_dir . $themeLayoutIdDefault .'/list.php';
                }
                else{
                    //$themeLayout = get_template_directory() .'/tim-location-list.php';
                    $themeLayout = get_stylesheet_directory() .'/tim-location-list.php';
                }
                
                require_once( $themeLayout );

                foreach ( $list as $key => $item) {
                    render_location_list_html( $item, $get_view, $this->plugin_name );
                }
                
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