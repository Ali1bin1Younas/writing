<?php
get_header(); ?>

<div class="main-content-w">
  <div class="main-content-m">
  <?php os_the_primary_sidebar('left'); ?>
  <div class="main-content-i">
    <div class="content side-padded-content">
      <?php if ( is_active_sidebar( 'sidebar-3' ) ) : ?>
        <div class="top-sidebar-wrapper"><?php dynamic_sidebar( 'sidebar-3' ); ?></div>
      <?php endif; ?>
      <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
        <article id="page-<?php the_ID(); ?>" <?php post_class('pluto-page-box'); ?>>
          <div class="post-body">
            <h1 class="page-title"><?php the_title(); ?></h1>
            <div class="post-content"><?php the_content(); ?></div>
          </div>
        </article>
      <?php endwhile; endif; ?>
    </div>
  </div>
  <?php os_the_primary_sidebar('right'); ?>
  </div>
  <?php os_footer(); ?>
</div>
<?php
get_footer();
?>