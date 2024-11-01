<?php

$categories = $this->public_data->get_miscellaneous( TIM_TRAVEL_MANAGER_POST_TYPE_CATEGORIES, 'location' );

$get_loc  = isset( $_GET['loc'] )  ? $_GET['loc']  : '';
$get_cat  = isset( $_GET['cat'] )  ? $_GET['cat']  : '';
$get_sort = isset( $_GET['sort'] ) ? $_GET['sort'] : '';
$get_view = isset( $_GET['view'] ) ? $_GET['view'] : '';

$full_url = explode('?', $_SERVER['REQUEST_URI'], 2); //Ex /travel_demo/model/

$base_url    = $_SERVER['HTTP_HOST'] . $full_url[0];
$current_url = $base_url;
$args_url    = $full_url[1];

$view_list = add_query_arg('view', 'list');
$view_grid = add_query_arg('view', 'grid');
?>
<div class="tim_list_search tim_row">
    <div class="tim_col_11">
        <?php _e( 'Filter', $this->plugin_name ); ?>:
        <form class="tim_list_search_filter_form">
            <?php
            if ( $locations ){
                ?><select name="loc"><?php
                echo '<option'. selected( $get_loc, 'all' ) .' value="all">- '. __( 'All locations', $this->plugin_name ) .' -</option>';
                foreach ( $locations as $location ) {
                    $name = $location['name']->$content_language;
                    if ( $name ){
                        ?><option<?php selected( $get_loc, $name ); ?> value="<?php echo esc_attr( $name ); ?>"><?php echo esc_html( $name ); ?></option><?php
                    }               
                }
                ?></select> <?php
            }

            if ( $categories ){
                ?><select name="cat"><?php
                echo '<option'. selected( $get_cat, 'all' ) .' value="all">- '. __( 'All categories', $this->plugin_name ) .' -</option>';
                foreach ( $categories as $category ) {
                    $name = $category['name']->$content_language;
                    if ( $name ){
                        ?><option<?php selected( $get_cat, $name ); ?> value="<?php echo esc_attr( $name ); ?>"><?php echo esc_html( $name ); ?></option><?php
                    }
                }
                ?></select><?php
            }
            if ( ! empty($get_view) ){
                ?><input type="hidden" name="view" value="<?php echo esc_attr( $get_view ); ?>" /><?php
            }
            ?>
            <input type="submit" value="<?php _e( 'Search', $this->plugin_name ); ?>">
    
            <?php _e( 'Sort', $this->plugin_name ); ?>:
            <select name="sort"><?php
                echo '<option'. selected( $get_sort, 'all' ) .' value="all">- '. __( 'Sort by', $this->plugin_name ) .' -</option>';
                echo '<option'. selected( $get_sort, 'title_asc' ) .' value="title_asc">'. __( 'Name ascending', $this->plugin_name ) .'</option>';
                echo '<option'. selected( $get_sort, 'title_desc' ) .' value="title_desc">'. __( 'Name descending', $this->plugin_name ) .'</option>';
                echo '<option'. selected( $get_sort, 'rate_asc' ) .' value="rate_asc">'. __( 'Higher rate', $this->plugin_name ) .'</option>';
                echo '<option'. selected( $get_sort, 'rate_desc' ) .' value="rate_desc">'. __( 'Lower rate', $this->plugin_name ) .'</option>';
                ?>
            </select>
            <?php
            if ( $args_url !== '' ){
                ?> <a href="<?php echo $base_url; ?>"><?php _e( 'Clear', $this->plugin_name ); ?></a><?php
            }
            ?>
        </form>
    </div>
    <div class="tim_col_1">
        <div class="tim_list_search_view">
            <a href="<?php echo $view_list; ?>" title="<?php _e( 'List view', $this->plugin_name ); ?>"><i class="fa fa-list fa-lg"></i></a>
            <a href="<?php echo $view_grid; ?>" title="<?php _e( 'Grid view', $this->plugin_name ); ?>"><i class="fa fa-th fa-lg"></i></a>
        </div>
    </div>
</div>