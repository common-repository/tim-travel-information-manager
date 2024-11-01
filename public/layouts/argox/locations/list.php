<?php
function render_location_list_html( $item, $get_view, $plugin_name ){

    $class_image_column = '';
    $class_desc_column  = '';
    $class_price_column = '';

    if ( $get_view !== 'grid' && $get_view !== 'related' ){
        $class_image_column = 'tim_col_4 ';
        $class_desc_column  = 'tim_col_5 ';
        $class_price_column = 'tim_col_3 ';
    }

    ?>
    <div class="tim_item swiper-slide">
        <div class="<?php echo $class_image_column; ?>tim_item_image">
            <a href="<?php echo $item['link']; ?>">
                <span class="rollover"></span>
                <img src="<?php echo $item['logo']; ?>" alt="<?php echo $item['name']; ?>" title="<?php echo $item['name']; ?>" />
            </a>

            <div class="tim_item_image_icons">
                <?php
                if ( $item['url_video'] != '' ){
                    ?>
                    <a class="tim_fancybox tim_fancybox.iframe" rel="gallery" href="<?php echo $item['url_video']; ?>" title="<?php echo $item['name']; ?>">
                        <i class="fa fa-video-camera"></i>
                    </a>
                    <?php
                }
                ?>
            </div>

        </div>
        <div class="<?php echo $class_desc_column; ?>tim_item_desc">
            <h3 class="tim_item_desc_title"><a href="<?php echo $item['link']; ?>"><?php echo $item['name']; ?></a></h3>

            <ul class="tim_tags">
                <li><i class="fa fa-map-marker fa-lg"></i><?php echo $item['parentLocation_name'] ?></li>
            </ul>

            <?php
            if ( $class_desc_column != '' ){
                ?><p><?php echo $item['short_description']; ?></p><?php
            }
            ?>
        </div>
        <div class="<?php echo $class_price_column; ?>tim_item_price">
            <div class="tim_item_price_box_wrap">
                <div class="tim_item_schedules">
                    <div class="tim_item_schedules_title"><?php _e( 'Highlights', $plugin_name ); ?></div>
                    <br />
                    <ul>
                        <?php
                        $highlights = preg_split('/\R/', $item['highlights']);

                        foreach ( $highlights as $highlight ) {
                            ?>
                            <li><?php echo $highlight; ?></li>
                            <?php
                        }
                        ?>
                    </ul>
                </div>
            </div>
            <br />
            <a href="<?php echo $item['link']; ?>" class="tim_item_btn"><?php _e( 'View destination', $plugin_name ); ?></a>
        </div>
    </div>
    <?php

}
?>