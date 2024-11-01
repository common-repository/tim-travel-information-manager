<div class="tim_clr">
    <div class="tim_col_7 tim_detail_title">
        <h1><?php echo $item['name']; ?></h1>
    </div>
    <div class="tim_col_5 tim_pull_right">
        <div class="tim_detail_menu_wrap">
            <ul class="tim_detail_menu">
                <li>
                    <a href="javascript:void(0);" class="active"><i class="fa fa-info-circle fa-lg"></i><span><?php _e( 'Detail', $plugin_name ); ?></span></a>
                </li>
                <?php
                if ( $item['url_video'] !== '' ) {
                    ?>
                    <li>
                        <a href="javascript:void(0);" onclick="timScrollTo('tim_detail_video')"><i class="fa fa-video-camera fa-lg"></i><span><?php _e( 'Video', $plugin_name ); ?></span></a>
                    </li><?php
                }

                if ( $item['geo_lat'] !== '' ) {
                    ?>
                    <li>
                        <a href="javascript:void(0);" onclick="timScrollTo('tim_detail_map')"><i class="fa fa-map-marker fa-lg"></i><span><?php _e( 'Map', $plugin_name ); ?></span></a>
                    </li><?php
                }
                ?>
                <!-- <li><a href="javascript:void(0);"><i class="fa fa-commenting fa-lg"></i><span><?php _e( 'Reviews', $plugin_name ); ?></span></a></li> -->
            </ul>
        </div>
    </div>
</div>

<div class="tim_detail">
    <?php
    if ( $item['photos'] ) {
        ?>
        <div class="swiper-container tim-detail-slider">
            <div class="swiper-wrapper">
                <?php
                foreach ( $item['photos'] as $photo ) {
                    ?>
                    <div class="swiper-slide">
                        <img src="<?php echo $photo['image']; ?>" alt="<?php echo $photo['title']; ?>" class="swiper-lazy" />
                        <!-- <div class="swiper-lazy-preloader"></div> -->
                    </div>
                    <?php
                }
                ?>
            </div>
            <div class="swiper-pagination swiper-pagination-white"></div>
            <div class="swiper-button-next swiper-button-white"></div>
            <div class="swiper-button-prev swiper-button-white"></div>
        </div>
        <?php
    }
    ?>

    <div class="tim_clr tim_detail_overview">
        <div class="tim_col_3">
            <div class="tim_clr tim_detail_line">
                <div class="tim_detail_line_label">
                    <i class="fa fa-map-marker fa-lg"></i><?php _e( 'Destination', $plugin_name ); ?>
                </div>
                <div class="tim_detail_line_content">
                    <?php echo $item['location_name']; ?>
                </div>
            </div>
        </div>
        <div class="tim_col_3">
            <div class="tim_clr tim_detail_line">
                <div class="tim_detail_line_label">
                    <i class="fa fa-calendar fa-lg"></i><?php _e( 'Schedules', $plugin_name ); ?>
                </div>
                <div class="tim_detail_line_content">
                    <?php echo $item['departures']; ?>
                </div>
            </div>
        </div>
        <div class="tim_col_3">
            <div class="tim_clr tim_detail_line">
                <div class="tim_detail_line_label">
                    <i class="fa fa-clock-o fa-lg"></i><?php _e( 'Duration', $plugin_name ); ?>
                </div>
                <div class="tim_detail_line_content">
                    <?php echo $item['duration']; ?> ( <?php _e( 'approx', $plugin_name ); ?>. )
                </div>
            </div>
        </div>

        <div class="tim_col_3">
            <div class="tim_clr tim_detail_line">
                <div class="tim_detail_line_label">
                    <?php _e( 'Starting from', $plugin_name ); ?>
                </div>
                <div class="tim_detail_line_content">
                    <div class="tim_item_price_box">
                        <span class="tim_item_price_amount"><?php echo $item['rate_from']; ?></span> <span class="tim_item_price_label"><?php _e( 'Per person', $plugin_name ); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="tim_detail_booking">
        <a href="javascript:void(0);" onclick="timScrollTo('tim_detail_rates')"><?php _e( 'Book', $plugin_name ); ?></a>
    </div>

    <div class="tim_detail_content">
        <div class="tim_clr">
            <div class="tim_col_8 tim_detail_desc">
                <!-- <div id="tim_detail_rates"> -->
                    <?php
                    // render_check_rate_widget( $item, $checkRateLayoutId );
                    ?>
                <!-- </div> -->

                <h2><?php _e( 'Tour overview', $plugin_name ); ?></h2>
                
                <?php
                echo $item['itinerary'];

                if ( $item['taggings'] ){
                    ?>
                    <ul class="tim_tags">
                        <?php
                        foreach ( $item['taggings'] as $tag ) {
                            ?>
                            <li><?php echo $tag->name; ?></li>
                            <?php
                        }
                        ?>
                    </ul>
                    <?php
                }
                ?>

                <div class="tim_clr">
                    <div class="tim_col_6">
                        <?php
                        if ( $item['facilities'] ){
                            ?>
                            <h3><?php _e( 'Included', $plugin_name ); ?></h3>
                            <ul>
                                <?php
                                foreach ( $item['facilities'] as $facility ) {
                                    ?>
                                    <li><i class="fa fa-check"></i> <?php echo $facility; ?></li>
                                    <?php
                                }
                                ?>
                            </ul>
                            <?php
                        }
                        ?>
                    </div>
                    <div class="tim_col_6">
                        <?php
                        if ( $item['wtobrings'] ) {
                            ?>
                            <h3><?php _e( 'Things to bring', $plugin_name ); ?></h3>
                            <ul>
                                <?php
                                foreach ( $item['wtobrings'] as $wtobring ) {
                                    ?>
                                    <li><i class="fa fa-check"></i> <?php echo $wtobring; ?></li>
                                    <?php
                                }
                                ?>
                            </ul>
                            <?php
                        }
                        ?>
                    </div>
                </div>

                <?php
                if ( $item['url_video'] !== '' ) {
                    ?>
                    <div id="tim_detail_video" class="tim_detail_line">
                        <h3><?php _e( 'Video', $plugin_name ); ?></h3>
                        <iframe class="tim_detail_video" src="<?php echo $item['url_video']; ?>" frameborder="0" allowfullscreen></iframe>
                    </div>
                    <?php
                }

                if ( $item['geo_lat'] !== '' ) {
                    ?>
                    <div id="tim_detail_map" class="tim_detail_line">
                        <h3><?php _e( 'Map', $plugin_name ); ?></h3>

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
                <div id="tim_detail_rates">
                    <?php
                    render_check_rate_widget( $item, $checkRateLayoutId );
                    ?>
                </div>
            </div>
        </div>

        <div class="tim_detail_line">
            <img src="<?php echo $plugin_url; ?>/public/img/share_tweet.gif" />
        </div>

        <?php
        if ( $item['notes'] != '' || $item['policies'] != '') {
            ?>
            <div class="tim_detail_line">
                <div class="tim_detail_line_label">
                    <?php _e( 'Additional info', $plugin_name ); ?>
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

                if ( $item['policies'] != '' ) {
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

    <?php
    if ( $item['related_tour_ids'] ){
        ?>
        <h3><?php _e( 'Related tours', $plugin_name ); ?></h3>
        <div class="swiper-container tim-carrousel-slider">
            <div class="swiper-wrapper">
                <?php
                $tim_travel_manager_tour->render_tour_list( 'related', $item['id'], '', $item['related_tour_ids'] );
                ?>
            </div>
            <div class="swiper-pagination swiper-pagination-black"></div>
            <div class="swiper-button-next swiper-button-white"></div>
            <div class="swiper-button-prev swiper-button-white"></div>
        </div>
    <?php
    }
    ?>
</div>