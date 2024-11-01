<div class="tim_detail">
    <div class="tim_clr">
        <div class="tim_col_8 tim_detail_title">
            <h1><?php echo $item['name']; ?></h1>
        </div>
        <div class="tim_col_4 tim_align_right">
            <img src="<?php echo $plugin_url; ?>/public/img/share_tweet.gif" />
        </div>
    </div>

    <div class="tim_clr">
        <div class="tim_col_8">

            <div class="swiper-container tim_detail_slider">
                <div class="swiper-wrapper">
                    <?php
                    foreach ( $item['photos'] as $photo ) {
                        ?>
                        <div class="swiper-slide tim_detail_slide"><img src="<?php echo $photo->image; ?>" /></div>
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

                <div class="tim_clr tim_detail_line">
                    <div class="tim_item_price">
                        <div class="tim_item_price_box">
                            <div class="tim_item_price_amount"><?php echo $item['min_rate']; ?></div>
                            <div class="tim_item_price_label"><?php _e( 'per person', $plugin_name ); ?></div>
                        </div>
                    </div>
                </div>

                <div class="tim_clr tim_detail_line">
                    <div class="tim_col_5 tim_detail_line_label">
                        <i class="fa fa-map-marker fa-lg"></i><?php _e( 'Location', $plugin_name ); ?>
                    </div>
                    <div class="tim_col_7 tim_detail_line_content">
                        <?php echo $item['location_name']; ?>
                    </div>
                </div>

                <div class="tim_clr tim_detail_line">
                    <div class="tim_col_5 tim_detail_line_label">
                        <i class="fa fa-calendar fa-lg"></i><?php _e( 'Schedules', $plugin_name ); ?>
                    </div>
                    <div class="tim_col_7 tim_detail_line_content">
                        <!-- <ul> -->
                            <?php
                            /*foreach ( $item['tour_schedules'] as $schedule ) {
                                ?>
                                <li>
                                    <b><?php echo $schedule->departure; ?> hrs.</b>
                                </li>
                                <?php
                            }*/
                            echo $item['departures'];
                            ?>
                        <!-- </ul> -->
                    </div>
                </div>

                <div class="tim_clr tim_detail_line">
                    <div class="tim_col_5 tim_detail_line_label">
                        <i class="fa fa-clock-o fa-lg"></i><?php _e( 'Duration', $plugin_name ); ?>
                    </div>
                    <div class="tim_col_7 tim_detail_line_content">
                        <?php echo $item['duration']; ?> ( <?php _e( 'approx.', $plugin_name ); ?> )
                    </div>
                </div>

            </div>

        </div>
    </div>

    <div class="entry-contentxxx">
        
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
                ?>
                <li><a href="javascript:void(0);"><i class="fa fa-commenting fa-lg"></i><span><?php _e( 'Reviews', $plugin_name ); ?></span></a></li>
            </ul>
        </div>

        <div class="tim_detail_content">

            <div class="tim_clr">
                <div class="tim_col_8 tim_detail_desc">
                    <h2><?php _e( 'Information', $plugin_name ); ?></h2>
                    <?php echo $item['itinerary']; ?>

                    <div class="tim_clr tim_detail_line">
                        <div class="tim_col_4 tim_detail_line_label">
                            <i class="fa fa-plus-square fa-lg"></i><?php _e( 'Included', $plugin_name ); ?>
                        </div>
                        <div class="tim_col_8 tim_detail_line_content">
                            <ul>
                                <?php
                                foreach ( $item['facilities'] as $facility ) {
                                    ?>
                                    <li><i class="fa fa-check"></i> <?php echo $facility; ?></li>
                                    <?php
                                }
                                ?>
                            </ul>
                        </div>
                    </div>

                    <div class="tim_clr tim_detail_line">
                        <div class="tim_col_4 tim_detail_line_label">
                            <i class="fa fa-suitcase fa-lg"></i><?php _e( 'Things to bring', $plugin_name ); ?>
                        </div>
                        <div class="tim_col_8 tim_detail_line_content">
                            <ul>
                                <?php
                                foreach ( $item['wtobrings'] as $wtobring ) {
                                    ?>
                                    <li><i class="fa fa-check"></i> <?php echo $wtobring; ?></li>
                                    <?php
                                }
                                ?>
                            </ul>
                        </div>
                    </div>

                    <div class="tim_clr tim_detail_line">
                        <div class="tim_col_4 tim_detail_line_label">
                            <i class="fa fa-flag fa-lg"></i><?php _e( 'Tags', $plugin_name ); ?>
                        </div>
                        <div class="tim_col_8 tim_detail_line_content">
                            <ul class="tim_tags">
                                <?php
                                foreach ( $item['taggings'] as $tag ) {
                                    ?>
                                    <li><?php echo $tag->name; ?></li>
                                    <?php
                                }
                                ?>
                            </ul>
                        </div>
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
                                    <i class="fa fa-map-marker"></i><?php echo $item['name']; ?>
                                    <div class="tim_detail_map_hr"></div>

                                    <i class="fa fa-location-arrow"></i><?php echo $item['address']; ?>
                                </div>

                                <div id="tim_googleMap" class="tim_detail_map" value="tour"></div>
                            </div>
                        </div>
                        <?php
                    }
                    ?>

                </div>
                <div class="tim_col_4">
                    <div id="tim_detail_booking_form" class="tim_detail_booking_form">
                        <div id="tim_detail_rates">
                            <h4><?php _e( 'Booking form', $plugin_name ); ?></h4>
                            
                            <?php
                            render_check_rate_widget( $item );
                            ?>
                        </div>
                    </div>
                </div>
            </div>

            <?php
            if ( $item['notes'] != '' || $item['policies'] != ''){
                ?>
                <div class="tim_detail_line">
                    <div class="tim_detail_line_label">
                        <!--<i class="fa fa-info-circle fa-lg"></i>--><?php _e( 'Additional info', $plugin_name ); ?>
                    </div>
                    <?php
                    if ( $item['notes'] != '' ){
                        ?><b><?php _e( 'Notes', $plugin_name ); ?>: </b><?php

                        ?><ul><?php
                        $notes = preg_split('/\R/', $item['notes']);

                        foreach ( $notes as $note ) {
                            ?>
                            <li><?php echo $note; ?></li>
                            <?php
                        }
                        ?></ul><br /><?php
                    }

                    if ( $item['policies'] != '' ){
                        ?><b><?php _e( 'Policies', $plugin_name ); ?>: </b><?php

                        ?><ul><?php
                        $policies = preg_split('/\R/', $item['policies']);

                        foreach ( $policies as $policy ) {
                            ?>
                            <li><?php echo $policy; ?></li>
                            <?php
                        }
                        ?></ul><?php
                    }
                    ?>
                </div>
                <?php
            }
            ?>

        </div>

        <h3><?php _e( 'Related tours', $plugin_name ); ?></h3>
        <div class="swiper-container tim_list_carrousel">
            <div class="swiper-wrapper">
                <?php
                $tim_travel_manager_tour->render_tour_list( 'related', get_the_ID() );
                ?>
            </div>
            <div class="swiper-pagination swiper-pagination-black"></div>
            <div class="swiper-button-next swiper-button-white"></div>
            <div class="swiper-button-prev swiper-button-white"></div>
        </div>

    </div>

</div>