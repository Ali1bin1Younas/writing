<?php get_header(); ?>
<div class="main-content-w">
  <div class="main-content-m">
    <?php os_the_primary_sidebar('left'); ?>
    <div class="main-content-i">
      <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
          <?php get_template_part( 'single-content', get_post_format() ); ?>
        </article>
      <?php endwhile; endif; ?>
    </div>
    <?php os_the_primary_sidebar('right'); ?>
  </div>
  <?php os_footer(); ?>
</div>
<?php get_footer(); ?>