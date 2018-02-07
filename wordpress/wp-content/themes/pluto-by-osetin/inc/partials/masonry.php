<?php get_header(); ?>

<?php 
  $layout_type = isset($layout_type) ? $layout_type : 'v3';
  $isotope_simple = (isset($isotope_simple) && $isotope_simple) ? true : false;
  $template_type = isset($template_type) ? $template_type : '';
  $layout_mode = (os_get_use_fixed_height_index_posts() == true) ? 'fitRows' : 'masonry';
  if(isset($forse_fixed_height) && $forse_fixed_height) $layout_mode = 'fitRows';
?>
<div class="main-content-w">
  <?php require_once(get_template_directory() . '/inc/partials/hero-image.php') ?>
  <?php require_once(get_template_directory() . '/inc/partials/featured-slider.php') ?>
  <div class="main-content-m">
    <?php os_the_primary_sidebar('left', true); ?>
    <div class="main-content-i">
      <?php require_once(get_template_directory() . '/inc/partials/top-ad-sidebar.php') ?>
      <?php osetin_show_filter_bar(); ?>
      <div class="content side-padded-content">
        <div id="primary-content" data-page-id="<?php echo get_the_ID(); ?>" class="index-isotope hidden-on-load <?php echo $layout_type; ?> <?php echo os_lightbox_posts_enabled_class(); ?> <?php echo ($isotope_simple) ? 'isotope-simple' : ''; ?>" data-layout-mode="<?php echo $layout_mode; ?>">
          <?php
          require_once(get_template_directory() . '/inc/osetin-custom-index-query.php');
          $double_width_posts_arr = osetin_get_double_width_posts_arr();
          $os_current_box_counter = 1; $os_ad_block_counter = 0;
          while ($osetin_query->have_posts()) : $osetin_query->the_post();
            if($layout_type == 'v3')
              get_template_part( 'v3-content', get_post_format() );
            else
              get_template_part( 'content', get_post_format() );
            os_ad_between_posts();
          endwhile; ?>
        </div>
        <?php if(os_get_next_posts_link($osetin_query)): ?>
          <div class="isotope-next-params" data-params="<?php echo os_get_next_posts_link($osetin_query); ?>" data-layout-type="<?php echo $layout_type; ?><?php echo ($isotope_simple) ? '-simple' : ''; ?>" data-template-type="<?php echo isset($template_type) ? $template_type : ''; ?>"></div>
          <?php if((os_get_current_navigation_type() == 'infinite_button') || (os_get_current_navigation_type() == 'infinite')): ?>
          <div class="load-more-posts-button-w">
            <a href="#"><i class="os-icon-plus"></i> <span><?php _e('Load More Posts', 'pluto'); ?></span></a>
          </div>
          <?php endif; ?>
        <?php endif; ?>
        <?php
        $temp_query = $wp_query;
        $wp_query = $osetin_query; ?>
        <div class="pagination-w hide-for-isotope">
          <?php if(function_exists('wp_pagenavi') && os_get_current_navigation_type() != 'default'): ?>
            <?php wp_pagenavi(); ?>
          <?php else: ?>
            <?php posts_nav_link(); ?>
          <?php endif; ?>
        </div>
        <?php $wp_query = $temp_query; ?>
        <?php wp_reset_postdata(); ?>
      </div>
    </div>
    <?php os_the_primary_sidebar('right', true); ?>
  </div>
  <?php os_footer(); ?>
</div>
<?php get_footer(); ?>