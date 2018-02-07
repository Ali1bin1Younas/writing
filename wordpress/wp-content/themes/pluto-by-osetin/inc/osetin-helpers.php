<?php

function os_get_global_less_var($key, $default = ''){
  global $my_less;
  return $my_less->get_merged_var($key, $default);
}

function os_count_sidebar_widgets( $sidebar_id, $echo = false ) {
    $the_sidebars = wp_get_sidebars_widgets();
    if( !isset( $the_sidebars[$sidebar_id] ) )
        return 4;
    $count = count( $the_sidebars[$sidebar_id] );
    if($count > 4) $count = 4;
    if( $echo )
        echo esc_attr($count);
    else
        return $count;
}

function os_item_data(){
  if(function_exists('echo_tptn_post_count')){ 
    $total_views = do_shortcode('[tptn_views daily="0"]');
  }else{
    $total_views = 0;
  }
  $likes = get_post_meta(get_the_ID(), '_zilla_likes', true);
  return ' data-post-id="'.get_the_ID().'" data-total-likes="'.esc_attr($likes).'" data-total-views="'.esc_attr($total_views).'"';
}

function osetin_get_double_width_posts_arr($page_id = false){
  $double_width_posts = osetin_get_field('items_with_double_width', $page_id);
  $double_width_posts_arr = array();
  if($double_width_posts){
    $double_width_posts = preg_replace('/\s+/', '', $double_width_posts);
    $double_width_posts_arr = explode(',', $double_width_posts);
  }
  return $double_width_posts_arr;
}

function osetin_get_double_width_class(){
  global $double_width_posts_arr;
  global $os_current_box_counter;
  if(isset($double_width_posts_arr) && isset($os_current_box_counter) && $double_width_posts_arr && in_array($os_current_box_counter, $double_width_posts_arr)){
    $double_width_class = ' item-double-width ';
  }else{
    $double_width_class = '';
  }
  return $double_width_class;
}


function os_list_categories_for_filtering($post_id){
  $categories_string = '';
  $categories = get_the_category( $post_id );
  if(is_array($categories)){
    foreach($categories as $category){
      $categories_string.= 'filter-cat-'.$category->term_id.' ';
    }
  }
  $format = get_post_format() ? : 'standard';
  $categories_string.= ' format-'.$format.' ';

  $double_width_class = osetin_get_double_width_class();

  $extra_classes = '';

  return $categories_string.$double_width_class.$extra_classes;
}


function osetin_show_filter_bar($post_id = false){

  if(false){
    $icons = array(
      'order' => 'os-new-icon os-new-icon-thin-0245_text_numbered_list',
      'filter' => 'os-new-icon os-new-icon-thin-0041_filter_funnel',
      'standard' => 'os-new-icon os-new-icon-thin-0010_newspaper_reading_news',
      'image' => 'os-new-icon os-new-icon-thin-0621_polaroid_picture_image_photo',
      'gallery' => 'os-new-icon os-new-icon-thin-0618_album_picture_image_photo',
      'video' => 'os-new-icon os-new-icon-thin-0587_movie_video_cinema_flm',
      'quote' => 'os-new-icon os-new-icon-thin-0285_chat_message_quote_reply',
      'audio' => 'os-new-icon os-new-icon-thin-0595_music_note_playing_sound_song',
      'link' => 'os-new-icon os-new-icon-thin-0260_link_url_chain_hyperlink',
    );
    $filter_extra_css = '';
  }else{
    $icons = array(
      'order' => '',
      'filter' => '',
      'standard' => 'os-new-icon os-new-icon-file-text',
      'image' => 'os-new-icon os-new-icon-image',
      'gallery' => 'os-new-icon os-new-icon-layers',
      'video' => 'os-new-icon os-new-icon-film',
      'quote' => 'os-new-icon os-new-icon-message-circle',
      'audio' => 'os-new-icon os-new-icon-music',
      'link' => 'os-new-icon os-new-icon-link',
    );
    $filter_extra_css = 'minimal';
  }
  if(!(osetin_get_field('hide_sorting', $post_id) && osetin_get_field('hide_category_filtering', $post_id) && osetin_get_field('hide_format_filtering', $post_id)) && (!osetin_get_field('hide_filter_toolbar', 'option', false) && !osetin_get_field('hide_filter_toolbar', $post_id, false))){
      $filter_bg_type = os_get_less_var('subBarBackgroundType', 'light');
      $filter_bg_color = osetin_get_field('filter_bar_background_color_option', 'option', false);
      $filter_bg_image_id = osetin_get_field('filter_bar_background_image_option', 'option', false);
      $filter_bg_image_url = false;
      if($filter_bg_image_id){ 
        $filter_bg_image_arr = wp_get_attachment_image_src($filter_bg_image_id, "osetin-for-background");
        if($filter_bg_image_arr && isset($filter_bg_image_arr[0])) $filter_bg_image_url = $filter_bg_image_arr[0];
      }

      $bg_color_css = $filter_bg_color ? 'background-color: '.$filter_bg_color.';' : '';
      $bg_image_css = $filter_bg_image_url ? 'background-image:url('.$filter_bg_image_url.'); background-repeat: repeat;' : '';

      echo '<div class="index-filter-bar color-scheme-'.$filter_bg_type.' '.$filter_extra_css.'" style="'.$bg_color_css.$bg_image_css.'">';
        if(osetin_get_field('hide_sorting', $post_id) != true){
          echo '<div class="index-sort-w">';
            echo '<div class="index-sort-label"><i class="'.$icons["order"].'"></i><span>'.esc_html__('Order By', 'pluto').'</span></div>';
            echo '<div class="index-sort-options"><button data-sort-value="likes" class="index-sort-option index-sort-hearts">'.esc_html__('Most Likes', 'pluto').'</button><button data-sort-value="views" class="index-sort-option index-sort-views">'.esc_html__('Most Views', 'pluto').'</button></div>';
          echo '</div>';
        }
        echo '<div class="index-filter-w">';
          $categories_to_show_as_buttons = osetin_get_field('categories_to_show_as_buttons', $post_id, false);
          $formats_to_show_in_filter = osetin_get_field('formats_to_show_in_filter', $post_id, false);
          if($formats_to_show_in_filter || $categories_to_show_as_buttons){
            echo '<div class="index-filter-label"><i class="'.$icons["filter"].'"></i><span>'.esc_html__('Category', 'pluto').'</span></div>';
          }
          if(osetin_get_field('hide_category_filtering', $post_id) != true && $categories_to_show_as_buttons){
            //echo '<div c`lass="index-filter-sub-label">'.esc_html__('Category', 'pluto').'</div>';
            if(osetin_get_field('categories_as_select_box', false, false)){
              echo '<div class="index-filter-categories-select">';
                echo '<div class="index-filter-select-selected"><div class="index-filter-select-placeholder">'.__('Select Category...', 'pluto').'</div></div>';
                echo '<div class="index-filter-options">';
                foreach($categories_to_show_as_buttons as $category_id){
                  if(term_exists($category_id, 'category')) echo '<div class="index-filter-option" data-filter-value="filter-cat-'.$category_id.'">'.get_the_category_by_ID($category_id).'</div>';
                }
                echo '</div>';
              echo '</div>';
            }else{
              echo '<div class="index-filter-categories">';
                foreach($categories_to_show_as_buttons as $category_id){
                  if(term_exists($category_id, 'category')) echo '<button class="index-filter-option" data-filter-value="filter-cat-'.$category_id.'">'.get_the_category_by_ID($category_id).'</button>';
                }
              echo '</div>';
            }
          }
          if(osetin_get_field('hide_format_filtering', $post_id) != true && $formats_to_show_in_filter){
            echo '<div class="index-filter-sub-label">'.esc_html__('Format', 'pluto').'</div>';
            echo '<div class="index-filter-formats">';
              if(in_array( 'standard', $formats_to_show_in_filter )) echo '<div class="index-filter-format" data-filter-value="standard"><i class="'.$icons["standard"].'"></i><div class="os-filter-tooltip">'.esc_html__('standard', 'pluto').'</div></div>';
              if(in_array( 'image', $formats_to_show_in_filter )) echo '<div class="index-filter-format" data-filter-value="image"><i class="'.$icons["image"].'"></i><div class="os-filter-tooltip">'.esc_html__('image', 'pluto').'</div></div>';
              if(in_array( 'gallery', $formats_to_show_in_filter )) echo '<div class="index-filter-format" data-filter-value="gallery"><i class="'.$icons["gallery"].'"></i><div class="os-filter-tooltip">'.esc_html__('gallery', 'pluto').'</div></div>';
              if(in_array( 'video', $formats_to_show_in_filter )) echo '<div class="index-filter-format" data-filter-value="video"><i class="'.$icons["video"].'"></i><div class="os-filter-tooltip">'.esc_html__('video', 'pluto').'</div></div>';
              if(in_array( 'quote', $formats_to_show_in_filter )) echo '<div class="index-filter-format" data-filter-value="quote"><i class="'.$icons["quote"].'"></i><div class="os-filter-tooltip">'.esc_html__('quote', 'pluto').'</div></div>';
              if(in_array( 'audio', $formats_to_show_in_filter )) echo '<div class="index-filter-format" data-filter-value="audio"><i class="'.$icons["audio"].'"></i><div class="os-filter-tooltip">'.esc_html__('audio', 'pluto').'</div></div>';
              if(in_array( 'link', $formats_to_show_in_filter )) echo '<div class="index-filter-format" data-filter-value="link"><i class="'.$icons["link"].'"></i><div class="os-filter-tooltip">'.esc_html__('link', 'pluto').'</div></div>';
            echo '</div>';
          }
          if(osetin_get_field('hide_clear_filters_button', $post_id) != true){
            echo '<div class="index-clear-filter-w inactive">';
              echo '<button class="index-clear-filter-btn"><i class="os-new-icon os-new-icon-x"></i> <span>'.esc_html__('Clear Filters', 'pluto').'</span></button>';
            echo '</div>';
          }
        echo '</div>';
      echo '</div>';
  }
}


// Excerpt "more" text settigns
function new_excerpt_more() {
  if(get_post_format(get_the_ID()) == 'link'){
    return '...<div class="read-more-link"><a href="'. osetin_get_field( 'external_link' ) . '">' . __('Read More', 'pluto') . '</a></div>';
  }else{
    return '...<div class="read-more-link"><a href="'. get_permalink( get_the_ID() ) . '">' . __('Read More', 'pluto') . '</a></div>';
  }
}
add_filter( 'excerpt_more', 'new_excerpt_more' );


function os_excerpt($limit = 16, $more = TRUE) {
  if(!$limit){
    $limit = 16;
  }
  if(osetin_get_double_width_class() != '') $limit = $limit * 2;
  if($more){
    return wp_trim_words(get_the_excerpt(), $limit, new_excerpt_more());
  }else{
    return wp_trim_words(get_the_excerpt(), $limit, "");
  }

}

function os_quote_excerpt($limit = 16){
  return wp_trim_words(get_the_excerpt(), $limit, '...<span class="quote-read-more-link">' . __('Read More', 'pluto') . '</span>');
}


function os_get_less_var($key, $default){
  global $my_less;
  return $my_less->get_var($key, $default);
}


function os_footer(){
  ?>

  <?php if ( is_active_sidebar( 'sidebar-footer' ) ) { ?>
  <div class="pre-footer stacked-widgets widgets-count-<?php echo os_count_sidebar_widgets('sidebar-footer'); ?> color-scheme-<?php echo os_get_less_var('preFooterScheme', 'light');?>">
      <?php dynamic_sidebar( 'sidebar-footer' ); ?>
  </div>
  <?php } ?>
  <div class="main-footer with-social color-scheme-<?php echo os_get_less_var('footerScheme', 'light');?>">
    <div class="footer-copy-and-menu-w">
      <?php if ( has_nav_menu( 'footer' ) ) { ?>
      <?php wp_nav_menu( array( 'theme_location' => 'footer', 'menu_id' => 'footer-menu', 'container_class' => 'footer-menu' ) ); ?>
      <?php } ?>
      <div class="footer-copyright"><?php echo osetin_get_field('footer_text', 'option', '<a href="https://pinsupreme.com/wordpress-theme/clean-personal-masonry-blog-wordpress-theme" target="_blank" title="High Quality Wordpress theme for personal blogs">Masonry Grid Style Wordpress Blog Theme</a>'); ?></div>
    </div>
    <div class="footer-social-w">
      <?php if( function_exists('zilla_social') ) zilla_social(); ?>
    </div>
  </div>
  <?php
}

function os_the_primary_sidebar($position = 'right', $masonry = FALSE){
  $sidebar_position_in_settings = osetin_get_field('sidebar_position', 'option', 'right');
  if($position != $sidebar_position_in_settings) return;
  $condition = $masonry ? (os_get_show_sidebar_on_masonry() == true) : true;
  if( ( $sidebar_position_in_settings != "none" ) && is_active_sidebar( 'sidebar-1' ) && $condition ){ ?>
    <div class="primary-sidebar-wrapper">
      <div id="primary-sidebar" class="primary-sidebar widget-area" role="complementary">
        <?php dynamic_sidebar( 'sidebar-1' ); ?>
      </div>
    </div><?php
  }
}

function os_get_content_class($masonry = FALSE){
  $content_class = 'main-content-w';
  return $content_class;
}

function osetin_single_top_social_share(){
  if(osetin_get_field('disable_social_share_icons_on_post', 'option') != TRUE): ?>
    <div class="single-post-top-share">
      <i class="os-new-icon os-new-icon-share-2 share-activator-icon share-activator"></i>
      <span class="share-activator-label share-activator caption"><?php _e("Share", "pluto") ?></span>
      <div class="os_social-head-w"><?php echo do_shortcode('[os_social_buttons]'); ?></div>
    </div>
  <?php endif;
}

function osetin_top_social_share(){
  if(osetin_get_field('disable_social_share_icons_on_post', 'option') != TRUE): ?>
    <div class="post-top-share">
      <i class="fa os-icon-plus share-activator-icon share-activator"></i>
      <span class="share-activator-label share-activator caption"><?php _e("Share", "pluto") ?></span>
      <div class="os_social-head-w"><?php echo do_shortcode('[os_social_buttons]'); ?></div>
    </div>
  <?php endif;
}
function osetin_top_social_share_index(){
  if(os_is_post_element_active('social')): ?>
    <?php if(is_rtl()): ?>
      <div class="post-top-share">
        <span class="share-activator-label share-activator caption"><?php _e("Share", "pluto") ?></span>
        <i class="fa os-icon-plus share-activator-icon share-activator"></i>
        <div class="os_social-head-w"><?php echo do_shortcode('[os_social_buttons]'); ?></div>
      </div>
    <?php else: ?>
      <div class="post-top-share">
        <i class="fa os-icon-plus share-activator-icon share-activator"></i>
        <span class="share-activator-label share-activator caption"><?php _e("Share", "pluto") ?></span>
        <div class="os_social-head-w"><?php echo do_shortcode('[os_social_buttons]'); ?></div>
      </div>
    <?php endif; ?>
  <?php endif;
}

function os_is_post_element_active($element){
  $forse_hide_element = 'forse_hide_element_'.$element;
  global $$forse_hide_element;
  if(!isset($$forse_hide_element)) $$forse_hide_element = false;
  if($$forse_hide_element == true) return false;

  if(osetin_get_field('hide_from_index_posts', 'options', array('view_count'))){
    return !in_array($element, osetin_get_field('hide_from_index_posts', 'options', array('view_count')));
  }else{
    return true;
  }
}

// Generate next page link for infinite scroll
function os_get_next_posts_link($os_query){
  $current_page = ( isset($os_query->query['paged']) ) ? $os_query->query['paged'] : 1;
  $next_page = ($current_page < $os_query->max_num_pages) ? $current_page + 1 : false;
  if($next_page){
    return http_build_query(wp_parse_args( array('paged' => $next_page), $os_query->query));
  }else{
    return false;
  }
}

// Loads get_template_part() into variable
function os_load_template_part($template_name, $part_name=null) {
    ob_start();
    get_template_part($template_name, $part_name);
    $var = ob_get_contents();
    ob_end_clean();
    return $var;
}

function get_current_menu_position()
{
  if(isset($_SESSION['menu_position'])){
    $menu_position = $_SESSION['menu_position'];
  }else{
    $menu_position = osetin_get_field('menu_position', 'option', 'left');
  }
  return $menu_position;
}


function get_current_menu_style()
{
  if(isset($_SESSION['menu_style'])){
    $menu_style = $_SESSION['menu_style'];
  }else{
    $menu_style = osetin_get_field('menu_style', 'option');
  }
  return $menu_style;
}




function os_get_current_color_scheme()
{
  if(isset($_SESSION['color_scheme'])){
    $color_scheme = $_SESSION['color_scheme'];
  }else{
    $color_scheme = osetin_get_field('color_scheme', 'option');
  }
  return $color_scheme;
}


function os_get_current_navigation_type()
{
  if(isset($_SESSION['navigation_type'])){
    $navigation_type = $_SESSION['navigation_type'];
  }else{
    $navigation_type = osetin_get_field('index_navigation_type', 'option', 'infinite');
  }
  return $navigation_type;
}

function os_lightbox_posts_enabled_class(){
  if(osetin_get_field('make_posts_open_in_lightbox_option', 'option') || osetin_get_field('make_posts_open_in_lightbox')) $lightbox_class = 'lightbox-tiles';
  else $lightbox_class = '';
  return $lightbox_class;
}

function os_get_show_sidebar_on_masonry()
{
  if(osetin_get_field('show_sidebar', false, false)){
    $show_sidebar_on_masonry = true;
  }else{
    $show_sidebar_on_masonry = osetin_get_field('show_sidebar_on_masonry_page', 'option');
  }
  return $show_sidebar_on_masonry;
}

function os_get_use_fixed_height_index_posts()
{
  global $forse_fixed_height;
  if(!isset($forse_fixed_height)) $forse_fixed_height = false;
  if($forse_fixed_height == true){
    $use_fixed_height_index_posts = true;
  }else{
    $use_fixed_height_index_posts = osetin_get_field('use_fixed_height_index_posts', 'option');
  }
  return $use_fixed_height_index_posts;
}

function os_get_show_featured_posts_on_index()
{
  $show_featured_posts_on_index = osetin_get_field('featured_posts_slider');
  return $show_featured_posts_on_index;
}

function os_get_featured_posts_type_on_index()
{
  $featured_posts_type_on_index = osetin_get_field('featured_slider_type');
  return $featured_posts_type_on_index;
}


/**
 * Osetin themes helpers functions
 *
 * @package Jupiter
 *
 */

function osetin_translate_column_width_to_span( $width = '' ){
  switch ( $width ) {
    case "1/12" :
      $column_class = "col-sm-1";
      break;
    case "1/6" :
      $column_class = "col-sm-2";
      break;
    case "1/4" :
      $column_class = "col-sm-3";
      break;
    case "1/3" :
      $column_class = "col-sm-4";
      break;
    case "5/12" :
      $column_class = "col-sm-5";
      break;
    case "1/2" :
      $column_class = "col-sm-6";
      break;
    case "7/12" :
      $column_class = "col-sm-7";
      break;
    case "2/3" :
      $column_class = "col-sm-8";
      break;
    case "3/4" :
      $column_class = "col-sm-9";
      break;
    case "5/6" :
      $column_class = "col-sm-10";
      break;
    case "11/12" :
      $column_class = "col-sm-11";
      break;
    case "1/1" :
      $column_class = "col-sm-12";
      break;
    default :
      $column_class = "col-sm-12";
    }
    return $column_class;
}


/**
 * Get url for the color directory with images
 */
function osetin_get_color_images_directory_uri($color = 'blue')
{
  return get_template_directory_uri() . "/assets/images/colors/" . $color;
}


/**
 * Get url for the color directory with images
 */
function osetin_get_images_directory_uri()
{
  return get_template_directory_uri() . "/assets/images";
}


function osetin_get_external_link_button(){
  $output = '';
  if(osetin_get_field('show_external_link_button') === true){
    $external_link_url = osetin_get_field('external_link_button_url', false, '');
    $external_link_show_link_as = osetin_get_field('external_link_button_label', false, esc_html__('Visit Link', 'sun-by-osetin'));
    $external_link_price = osetin_get_field('external_link_button_price');
    if($external_link_url){
      $output.= '<a class="external-link-link" href="'.esc_url($external_link_url).'" target="_blank"><span>'.$external_link_show_link_as.'</span></a>';
      if($external_link_price){
        $output.= '<a href="'.esc_url($external_link_url).'" target="_blank" class="external-link-price">'.$external_link_price.'</a>';
      }
    }
  }
  return $output;
}

function osetin_generate_sub_bar($woo = false){
  if(osetin_get_field('remove_breadcrumbs', 'option') === true) return;
  echo '<div class="sub-bar-w hidden-sm hidden-xs"><div class="sub-bar-i">';
  if($woo){
    if(function_exists('woocommerce_breadcrumb')) woocommerce_breadcrumb();
  }else{
    osetin_output_breadcrumbs();
  }
  osetin_social_share_icons('header');
  echo '</div></div>';
}


function osetin_social_share_icons($location){

}

function osetin_output_breadcrumbs(){
  echo '<ul class="bar-breadcrumbs">';
    if(is_home()){
      echo '<li><span>'.esc_html__('Home', 'pluto').'</span></li>';
    }elseif(is_category()){
      echo '<li><a href="'.site_url().'">'.esc_html__('Home', 'pluto').'</a></li>';
      echo '<li>'.get_cat_name(get_query_var('cat')).'</li>';
    }elseif(is_search()){
      echo '<li><a href="'.site_url().'">'.esc_html__('Home', 'pluto').'</a></li>';
      echo '<li>'.esc_html__('Search results for: ', 'pluto').get_search_query().'</li>';
    }elseif(is_archive()){
      echo '<li><a href="'.site_url().'">'.esc_html__('Home', 'pluto').'</a></li>';
      echo '<li>'.get_the_archive_title().'</li>';
    }else{
      echo '<li><a href="'.site_url().'">'.esc_html__('Home', 'pluto').'</a></li>';
      $categories = get_the_category();
      if(!empty($categories)){
        $category = $categories[0];
        echo '<li><a href="'.get_category_link( $category->term_id ).'" title="' . esc_attr( sprintf( esc_html__( "View all posts in %s", 'pluto' ), $category->name ) ) . '">'.$category->cat_name.'</a></li>';
      }
      echo '<li><span>'.get_the_title().'</span></li>';
    }
  echo '</ul>';
}

function osetin_get_media_content($size = false, $forse_single = false, $as_background = false)
{
  switch(get_post_format()):
    case "video": ?>
      <div class="post-video-box post-media-body"  data-featured-image-url="<?php if(has_post_thumbnail()) the_post_thumbnail_url( 'thumbnail' ); ?>">
        <?php
        global $wp_embed;
        echo $wp_embed->run_shortcode('[embed]'.osetin_get_field('video_url').'[/embed]');
        ?>
      </div>
      <?php
    break;
    case "gallery": ?>
      <?php
        $images = osetin_get_field('gallery_of_images');
        if( $images ){
          $images_arr = array();
          $padding_style = '';
          $max_proportion = 0;
          foreach( $images as $image ){
            if($size != false){
              $img_size = $size;
            }else{
              if(is_single()){
                $img_size = 'large';
              }else{
                if(os_get_use_fixed_height_index_posts() == true){
                  $img_size = 'pluto-fixed-height';
                }else{
                  if(osetin_get_double_width_class() != ''){
                    $img_size = 'pluto-full-width';
                  }else{
                    $img_size = 'pluto-index-width';
                  }
                }
              }
            }
            $img_src = $image['sizes']["{$img_size}"];

            if(!empty($image['sizes']["{$img_size}-width"]) && !empty($image['sizes']["{$img_size}-height"])){
              // calculate ratio percentage by dividing height on width and times 100 to get percent
              $max_proportion = max(((floor($image['sizes']["{$img_size}-height"] / $image['sizes']["{$img_size}-width"] * 100) / 100)  * 100), $max_proportion);
            }
            if($img_src) array_push($images_arr, array('src' => $img_src, 'alt' => $image['alt']));
          } 
          if($max_proportion > 0) $padding_style = 'padding-bottom: '.$max_proportion.'%;';
          ?>
          <div class="post-gallery-box post-media-body" data-featured-image-url="<?php if(has_post_thumbnail()) the_post_thumbnail_url( 'thumbnail' ); ?>">
            <div id="slider-<?php the_ID(); ?>" class="slick-gallery">
                <?php foreach( $images_arr as $image_arr ){
                  if($as_background)
                    echo '<figure class="slide-gallery-media" style="background-image: url('.$image_arr['src'].');' . $padding_style . '"></figure>';
                  else
                    echo '<figure><img src="'.$image_arr['src'].'" alt="'.$image_arr['alt'].'" /></figure>';
                } ?>
            </div>
          </div><?php
        }else{
          os_output_post_thumbnail($size, $forse_single);
        } ?>
      <?php
    break;
    case "image":
      os_output_post_thumbnail($size, $forse_single);
    break;
    default:
      os_output_post_thumbnail($size, $forse_single);
    break;
  endswitch;
}


function os_ad_between_posts($os_current_box_counter = false, $do_echo = true){
  $output = '';
  if($os_current_box_counter == false){
    global $os_current_box_counter;
  }
  if(osetin_get_field('enable_ads_between_posts', 'option') === true){
    // remove anything except commas and numbers from a position list
    $clean_positions = preg_replace( array('/[^\d,]/', '/(?<=,),+/', '/^,+/', '/,+$/'), '', osetin_get_field('ad_positions', 'option'));
    $os_positions = explode(",", $clean_positions);

    $key = array_search($os_current_box_counter, $os_positions);
    if($key !== false){
      $ad_blocks = osetin_get_field('ad_blocks', 'option');
      if(isset($ad_blocks[$key])){
        $current_ad_block = $ad_blocks[$key];
        switch( $current_ad_block['ad_type'] ){
          case 'image':
            $output = '<div class="item-isotope magic-item-w" data-total-likes="0" data-total-views="0"><article class="pluto-post-box"><div class="post-body"><div class="post-media-body"><a href="'.$current_ad_block['ad_link'].'"><figure><img src="'.$current_ad_block['ad_image'].'" alt="pluto"/></figure></a></div></div></article></div>';
            $key++;
          break;
          case 'html':
            $output = '<div class="item-isotope magic-item-w" data-total-likes="0" data-total-views="0"><article class="pluto-post-box"><div class="post-body"><div class="post-media-body">'.$current_ad_block['ad_html'].'</div></div></article></div>';
            $key++;
          break;
        }
      }
    }
  }
  $os_current_box_counter++;
  if($do_echo){
    echo $output;
  }else{
    return $output;
  }
}


function os_output_post_thumbnail($size = false, $forse_single = false)
{
  if(has_post_thumbnail()):
    if(is_single() || $forse_single): ?>
      <div class="post-media-body">
        <div class="figure-link-w">
          <a href="<?php echo wp_get_attachment_url( get_post_thumbnail_id() ); ?>" class="figure-link os-lightbox-activator">
            <figure>
            <?php
            if($size != false){
              $thumb_size = $size;
            }else{
              $thumb_size = 'full';
            } ?>
            <?php the_post_thumbnail($thumb_size); ?>
            <?php if(osetin_get_field('disable_image_hover_effect', 'option') != true): ?>
              <div class="figure-shade"></div><i class="figure-icon os-new-icon os-new-icon-eye"></i>
            <?php endif ?>
            </figure>
          </a>
        </div>
      </div> <?php
    else:
      $padding_style = '';
      if($size != false){
        $img_html = get_the_post_thumbnail(get_the_ID(), $size);
      }else{
        if ( basename(get_page_template()) == 'page-blog.php' ) {
          $img_html =  get_the_post_thumbnail(get_the_ID(), 'full');
        }else{

          if(os_get_use_fixed_height_index_posts() == true){
            $img_size = 'pluto-fixed-height';
          }else{
            if(osetin_get_double_width_class() != ''){
              $img_size = 'pluto-full-width';
            }else{
              $img_size = 'pluto-index-width';
            }
          }

          $img_html = get_the_post_thumbnail(get_the_ID(), $img_size);
          $item_image_arr = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), $img_size);

          if(!empty($item_image_arr[0]) && !empty($item_image_arr[1]) && !empty($item_image_arr[2])){
            // calculate ratio percentage by dividing height on width and times 100 to get percent
            $item_image_proportion_percent = (floor($item_image_arr[2] / $item_image_arr[1] * 100) / 100)  * 100;
            $padding_style = 'padding-bottom: '.$item_image_proportion_percent.'%;';
          }
        }
      }
      $extra_classes = '';
      $os_link = get_permalink();
      if(get_post_format() == 'image'){
        $extra_classes = 'os-lightbox-activator';
        $os_link = wp_get_attachment_url( get_post_thumbnail_id() );
      }
      if(get_post_format() == 'link'){
        $os_link = osetin_get_field('external_link');
      }
      $shade_html = (osetin_get_field('disable_image_hover_effect', 'option') == true) ? "" : '<div class="figure-shade"></div><i class="figure-icon  os-new-icon os-new-icon-eye"></i>';
       ?>
      <?php $new_window = (get_post_format() == 'link') ? 'target="_blank"' : ""; ?>
      <div class="post-media-body"><div class="figure-link-w"><a href="<?php echo $os_link; ?>" <?php echo $new_window ?> class="figure-link <?php echo $extra_classes; ?>"><figure <?php if($padding_style != '') echo 'class="abs-image" style="'.$padding_style.'"'; ?>><?php echo $img_html; ?><?php echo $shade_html; ?></figure></a></div></div>
      <?php
    endif;
  endif;
}



if ( ! function_exists( 'osetin_the_attached_image' ) ) :
/**
 * Print the attached image with a link to the next attached image.
 *
 * @since Pluto 1.0
 *
 * @return void
 */
function osetin_the_attached_image() {
  $post                = get_post();
  /**
   * Filter the default Pluto attachment size.
   *
   * @since Pluto 1.0
   *
   * @param array $dimensions {
   *     An array of height and width dimensions.
   *
   *     @type int $height Height of the image in pixels. Default 810.
   *     @type int $width  Width of the image in pixels. Default 810.
   * }
   */
  $attachment_size     = apply_filters( 'osetin_attachment_size', array( 810, 810 ) );
  $next_attachment_url = wp_get_attachment_url();

  /*
   * Grab the IDs of all the image attachments in a gallery so we can get the URL
   * of the next adjacent image in a gallery, or the first image (if we're
   * looking at the last image in a gallery), or, in a gallery of one, just the
   * link to that image file.
   */
  $attachment_ids = get_posts( array(
    'post_parent'    => $post->post_parent,
    'fields'         => 'ids',
    'numberposts'    => -1,
    'post_status'    => 'inherit',
    'post_type'      => 'attachment',
    'post_mime_type' => 'image',
    'order'          => 'ASC',
    'orderby'        => 'menu_order ID',
  ) );

  // If there is more than 1 attachment in a gallery...
  if ( count( $attachment_ids ) > 1 ) {
    foreach ( $attachment_ids as $attachment_id ) {
      if ( $attachment_id == $post->ID ) {
        $next_id = current( $attachment_ids );
        break;
      }
    }

    // get the URL of the next image attachment...
    if ( $next_id ) {
      $next_attachment_url = get_attachment_link( $next_id );
    }

    // or get the URL of the first image attachment.
    else {
      $next_attachment_url = get_attachment_link( array_shift( $attachment_ids ) );
    }
  }

  printf( '<a href="%1$s" rel="attachment">%2$s</a>',
    esc_url( $next_attachment_url ),
    wp_get_attachment_image( $post->ID, $attachment_size )
  );
}
endif;