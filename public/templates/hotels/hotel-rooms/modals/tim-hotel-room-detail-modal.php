<?php

$hotel_room_facilities = $public_data->get_miscellaneous( TIM_TRAVEL_MANAGER_POST_TYPE_FACILITIES, 'hotel_room' );

$facilities = $public_data->find_item_ids_in_array( $postmeta->facility_ids, $hotel_room_facilities, 'name', $content_language );

$room_photos = '';
if ( count($postmeta->photos) ){
    $room_photos = array();
    foreach ( $postmeta->photos as $photo ) {
        $photo = array(
            'image' => $photo->image, 
            'title' => $photo->title->$content_language
        );

        array_push( $room_photos, $photo );
    }
}

$item['logo']                = $postmeta->logo;
$item['name']                = $postmeta->name->$content_language;
$item['description']         = $postmeta->description->$content_language;
$item['url_video']           = $public_data->embed_video( $postmeta['url_video']->$content_language );

$item['bed_configuration']   = $postmeta->bed_configuration->$content_language;
$item['max_occupancy']       = $postmeta->max_occupancy;
$item['children_allowed']    = $postmeta->children_allowed;
$item['total_bedrooms']      = $postmeta->total_bedrooms;
// $item['base_allotments']     = $postmeta->base_allotments;
$item['size']                = $postmeta->size;
$item['meals_included']      = $postmeta->meals_included;
$item['wifi']                = $postmeta->wifi;
$item['not_smoking']         = $postmeta->not_smoking;
$item['facilities']          = $room_facilities;
$item['room_type_name']      = $postmeta->room_type->name->$content_language;
$item['occupancy_type_name'] = $postmeta->occupancy_type->name->$content_language;
$item['photos']              = $room_photos;

$bed_configuration = $item['bed_configuration'] ? ' ('. $item['bed_configuration'] .')' : '';

?>
<div class="tim_wrapper">
    <div style="margin-bottom: 20px;">
        <h2 style="margin: 0;"><?php echo $item['name']; ?></h2>
        <i class="fa fa-bed"></i> <b><?php echo $item['occupancy_type_name']; ?></b><?php echo $bed_configuration; ?>
        <?php
        if ( $item['wifi'] ){
            ?><span class="tim_label tim_label_success tim_label_rd"><?php _e( 'Wi-fi', $plugin_name ); ?></span> <?php
        }
        if ( $item['not_smoking'] ){
            ?><span class="tim_label tim_label_success tim_label_rd"><?php _e( 'Not-smoking', $plugin_name ); ?></span> <?php
        }
        ?>
    </div>

    <div class="tim_clr">
        <?php
        if ( $item['url_video'] || $item['photos'] ){
            ?>
            <div class="tim_col_6">
                <div class="swiper-container tim_detail_slider_modal" style="background: #000;">
                    <div class="swiper-wrapper">
                        <?php
                        if ( $item['url_video'] ){
                            ?>
                            <div class="swiper-slide">
                                <iframe class="tim_detail_video" src="<?php echo $item['url_video']; ?>" frameborder="0" height="400" allowfullscreen></iframe>
                            </div>
                            <?php
                        }

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
            </div>
            <?php
        }
        elseif ( $item['logo'] ){
            ?>
            <div class="tim_col_6">
                <img src="<?php echo $item['logo']; ?>" alt="<?php echo $item['name']; ?>" />
            </div>
            <?php
        }
        ?>
        <div class="tim_col_6">
            <?php echo $item['description']; ?>

            <div class="tim_clr tim_detail_line">
                <div class="tim_col_3">
                    <?php _e( 'Occupancy', $plugin_name ); ?>
                </div>
                <div class="tim_col_9">
                    <i class="fa fa-user"></i> <b><?php echo $item['max_occupancy']; ?></b> <small>(<?php _e( 'Maximum', $plugin_name ); ?>)</small>  
                </div>
            </div>

            <div class="tim_clr tim_detail_line">
                <div class="tim_col_3">
                    <?php _e( 'Room size', $plugin_name ); ?>
                </div>
                <div class="tim_col_9">
                    <?php echo $item['size']; ?> m<sup>2</sup>
                </div>
            </div>

            <?php
            if ( $item['facilities'] ){
                ?>
                <div class="tim_clr tim_detail_line">
                    <div class="tim_col_3">
                        <?php _e( 'Amenities', $plugin_name ); ?>
                    </div>
                    <div class="tim_col_9">
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
            }
            ?>
        </div>
    </div>
</div>