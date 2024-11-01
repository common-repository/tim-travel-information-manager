<?php
function render_hotel_list_html( $item, $get_view, $plugin_name ){

	$class_image_column = '';
	$class_desc_column  = '';
	$class_price_column = '';

	if ( $get_view !== 'grid' && $get_view !== 'related' ){
		$class_image_column = ' class="tim_col_3"';
		$class_desc_column  = ' class="tim_col_6"';
		$class_price_column = ' class="tim_col_3"';
	}

	?>
	<div class="tim_item swiper-slide">
		<div<?php echo $class_image_column; ?>>
			<div class="tim_item_image">
				<a href="<?php echo $item['link']; ?>">
					<span class="rollover"></span>
					<img 
						src=data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw== 
						data-src="<?php echo $item['logo']; ?>" 
						alt="<?php echo $item['name']; ?>" 
						class="b-lazy" />
				</a>

				<?php
				if ( $item['featured'] ){
					?>
					<div class="tim_item_image_angle_wrap">
						<div class="tim_item_image_angle">
							<?php _e( 'Featured', $plugin_name ); ?>
						</div>
					</div>
					<?php
				}
				?>

				<div class="tim_item_image_icons">
					<?php
					if ( $item['url_video'] ){
						?>
						<a class="tim_fancybox tim_fancybox.iframe" rel="gallery" href="<?php echo $item['url_video']; ?>" title="<?php echo $item['name']; ?>">
							<i class="fa fa-video-camera"></i>
						</a>
						<?php
					}
					?>
				</div>
			</div>
		</div>
		<div<?php echo $class_desc_column; ?>>
			<div class="tim_item_desc">
				<h3 class="tim_item_desc_title">
					<a href="<?php echo $item['link']; ?>"><?php echo $item['name']; ?></a>
					<div>
						<?php
						for ($i = 0; $i < $item['stars_rating']; $i++){
							?><i class="fa fa-star"></i> <?php
						}
						?>
					</div>
				</h3>
				<ul>
					<li><i class="fa fa-map-marker fa-lg"></i> <?php echo $item['location_name'] ?></li>
				</ul>
				<?php
				if ( $class_desc_column !== '' ){
					?><p><?php echo $item['description']; ?></p><?php
				}
				?>
				<ul class="tim_tags">
					<li><?php echo $item['product_category_name'] ?></li>
				</ul>
			</div>
		</div>
		<div<?php echo $class_price_column; ?>>
			<div class="tim_item_price">
				<div class="tim_item_price_box_wrap">
					<div class="tim_item_schedules">
						<div class="tim_item_schedules_title"><?php _e( 'per night', $plugin_name ); ?></div>
						<ul>
			                <li><?php _e( 'Double occupancy', $plugin_name ); ?></li>
						</ul>
					</div>

					<div class="tim_item_price_box">
						<div class="tim_item_price_amount"><?php echo $item['rate_from']; ?></div>
					</div>
				</div>

				<a href="<?php echo $item['link']; ?>" class="tim_item_btn"><?php _e( 'View hotel', $plugin_name ); ?></a>
			</div>
		</div>
	</div>
	<?php

}
?>