<?php
// Setup the event handler for marking this post as read for the current user
add_action( 'wp_ajax_load_infinite_content', 'load_infinite_content' );
add_action( 'wp_ajax_nopriv_load_infinite_content', 'load_infinite_content' );

function load_infinite_content(){
  $new_posts = '';
  $post_query_args = $_POST['next_params'].'&post_status=publish';
  $os_query = new WP_Query($post_query_args);
  $posts_per_page = isset($os_query->query['posts_per_page']) ? $os_query->query['posts_per_page'] : get_option('posts_per_page');
  
  global $double_width_posts_arr;
  global $os_current_box_counter;

  $os_current_box_counter = ($os_query->query['paged'] - 1) * $posts_per_page;

  $double_width_posts_arr = array();
  if($_POST['page_id']){
    $double_width_posts_arr = osetin_get_double_width_posts_arr($_POST['page_id']);
  }

  while ($os_query->have_posts()) : $os_query->the_post();
    $os_current_box_counter++;
    $content_partial = 'content';
    if(isset($_POST['layout_type'])){
      if($_POST['layout_type'] == 'v2'){
        $content_partial = 'v2-content';
      }
      if($_POST['layout_type'] == 'v3'){
        $content_partial = 'v3-content';
      }
      if($_POST['layout_type'] == 'v3-simple'){
        $content_partial = 'v3-content';
        global $forse_hide_element_read_more;
        global $forse_hide_element_date;
        $forse_hide_element_read_more = true;
        $forse_hide_element_date = true;
      }
      if(in_array($_POST['template_type'], array('page-masonry-condensed-facebook', 'page-masonry-simple-facebook'))){
        global $likes_type;
        $likes_type = 'facebook';
      }
      if(in_array($_POST['template_type'], array('page-masonry-condensed-pinterest', 'page-masonry-simple-pinterest'))){
        global $likes_type;
        $likes_type = 'pinterest';
      }
      if($_POST['template_type'] == 'page-masonry-condensed-fixed-height'){
        global $forse_fixed_height;
        $forse_fixed_height = true;
      }
      if($_POST['template_type'] == 'page-masonry-condensed-with-author'){
        global $show_author_face;
        $show_author_face = true;
      }
    }
    $new_posts.= os_ad_between_posts($os_current_box_counter, false);

    
    // $new_posts.= os_load_template_part( $content_partial, get_post_format(), $double_width_posts_arr, $os_current_box_counter );

    ob_start();
    get_template_part( $content_partial, get_post_format() );
    // get_template_part($template_name, $part_name);
    $new_posts.= ob_get_clean();

  endwhile;
  if($os_query->query['paged'] < $os_query->max_num_pages){
    $next_params = os_get_next_posts_link($os_query);
  }else{
    $next_params = null;
  }
  wp_reset_postdata();
  $json_response = json_encode(array());
  if($new_posts != ''){
    $json_response = json_encode(array('success' => TRUE, 'has_posts' => TRUE, 'new_posts' => $new_posts, 'next_params' => $next_params, 'no_more_posts_message' => __('No more posts', 'pluto')));
  }else{
    $json_response = json_encode(array('success' => TRUE, 'has_posts' => FALSE, 'no_more_posts_message' => __('No more posts', 'pluto')));
  }
  echo $json_response;
  die();
}