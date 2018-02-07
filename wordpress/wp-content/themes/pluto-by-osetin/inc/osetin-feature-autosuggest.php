<?php

if (!defined('OSETIN_FEATURE_AUTOSUGGEST_VERSION')) define('OSETIN_FEATURE_AUTOSUGGEST_VERSION', '1.0');

// --------------------------
// VOTING FUNCTIONS BY OSETIN
// --------------------------


function os_autosuggest_init(){
  add_action( 'wp_ajax_os_autosuggest_process_request', 'os_autosuggest_process_request' );
  add_action( 'wp_ajax_nopriv_os_autosuggest_process_request', 'os_autosuggest_process_request' );
}


function os_autosuggest_process_request(){
  if(isset($_POST['search_query_string'])){

    $args = array(  
    'post_status' => 'publish',
    'post_type' => array('post'),
    's' => $_POST['search_query_string']);

    $response_html = '';

    $os_search_query = new WP_Query( $args );
    if ( $os_search_query->have_posts() ) {
      $response_html.= '<div class="autosuggest-items-shadow"></div><div class="autosuggest-items">';
      while ( $os_search_query->have_posts() ) : $os_search_query->the_post();
        $response_html.= '<a href="'.get_the_permalink().'" class="autosuggest-item">';
          $response_html.= '<div class="autosuggest-item-media-w">';
            $response_html.= '<div class="autosuggest-item-media-thumbnail fader-activator" style="background-image:url('.wp_get_attachment_url( get_post_thumbnail_id() ).'); background-size: cover;">';
            $response_html.= '</div>';
          $response_html.= '</div>';
          $response_html.= '<h5 class="autosuggest-item-title">'.get_the_title().'</h5>';
        $response_html.= '</a>';
      endwhile;
      $response_html.= '</div>';
    }
    
    if($response_html){
      echo wp_send_json(array('status' => 200, 'message' => $response_html));
    }else{
      echo wp_send_json(array('status' => 404, 'message' => esc_html__('No posts found', 'pluto')));
    }

  }else{
    echo wp_send_json(array('status' => 422, 'message' => esc_html__('Invalid data supplied', 'pluto')));
  }
  wp_die();
}
