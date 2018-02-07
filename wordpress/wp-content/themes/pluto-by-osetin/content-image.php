<div class="item-isotope <?php echo os_list_categories_for_filtering(get_the_ID()); ?>" <?php echo os_item_data(); ?>>
<article id="post-<?php the_ID(); ?>" <?php post_class('pluto-post-box'); ?>>
  <div class="post-body">
    <?php osetin_top_social_share_index(); ?>
    <?php osetin_get_media_content(); ?>
  </div>
</article>
</div>