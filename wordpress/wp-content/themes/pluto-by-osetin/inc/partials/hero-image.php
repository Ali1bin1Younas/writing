<?php if(osetin_get_field('add_big_header_with_image')){
  $hero_image = osetin_get_field('image_to_use_for_header_background');
  if( !empty($hero_image) ): ?>

    <div class="page-hero-image" style="background-image: url( <?php echo $hero_image['url']; ?>);">
      <?php echo osetin_get_field('text_for_header_with_image'); ?>
    </div>

  <?php endif; ?>
<?php } ?>