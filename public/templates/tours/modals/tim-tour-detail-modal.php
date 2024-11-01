<?php

$tour_facilities = $public_data->get_miscellaneous( TIM_TRAVEL_MANAGER_POST_TYPE_FACILITIES, 'tour' );
$tour_wtobrings  = $public_data->get_miscellaneous( TIM_TRAVEL_MANAGER_POST_TYPE_WTOBRING, 'tour' );

$facilities = $public_data->find_item_ids_in_array( $postmeta['facility_ids'], $tour_facilities, 'name', $content_language );
$wtobrings  = $public_data->find_item_ids_in_array( $postmeta['what_to_bring_ids'], $tour_wtobrings, 'name', $content_language );

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

$item['geo_lat']       = $postmeta['geo_lat'];
$item['geo_lng']       = $postmeta['geo_lng'];
$item['geo_zoom']      = $postmeta['geo_zoom'];
// $item['duration']      = $public_data->format_duration_time( $postmeta['duration'] );
$item['duration']      = $postmeta['duration'] .' '. __( $postmeta['duration_time_unit'], $plugin_name );
$item['location_name'] = $postmeta['location']->name->$content_language;
$item['departures']    = $postmeta['departures'];
$item['facilities']    = $facilities;
$item['wtobrings']     = $wtobrings;

$item['name']          = $postmeta['name']->$content_language;
$item['itinerary']     = $postmeta['itinerary']->$content_language;
$item['url_video']     = $public_data->embed_video( $postmeta['url_video']->$content_language );
$item['address']       = $postmeta['address']->$content_language;
$item['url_video']     = $public_data->embed_video( $postmeta['url_video']->$content_language );
$item['photos']        = $photos;

?>
<div class="tim_wrapper">
    <div class="tim_clr tim_detail_title_wrapperx">
        <div class="tim_col_7 tim_detail_title">
            <h1><?php echo $item['name']; ?></h1>
        </div>
        <div class="tim_col_5 tim_pull_right">
            <div class="tim_detail_menu_wrap">
                <ul class="tim_detail_menu">
                    <li>
                        <a href="javascript:void(0);" class="tablink active" onclick="timTabContent(event, 'timTabOverview');"><i class="fa fa-info-circle fa-lg"></i><span><?php _e( 'Detail', $plugin_name ); ?></span></a>
                    </li>
                    <?php
                    if ( $item['url_video'] ){
                        ?>
                        <li>
                            <a href="javascript:void(0);" class="tablink" onclick="timTabContent(event, 'timTabVideo');"><i class="fa fa-video-camera fa-lg"></i><span><?php _e( 'Video', $plugin_name ); ?></span></a>
                        </li><?php
                    }
                    if ( $item['geo_lat'] !== '' ){
                        ?>
                        <li>
                            <a href="javascript:void(0);" class="tablink" onclick="timTabContent(event, 'timTabMap');"><i class="fa fa-map-marker fa-lg"></i><span><?php _e( 'Map', $plugin_name ); ?></span></a>
                        </li><?php
                    }
                    ?>
                </ul>
            </div>
        </div>
    </div>

    <div class="tim_detail">
    	<div class="tim_detail_content">
            <!-- Overview -->
    		<div id="timTabOverview" class="tim_tab_content active">
    			<?php
                if ( $item['photos'] ){
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
                                <i class="fa fa-map-marker fa-lg"></i><?php _e( 'Destination', $plugin_name ); ?>
                            </div>
                            <div class="tim_detail_line_content">
                                <?php echo $item['location_name']; ?>
                            </div>
                        </div>
                    </div>
                    <div class="tim_col_4">
                        <div class="tim_clr tim_detail_line">
                            <div class="tim_detail_line_label">
                                <i class="fa fa-calendar fa-lg"></i><?php _e( 'Schedules', $plugin_name ); ?>
                            </div>
                            <div class="tim_detail_line_content">
                                <?php echo $item['departures']; ?>
                            </div>
                        </div>
                    </div>
                    <div class="tim_col_4">
                        <div class="tim_clr tim_detail_line">
                            <div class="tim_detail_line_label">
                                <i class="fa fa-clock-o fa-lg"></i><?php _e( 'Duration', $plugin_name ); ?>
                            </div>
                            <div class="tim_detail_line_content">
                                <?php echo $item['duration']; ?> ( <?php _e( 'approx.', $plugin_name ); ?> )
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tim_detail_desc">
                    <h2><?php _e( 'Tour Overview', $plugin_name ); ?></h2>
                    <?php echo $item['itinerary']; ?>
                </div>

                <div class="tim_clr">
                    <div class="tim_col_6">
                        <?php
                        if ( $item['facilities'] ){
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
                    <div class="tim_col_6">
                        <?php
                        if ( $item['wtobrings'] ){
                            ?>
                            <h3><i class="fa fa-suitcase"></i> <?php _e( 'Things to bring', $plugin_name ); ?></h3>
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