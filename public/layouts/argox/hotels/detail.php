<div class="tim_clr">
    <div class="tim_col_7 tim_detail_title">
        <h1><?php echo $item['name']; ?></h1>
        <span>
            <?php
            for ($i = 0; $i < $item['stars_rating']; $i++){
                ?><i class="fa fa-star"></i> <?php
            }
            ?>
        </span>
        <em><?php echo $item['address']; ?></em>
    </div>
    <div class="tim_col_5 tim_pull_right">
        <div class="tim_detail_menu_wrap">
            <ul class="tim_detail_menu">
                <li><a href="javascript:void(0);" class="active"><i class="fa fa-info-circle fa-lg"></i><span><?php _e( 'Detail', $plugin_name ); ?></span></a></li>
                <?php
                if ( $item['geo_lat'] !== '' ){
                    ?>
                    <li>
                        <a href="javascript:void(0);" onclick="timScrollTo('tim_detail_map')"><i class="fa fa-map-marker fa-lg"></i><span><?php _e( 'Map', $plugin_name ); ?></span></a>
                    </li><?php
                }
                ?>
                <li>
                    <a href="javascript:void(0);" onclick="timScrollTo('tim_detail_rates')"><i class="fa fa-calendar fa-lg"></i><span><?php _e( 'Rates', $plugin_name ); ?></span></a>
                </li>
            </ul>
        </div>
    </div>
</div>

<div class="tim_detail">
    <?php
    if ( $item['photos'] ){
        ?>
        <div class="swiper-container tim-detail-slider">
            <div class="swiper-wrapper">
                <?php
                foreach ( $item['photos'] as $photo ) {
                    ?>
                    <div class="swiper-slide">
                        <img src="<?php echo $photo['image']; ?>" alt="<?php echo $photo['title']; ?>" class="swiper-lazy" />
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
                    <i class="fa fa-map-marker fa-lg"></i><?php _e( 'Location', $plugin_name ); ?>
                </div>
                <div class="tim_detail_line_content">
                    <?php echo $item['location_name']; ?>
                </div>
            </div>
        </div>
        <div class="tim_col_3">
            <div class="tim_clr tim_detail_line">
                <div class="tim_detail_line_label">
                    <i class="fa fa-clock-o fa-lg"></i><?php _e( 'Category', $plugin_name ); ?>
                </div>
                <div class="tim_detail_line_content">
                    <?php echo $item['product_category_name']; ?>
                </div>
            </div>
        </div>
        <div class="tim_col_3">
            <div class="tim_clr tim_detail_line">
                <div class="tim_detail_line_label">
                    <i class="fa fa-calendar fa-lg"></i><?php _e( 'Entrance', $plugin_name ); ?>
                </div>
                <div class="tim_detail_line_content">
                    <ul>
                        <li><?php _e( 'Check-in', $plugin_name ); ?>: <?php echo $item['check_in']; ?></li>
                        <li><?php _e( 'Check-out', $plugin_name ); ?>: <?php echo $item['check_out']; ?></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="tim_col_3">
            <div class="tim_clr tim_detail_line">
                <div class="tim_detail_line_label">
                    <?php _e( 'Price per night', $plugin_name ); ?>
                </div>
                <div class="tim_detail_line_content">
                    <div class="tim_item_price_box">
                        <div class="tim_item_price_amount"><?php echo $item['rate_from']; ?></div>
                        <span class="tim_item_price_label"><?php _e( 'Double occupancy', $plugin_name ); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="tim_detail_booking">
        <a href="javascript:void(0);" onclick="timScrollTo('tim_detail_booking_form')" class="tim-btn"><?php _e( 'Book', $plugin_name ); ?></a>
    </div>

    <div class="tim_detail_content">
        <div class="tim_detail_desc">
            <h2><?php _e( 'Hotel Overview', $plugin_name ); ?></h2>

            <div class="tim_detail_linex">
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
                    </ul><br />
                    <?php
                }
                ?>
            </div>
        </div>

        <?php
        if ( $item['url_video'] != '' ){
            ?>
            <div id="tim_detail_video">
                <iframe class="tim_detail_video" src="<?php echo $item['url_video']; ?>" frameborder="0" allowfullscreen></iframe>
            </div>
            <?php
        }

        if ( $item['geo_lat'] != '' ){
            ?>
            <div id="tim_detail_map">
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

        <h3><?php _e( 'Hotel rooms', $plugin_name ); ?></h3>
        <div class="tim-tabs">
            <ul class="tim-tabs-links swipper">
                <?php
                foreach ( $item['hotel_rooms'] as $index => $hotel_room ) {
                    $active = ( $index === 0 ) ? ' class="active"' : '';
                    echo '<li'. $active .'><a href="#tab'. $index .'">'. $hotel_room['name'] .'</a></li>';
                }
                ?>
            </ul>

            <div class="tim-tabs-content">
                <?php
                foreach ( $item['hotel_rooms'] as $index => $hotel_room ) {
                    $active = ( $index === 0 ) ? ' active' : '';
                    $bed_configuration = $hotel_room['bed_configuration'] ? ' ('. $hotel_room['bed_configuration'] .')' : '';
                    ?>
                    <div id="tab<?php echo $index; ?>" class="tab-pane<?php echo $active; ?>">
                        <div class="tim_wrapper">
                            <div style="margin-bottom: 20px;">
                                <h2 style="margin: 0;"><?php echo $hotel_room['name']; ?></h2>
                                <i class="fa fa-bed"></i> <b><?php echo $hotel_room['occupancy_type_name']; ?></b><?php echo $bed_configuration; ?>
                                <?php
                                if ( $hotel_room['wifi'] ){
                                    ?><span class="tim_label tim_label_success tim_label_rd"><?php _e( 'Wi-fi', $plugin_name ); ?></span> <?php
                                }
                                if ( $hotel_room['not_smoking'] ){
                                    ?><span class="tim_label tim_label_success tim_label_rd"><?php _e( 'Not-smoking', $plugin_name ); ?></span> <?php
                                }
                                ?>
                            </div>

                            <div class="tim_clr">
                                <?php
                                if ( $hotel_room['url_video'] || $hotel_room['photos'] ){
                                    ?>
                                    <div class="tim_col_4">
                                        <div class="swiper-container tim-rooms-slider">
                                            <div class="swiper-wrapper">
                                                <?php
                                                if ( $hotel_room['url_video'] ){
                                                    ?>
                                                    <div class="swiper-slide">
                                                        <iframe class="tim_detail_video_tab" src="<?php echo $hotel_room['url_video']; ?>" frameborder="0" allowfullscreen></iframe>
                                                    </div>
                                                    <?php
                                                }

                                                foreach ( $hotel_room['photos'] as $photo ) {
                                                    ?>
                                                    <div class="swiper-slide">
                                                        <img src="<?php echo $photo['image']; ?>" alt="<?php echo $photo['title']; ?>" title="<?php echo $photo['title']; ?>" />
                                                    </div>
                                                    <?php
                                                }
                                                ?>
                                            </div>
                                            <div class="swiper-button-next swiper-button-white"></div>
                                            <div class="swiper-button-prev swiper-button-white"></div>
                                        </div>
                                    </div>
                                    <?php
                                }
                                // elseif ($hotel_room['logo'] !== '' && $hotel_room['logo'] !== null){
                                elseif ( $hotel_room['logo'] ){
                                    ?>
                                    <div class="tim_col_4">
                                        <img src="<?php echo $hotel_room['logo']; ?>" alt="<?php echo $hotel_room['name']; ?>" />
                                    </div>
                                    <?php
                                }
                                ?>
                                <div class="tim_col_8">
                                    <?php echo $hotel_room['description']; ?>

                                    <div class="tim_clr tim_detail_line">
                                        <div class="tim_col_3">
                                            <?php _e( 'Occupancy', $plugin_name ); ?>
                                        </div>
                                        <div class="tim_col_9">
                                            <i class="fa fa-user"></i> <b><?php echo $hotel_room['max_occupancy']; ?></b> <small>(<?php _e( 'Maximum', $plugin_name ); ?>)</small>  
                                        </div>
                                    </div>

                                    <div class="tim_clr tim_detail_line">
                                        <div class="tim_col_3">
                                            <?php _e( 'Room size', $plugin_name ); ?>
                                        </div>
                                        <div class="tim_col_9">
                                            <?php echo $hotel_room['size']; ?> m<sup>2</sup>
                                        </div>
                                    </div>

                                    <?php
                                    if ( $hotel_room['facilities'] ){
                                        ?>
                                        <div class="tim_clr tim_detail_line">
                                            <div class="tim_col_3">
                                                <?php _e( 'Amenities', $plugin_name ); ?>
                                            </div>
                                            <div class="tim_col_9">
                                                <ul>
                                                    <?php
                                                    foreach ( $hotel_room['facilities'] as $facility ) {
                                                        ?>
                                                        <li><i class="fa fa-check"></i> <?php echo $facility; ?></li>
                                                        <?php
                                                    }
                                                    ?>
                                                </ul>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                }
                ?>                
            </div>
        </div>

        <div id="tim_detail_rates" class="tim_detail_line">
            <?php
            render_check_availability_widget( $item );
            ?>
        </div>

        <div class="tim_row">
            <div class="tim_col_12">
                <h3><?php _e( 'Amenities', $plugin_name ); ?></h3>
                <div class="tim_padder_15">
                    <ul>
                        <?php
                        if ( $item['facilities'] ){
                            foreach ( $item['facilities'] as $facility ) {
                                ?>
                                <li><i class="fa fa-check"></i> <?php echo $facility; ?></li>
                                <?php
                            }
                        }
                        else{
                            ?>-<?php
                        }            
                        ?>
                    </ul>
                </div>
            </div>
            <?php
            /*
            <div class="tim_col_4">
                <h3><?php _e( 'HOTEL RATING EDITOR', $plugin_name ); ?></h3>
                Pending
                <br />

                <h3><?php _e( 'HOTEL RATING GUESTS', $plugin_name ); ?></h3>
                Pending
            </div>
            */
            ?>
        </div>

        <div class="tim_detail_line">
            <img src="<?php echo $plugin_url; ?>/public/img/share_tweet.gif" />
        </div>

        <?php
        if ( $item['notes'] != '' || $item['policies'] != ''){
            ?>
            <div class="tim_detail_line">
                <?php
                if ( $item['notes'] != '' ){
                    ?><b><?php _e( 'Notes', $plugin_name ); ?>:</b> <?php

                    ?><ul><?php
                    $notes = preg_split('/\R/', $item['notes']);

                    foreach ( $notes as $note ) {
                        ?>
                        <li><?php echo $note; ?></li>
                        <?php
                    }
                    ?></ul><br /><?php
                }

                /*if ( $item['policies'] != '' ){
                    ?><b><?php _e( 'Policies', $plugin_name ); ?>: </b><?php

                    ?><ul><?php
                    $policies = preg_split('/\R/', $item['policies']);

                    foreach ( $policies as $policy ) {
                        ?>
                        <li><?php echo $policy; ?></li>
                        <?php
                    }
                    ?></ul><?php
                }*/
                ?>
            </div>
            <?php
        }
        ?>
    </div>

    <?php
    if ( $item['related_hotel_ids'] ){
        ?>
        <h3><?php _e( 'Related hotels', $plugin_name ); ?></h3>
        <div class="swiper-container tim-carrousel-slider">
            <div class="swiper-wrapper">
                <?php
                $tim_travel_manager_hotel->render_hotel_list( 'related', $item['id'], '', $item['related_hotel_ids'] );
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