<div class="tim_clr">
    <div class="tim_col_7 tim_detail_title">
        <h1><?php echo $item['name']; ?></h1>
    </div>
    <div class="tim_col_5 tim_pull_right">
        <div class="tim_detail_menu_wrap">
            <ul class="tim_detail_menu">
                <li><a href="javascript:void(0);" class="active"><i class="fa fa-info-circle fa-lg"></i><span><?php _e( 'Detail', $plugin_name ); ?></span></a></li>
                <?php
                if ( $item['url_video'] !== '' ){
                    ?><li><a href="javascript:void(0);" onclick="timScrollTo('tim_detail_video')"><i class="fa fa-video-camera fa-lg"></i><span><?php _e( 'Video', $plugin_name ); ?></span></a></li><?php
                }
                ?>
                <li><a href="javascript:void(0);" onclick="timScrollTo('tim_detail_map')"><i class="fa fa-map-marker fa-lg"></i><span><?php _e( 'Map', $plugin_name ); ?></span></a></li>
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
                    <i class="fa fa-map-marker fa-lg"></i><?php _e( 'Location', $plugin_name ); ?>
                </div>
                <div class="tim_detail_line_content">
                    <ul>
                        <li><?php _e( 'Departure', $plugin_name ); ?>: <b><?php echo $item['departure_location_name'] ?></b></li>
                        <li><?php _e( 'Arrival', $plugin_name ); ?>: <b><?php echo $item['arrival_location_name'] ?></b></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="tim_col_3">
            <div class="tim_clr tim_detail_line">
                <div class="tim_detail_line_label">
                    <i class="fa fa-clock-o fa-lg"></i><?php _e( 'Duration', $plugin_name ); ?>
                </div>
                <div class="tim_detail_line_content">
                    <?php echo $item['days']; ?> <?php _e( 'days', $plugin_name ); ?> / <?php echo $item['nights']; ?> <?php _e( 'nights', $plugin_name ); ?>
                </div>
            </div>
        </div>
        <div class="tim_col_3">
            <div class="tim_clr tim_detail_line">
                <div class="tim_detail_line_label">
                    <i class="fa fa-flag fa-lg"></i><?php _e( 'Category', $plugin_name ); ?>
                </div>
                <div class="tim_detail_line_content">
                    <?php echo $item['product_category_name']; ?>
                </div>
            </div>
        </div>
        <div class="tim_col_3">
            <div class="tim_row tim_detail_line">
                <div class="tim_detail_line_label">
                    <i class="fa fa-clock-o fa-lg"></i><?php _e( 'Price', $plugin_name ); ?>
                </div>
                <div class="tim_detail_line_content">
                    <div class="tim_item_price_box">
                        <span class="tim_item_price_amount"><?php echo $item['rate_from']; ?></span> <span class="tim_item_price_label"><?php _e( 'per person', $plugin_name ); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="tim_detail_booking">
        <a href="javascript:void(0);" onclick="timScrollTo('tim_detail_booking_form')" class="tim-btn"><?php _e( 'Book', $plugin_name ); ?></a>
    </div>

    <div class="tim_detail_content">
        <div class="tim_row">
            <div class="tim_col_8 tim_detail_desc">
                <h2><?php _e( 'Itinerary', $plugin_name ); ?></h2>
                
                <div class="tim_row">
                <?php
                foreach ( $item['package_days'] as $package_day ) {
                    ?>
                    <div class="tim_detail_day">
                        <h3>
                            <span class="tim_detail_day_number"><?php _e( 'Day', $plugin_name ); ?> <?php echo $package_day['day_number']; ?></span> 
                            <?php echo $package_day['name']; ?>
                        </h3>
                        
                        <?php
                        if ( $package_day['logo'] ) {
                            ?>
                            <div class="tim_detail_day_image">
                                <!-- <a class="tim_fancybox" rel="gallery" href="<?php echo $package_day['logo']; ?>" title="<?php _e( 'Day', $plugin_name ); ?> <?php echo $package_day['day_number']; ?> - <?php echo $package_day['name']; ?>"> -->
                                    <img 
                                        src=data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw== 
                                        data-src="<?php echo $package_day['logo']; ?>" 
                                        alt="<?php echo $package_day['name']; ?>" 
                                        class="b-lazy" />
                                <!-- </a> -->

                                <div class="tim_detail_day_video">
                                    <?php
                                    if ( $item['url_video'] ) {
                                        ?>
                                        <a class="tim_fancybox tim_fancybox.iframe" rel="gallery" href="<?php echo $item['url_video']; ?>" title="<?php echo $item['name']; ?>">
                                            <i class="fa fa-video-camera"></i>
                                        </a>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                            <?php
                        }
                        ?>

                        <div class="tim_detail_day_desc">
                            <?php
                            if ( $package_day['description'] ) {
                                ?><p><?php echo $package_day['description']; ?></p><?php
                            }

                            if ( $package_day['hotel_rooms'] ) {
                                ?>
                                <div>
                                    <i class="fa fa-bed" title="<?php _e( 'Hotels included', $plugin_name ); ?>"></i> 
                                    <?php
                                    foreach ( $package_day['hotel_rooms'] as $hotel_room ) {
                                        if ( $hotel_room->enableHotelModal ) {
                                            ?>
                                            <a href="javascript:void(0)" onclick="timAjaxContent('hotel', '<?php echo $hotel_room->hotel_id; ?>')" class="tim_label tim_label_default tim_label_md">
                                                <b><?php echo $hotel_room->hotel_name; ?></b> (<?php echo $hotel_room->name; ?>)
                                            </a> &nbsp; 
                                            <?php
                                        } else {
                                            if (is_string($hotel_room->hotel_name)) {
                                                ?>
                                                <span class="tim_label tim_label_md">
                                                    <b><?php echo $hotel_room->hotel_name; ?></b> (<?php echo $hotel_room->name; ?>)
                                                </span> &nbsp; 
                                                <?php
                                            }
                                        }
                                    }
                                    ?>
                                </div>
                                <?php
                            }

                            if ( $package_day['tour_schedules'] ) {
                                ?>
                                <div>
                                    <i class="fa fa-binoculars" title="<?php _e( 'Tours included', $plugin_name ); ?>"></i> 
                                    <?php
                                    foreach ( $package_day['tour_schedules'] as $tour_schedule ) {
                                        if ($tour_schedule->enableTourModal) {
                                            ?>
                                            <a href="javascript:void(0)" onclick="timAjaxContent('tour', '<?php echo $tour_schedule->tour_id; ?>')" class="tim_label tim_label_default tim_label_md">
                                                <b><?php echo $tour_schedule->tour_name; ?></b> / <?php echo $tour_schedule->tour_option_name; ?> (<?php echo $tour_schedule->departure; ?>)
                                            </a> &nbsp; 
                                            <?php
                                        } else {
                                            ?>
                                            <span class="tim_label tim_label_md">
                                                <b><?php echo $tour_schedule->tour_name; ?></b> / <?php echo $tour_schedule->tour_option_name; ?> (<?php echo $tour_schedule->departure; ?>)
                                            </span> &nbsp; 
                                            <?php
                                        }
                                    }
                                    ?>
                                </div>
                                <?php
                            }

                            if ( $package_day['meals_included'] ) {
                                ?>
                                <i class="fa fa-cutlery" title="<?php _e( 'Meals included', $plugin_name ); ?>"></i> 
                                <?php echo $package_day['meals_included']; ?>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                    <?php
                }
                ?>
                </div>

                <div class="tim_row tim_detail_line">
                    <div class="tim_col_6">
                        <?php
                        if ( $item['facilities'] ) {
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

                ?>
                <div id="tim_detail_map" class="tim_detail_line">
                    <h3><?php _e( 'Map', $plugin_name ); ?></h3>

                    <input type="hidden" id="tim_geoLat" value="<?php echo $item['geo_lat']; ?>" />
                    <input type="hidden" id="tim_geoLng" value="<?php echo $item['geo_lng']; ?>" />
                    <input type="hidden" id="tim_geoZoom" value="<?php echo $item['geo_zoom']; ?>" />

                    <div class="tim_detail_map_wrap">
                        <div class="tim_detail_map_labels">
                            <i class="fa fa-clock-o"></i><?php echo $item['days']; ?> <?php _e( 'days', $plugin_name ); ?> / <?php echo $item['nights']; ?> <?php _e( 'nights', $plugin_name ); ?>
                            <div class="tim_detail_map_hr"></div>

                            <i class="fa fa-map-marker"></i><?php _e( 'Departure', $plugin_name ); ?>: <b><?php echo $item['departure_location_name']; ?></b><br />
                            <i class="fa fa-map-marker"></i><?php _e( 'Arrival', $plugin_name ); ?>: <b><?php echo $item['arrival_location_name']; ?></b>
                        </div>

                        <div id="tim_googleMap" class="tim_detail_map" value="package"></div>
                    </div>
                </div>
            </div>
            <div class="tim_col_4">
                <div id="tim_detail_booking_form">
                    <?php
                    render_check_rate_widget( $item, $checkRateLayoutId );
                    ?>
                </div>
            </div>
        </div>

        <div class="tim_detail_line">
            <img src="<?php echo $plugin_url; ?>/public/img/share_tweet.gif" />
        </div>

        <div class="tim_detail_line">
            <div class="tim_detail_line_label">
                <?php _e( 'Additional info', $plugin_name ); ?>
            </div>
            
            <b><?php _e( 'Notes', $plugin_name ); ?>: </b>
            <ul>
                <li><?php _e( 'Children age', $plugin_name ); ?>: <?php echo $item['min_children_age']; ?>-<?php echo $item['max_children_age']; ?> <?php _e( 'years old', $plugin_name ); ?></li>
                <li><?php _e( 'Minimum persons required', $plugin_name ); ?>: <?php echo $item['min_pax_required']; ?></li>

                <?php
                $notes = preg_split('/\R/', $item['notes']);

                foreach ( $notes as $note ) {
                    ?>
                    <li><?php echo $note; ?></li>
                    <?php
                }
                ?>
            </ul>    

            <?php
            if ( $item['policies'] !== '' ) {
                ?>
                <br />

                <b><?php _e( 'Policies', $plugin_name ); ?>: </b>
                <ul>
                    <?php
                    $policies = preg_split('/\R/', $item['policies']);

                    foreach ( $policies as $policy ) {
                        ?>
                        <li><?php echo $policy; ?></li>
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
    if ( $item['related_package_ids'] ) {
    ?>
        <h3><?php _e( 'Related packages', $plugin_name ); ?></h3>
        <div class="swiper-container tim-carrousel-slider">
            <div class="swiper-wrapper">
                <?php
                $tim_travel_manager_package->render_package_list( 'related', get_the_ID(), '', $item['related_package_ids'] );
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

<script type="text/javascript">
    // var timLabels = {};
    // jQuery( function() {
    //     timProductDatePicker('package', 1, '<?php echo $content_language; ?>');
    // });
</script>