<article class="hentry tim_wrapper">
	
	<div class="tim_detail">
        <div class="tim_row">
            <div class="tim_col_8 tim_detail_title">
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
                        <div class="tim_item_price">
                            <div class="tim_item_price_box">
                                <div class="tim_item_price_amount"><?php echo $item['min_rate']; ?></div>
                                <div class="tim_item_price_label">
                                    <?php _e( 'per night', $plugin_name ); ?><br />
                                    <span><?php _e( 'double occupancy', $plugin_name ); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tim_row tim_detail_line">
                        <div class="tim_col_5 tim_detail_line_label">
                            <i class="fa fa-map-marker fa-lg"></i><?php _e( 'Location', $plugin_name ); ?>
                        </div>
                        <div class="tim_col_7 tim_detail_line_content">
                            <?php echo $item['location_name']; ?>
                        </div>
                    </div>

                    <div class="tim_row tim_detail_line">
                        <div class="tim_col_5 tim_detail_line_label">
                            <i class="fa fa-map-marker fa-lg"></i><?php _e( 'Entrance', $plugin_name ); ?>
                        </div>
                        <div class="tim_col_7 tim_detail_line_content">
                            <ul>
                                <li><?php _e( 'Check-in', $plugin_name ); ?>: <?php echo $item['check_in']; ?></li>
                                <li><?php _e( 'Check-out', $plugin_name ); ?>: <?php echo $item['check_out']; ?></li>
                            </ul>
                        </div>
                    </div>

                    <div class="tim_detail_line">
                        <a id="_tim_detail_rates" href="javascript:void(0);" class="tim-btn tim-btn-block"><b><?php _e( 'Check rates', $plugin_name ); ?></b></a>
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
                    ?>
                    <li><a href="javascript:void(0);"><i class="fa fa-commenting fa-lg"></i><span><?php _e( 'Reviews', $plugin_name ); ?></span></a></li>
                </ul>
            </div>

            <div class="tim_detail_content">
                <div class="tim_detail_line">
                    <div class="tim_detail_desc">
                        <h2><?php _e( 'Information', $plugin_name ); ?></h2>
                        <?php echo $item['long_description']; ?>
                    </div>
                </div>

                <div id="tim_detail_rates" class="tim_detail_line">
                    <div style="background:#333; padding:15px; overflow:hidden;">
                        <h3 style="color:#fff;"><?php _e( 'Check rates at', $plugin_name ); ?> <?php echo $item['name']; ?></h3>
                        <?php
                        require_once( $check_rate_widget );

                        render_check_rate_widget( $item, $checkIn, $checkInDB, $checkOut, $checkOutDB, $total_rooms, $arrayAdults, $arrayChildren, $arrayInfants );
                        ?>
                    </div>

                    <table class="tim_table">
                        <thead>
                            <tr>
                                <th><?php _e( 'Room type', $plugin_name ); ?></th>
                                <th><?php _e( 'Maximum', $plugin_name ); ?></th>
                                <th><?php _e( 'Room details', $plugin_name ); ?></th>
                                <th style="min-width: 180px;"><?php _e( 'Price', $plugin_name ); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ( $item['hotel_rooms'] as $hotel_room ) {
                                ?>
                                <tr class="tim_table_tr_align_top">
                                    <td data-th="<?php _e( 'Room type', $plugin_name ); ?>">
                                        <div class="tim_table_td_title"><?php echo $hotel_room['name']; ?></div>
                                        
                                        <?php
                                        if ( $hotel_room['logo'] != '' ){
                                            ?>
                                            <a href="javascript:void(0)" onclick="timAjaxContent('hotel-room', '<?php echo $hotel_room['id']; ?>')">
                                                <img src="<?php echo $hotel_room['logo']; ?>" alt="<?php echo $hotel_room['name']; ?>" style="max-width:200px;" />
                                            </a>
                                            <?php
                                        }
                                        ?>
                                    </td>
                                    <td data-th="<?php _e( 'Maximum', $plugin_name ); ?>" class="tim_align_center">
                                        <i class="fa fa-user"></i> <b><?php echo $hotel_room['max_room_occupancy']; ?><b/>
                                    </td>
                                    <td data-th="<?php _e( 'Room details', $plugin_name ); ?>">
                                        <p>
                                            <i class="fa fa-bed"></i> <b><?php echo $hotel_room['occupancy_name']; ?></b> ( <em><?php echo $hotel_room['bed_configuration']; ?></em> )
                                        </p>
                                        <p><?php echo $hotel_room['description']; ?></p>
                                        + <a href="javascript:void(0)" onclick="timAjaxContent('hotel-room', '<?php echo $hotel_room['id']; ?>')"><?php _e( 'See all room amenities', $plugin_name ); ?></a>
                                    </td>
                                    <td data-th="<?php _e( 'Price', $plugin_name ); ?>" class="tim_table_total">
                                        <div class="tim_show_room_price" style="display:none;">
                                            <div id="is_max_occupancy_allowed_<?php echo $hotel_room['id']; ?>">
                                                <div id="tim_subtotal_cost_<?php echo $hotel_room['id']; ?>"></div>
                                                <div id="tim_total_nights_<?php echo $hotel_room['id']; ?>"></div>
                                                <div id="tim_total_rooms_<?php echo $hotel_room['id']; ?>"></div>
                                                <br /><br />
                                                <a href="javascript:void(0)" class="tim-btn" onclick="timAddRoomToOrder()"><?php _e( 'Add to order', $plugin_name ); ?></a>
                                            </div>
                                            <div id="is_max_occupancy_exceeded_<?php echo $hotel_room['id']; ?>"></div>
                                        </div>
                                        <div class="tim_hide_room_price">
                                            <?php _e( 'Enter dates', $plugin_name ); ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                <div class="tim_row">
                    <div class="tim_col_8">
                        <div class="tim_row tim_detail_line">
                            <div class="tim_col_4 tim_detail_line_label">
                                <i class="fa fa-flag fa-lg"></i><?php _e( 'Category', $plugin_name ); ?>
                            </div>
                            <div class="tim_col_8 tim_detail_line_content">
                                <ul class="tim_tags">
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
                            <div class="tim_col_4 tim_detail_line_label">
                                <i class="fa fa-plus-square fa-lg"></i><?php _e( 'Amenities', $plugin_name ); ?>
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
                        HOTEL RATING EDITOR<br />
                        HOTEL RATING GUESTS
                    </div>
                </div>

                <div class="tim_detail_line">
                    <div class="tim_detail_line_label">
                        <?php _e( 'Additional info', $plugin_name ); ?>
                    </div>
                    <b><?php _e( 'Notes', $plugin_name ); ?>: </b>
                    <ul>
                        <li>
                            <?php _e( 'Children rate', $plugin_name ); ?>: <?php _e( 'Between', $plugin_name ); ?> <?php echo $item['min_children_age']; ?>-<?php echo $item['max_children_age']; ?> <?php _e( 'years old', $plugin_name ); ?>.
                        </li>
                        <?php
                        if ( $item['notes'] != '' ){
                            $notes = preg_split('/\R/', $item['notes']);

                            foreach ( $notes as $note ) {
                                ?>
                                <li><?php echo $note; ?></li>
                                <?php
                            }
                        }
                        ?>
                    </ul><br />
                    <?php

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
 
            </div>

            <h3><?php _e( 'Related hotels', $plugin_name ); ?></h3>
            <div class="swiper-container tim_list_carrousel">
                <div class="swiper-wrapper">
                    <?php
                    $tim_travel_manager_hotel->render_hotel_list( 'related', get_the_ID() );
                    ?>
                </div>
                <div class="swiper-pagination swiper-pagination-black"></div>
                <div class="swiper-button-next swiper-button-white"></div>
                <div class="swiper-button-prev swiper-button-white"></div>
            </div>

        </div>

    </div>

</article>