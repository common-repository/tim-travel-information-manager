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
                        <div class="tim_item_price">
                            <div class="tim_item_price_box">
                                <div class="tim_item_price_amount"><?php echo $item['min_rate']; ?></div>
                                <div class="tim_item_price_label"><?php _e( 'per person', $plugin_name ); ?></div>
                            </div>
                        </div>
                    </div>

                    <div class="tim_row tim_detail_line">
                        <div class="tim_col_5 tim_detail_line_label">
                            <i class="fa fa-clock-o fa-lg"></i><?php _e( 'Duration', $plugin_name ); ?>
                        </div>
                        <div class="tim_col_7 tim_detail_line_content">
                            <?php echo $item['days']; ?> <?php _e( 'days', $plugin_name ); ?> / <?php echo $item['nights']; ?> <?php _e( 'nights', $plugin_name ); ?>
                        </div>
                    </div>

                    <div class="tim_row tim_detail_line">
                        <div class="tim_col_5 tim_detail_line_label">
                            <i class="fa fa-map-marker fa-lg"></i><?php _e( 'Departure', $plugin_name ); ?>
                        </div>
                        <div class="tim_col_7 tim_detail_line_content">
                            <?php echo $item['departureLocation_name']; ?>
                        </div>
                    </div>

                    <div class="tim_row tim_detail_line">
                        <div class="tim_col_5 tim_detail_line_label">
                            <i class="fa fa-map-marker fa-lg"></i><?php _e( 'Arrival', $plugin_name ); ?>
                        </div>
                        <div class="tim_col_7 tim_detail_line_content">
                            <?php echo $item['arrivalLocation_name']; ?>
                        </div>
                    </div>

                </div>

            </div>
        </div>

        <div class="entry-content">
            
            <div class="tim_detail_menu_wrap">
                <ul class="tim_detail_menu">
                    <li class="tim_detail_booking"><a href="javascript:void(0);" onclick="timScrollTo('tim_detail_booking_form')"><?php _e( 'Book', $plugin_name ); ?></a></li>
                    <li><a href="javascript:void(0);" class="active"><i class="fa fa-info-circle fa-lg"></i><span><?php _e( 'Itinerary', $plugin_name ); ?></span></a></li>
                    <?php
                    if ( $item['url_video'] != '' ){
                        ?><li><a href="javascript:void(0);" onclick="timScrollTo('tim_detail_video')"><i class="fa fa-video-camera fa-lg"></i><span><?php _e( 'Video', $plugin_name ); ?></span></a></li><?php
                    }
                    ?>
                    <li><a href="javascript:void(0);" onclick="timScrollTo('tim_detail_map')"><i class="fa fa-map-marker fa-lg"></i><span><?php _e( 'Map', $plugin_name ); ?></span></a></li>
                </ul>
            </div>

            <div class="tim_detail_content">
                <div class="tim_row">
                    <div class="tim_col_8 tim_detail_desc">
                        
                        <div class="tim_row">
                        <?php
                        foreach ( $item['itineraries'] as $itinerary ) {
                            ?>
                            <div class="tim_detail_line">
                                <div class="tim_row tim_detail_day">
                                    <div class="tim_col_1">
                                        <span class="tim_detail_day_number"><?php _e( 'Day', $plugin_name ); ?> <?php echo $itinerary['day_number']; ?></span>
                                    </div>
                                    <div class="tim_col_11">
                                        <h3><?php echo $itinerary['name']; ?></h3>
                                        
                                        <?php
                                        if ( $itinerary['logo'] != '' ){
                                            ?>
                                            <a class="tim_fancybox" rel="gallery" href="<?php echo $itinerary['logo']; ?>" title="<?php _e( 'Day', $plugin_name ); ?> <?php echo $itinerary['day_number']; ?> - <?php echo $itinerary['name']; ?>">
                                                <img src="<?php echo $itinerary['logo']; ?>" alt="<?php echo $itinerary['name']; ?>" />
                                            </a>
                                            <?php
                                        }
                                        ?>

                                        <div class="tim_detail_day_desc">
                                            <p><?php echo $itinerary['description']; ?></p>
                                            
                                            <?php
                                            if ( $itinerary['hotels_included'] ){
                                                ?>
                                                <div>
                                                    <i class="fa fa-bed" title="<?php _e( 'Hotels included', $plugin_name ); ?>"></i> 
                                                    <?php
                                                    $i = 1;
                                                    foreach ( $itinerary['hotels_included'] as $hotel ) {
                                                        $sepearator = ( $i < count( $itinerary['hotels_included'] ) ) ? ' / ' : '';

                                                        ?>
                                                        <a href="javascript:void(0)" onclick="timAjaxContent('hotel', '<?php echo $hotel['id']; ?>')"><?php echo $hotel['name']; ?> (<?php echo $hotel['room_name']; ?>)</a>
                                                        <?php echo $sepearator;
                                                        $i++;
                                                    }
                                                    ?>
                                                </div>
                                                <?php
                                            }

                                            if ( $itinerary['tours_included'] ){
                                                ?>
                                                <div>
                                                    <i class="fa fa-binoculars" title="<?php _e( 'Tours included', $plugin_name ); ?>"></i> 
                                                    <?php
                                                    $i = 1;
                                                    foreach ( $itinerary['tours_included'] as $tour ) {
                                                        $sepearator = ( $i < count( $itinerary['tours_included'] ) ) ? ' / ' : '';

                                                        ?>
                                                        <a href="javascript:void(0)" onclick="timAjaxContent('tour', '<?php echo $tour['id']; ?>')"><?php echo $tour['name']; ?></a>
                                                        <?php echo $sepearator;
                                                        $i++;
                                                    }
                                                    ?>
                                                </div>
                                                <?php
                                            }
                                            ?>
                                            <i class="fa fa-cutlery" title="<?php _e( 'Meals included', $plugin_name ); ?>"></i> 
                                            <?php
                                            echo $itinerary['meals_included'];
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                        </div>

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

                        <div class="tim_row tim_detail_line">
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
                      
                        <?php
                        if ( $item['url_video'] != '' ){
                            ?>
                            <div id="tim_detail_video" class="tim_detail_line">
                                <h3><i class="fa fa-video-camera"></i> <?php _e( 'Video', $plugin_name ); ?></h3>
                                <iframe class="tim_detail_video" src="<?php echo $item['url_video']; ?>" frameborder="0" allowfullscreen></iframe>
                            </div>
                            <?php
                        }

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

                                    <i class="fa fa-clock-o"></i><?php echo $item['days']; ?> <?php _e( 'days', $plugin_name ); ?> / <?php echo $item['nights']; ?> <?php _e( 'nights', $plugin_name ); ?>
                                </div>

                                <div id="tim_googleMap" class="tim_detail_map" value="package"></div>
                            </div>
                        </div>

                        <div class="tim_detail_line">
                            <h3><?php _e( 'Available Dates & Prices', $plugin_name ); ?></h3>
                            <table class="tim_table">
                                <thead>
                                    <tr>
                                        <th><?php _e( 'Availability', $plugin_name ); ?></th>
                                        <th><?php _e( 'Season', $plugin_name ); ?></th>
                                        <th><?php _e( 'Dates', $plugin_name ); ?></th>
                                        <th><?php _e( 'Price per person', $plugin_name ); ?></th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ( $item['package_rates'] as $rate ) {
                                        ?>
                                        <tr>
                                            <td data-th="<?php _e( 'Availability', $plugin_name ); ?>"><i class="fa fa-check-circle"></i></td>
                                            <td data-th="<?php _e( 'Season', $plugin_name ); ?>"><?php echo $rate['season']; ?></td>
                                            <td data-th="<?php _e( 'Dates', $plugin_name ); ?>"><?php echo $rate['date']; ?></td>
                                            <td data-th="<?php _e( 'Price per person', $plugin_name ); ?>" class="tim_table_total"><?php echo $rate['price']; ?></td>
                                            <td><a href="javascript:void(0)" class="tim-btn tim-btn-sm" onclick="timScrollTo('tim_detail_booking_form')"><?php _e( 'Check price', $plugin_name ); ?></a></td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>

                    </div>
                    <div class="tim_col_4">
                        <div id="tim_detail_booking_form" class="tim_detail_booking_form">
                            <h4><?php _e( 'BOOKING FORM', $plugin_name ); ?></h4>

                            <form>
                                <label><?php _e( 'Arrival date', $plugin_name ); ?></label><br>
                                <input type="text" /><br>

                                <label><?php _e( 'Adults', $plugin_name ); ?></label><br>
                                <input type="text" /><br>

                                <label><?php _e( 'Children', $plugin_name ); ?></label><br>
                                <input type="text" /><br><br>

                                <button><?php _e( 'Add to cart', $plugin_name ); ?></button>
                            </form>
                        </div>
                    </div>
                </div>

                <?php
                if ( $item['notes'] != '' || $item['policies'] != ''){
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

            <h3><?php _e( 'Related packages', $plugin_name ); ?></h3>
            <div class="swiper-container tim_list_carrousel">
                <div class="swiper-wrapper">
                    <?php
                    $tim_travel_manager_package->render_package_list( 'related', get_the_ID() );
                    ?>
                </div>
                <div class="swiper-pagination swiper-pagination-black"></div>
                <div class="swiper-button-next swiper-button-white"></div>
                <div class="swiper-button-prev swiper-button-white"></div>
            </div>

        </div>

    </div>

</article>