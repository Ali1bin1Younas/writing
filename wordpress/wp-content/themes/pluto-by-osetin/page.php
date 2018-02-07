<?php
get_header(); ?>

<div class="main-content-w">
  <div class="main-content-m">
    <?php os_the_primary_sidebar('left'); ?>
    <div class="main-content-i">
    <?php require_once(get_template_directory() . '/inc/partials/featured-slider.php') ?>
      <div class="content padded-top padded-bottom">
        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
          <div id="page-<?php the_ID(); ?>" <?php post_class(); ?>>
            <div class="post-content"><?php the_content(); ?></div>
          </div>
        <?php endwhile; endif; ?>
      </div>
    </div>
    <?php os_the_primary_sidebar('right'); ?>
  </div>
  <?php os_footer(); ?>
</div>
<?php get_footer(); ?>