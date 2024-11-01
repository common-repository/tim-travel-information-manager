<?php

$hotel_facilities = $public_data->get_miscellaneous( TIM_TRAVEL_MANAGER_POST_TYPE_FACILITIES, 'hotel' );
$hotel_room_facilities = $public_data->get_miscellaneous( TIM_TRAVEL_MANAGER_POST_TYPE_FACILITIES, 'hotel_room' );

$facilities = $public_data->find_item_ids_in_array( $postmeta['facility_ids'], $hotel_facilities, 'name', $content_language );

$photos = array();
if ( $postmeta['photos'] ){
    foreach ( $postmeta['photos'] as $photo ) {
        $photo = array(
            'image' => $photo->image, 
            'title' => $photo->title->$content_language
        );

        array_push( $photos, $photo );
    }
}

$hotel_rooms = array();
if ( $postmeta['hotel_rooms'] ) {
    foreach ( $postmeta['hotel_rooms'] as $hotel_room ) {
        $room_facilities = $public_data->find_item_ids_in_array( $hotel_room->facility_ids, $hotel_room_facilities, 'name', $content_language );

        if ( $hotel_room->name->$content_language ) {
            $hotel_room_name = $hotel_room->name->$content_language;
        } else {
            $hotel_room_name = $hotel_room->name->en;
        }

        $hotel_room_ideal_for = $hotel_room->ideal_for->$content_language ? $hotel_room->ideal_for->$content_language : $hotel_room->ideal_for->en;
        
        $hotel_room_description = $hotel_room->description->$content_language ? $hotel_room->description->$content_language : $hotel_room->description->en;

        $hotel_room_bed_configuration = $hotel_room->bed_configuration->$content_language ? $hotel_room->bed_configuration->$content_language : $hotel_room->bed_configuration->en;


        $hotel_room_type_name = $hotel_room->room_type->name->$content_language ? $hotel_room->room_type->name->$content_language : $hotel_room->room_type->name->en;

        $hotel_room_occupancy_type_name = $hotel_room->occupancy_type->name->$content_language ? $hotel_room->occupancy_type->name->$content_language : $hotel_room->occupancy_type->name->en;

        $room = array(
            'id' => $hotel_room->id, 
            'logo' => $hotel_room->logo, 
            'name' => $hotel_room_name, 
            'ideal_for' => $hotel_room_ideal_for, 
            'description' => $hotel_room_description,  
            'bed_configuration' => $hotel_room_bed_configuration, 
            'max_occupancy' => $hotel_room->max_occupancy, 
            'children_allowed' => $hotel_room->children_allowed, 
            'total_bedrooms' => $hotel_room->total_bedrooms, 
            'size' => $hotel_room->size, 
            'meals_included' => $hotel_room->meals_included, 
            'wifi' => $hotel_room->wifi, 
            'not_smoking' => $hotel_room->not_smoking, 
            
            'room_type_name' => $hotel_room_type_name, 
            'occupancy_type_name' => $hotel_room_occupancy_type_name, 
            'facilities' => $room_facilities, 
            // 'photos' => $room_photos, 
            'url_video' => $public_data->embed_video( $hotel_room->url_video->$content_language )
        );

        array_push( $hotel_rooms, $room );
    }
}

$location_name = $postmeta['location']->name->$content_language ? $postmeta['location']->name->$content_language : $postmeta['location']->name->en;

$product_category_name = $postmeta['product_category']->name->$content_language ? $postmeta['product_category']->name->$content_language : $postmeta['product_category']->name->en;

$name = $postmeta['name']->$content_language ? $postmeta['name']->$content_language : $postmeta['name']->en;

// var_dump($postmeta);

$itinerary = $postmeta['itinerary']->$content_language ? $postmeta['itinerary']->$content_language : $postmeta['itinerary']->en;

$address = $postmeta['address']->$content_language ? $postmeta['address']->$content_language : $postmeta['address']->en;

$item['geo_lat'] = $postmeta['geo_lat'];
$item['geo_lng'] = $postmeta['geo_lng'];
$item['geo_zoom'] = $postmeta['geo_zoom'];
$item['stars_rating'] = $postmeta['stars_rating'];
$item['check_in'] = $postmeta['check_in'];
$item['check_out'] = $postmeta['check_out'];

$item['hotel_rooms'] = $hotel_rooms;
// $item['taggings'] = ( count($postmeta['taggings']) ) ? $postmeta['taggings'] : '';
$item['location_name'] = $location_name;
$item['categories'] = $categories;
$item['facilities'] = $facilities;
$item['product_category_name'] = $product_category_name;

$item['name'] = $name;
$item['itinerary'] = $itinerary;
$item['url_video'] = $public_data->embed_video( $postmeta['url_video']->$content_language );
$item['address'] = $address;
$item['photos'] = $photos;

?>
<div class="tim_wrapper">
	<div class="tim_row">
        <div class="tim_col_7 tim_detail_title">
            <h1><?php echo $item['name']; ?></h1>
            <span>
                <?php
                for ($i = 0; $i < $item['stars_rating']; $i++){
                    ?><i class="fa fa-star"></i> <?php
                }
                ?>
            </span>
        </div>
        <div class="tim_col_5 tim_pull_right">
            <div class="tim_detail_menu_wrap">
                <ul class="tim_detail_menu">
                    <li>
                        <a href="javascript:void(0);" class="tablink active" onclick="timTabContent(event, 'timTabOverview');"><i class="fa fa-info-circle fa-lg"></i><span><?php _e( 'Detail', $plugin_name ); ?></span></a>
                    </li>
                    <li>
                        <a href="javascript:void(0);" class="tablink" onclick="timTabContent(event, 'timTabRooms');"><i class="fa fa-bed fa-lg"></i><span><?php _e( 'Rooms', $plugin_name ); ?></span></a>
                    </li>
                    <?php
                    if ( $item['url_video'] ) {
                        ?>
                        <li>
                            <a href="javascript:void(0);" class="tablink" onclick="timTabContent(event, 'timTabVideo');"><i class="fa fa-video-camera fa-lg"></i><span><?php _e( 'Video', $plugin_name ); ?></span>
                            </a>
                        </li><?php
                    }
                    if ( $item['geo_lat'] !== '' ) {
                        ?>
                        <li>
                            <a href="javascript:void(0);" class="tablink" onclick="timTabContent(event, 'timTabMap');"><i class="fa fa-map-marker fa-lg"></i><span><?php _e( 'Map', $plugin_name ); ?></span>
                            </a>
                        </li>
                        <?php
                    }
                    ?>
                </ul>
            </div>
        </div>
    </div>

    <div class="tim_detail">
    	<div class="tim_detail_content">
    		<div id="timTabOverview" class="tim_tab_content active">
                <?php
                if ( $item['photos'] ) {
                    ?>
                    <div class="swiper-container tim-detail-slider tim_detail_slider_modal">
                        <div class="swiper-wrapper">
                            <?php
                            foreach ( $item['photos'] as $photo ) {
                                ?>
                                <div class="swiper-slide">
                                    <img src="<?php echo $photo['image']; ?>" alt="<?php echo $photo['title']; ?>" title="<?php echo $photo['title']; ?>" />
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
                    <div class="tim_col_4">
                        <div class="tim_clr tim_detail_line">
                            <div class="tim_detail_line_label">
                                <i class="fa fa-map-marker fa-lg"></i><?php _e( 'Location', $plugin_name ); ?>
                            </div>
                            <div class="tim_detail_line_content">
                                <?php echo $item['location_name']; ?>
                            </div>
                        </div>
                    </div>
                    <div class="tim_col_4">
                        <div class="tim_clr tim_detail_line">
                            <div class="tim_detail_line_label">
                                <i class="fa fa-clock-o fa-lg"></i><?php _e( 'Category', $plugin_name ); ?>
                            </div>
                            <div class="tim_detail_line_content">
                                <?php echo $item['product_category_name']; ?>
                            </div>
                        </div>
                    </div>
                    <div class="tim_col_4">
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
                </div>

    		    <div class="tim_detail_desc">
                    <h2><?php _e( 'Hotel Overview', $plugin_name ); ?></h2>
                    <?php echo $item['itinerary']; ?>
    			</div>

                <?php
                if ( $item['facilities'] ) {
                    ?>
                    <h3><i class="fa fa-plus-square"></i> <?php _e( 'Included', $plugin_name ); ?></h3>
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

            <div id="timTabRooms" class="tim_tab_content">
                <h3><?php _e( 'Hotel Rooms', $plugin_name ); ?></h3>
                <?php
                foreach ( $item['hotel_rooms'] as $hotel_room ) {
                    $bed_configuration = $hotel_room['bed_configuration'] ? ' ('. $hotel_room['bed_configuration'] .')' : '';

                    ?>
                    <div class="tim_row">
                        <?php
                        if ( $hotel_room['logo'] ){
                            ?>
                            <div class="tim_col_3">
                                <img src="<?php echo $hotel_room['logo']; ?>" alt="<?php echo $hotel_room['name']; ?>" />
                            </div>
                            <?php
                        }
                        ?>
                        <div class="tim_col_9">
                            <h4 style="margin:0;"><?php echo $hotel_room['name']; ?></h4>
                            <i class="fa fa-bed"></i> <b><?php echo $hotel_room['occupancy_type_name']; ?></b><?php echo $bed_configuration; ?>
                            <?php
                            if ( $hotel_room['wifi'] ){
                                ?><span class="tim_label tim_label_success tim_label_rd"><?php _e( 'Wi-fi', $plugin_name ); ?></span> <?php
                            }
                            if ( $hotel_room['not_smoking'] ){
                                ?><span class="tim_label tim_label_success tim_label_rd"><?php _e( 'Not-smoking', $plugin_name ); ?></span> <?php
                            }
                            ?>
                            <p><?php echo $hotel_room['description']; ?></p>
                            
                            <small>
                                <b><?php _e( 'Amenities', $plugin_name ); ?></b>
                                <ul>
                                <?php
                                foreach ( $hotel_room['facilities'] as $facility ) {
                                    ?>
                                    <li class="tim_col_6"><i class="fa fa-check"></i> <?php echo $facility; ?></li>
                                    <?php
                                }
                                ?>
                                </ul>
                            </small>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>

    		<?php
            if ( $item['url_video'] ){
                ?>
                <!-- Video -->
                <div id="timTabVideo" class="tim_tab_content">
                    <h3><?php _e( 'Video', $plugin_name ); ?></h3>
                    <iframe class="tim_detail_video" src="<?php echo $item['url_video']; ?>" frameborder="0" allowfullscreen></iframe>
                </div>
                <?php
            }
            ?>

    		<?php
            if ( $item['geo_lat'] !== '' ){
                ?>
                <!-- Map -->
                <div id="timTabMap" class="tim_tab_content">
                    <h3><?php _e( 'Map', $plugin_name ); ?></h3>
                    <input type="hidden" id="tim_geoLatTab" value="<?php echo $item['geo_lat']; ?>" />
                    <input type="hidden" id="tim_geoLngTab" value="<?php echo $item['geo_lng']; ?>" />
                    <input type="hidden" id="tim_geoZoomTab" value="<?php echo $item['geo_zoom']; ?>" />

                    <div class="tim_detail_map_wrap">
                        <div class="tim_detail_map_labels">
                            <i class="fa fa-map-marker"></i><?php echo $item['name']; ?>
                            <div class="tim_detail_map_hr"></div>

                            <i class="fa fa-location-arrow"></i><?php echo $item['address']; ?>
                        </div>

                        <div id="tim_googleMapTab" class="tim_detail_map"></div>
                    </div>
                </div>
                <?php
            }
            ?>
    	</div>
    </div>
</div>