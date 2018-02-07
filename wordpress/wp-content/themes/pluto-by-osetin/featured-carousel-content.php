<?php 
if((get_post_format() != 'gallery') && (get_post_format() != 'video') && has_post_thumbnail()){
  $featured_image_url = get_the_post_thumbnail_url($post, 'pluto-carousel-post');
  $style = 'background-image: url('.$featured_image_url.');';
}else{
  $style = '';
} ?>
<article id="post-carousel-<?php the_ID(); ?>" <?php post_class('featured-carousel-post'); ?> style="<?php echo $tile_css; ?>">
	<div class="featured-carousel-post-i" style="<?php echo $fader_css; ?>">
		<a class="carousel-post-media" href="<?php echo esc_url( get_permalink() ); ?>" style="<?php echo $style.$media_css; ?>"><div class="fader" style="<?php echo $fader_css; ?>"></div></a>
	  <?php osetin_top_social_share_index(); ?>
	  <?php if($style == '') osetin_get_media_content('pluto-carousel-post', false, true); ?>
	  <div class="post-content-body">
	    <a href="<?php echo esc_url( get_permalink() ); ?>" class="post-title entry-title"><?php the_title(); ?></a>
	    <?php echo get_the_category_list(); ?>
	  </div>
	 </div>
</article>