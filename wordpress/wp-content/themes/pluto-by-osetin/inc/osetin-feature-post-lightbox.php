<?php

if (!defined('OSETIN_FEATURE_POST_LIGHTBOX_VERSION')) define('OSETIN_FEATURE_POST_LIGHTBOX_VERSION', '1.0');

// --------------------------
// VOTING FUNCTIONS BY OSETIN
// --------------------------


function pluto_post_lightbox_init(){
  add_action( 'wp_ajax_pluto_post_lightbox_process_request', 'pluto_post_lightbox_process_request' );
  add_action( 'wp_ajax_nopriv_pluto_post_lightbox_process_request', 'pluto_post_lightbox_process_request' );
}


function pluto_post_lightbox_process_request(){
  if(isset($_POST['post_id'])){

    $args = array(
    'post_status' => 'publish',
    'p' => $_POST['post_id']
    );

    $response_html = '';

    $pluto_post = new WP_Query( $args );
    if ( $pluto_post->have_posts() ) {
      while ( $pluto_post->have_posts() ) : $pluto_post->the_post();
        $response_html.= '<div class="lightbox-post-w" data-post-id="'.get_the_ID().'"><div class="lightbox-post-i">';

          ob_start();
          $forse_single = true;
          // get_template_part( 'single-content', get_post_format() );
          include(locate_template('single-content.php'));

          $response_html.= ob_get_clean();


        $response_html.= '</div></div>';
      endwhile;
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
