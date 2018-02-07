<?php
/**
 * The main template file.
 *
 * @package pluto
 */
?>

<?php get_header(); ?>
<div class="main-content-w">
<div class="main-content-m">
  <?php os_the_primary_sidebar('left'); ?>
  <div class="main-content-i">
    <?php require_once(get_template_directory() . '/inc/partials/featured-slider.php') ?>
    <div class="content side-padded-content reading-mode-content">
      <?php if ( is_active_sidebar( 'sidebar-3' ) ){ ?>
        <div class="top-sidebar-wrapper"><?php dynamic_sidebar( 'sidebar-3' ); ?></div>
      <?php } ?>
      <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
        <?php if(osetin_get_field('single_post_navigation_type', 'option') == 'unique'){ ?>
          <?php if (get_adjacent_post(false, '', true)){ ?>
          <div class="post-navigation-unique">
            <div class="post-navigation-previous">
              <div class="arrow"><i class="fa os-icon-angle-up"></i></div>
              <div class="caption"><?php _e('Previous Post', 'pluto') ?></div>
              <div class="navi-link"><?php previous_post_link('%link'); ?></div>
            </div>
          </div>
          <?php } ?>
        <?php } ?>
        <?php osetin_generate_sub_bar(); ?>
        <?php get_template_part( 'single-content', get_post_format() ); ?>
        <?php if(osetin_get_field('single_post_navigation_type', 'option') == 'unique'){ ?>
          <?php if (get_adjacent_post(false, '', false)){ ?>
          <div class="post-navigation-unique">
            <div class="post-navigation-next">
              <div class="arrow"><i class="fa os-icon-angle-down"></i></div>
              <div class="caption"><?php _e('Next Post', 'pluto') ?></div>
              <div class="navi-link"><?php next_post_link('%link'); ?></div>
            </div>
          </div>
          <?php } ?>
        <?php } ?>
        <?php if(osetin_get_field('single_post_navigation_type', 'option') == 'classic'){ ?>
          <?php wp_link_pages(); ?>
          <div class="post-navigation-classic">
            <div class="row">
              <div class="col-sm-6">
                <?php if (get_adjacent_post(false, '', true)): ?>
                <div class="post-navigation-previous">
                  <div class="arrow"><i class="fa os-icon-angle-left"></i></div>
                  <div class="caption"><?php _e('Previous Post', 'pluto') ?></div>
                  <div class="navi-link"><?php previous_post_link('%link'); ?></div>
                </div>
                <?php endif; ?>
              </div>
              <div class="col-sm-6">
                <?php if (get_adjacent_post(false, '', false)): ?>
                <div class="post-navigation-next">
                  <div class="arrow"><i class="fa os-icon-angle-right"></i></div>
                  <div class="caption"><?php _e('Next Post', 'pluto') ?></div>
                  <div class="navi-link"><?php next_post_link('%link'); ?></div>
                </div>
                <?php endif; ?>
              </div>
            </div>
          </div><?php
        } ?>
        <div class="sidebar-under-post">
          <div class="row"><?php
            if(osetin_get_field('disable_author_on_single', 'option') != TRUE){ ?>
              <div class="col-md-6 under-post-widget-column">
                <div class="widget widget-written-by">
                  <h4 class="widget-title"><?php _e('Written by', 'pluto') ?></h4>
                  <div class="row">
                    <div class="col-sm-4 col-xs-3">
                      <figure><?php echo get_avatar(get_the_author_meta('ID')); ?></figure>
                    </div>
                    <div class="col-sm-8 col-xs-9">
                      <h5 class="widget-caption author vcard"><a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) )) ; ?>" class="url fn n" rel="author"><?php echo get_the_author(); ?></a></h5>
                      <div class="widget-content author-description"><?php the_author_meta('description'); ?></div>
                    </div>
                  </div>
                </div>
              </div>
              <?php
            }

            $tags = wp_get_post_tags(get_the_ID());
            if($tags){
              // if the the author box is disabled - show 2 related posts otherwise just 1
              $posts_count = osetin_get_field('disable_author_on_single', 'option') ? 2 : 1;
              $tag_ids = array();
              foreach($tags as $individual_tag){
                $tag_ids[] = $individual_tag->term_id;
              }
              $args = array(
                'tag__in'             => $tag_ids,
                'post__not_in'        => array(get_the_ID()),
                'posts_per_page'      => $posts_count,
                'post_status'         => 'publish',
                'ignore_sticky_posts' => 1
              );
              $osetin_query = new WP_Query( $args );
              if (!$osetin_query->have_posts()){
                wp_reset_postdata();
                $args = array(
                  'post__not_in'        => array(get_the_ID()),
                  'posts_per_page'      => $posts_count,
                  'post_status'         => 'publish',
                  'ignore_sticky_posts' => 1
                );
                $osetin_query = new WP_Query( $args );
              }
              if ($osetin_query->have_posts()){
                while ($osetin_query->have_posts()){
                  $osetin_query->the_post(); ?>
                  <div class="col-md-6 under-post-widget-column">
                    <div class="widget widget-related-post">
                      <h4 class="widget-title"><?php _e('Related Post', 'pluto') ?></h4>
                      <div class="row">
                        <?php
                        if(has_post_thumbnail()){ ?>
                          <div class="col-sm-4 col-xs-3">
                            <a href="<?php the_permalink(); ?>"><figure><?php the_post_thumbnail('thumbnail'); ?></figure></a>
                          </div>
                          <?php
                          $related_content_class = 'col-sm-8 col-xs-9';
                        }else{
                          $related_content_class = 'col-xs-12';
                        } ?>
                        <div class="<?php echo $related_content_class; ?>">
                          <h5 class="widget-caption entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
                          <div class="widget-content entry-summary"><?php echo os_excerpt(15, false); ?></div>
                        </div>
                      </div>
                    </div>
                  </div><?php
                }
              }
              wp_reset_postdata();
            } ?>
          </div>
        </div>

        <div class="sidebar-under-post">
        <?php 
        $args = array(
          'post__not_in'        => array(get_the_ID()),
          'posts_per_page'      => 4,
          'post_status'         => 'publish',
          'ignore_sticky_posts' => 1,
          'orderby'             => 'post_date',
          'order'               => 'DESC',
        );
        $recent_posts_query = new WP_Query( $args );
        if ($recent_posts_query->have_posts()){ ?>
          <div class="latest-posts-w">
          <div class="widget"><h4 class="widget-title"><?php _e('Latest Posts', 'pluto') ?></h4>
          <div class="row">
          <?php
          while ($recent_posts_query->have_posts()){
            $recent_posts_query->the_post();

            $feat_image_url = wp_get_attachment_url( get_post_thumbnail_id() );
            ?>
            <div class="col-sm-3 col-xs-6">
              <a href="<?php the_permalink(); ?>" style="background-image:url('<?php echo $feat_image_url; ?>');">
                <span><?php the_title(); ?></span>
              </a>
            </div>
            <?php
          }
          echo '</div></div></div>';
        }
        wp_reset_postdata(); ?>
        </div>
      <?php endwhile; endif; ?>
      <?php
        // If comments are open or we have at least one comment, load up the comment template.
        if ( comments_open() || get_comments_number() ) {
          comments_template();
        }
      ?>
    </div>
  </div>
  <?php os_the_primary_sidebar('right'); ?>
  </div>
    <?php os_footer(); ?>
</div>
<?php get_footer(); ?>