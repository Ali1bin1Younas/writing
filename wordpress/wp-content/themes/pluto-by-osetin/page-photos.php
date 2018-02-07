<?php
/*
* Template Name: Photos Masonry
*/
get_header(); ?>
<div class="main-content-w">
  <?php require_once(get_template_directory() . '/inc/partials/hero-image.php') ?>
  <?php require_once(get_template_directory() . '/inc/partials/featured-slider.php') ?>
  <?php osetin_show_filter_bar(); ?>
  <div class="main-content-m">
    <?php os_the_primary_sidebar('left', true); ?>
    <div class="main-content-i">
      <div class="content">
        <div id="primary-content" data-page-id="<?php echo get_the_ID(); ?>" class="index-isotope v2 <?php echo os_lightbox_posts_enabled_class(); ?>" data-layout-mode="<?php echo (os_get_use_fixed_height_index_posts() == true) ? 'fitRows' : 'masonry'; ?>">
          <?php
          if(get_query_var('page')){
            $paged = ( get_query_var('page') ) ? get_query_var('page') : 1;
          }else{
            $paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
          }
          $args = array(
            'paged' => $paged,
            'posts_per_page' => 30,
            'post_status' => 'publish',
            'meta_key' => '_thumbnail_id',
            'tax_query' => array(
                            array(
                              'taxonomy' => 'post_format',
                              'field' => 'slug',
                              'terms' => array(
                                  'post-format-aside',
                                  'post-format-audio',
                                  'post-format-chat',
                                  'post-format-gallery',
                                  'post-format-image',
                                  'post-format-link',
                                  'post-format-quote',
                                  'post-format-status',
                                  'post-format-video'
                              ),
                              'operator' => 'NOT IN'
                              )
                            )
          );
          $osetin_query = new WP_Query( $args );
          $double_width_posts_arr = osetin_get_double_width_posts_arr();
          while ($osetin_query->have_posts()) : $osetin_query->the_post(); ?>
              <?php get_template_part( 'v2-content', get_post_format() ); ?>
          <?php endwhile; ?>

        </div>
        <?php if(os_get_next_posts_link($osetin_query)): ?>
          <div class="isotope-next-params" data-params="<?php echo os_get_next_posts_link($osetin_query); ?>" data-layout-type="v2"></div>
          <?php if((os_get_current_navigation_type() == 'infinite_button') || (os_get_current_navigation_type() == 'infinite')): ?>
          <div class="load-more-posts-button-w">
            <a href="#"><i class="os-icon-plus"></i> <span><?php _e('Load More Posts', 'pluto'); ?></span></a>
          </div>
          <?php endif; ?>
        <?php endif; ?>
        <div class="pagination-w hide-for-isotope">
          <?php if(function_exists('wp_pagenavi') && os_get_current_navigation_type() != 'default'): ?>
            <?php wp_pagenavi(); ?>
          <?php else: ?>
            <?php posts_nav_link(); ?>
          <?php endif; ?>
        </div>
        <?php wp_reset_postdata(); ?>
      </div>
    </div>
    <?php os_the_primary_sidebar('right', true); ?>
  </div>
  <?php os_footer(); ?>
</div>
<?php get_footer(); ?>