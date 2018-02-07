<?php
/**
 * The template for displaying the footer
 *
 * Contains footer content and the closing of the #main and #page div elements.
 *
 * @package Pluto
 * @since Pluto 1.0
 */
?>
  </div>
  <a href="#" class="os-back-to-top"></a>
  <div class="display-type"></div>
  <?php
    // if protect images checkbox in admin is set to true - load tag with copyright text
    if(osetin_get_field('protect_images_from_copying', 'option') === true){
      $copyright_text = (osetin_get_field('text_for_image_right_click', 'option') != '') ? osetin_get_field('text_for_image_right_click', 'option') : __('This photo is copyright protected', 'pluto');
      echo '<div class="copyright-tooltip">'.$copyright_text.'</div>';
    }
  ?>
  <div class="main-search-form-overlay"></div>
  <div class="main-search-form">
    <?php get_search_form(true); ?>
    <div class="autosuggest-results"></div>
  </div>
  <?php os_the_primary_sidebar('left', false); ?>
  <?php os_the_primary_sidebar('right', false); ?>
  <?php wp_footer(); ?>
</body>
</html>