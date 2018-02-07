<?php

if (!defined('OSETIN_FEATURE_VOTE_VERSION')) define('OSETIN_FEATURE_VOTE_VERSION', '1.0');

// --------------------------
// VOTING FUNCTIONS BY OSETIN
// --------------------------


function os_vote_init(){
  add_action( 'wp_ajax_os_vote_process_request', 'os_vote_process_request' );
  add_action( 'wp_ajax_nopriv_os_vote_process_request', 'os_vote_process_request' );
}


function os_vote_build_button($post_id, $extra_class = '', $vote_data = false, $likes_type = 'squared', $link = ''){
  $voted_label = '';
  $not_voted_label = '';
  if($likes_type == 'facebook'){
    echo '<div class="fb-like" data-href="'.esc_url($link).'" data-layout="button_count" data-action="like" data-show-faces="false" data-share="false"></div>';
  }elseif($likes_type == 'pinterest'){
    echo '<a data-pin-do="buttonPin" href="https://www.pinterest.com/pin/create/button/?url='.esc_url($link).'" data-pin-config="beside"  data-pin-color="red"></a>';
  }else{
    if($vote_data == false){
      $vote_data = os_vote_get_post_vote_data($post_id);
    } ?>
    <a href="#" class="<?php echo esc_attr($extra_class); ?> os-like-button osetin-vote-trigger <?php if(!$vote_data['count']) echo 'zero-votes'; ?> <?php echo ($vote_data['has_voted']) ? 'osetin-vote-has-voted' : 'osetin-vote-not-voted'; ?>" data-has-voted-label="<?php $voted_label; ?>" data-not-voted-label="<?php $not_voted_label; ?>" data-post-id="<?php echo esc_attr($post_id); ?>" data-vote-action="<?php echo ($vote_data['has_voted']) ? 'unvote' : 'vote'; ?>" data-votes-count="<?php echo esc_attr($vote_data['count']); ?>">
      <span class="os-like-button-i">
        <?php 
          switch($likes_type){ 
            case 'regular':
              echo '<span class="os-like-button-icon"><i class="os-icon os-icon-heart"></i></span>';
            break;
            case 'outlined':
              echo '<span class="os-like-button-icon"><i class="os-icon os-icon-heart3"></i></span>';
            break;
            case 'squared':
            default:
              echo '<span class="os-like-button-icon"><i class="os-icon-heart"></i></span>';
            break;
          }
          ?>
          <span class="os-like-button-label osetin-vote-action-label">
            <?php echo ($vote_data['has_voted']) ? $voted_label : $not_voted_label; ?>
          </span>
          <span class="os-like-button-sub-label osetin-vote-count <?php if(!$vote_data['count']) echo 'hidden'; ?>">
            <?php echo esc_html($vote_data['count']);  ?>
          </span>
      </span>
    </a><?php
  }
}

function os_vote_process_request(){
  $post_id = $_POST['vote_post_id'];
  $vote_action = $_POST['vote_action'];

  if($post_id && $vote_action){
    switch($vote_action){
      case 'vote':
        echo wp_send_json(array('status' => 200, 'message' => os_vote_do_vote($post_id)));
      break;
      case 'unvote':
        echo wp_send_json(array('status' => 200, 'message' => os_vote_do_unvote($post_id)));
      break;
      case 'read':
        echo wp_send_json(array('status' => 200, 'message' => os_vote_get_post_vote_data($post_id)));
      break;
    }
  }else{
    echo wp_send_json(array('status' => 422, 'message' => 'Invalid data supplied'));
  }
  wp_die();
}

// --------------------------
// GET VOTE INFO ABOUT A POST
// --------------------------

function os_vote_get_post_vote_data($post_id = false){
  $votes_count = get_post_meta($post_id, '_zilla_likes', true);

  // create a post meta if the field does not exist yet
  if(!$votes_count) add_post_meta($post_id, '_zilla_likes', 0, true);

  $has_voted = os_vote_has_voted($post_id);
  $vote_data = array('count' => $votes_count, 'has_voted' => $has_voted);



  return $vote_data;
}






// -------------------------------
// CHECK IF USER HAS ALREADY VOTED
// -------------------------------

function os_vote_has_voted($post_id = false){
  return isset($_COOKIE['os_vote_'. $post_id]);
}







// ----------
// ADD A VOTE 
// ----------

function os_vote_do_vote($post_id = false){
  $vote_data = os_vote_get_post_vote_data($post_id);

  // if user has already voted - exit
  if($vote_data['has_voted']) return $vote_data;

  update_post_meta($post_id, '_zilla_likes', $vote_data['count'] + 1);
  $cookie_expire_on = time()+60*60*24*30;
  setcookie('os_vote_'. $post_id, $post_id, $cookie_expire_on, '/');




  return os_vote_get_post_vote_data($post_id);
}





// -------------
// REMOVE A VOTE 
// -------------

function os_vote_do_unvote($post_id = false){
  $vote_data = os_vote_get_post_vote_data($post_id);

  // check if user has voted for this post and there are any votes on this post 
  if($vote_data['has_voted'] && ($vote_data['count'] > 0)){

    update_post_meta($post_id, '_zilla_likes', $vote_data['count'] - 1);
    setcookie('os_vote_'. $post_id, $post_id, 1, '/');



    return os_vote_get_post_vote_data($post_id);

  }else{

    return $vote_data; 

  }

} 


