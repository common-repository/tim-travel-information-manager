<?php

$tour_facilities = $public_data->get_miscellaneous( TIM_TRAVEL_MANAGER_POST_TYPE_FACILITIES, 'Tour' );
$tour_wtobrings  = $public_data->get_miscellaneous( TIM_TRAVEL_MANAGER_POST_TYPE_WTOBRING, 'Tour' );


$facilities = '';
if ( count($postmeta['facility_ids']) ){
    $facilities = array();
    foreach ( $postmeta['facility_ids'] as $facility_id ) {
    	foreach ( $tour_facilities as $facility ) {
    		if ( $facility_id === $facility['id'] ){
    			$name = $facility['name']->$content_language;
    			array_push( $facilities, $name );

    			break;
    		}
    	}
    }
}

$wtobrings = '';
if ( count($postmeta['what_to_bring_ids']) ){
    $wtobrings = array();
    foreach ( $postmeta['what_to_bring_ids'] as $wtobring_id ) {
    	foreach ( $tour_wtobrings as $wtobring ) {
    		if ( $wtobring_id === $wtobring['id'] ){
    			$name = $wtobring['name']->$content_language;
    			array_push( $wtobrings, $name );

    			break;
    		}
    	}
    }
}

$item['name']             = $postmeta['name']->$content_language;
$item['long_description'] = $postmeta['long_description']->$content_language;
$item['address']          = $postmeta['address']->$content_language;

if ( $postmeta['url_video']->$content_language != '' ){
    $item['url_video'] = $public_data->embed_video( $postmeta['url_video']->$content_language );
}

$item['location_name']    = $postmeta['location']->name->$content_language;
// $item['duration']          = $public_data->format_duration_time( $postmeta['duration'] );
$item['duration']         = $postmeta['duration'] .' '. __( $postmeta['duration_time_unit'], $plugin_name );

$item['geo_lat']          = $postmeta['geo_lat'];
$item['geo_lng']          = $postmeta['geo_lng'];
$item['geo_zoom']         = $postmeta['geo_zoom'];

$item['tour_schedules']   = $postmeta['tour_schedules'];
$item['pictures']         = $postmeta['pictures'];
$item['facilities']       = $facilities;
$item['wtobrings']        = $wtobrings;

?>
<div class="tim_wrapper">
    <div class="tim_row tim_detail_title_wrapper">
        <div class="tim_col_6 tim_detail_title">
            <h1><?php echo $item['name']; ?></h1>
        </div>
        <div class="tim_col_6 tim_pull_right">
            <div class="tim_detail_menu_wrap tim_pull_right">
                <ul class="tim_detail_menu">
                    <li><a href="javascript:void(0);" class="tablink active" onclick="timTabContent(event, 'timTabOverview');"><i class="fa fa-info-circle fa-lg"></i><span><?php _e( 'Detail', $plugin_name ); ?></span></a></li>
                    <?php
                    if ( $item['url_video'] != '' ){
                        ?><li><a href="javascript:void(0);" class="tablink" onclick="timTabContent(event, 'timTabVideo');"><i class="fa fa-video-camera fa-lg"></i><span><?php _e( 'Video', $plugin_name ); ?></span></a></li><?php
                    }
                    if ( $item['geo_lat'] != '' ){
                        ?><li><a href="javascript:void(0);" class="tablink" onclick="timTabContent(event, 'timTabMap');"><i class="fa fa-map-marker fa-lg"></i><span><?php _e( 'Map', $plugin_name ); ?></span></a></li><?php
                    }
                    ?>
                </ul>
            </div>
        </div>
    </div>

	<div class="tim_detail_content">
		<div id="timTabOverview" class="tim_tab_content active">

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

		    <div class="tim_detail_line">
				<?php echo $item['long_description']; ?>
			</div>

			<div class="tim_row tim_detail_line">
                <div class="tim_col_4 tim_detail_line_label">
                    <i class="fa fa-calendar fa-lg"></i><?php _e( 'Schedules', $plugin_name ); ?>
                </div>
                <div class="tim_col_8 tim_detail_line_content">
                    <ul>
                        <?php
                        foreach ( $item['tour_schedules'] as $schedule ) {
                            ?>
                            <li>
                                <b><?php echo $schedule->departure; ?> hrs.</b>
                            </li>
                            <?php
                        }
                        ?>
                    </ul>
                </div>
            </div>

            <div class="tim_row tim_detail_line">
                <div class="tim_col_4 tim_detail_line_label">
                    <i class="fa fa-clock-o fa-lg"></i><?php _e( 'Duration', $plugin_name ); ?>
                </div>
                <div class="tim_col_8 tim_detail_line_content">
                    <?php echo $item['duration']; ?> ( <?php _e( 'approx.', $plugin_name ); ?> )
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
		</div>

		<?php
        if ( $item['url_video'] != '' ){
            ?>
            <div id="timTabVideo" class="tim_tab_content">
                <iframe class="tim_detail_video" src="<?php echo $item['url_video']; ?>" frameborder="0" allowfullscreen></iframe>
            </div>
            <?php
        }
        ?>

		<?php
        if ( $item['geo_lat'] != '' ){
            ?>
            <div id="timTabMap" class="tim_tab_content">
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