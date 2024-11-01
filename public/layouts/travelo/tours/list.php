<?php
function render_tour_list_html( $item, $get_view, $plugin_name ){

	$class_image_column = '';
	$class_desc_column  = '';
	$class_price_column = '';

	if ( $get_view !== 'grid' && $get_view !== 'related' ){
		$class_image_column = 'tim_col_4 ';
		$class_desc_column  = 'tim_col_6 ';
		$class_price_column = 'tim_col_2 ';
	}

	?>
	<div class="tim_item swiper-slide">
		<div class="<?php echo $class_image_column; ?>tim_item_image">
			<a href="<?php echo $item['link']; ?>">
				<span class="rollover"></span>
				<img src="<?php echo $item['logo']; ?>" alt="<?php echo $item['name']; ?>" title="<?php echo $item['name']; ?>" />
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
				if ( $item['url_video'] != '' ){
					?>
					<a class="tim_fancybox tim_fancybox.iframe" rel="gallery" href="<?php echo $item['url_video']; ?>" title="<?php echo $item['name']; ?>">
						<i class="fa fa-video-camera"></i>
					</a>
					<?php
				}
				?>
			</div>

			<!--<div class="tim_item_price_image">
				<a href="#" class="tim_item_price_image_button">
					<del><span class="amount">$2,225</span></del> <ins><span class="amount">$1,925</span></ins>
				</a>
			</div>-->

		</div>
		<div class="<?php echo $class_desc_column; ?>tim_item_desc">
			<h3 class="tim_item_desc_title"><a href="<?php echo $item['link']; ?>"><?php echo $item['name']; ?></a></h3>
			<?php
			if ( $class_desc_column != '' ){
				?><p><?php echo $item['description']; ?></p><?php
			}
			?>
			<ul class="tim_tags">
				<li><i class="fa fa-clock-o fa-lg"></i><?php echo $item['duration']; ?></li>
				<li><i class="fa fa-map-marker fa-lg"></i><?php echo $item['location_name'] ?></li>
			</ul>
		</div>
		<div class="<?php echo $class_price_column; ?>tim_item_price">
			<div class="tim_item_price_box">
				<div class="tim_item_price_amount"><?php echo $item['min_rate']; ?></div>
				<div class="tim_item_price_label"><?php _e( 'per person', $plugin_name ); ?></div>
			</div>
			
			<a href="<?php echo $item['link']; ?>" class="tim_item_btn"><?php _e( 'View tour', $plugin_name ); ?></a>
		</div>
	</div>
	<?php

}
?>