<?php
/**
 * The Header for our theme
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package Pluto
 * @since Pluto 1.0
 */
?><!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8) ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php bloginfo('name'); ?> | <?php is_front_page() ? bloginfo('description') : wp_title(''); ?></title>
  <link rel="profile" href="http://gmpg.org/xfn/11">
  <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
  <?php if(osetin_get_field("google_plus_authorship_url", "option")): ?>
    <link rel="author" href="<?php echo osetin_get_field('google_plus_authorship_url', 'option'); ?>">
  <?php endif; ?>
  <?php wp_head(); ?>
  <!--[if lt IE 9]>
  <script src="<?php echo get_template_directory_uri(); ?>/js/html5shiv.min.js"></script>
  <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/respond.min.js"></script>
  <![endif]-->
</head>

<body <?php body_class(); ?>>
  <?php if(osetin_get_field('google_analytics_code', 'option')): ?>
    <?php echo osetin_get_field('google_analytics_code', 'option'); ?>
  <?php endif; ?>
  <div class="all-wrapper with-loading">
  <?php 
    if(get_current_menu_position() == "top"): 
    // FIXED HEADER MENU START
    ?>
    <div class="menu-position-top menu-style-v2">
    <div class="fixed-header-w">
      <div class="menu-block">
        <div class="menu-inner-w">
          <div class="logo">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
              <?php if(osetin_get_field('logo_image', 'option')){ ?>
                <img src="<?php echo osetin_get_field('logo_image', 'option'); ?>" alt="">
              <?php }else{ ?>
                <img src="<?php echo get_template_directory_uri() . '/assets/images/logo-' . os_get_less_var('menuLogoScheme', 'light') . '.png'; ?>" alt="">
              <?php } ?>
              <?php if(osetin_get_field('logo_text', 'option')): ?>
                <span><?php echo osetin_get_field('logo_text', 'option'); ?></span>
              <?php endif; ?>
            </a>
          </div>
          <div class="menu-activated-on-hover menu-w">
            <?php wp_nav_menu(array('theme_location'  => 'side_menu', 'fallback_cb' => false, 'container_class' => 'os_menu')); ?>
          </div>
          <?php if(!osetin_get_field('hide_search_box_from_top_bar', 'option')){ ?>
            <div class="menu-search-form-w <?php if(!osetin_get_field('no_hide_search_box_on_smaller_screens', 'option')) echo 'hide-on-narrow-screens'; ?>">
              <div class="search-trigger"><i class="os-new-icon os-new-icon-search"></i></div>
            </div>
          <?php } ?>
          <?php if(!osetin_get_field('hide_social_icons_from_top_bar', 'option')){ ?>
            <div class="menu-social-w hidden-sm hidden-md">
              <?php if( function_exists('zilla_social') ) zilla_social(); ?>
            </div>
          <?php } ?>
        </div>
      </div>
    </div>
    </div>
  <?php 
    // END  --- FIXED HEADER MENU  --- END
    endif; ?>
  <div class="menu-block <?php if(osetin_get_field('hide_widgets_under_menu', 'option') == TRUE) echo 'hidden-on-smaller-screens'; ?>">
    <?php if(get_current_menu_position() == "top"): ?>
      <?php if(get_current_menu_style() == 'v2'){ 
        // COMPACT MENU ON TOP
        ?>
        <div class="menu-inner-w">
          <div class="logo">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
              
            <?php if(osetin_get_field('logo_image', 'option')){ ?>
              <img src="<?php echo osetin_get_field('logo_image', 'option'); ?>" alt="">
            <?php }else{ ?>
              <img src="<?php echo get_template_directory_uri() . '/assets/images/logo-' . os_get_less_var('menuLogoScheme', 'light') . '.png'; ?>" alt="">
            <?php } ?>
              <?php if(osetin_get_field('logo_text', 'option')): ?>
                <span><?php echo osetin_get_field('logo_text', 'option'); ?></span>
              <?php endif; ?>
            </a>
          </div>
          <div class="menu-activated-on-hover menu-w">
            <?php wp_nav_menu(array('theme_location'  => 'side_menu', 'fallback_cb' => false, 'container_class' => 'os_menu')); ?>
          </div>
          <?php if(!osetin_get_field('hide_search_box_from_top_bar', 'option')){ ?>
            <div class="menu-search-form-w <?php if(!osetin_get_field('no_hide_search_box_on_smaller_screens', 'option')) echo 'hide-on-narrow-screens'; ?>">
              <div class="search-trigger"><i class="os-new-icon os-new-icon-search"></i></div>
            </div>
          <?php } ?>
          <?php if(!osetin_get_field('hide_social_icons_from_top_bar', 'option')){ ?>
            <div class="menu-social-w hidden-sm hidden-md">
              <?php if( function_exists('zilla_social') ) zilla_social(); ?>
            </div>
          <?php } ?>
        </div>
      <?php }else{ 
        // BIG MENU ON TOP
      ?>
      <div class="menu-inner-w">
        <?php if( function_exists('zilla_social') ) zilla_social(); ?>
        <div class="logo">
          <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
            
            <?php if(osetin_get_field('logo_image', 'option')){ ?>
              <img src="<?php echo osetin_get_field('logo_image', 'option'); ?>" alt="">
            <?php }else{ ?>
              <img src="<?php echo get_template_directory_uri() . '/assets/images/logo-' . os_get_less_var('menuLogoScheme', 'light') . '.png'; ?>" alt="">
            <?php } ?>
            <?php if(osetin_get_field('logo_text', 'option')): ?>
              <span><?php echo osetin_get_field('logo_text', 'option'); ?></span>
            <?php endif; ?>
          </a>
        </div>
        <?php get_search_form(); ?>
      </div>
      <div class="menu-activated-on-hover">
        <?php wp_nav_menu(array('theme_location'  => 'side_menu', 'fallback_cb' => false, 'container_class' => 'os_menu')); ?>
      </div>
      <?php } ?>

    <?php else: ?>

      <div class="menu-left-i">
      <div class="logo">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
          <?php if(osetin_get_field('logo_image', 'option')){ ?>
            <img src="<?php echo osetin_get_field('logo_image', 'option'); ?>" alt="">
          <?php }else{ ?>
            <img src="<?php echo get_template_directory_uri() . '/assets/images/logo-' . os_get_less_var('menuLogoScheme', 'light') . '.png'; ?>" alt="">
          <?php } ?>
          <?php if(osetin_get_field('logo_text', 'option')): ?>
            <span><?php echo osetin_get_field('logo_text', 'option'); ?></span>
          <?php endif; ?>
        </a>
      </div>
      <?php if(osetin_get_field('search_form_position', 'option', 'above_menu') == 'above_menu') get_search_form(); ?>

      <?php if(!osetin_get_field('hide_highlight_elements_in_menu', 'option', false)) echo '<div class="divider"></div>'; ?>

      <div class="menu-activated-on-click">
        <?php wp_nav_menu(array('theme_location'  => 'side_menu', 'fallback_cb' => false, 'container_class' => 'os_menu')); ?>
      </div>


      <?php if(osetin_get_field('search_form_position', 'option') == 'under_menu') get_search_form(); ?>



      <?php if(!osetin_get_field('hide_highlight_elements_in_menu', 'option', false)) echo '<div class="divider"></div>'; ?>

      <?php if(osetin_get_field('search_form_position', 'option') == 'above_social') get_search_form(); ?>


      <?php if( function_exists('zilla_social') ) zilla_social(); ?>


      <?php if(osetin_get_field('search_form_position', 'option') == 'under_social') get_search_form(); ?>



      <?php if ( is_active_sidebar( 'sidebar-2' ) ) : ?>
        <div class="under-menu-sidebar-wrapper">
            <?php dynamic_sidebar( 'sidebar-2' ); ?>
        </div>
      <?php endif; ?>


      </div>

    <?php endif; ?>
  </div>
  <div class="menu-toggler-w">
    <a href="#" class="menu-toggler">
      <i class="os-new-icon os-new-icon-menu"></i>
      <span class="menu-toggler-label"><?php _e('Menu', 'pluto') ?></span>
    </a>
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="logo">
      <?php if(osetin_get_field('logo_image', 'option')){ ?>
        <img src="<?php echo osetin_get_field('logo_image', 'option'); ?>" alt="">
      <?php }else{ ?>
        <img src="<?php echo get_template_directory_uri() . '/assets/images/logo-' . os_get_less_var('menuLogoScheme', 'light') . '.png'; ?>" alt="">
      <?php } ?>
      <?php if(osetin_get_field('logo_text', 'option')): ?>
        <span><?php echo osetin_get_field('logo_text', 'option'); ?></span>
      <?php endif; ?>
    </a>
    <a href="#" class="search-trigger">
      <i class="os-new-icon os-new-icon-search"></i>
    </a>
    
    <a href="#" class="sidebar-toggler">
      <i class="os-new-icon os-new-icon-grid"></i>
    </a>
  </div>
  <div class="mobile-menu-w">
    <?php wp_nav_menu(array('theme_location'  => 'side_menu', 'fallback_cb' => false, 'container_class' => 'mobile-menu menu-activated-on-click')); ?>
  </div>
  <?php if(osetin_get_field('show_sidebar_on_mobile', 'option')){ ?>
    <div class="sidebar-main-toggler">
      <i class="os-new-icon os-new-icon-grid"></i>
    </div>
  <?php } ?>