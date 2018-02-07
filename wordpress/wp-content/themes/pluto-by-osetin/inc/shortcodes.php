<?php


// CATEGORIES ICONS SHORTCODE
// [osetin_categories_icons]
function osetin_shortcode_categories_icons_func( $atts, $content = "" ) {
    $atts = shortcode_atts( array(
      'limit' => false,
      'include_child_categories' => false,
      'specific_ids' => false
    ), $atts );

    $args = array( 'orderby' => 'name', 'order' => 'ASC' );
    if(($atts['include_child_categories'] == false) && ($atts['specific_ids'] == false)) $args['parent'] = 0;
    if($atts['limit']) $args['number'] = $atts['limit'];
    if($atts['specific_ids']) $args['include'] = $atts['specific_ids'];

    $categories = get_categories($args);

    $output = '';
    $output.= '<div class="shortcode-categories-icons">';
    $output.= '<table>';
    $output.= '<tr>';
    $counter = 0;
    foreach($categories as $category) {
      $category_icon_url = osetin_get_field('category_icon', "category_{$category->cat_ID}");
      if(empty($category_icon_url)) $category_icon_url = plugin_dir_url( __FILE__ ) . 'assets/img/placeholder-category.png';
      if((($counter % 2) == 0) && ($counter > 0)) $output.= '</tr><tr>';
      $output.= '<td>';
      $output.= '<div class="sci-media"><a href="'.get_category_link($category->cat_ID).'"><img src="'.$category_icon_url.'" alt="'.esc_attr($category->name).'"/></a></div>';
      $output.= '<div class="sci-title"><h3><a href="'.get_category_link($category->cat_ID).'">'.$category->name.'</a></h3></div>';
      $output.= '</td>';
      $counter++;
    }
    if(($counter % 2) != 0) $output .= '<td></td>';
    $output.= '</tr>';

    $output.= '</table>';
    $output.= '</div>';
    return $output;
}
add_shortcode( 'osetin_categories_icons', 'osetin_shortcode_categories_icons_func' );




// [os_social_buttons]
function shortcode_os_social_buttons_func( $atts ) {
    $a = shortcode_atts( array(
        'foo' => 'something',
        'bar' => 'something else',
    ), $atts );

    global $wp;
    $url = get_permalink();
    $text = urlencode(get_the_title());
    $icons_uri = get_template_directory_uri().'/assets/images/socialicons';
    $img_to_pin = has_post_thumbnail() ? wp_get_attachment_url( get_post_thumbnail_id() ) : "";

    $networks_to_hide = osetin_get_field('social_networks_to_hide_on_share', 'option', array());


    $html = '<div class="os_social">';
    $html.= '<a class="os_social_twitter_share" href="http://twitter.com/share?url='.$url.'&amp;text='.$text.'" target="_blank"><img src="'.$icons_uri.'/twitter.png" title="Twitter" class="os_social" alt="Tweet about this on Twitter"></a>';

    $pinterest_code = '//www.pinterest.com/pin/create/button/?url='.$url.'&amp;media='.$img_to_pin.'&amp;description='.$text;
    if(!in_array('pinterest', $networks_to_hide)) $html.= '<a class="os_social_pinterest_share" data-pin-custom="true" target="_blank" href="'.$pinterest_code.'"><img src="'.$icons_uri.'/pinterest.png" title="Pinterest" class="os_social" alt="Pin on Pinterest"></a>';
    if(!in_array('linkedin', $networks_to_hide)) $html.= '<a class="os_social_linkedin_share" href="http://www.linkedin.com/shareArticle?mini=true&amp;url='.$url.'" target="_blank"><img src="'.$icons_uri.'/linkedin.png" title="Linkedin" class="os_social" alt="Share on LinkedIn"></a>';
    if(!in_array('google', $networks_to_hide)) $html.= '<a class="os_social_google_share" href="https://plus.google.com/share?url='.$url.'" target="_blank"><img src="'.$icons_uri.'/google.png" title="Google+" class="os_social" alt="Share on Google+"></a>';
    if(!in_array('email', $networks_to_hide)) $html.= '<a class="os_social_email_share" href="mailto:?Subject='.$text.'&amp;Body=%20'.$url.'"><img src="'.$icons_uri.'/email.png" title="Email" class="os_social" alt="Email this to someone"></a>';
    if(!in_array('facebook', $networks_to_hide)) $html.= '<a class="os_social_facebook_share" href="http://www.facebook.com/sharer.php?u='.$url.'" target="_blank"><img src="'.$icons_uri.'/facebook.png" title="Facebook" class="os_social" alt="Share on Facebook"></a>';
    if(!in_array('vk', $networks_to_hide)) $html.= '<a class="os_social_vk_share" href="http://vkontakte.ru/share.php?url='.$url.'" target="_blank"><img src="'.$icons_uri.'/vkontakte.png" title="Vkontakte" class="os_social" alt="Share on Vkontakte"></a>';
    if(!in_array('ok', $networks_to_hide)) $html.= '<a class="os_social_ok_share" href="http://www.odnoklassniki.ru/dk?st.cmd=addShare&st._surl='.$url.'" target="_blank"><img src="'.$icons_uri.'/ok.png" title="Odnoklassniki" class="os_social" alt="Share on Odnoklassniki"></a>';
    $html.= '</div>';
    return $html;
}
add_shortcode( 'os_social_buttons', 'shortcode_os_social_buttons_func' );




// Featured Posts Slider shortcode
function shortcode_os_featured_slider($atts){
    $a = shortcode_atts( array(
        'foo' => 'something',
        'bar' => 'something else',
    ), $atts );
    $featured_posts = osetin_get_field('featured_posts_slider', false, false);
    $html = '<div class="featured-posts-slider-w featured-posts hidden-xs hidden-sm">';
        $html.= '<div class="featured-posts-slider-i">';
            $html.= '<div class="featured-posts-label">'.__('Featured', 'pluto').'</div>';
            $html.= '<div class="featured-posts-slider-contents side-padded-content">';
            global $post;
            foreach($featured_posts as $post){
              setup_postdata($post);
              $html.= os_load_template_part( 'featured-content', get_post_format() );
            }
            wp_reset_postdata();
            $html.= '</div>';
            $html.= '<div class="featured-posts-slider-controls">';
                $html.= '<a href="#" class="featured-post-control-up"><i class="os-icon-angle-up"></i></a>';
                $html.= '<a href="#" class="featured-post-control-down"><i class="os-icon-angle-down"></i></a>';
            $html.= '</div>';
        $html.= '</div>';
    $html.= '</div>';
    return $html;
}
add_shortcode( 'os_featured_slider', 'shortcode_os_featured_slider' );



// Featured Posts Carousel shortcode
function shortcode_os_featured_carousel($atts = array()){
    $a = shortcode_atts( array(
        'foo' => 'something',
        'bar' => 'something else',
    ), $atts );
    $featured_posts = osetin_get_field('featured_posts_slider', false, false);
    $tile_height = osetin_get_field('tile_height', false, false);
    $margin_between_tiles = osetin_get_field('margin_between_tiles', false, false);
    $tile_border_radius = osetin_get_field('tile_border_radius', false, false);
    $number_of_columns = osetin_get_field('number_of_columns', false, 4);
    $tile_css = '';
    $media_css = '';
    $fader_css = '';

    if($tile_height) $media_css = 'padding-bottom: '.$tile_height.';';
    if($margin_between_tiles) $tile_css.= 'padding: '.$margin_between_tiles.';';
    if($tile_border_radius){ 
        $media_css.= 'border-radius: '.$tile_border_radius.'px;';
        $fader_css.= 'border-radius: '.$tile_border_radius.'px;';
    }
    if(($number_of_columns > 0) || ($number_of_columns != 4)) $tile_css.= 'width: '.round(100 / $number_of_columns).'%';
    if(osetin_get_field('featured_posts_heading', false, false)) $heading = '<h4 class="featured-heading">'.osetin_get_field('featured_posts_heading', false, '').'</h4>';
    $html = '<div class="featured-carousel-w">'.$heading.'<div class="featured-carousel"  data-number-of-columns="'.$number_of_columns.'">';
    global $post;
    foreach($featured_posts as $post){
      setup_postdata($post);
        ob_start();
        require( get_template_directory() . '/featured-carousel-content.php');
        $html .= ob_get_contents();
        ob_end_clean();
    }
    wp_reset_postdata();
    $html .= '</div></div>';
    return $html;
}
add_shortcode( 'os_featured_carousel', 'shortcode_os_featured_carousel' );