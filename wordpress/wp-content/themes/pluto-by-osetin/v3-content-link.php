<div class="item-isotope <?php echo os_list_categories_for_filtering(get_the_ID()); ?>" <?php echo os_item_data(); ?>>
  <article id="post-<?php the_ID(); ?>" <?php post_class('pluto-post-box'); ?>>
    <div class="post-body">
      <?php osetin_top_social_share_index(); ?>
      <?php osetin_get_media_content(); ?>

      <?php if(os_is_post_element_active('title') || os_is_post_element_active('category') || os_is_post_element_active('excerpt')){ ?>
        <div class="post-content-body">
          <?php if(os_is_post_element_active('title')): ?>
            <h4 class="post-title entry-title"><a href="<?php echo osetin_get_field( 'external_link' ); ?>"><?php the_title(); ?></a></h4>
          <?php endif; ?>
          <?php if(os_is_post_element_active('excerpt')): ?>
            <div class="post-content entry-summary"><?php echo os_excerpt(osetin_get_field('index_excerpt_length', 'option'), os_is_post_element_active('read_more')); ?></div>
          <?php endif; ?>
          <?php if(os_is_post_element_active('external_link_button')): ?>
            <?php echo osetin_get_external_link_button(); ?>
          <?php endif; ?>
        </div>
      <?php } ?>
    </div>
    <?php if(os_is_post_element_active('date') || os_is_post_element_active('author') || os_is_post_element_active('like') || os_is_post_element_active('view_count')): ?>
      <div class="post-meta entry-meta">

        <?php global $show_author_face; ?>
        <?php if(!isset($show_author_face)) $show_author_face = false; ?>
        <?php if($show_author_face){ ?>


            <div class="meta-author-face">
              <div class="meta-author-avatar">
                <?php echo get_avatar(get_the_author_meta('ID')); ?>
              </div>
              <div class="meta-author-info">
                <div class="meta-author-info-by"><?php _e('Written by', 'pluto') ?></div>
                <div class="meta-author-name vcard"><strong><a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) )) ; ?>" class="url fn n" rel="author"><?php echo get_the_author(); ?></a></strong></div>
              </div>
            </div>

        <?php }else{ ?>

          <?php if(os_is_post_element_active('category')): ?>
            <?php echo get_the_category_list(); ?>
          <?php endif; ?>
          <?php if(os_is_post_element_active('date')): ?>
            <div class="meta-date">
              <i class="os-new-icon os-new-icon-clock"></i>
              <time class="entry-date updated" datetime="<?php echo get_the_date( 'c' ); ?>"><?php echo get_the_date('M j'); ?></time>
            </div>
          <?php endif; ?>
          <?php if(os_is_post_element_active('view_count')): ?>
            <div class="meta-view-count">
              <i class="fa os-icon-eye"></i>
              <span><?php if(function_exists('echo_tptn_post_count')) echo do_shortcode('[tptn_views]'); ?></span>
            </div>
          <?php endif; ?>

        <?php } ?>

        <?php if(os_is_post_element_active('like')){ ?>
          <div class="meta-like">
            <?php
            global $likes_type;
            if(!isset($likes_type)) $likes_type = 'regular';
            os_vote_build_button(get_the_ID(), '', false, $likes_type, get_the_permalink()); ?>
          </div>
        <?php } ?>



      </div>
    <?php endif; ?>
  </article>
</div>