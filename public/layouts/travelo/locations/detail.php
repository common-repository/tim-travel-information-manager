<article class="hentry tim_wrapper">
    
    <div class="tim_detail">
        <div class="tim_row">
            <div class="tim_col_8 tim_detail_title">
                <h1><?php echo $item['name']; ?></h1>
            </div>
            <div class="tim_col_4 tim_align_right">
                <img src="<?php echo $plugin_url; ?>/public/img/share_tweet.gif" />
            </div>
        </div>
    
        <div class="tim_row">
            <div class="tim_col_8">

                <div class="swiper-container tim_detail_slider">
                    <div class="swiper-wrapper">
                        <?php
                        foreach ( $item['pictures'] as $picture ) {
                            ?>
                            <div class="swiper-slide tim_detail_slide"><img src="<?php echo $picture->image->url; ?>" /></div>
                            <?php
                        }
                        ?>
                    </div>
                    <div class="swiper-pagination swiper-pagination-white"></div>
                    <div class="swiper-button-next swiper-button-white"></div>
                    <div class="swiper-button-prev swiper-button-white"></div>
                </div>

            </div>
            <div class="tim_col_4">

                <div class="tim_detail_overview">
                    <div class="tim_row tim_detail_line">
                        <div class="tim_col_5 tim_detail_line_label">
                            <i class="fa fa-map-marker fa-lg"></i><?php _e( 'Location', $plugin_name ); ?>
                        </div>
                        <div class="tim_col_7 tim_detail_line_content">
                            <?php echo $item['parentLocation_name']; ?>, <?php echo $item['country_name']; ?>
                        </div>
                    </div>

                    <div class="tim_row tim_detail_line">
                        <div class="tim_col_5 tim_detail_line_label">
                            <i class="fa fa-flag fa-lg"></i><?php _e( 'Category', $plugin_name ); ?>
                        </div>
                        <div class="tim_col_7 tim_detail_line_content">
                            <ul>
                                <?php
                                foreach ( $item['categories'] as $category ) {
                                    ?>
                                    <li><?php echo $category; ?></li>
                                    <?php
                                }
                                ?>
                            </ul>
                        </div>
                    </div>

                    <div class="tim_row tim_detail_line">
                        <div class="tim_col_5 tim_detail_line_label">
                            <i class="fa fa-star fa-lg"></i><?php _e( 'Highlights', $plugin_name ); ?>
                        </div>
                        <div class="tim_col_7 tim_detail_line_content">
                            <ul>
                                <?php
                                $highlights = preg_split('/\R/', $item['highlights']);

                                foreach ( $highlights as $highlight ) {
                                    ?>
                                    <li><?php echo $highlight; ?></li>
                                    <?php
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="entry-content">
            
            <div class="tim_detail_menu_wrap">
                <ul class="tim_detail_menu">
                    <li class="tim_detail_booking"><a href="javascript:void(0);" onclick="timScrollTo('tim_detail_booking_form')"><?php _e( 'Book', $plugin_name ); ?></a></li>
                    <li><a href="javascript:void(0);" class="active"><i class="fa fa-info-circle fa-lg"></i><span><?php _e( 'Detail', $plugin_name ); ?></span></a></li>
                    <?php
                    if ( $item['url_video'] != '' ){
                        ?><li><a href="javascript:void(0);" onclick="timScrollTo('tim_detail_video')"><i class="fa fa-video-camera fa-lg"></i><span><?php _e( 'Video', $plugin_name ); ?></span></a></li><?php
                    }
                    if ( $item['geo_lat'] != '' ){
                        ?><li><a href="javascript:void(0);" onclick="timScrollTo('tim_detail_map')"><i class="fa fa-map-marker fa-lg"></i><span><?php _e( 'Map', $plugin_name ); ?></span></a></li><?php
                    }
                    if ($hotelsByLocation){
                        ?><li><a href="javascript:void(0);" onclick="timScrollTo('tim_detail_hotels')"><i class="fa fa-bed fa-lg"></i><span><?php _e( 'Hotels', $plugin_name ); ?></span></a></li><?php
                    }
                    if ($toursByLocation){
                        ?><li><a href="javascript:void(0);" onclick="timScrollTo('tim_detail_tours')"><i class="fa fa-fa fa-binoculars fa-lg"></i><span><?php _e( 'Tours', $plugin_name ); ?></span></a></li><?php
                    }
                    ?>
                </ul>
            </div>

            <div class="tim_detail_content">

                <div class="tim_detail_desc">
                    <h2><?php _e( 'Information', $plugin_name ); ?></h2>
                    <?php echo $item['long_description']; ?>
                </div>

                <?php
                if ( $item['url_video'] != '' ){
                    ?>
                    <div id="tim_detail_video" class="tim_detail_line">
                        <h3><i class="fa fa-video-camera"></i> <?php _e( 'Video', $plugin_name ); ?></h3>
                        <iframe class="tim_detail_video" src="<?php echo $item['url_video']; ?>" frameborder="0" allowfullscreen></iframe>
                    </div>
                    <?php
                }

                if ( $item['geo_lat'] != '' ){
                    ?>
                    <div id="tim_detail_map" class="tim_detail_line">
                        <h3><i class="fa fa-map-marker"></i> <?php _e( 'Map', $plugin_name ); ?></h3>

                        <input type="hidden" id="tim_geoLat" value="<?php echo $item['geo_lat']; ?>" />
                        <input type="hidden" id="tim_geoLng" value="<?php echo $item['geo_lng']; ?>" />
                        <input type="hidden" id="tim_geoZoom" value="<?php echo $item['geo_zoom']; ?>" />

                        <div class="tim_detail_map_wrap">
                            <div class="tim_detail_map_labels">
                                <i class="fa fa-map-marker"></i><?php echo $item['name']; ?>, <?php echo $item['parentLocation_name']; ?> <?php echo $item['country_name']; ?>
                            </div>

                            <div id="tim_googleMap" class="tim_detail_map" value="tour"></div>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>

            <?php
            if ($hotelsByLocation){
                ?>
                <div id="tim_detail_hotels">
                    <h3><?php _e( 'Hotels', $plugin_name ); ?></h3>
                    <div class="swiper-container tim_list_carrousel">
                        <div class="swiper-wrapper">
                            <?php
                            $tim_travel_manager_hotel->render_hotel_list( 'related', '', $item['id'] );
                            ?>
                        </div>
                        <div class="swiper-pagination swiper-pagination-black"></div>
                        <div class="swiper-button-next swiper-button-white"></div>
                        <div class="swiper-button-prev swiper-button-white"></div>
                    </div>
                </div>
                <br />
                <?php
            }
            ?>

            <div id="tim_detail_tours">
                <h3><?php _e( 'Tours and activities', $plugin_name ); ?></h3>
                <div class="swiper-container tim_list_carrousel">
                    <div class="swiper-wrapper">
                        <?php
                        $tim_travel_manager_tour->render_tour_list( 'related', '', $item['id'] );
                        ?>
                    </div>
                    <div class="swiper-pagination swiper-pagination-black"></div>
                    <div class="swiper-button-next swiper-button-white"></div>
                    <div class="swiper-button-prev swiper-button-white"></div>
                </div>
            </div>

        </div>

    </div>

</article>