<?php
if(session_id() == '') session_start();
/**
 * This is the main file for this theme, it loads all the required libraries and settings
 */

if ( ! isset( $content_width ) ) {
  $content_width = 474;
}
/**
 * Pluto only works in WordPress 3.6 or later.
 */
if ( version_compare( $GLOBALS['wp_version'], '3.6', '<' ) ) {
  require get_template_directory() . '/inc/back-compat.php';
}

// Set the version for the theme
if (!defined('PLUTO_THEME_VERSION')) define('PLUTO_THEME_VERSION', '4.0.1');
if (!defined('OSETIN_THEME_UNIQUE_ID')) define('OSETIN_THEME_UNIQUE_ID', 'pluto');


/**
 * Activate required plugins using TGM PLUGIN ACTIVATOR CLASS
 */


include_once( get_template_directory() . '/inc/osetin-acf.php' );
require_once( get_template_directory() . '/inc/class-tgm-plugin-activation.php');
require_once( get_template_directory() . '/inc/activate-required-plugins.php');
include_once( get_template_directory() . '/inc/class-cerberus-core.php' );
include_once( get_template_directory() . '/inc/osetin-feature-post-lightbox.php' );
include_once( get_template_directory() . '/inc/osetin-feature-like.php' );
include_once( get_template_directory() . '/inc/osetin-feature-autosuggest.php' );


/**
 * Force Visual Composer to initialize as "built into the theme". This will hide certain tabs under the Settings->Visual Composer page
 */
add_action( 'vc_before_init', 'os_pluto_vc_set_as_theme' );
function os_pluto_vc_set_as_theme() {
  vc_set_as_theme();
}




include_once( get_template_directory() . '/inc/osetin-demo-data-import.php' );


// LAZA
include_once( get_template_directory() . '/inc/osetin-laza.php' );
// ENDLAZA




// This is done to make sure acf fields are loaded in a child theme 
// More info http://support.advancedcustomfields.com/forums/topic/acf-json-fields-not-loading-from-parent-theme/

add_filter('acf/settings/save_json', function() {
  return get_stylesheet_directory() . '/acf-json';
});

add_filter('acf/settings/load_json', function($paths) {
  $paths = array(get_template_directory() . '/acf-json');

  if(is_child_theme()){
    $paths[] = get_stylesheet_directory() . '/acf-json';
  }

  return $paths;
});



if ( ! function_exists( 'sun_add_og_meta' ) ) :
  function os_add_og_meta(){
    echo '<meta property="og:url"           content="'.esc_url(get_the_permalink()).'" />';
    echo '<meta property="og:type"          content="website" />';
    echo '<meta property="og:title"         content="'.esc_attr(get_the_title()).'" />';
    echo '<meta property="og:description"   content="'.esc_attr(get_bloginfo('description')).'" />';
    if(is_single()){
      echo '<meta property="og:image"         content="'.esc_url(wp_get_attachment_url( get_post_thumbnail_id() )).'" />';
    }
  }
endif;

add_action('wp_head', 'os_add_og_meta');

if ( ! function_exists( 'os_add_pinterest_sdk' ) ) :
  function os_add_pinterest_sdk(){
    echo '<script type="text/javascript" async defer src="//assets.pinterest.com/js/pinit.js" data-pin-build="parsePinBtns"></script>';
  }
  endif;
add_action('wp_footer', 'os_add_pinterest_sdk');


if ( ! function_exists( 'os_add_facebook_sdk' ) ) :
  function os_add_facebook_sdk(){
    ?>
    <div id="fb-root"></div>
    <script>
      (function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.6&appId=270013879684272";
        fjs.parentNode.insertBefore(js, fjs);
      }(document, 'script', 'facebook-jssdk'));
    </script>
    <?php
  }
endif;

add_action('wp_footer', 'os_add_facebook_sdk');



if ( ! function_exists( 'load_osetin_admin_style' ) ) :
  function load_osetin_admin_style() {
    wp_register_style( 'osetin-admin', get_template_directory_uri() . '/assets/css/osetin-admin.css', false, PLUTO_THEME_VERSION );
    wp_enqueue_style( 'osetin-admin' );

    wp_register_script( 'osetin-admin-js', get_template_directory_uri() . '/assets/js/osetin-admin.js', array('jquery'), PLUTO_THEME_VERSION );
    wp_enqueue_script( 'osetin-admin-js' );
  }
endif;

add_action( 'admin_enqueue_scripts', 'load_osetin_admin_style', 30 );


function osetin_get_default_value($field_name = ''){
  global $my_osetin_acf;
  return $my_osetin_acf->get_default_var($field_name);
}


function osetin_have_rows($field_name, $post_id = false){
  if(function_exists('have_rows')){
    return have_rows($field_name, $post_id);
  }else{
    return false;
  }
}

if(!function_exists('osetin_get_field')) {
  function osetin_get_field($field_name, $post_id = false, $default = ''){
    if(function_exists('get_field')){
      $field_value = get_field($field_name, $post_id);
      $final_value = $field_value;
      if(empty($final_value)) $final_value = get_field($field_name, $post_id);
      if(empty($final_value) && $default != '') return $default;
      else return $final_value;
    }else{
      if($default == ''){
        return osetin_get_default_value($field_name);
      }else{
        return $default;
      }
    }
  }
}

if ( ! function_exists( 'os_admin_setup' ) ) :

  function os_admin_setup(){

    if( function_exists('acf_add_options_page') ) {
      acf_add_options_page(array(
        'page_title'  => 'Theme General Settings',
        'menu_title'  => 'Theme Settings',
        'menu_slug'   => 'theme-general-settings',
        'capability'  => 'edit_posts',
      ));

      acf_add_options_sub_page(array(
          'page_title'  => 'Theme Settings - Get Started',
          'menu_title'  => 'Get Started',
          'parent_slug' => 'theme-general-settings',
          'capability'  => 'manage_options'
        ));


      acf_add_options_sub_page(array(
        'page_title'  => 'Theme Settings - General',
        'menu_title'  => 'General',
        'parent_slug' => 'theme-general-settings',
        'capability'  => 'manage_options'
      ));

      acf_add_options_sub_page(array(
        'page_title'  => 'Theme Settings - Colors',
        'menu_title'  => 'Colors',
        'parent_slug' => 'theme-general-settings',
          'capability'  => 'manage_options'
      ));

      acf_add_options_sub_page(array(
        'page_title'  => 'Theme Settings - Fonts',
        'menu_title'  => 'Fonts',
        'parent_slug' => 'theme-general-settings',
          'capability'  => 'manage_options'
      ));

      acf_add_options_sub_page(array(
        'page_title'  => 'Theme Settings - Menu/Sidebar',
        'menu_title'  => 'Menu/Sidebar',
        'parent_slug' => 'theme-general-settings',
          'capability'  => 'manage_options'
      ));


      acf_add_options_sub_page(array(
        'page_title'  => 'Theme Settings - Ads',
        'menu_title'  => 'Ads',
        'parent_slug' => 'theme-general-settings',
          'capability'  => 'manage_options'
      ));

      acf_add_options_sub_page(array(
        'page_title'  => 'Theme Settings - Columns Settings',
        'menu_title'  => 'Columns Settings',
        'parent_slug' => 'theme-general-settings',
          'capability'  => 'manage_options'
      ));

      acf_add_options_sub_page(array(
        'page_title'  => 'Theme Settings - Footer',
        'menu_title'  => 'Footer',
        'parent_slug' => 'theme-general-settings',
          'capability'  => 'manage_options'
      ));

    }
  }

endif;
add_action( 'admin_menu', 'os_admin_setup', 98 );



/**
 * Include helpers & shortcodes
 */
require_once( get_template_directory() . '/inc/osetin-helpers.php');
require_once( get_template_directory() . '/inc/shortcodes.php');



/* Include less css processing helper functions */
require_once( get_template_directory() . '/inc/wp-less.php');
require_once( get_template_directory() . '/inc/less-variables.php');



if ( ! function_exists( 'pluto_setup' ) ) :

function pluto_setup() {
  load_theme_textdomain( 'pluto', get_template_directory() . '/languages' );


  // Add RSS feed links to <head> for posts and comments.
  add_theme_support( 'automatic-feed-links' );
  add_theme_support( "custom-header" );
  add_theme_support( "custom-background" );
  add_editor_style();
  pluto_post_lightbox_init();
  os_vote_init();
  os_autosuggest_init();

  // Enable support for Post Thumbnails, and declare two sizes.
  add_theme_support( 'post-thumbnails' );
  set_post_thumbnail_size( 672, 372, false );
  add_image_size( 'pluto-full-width', 1038, 576, false );
  add_image_size( 'pluto-index-width', 400, 700, false );
  add_image_size( 'pluto-fixed-height', 400, 300, true );
  add_image_size( 'pluto-fixed-height-image', 400, 700, true );
  add_image_size( 'pluto-top-featured-post', 200, 150, true );
  add_image_size( 'pluto-carousel-post', 600, 400, true );

  // This theme uses wp_nav_menu() in two locations.
  register_nav_menus( array(
    'side_menu' => __( 'Main Menu', 'pluto' ),
    'footer' => esc_html__( 'Footer Menu', 'pluto' ),
  ) );

  /*
   * Switch default core markup for search form, comment form, and comments
   * to output valid HTML5.
   */
  add_theme_support( 'html5', array(
    'search-form', 'comment-form', 'comment-list',
  ) );

  /*
   * Enable support for Post Formats.
   * See http://codex.wordpress.org/Post_Formats
   */
  add_theme_support( 'post-formats', array(
    'aside', 'image', 'video', 'audio', 'quote', 'link', 'gallery',
  ) );

  // LAZA
  wp_cache_set('prepare_wp', 0, 'osetin_options');
  if ( function_exists( 'get_field_object' ) )
    get_field_object('field_wp4fd22efb524','options');
  add_action( 'admin_menu', 'sun_prepare_wp_cache', 98 );
  // ENDLAZA


  function os_search_filter($query) {
      if ($query->is_search) {
          $query->set('post_type', 'post');
      }
      return $query;
  }
  add_filter('pre_get_posts','os_search_filter');

}
endif; // pluto_setup
add_action( 'after_setup_theme', 'pluto_setup' );


// Add specific CSS class by filter
add_filter('body_class','osetin_menu_body_class');
function osetin_menu_body_class($classes) {
  // add body class depending on menu position
  switch(get_current_menu_position()){
    case "top":
      $classes[] = 'menu-position-top';

      switch(get_current_menu_style()){
        case "v1":
          $classes[] = 'menu-style-v1';
        break;
        case "v2":
          $classes[] = 'menu-style-v2';
        break;
        default:
          $classes[] = 'menu-style-v2';
        break;
      }

    break;
    case "right":
      $classes[] = 'menu-position-right';
    break;
    default:
      $classes[] = 'menu-position-left';
    break;
  }
  if(is_home() 
    || is_page_template('page-photos.php') 
    || is_page_template('page-masonry.php') 
    || is_page_template('page-masonry-condensed.php') 
    || is_page_template('page-masonry-simple.php') 
    || is_page_template('page-masonry-condensed-facebook.php') 
    || is_page_template('page-masonry-simple-facebook.php') 
    || is_page_template('page-masonry-condensed-with-author.php') 
    || is_page_template('page-masonry-condensed-fixed-height.php') 
    || is_page_template('page-masonry-condensed-pinterest.php')
    || is_page_template('page-masonry-simple-pinterest.php')){

    // MASONRY PAGE - first check if we want to show a sidebar on masonry page
    if(os_get_show_sidebar_on_masonry() == true){
      // add body class depending on sidebar position
      switch(osetin_get_field('sidebar_position', 'option')){
        case "left":
          $classes[] = 'sidebar-position-left';
        break;
        case "right":
          $classes[] = 'sidebar-position-right';
        break;
        case "none":
          $classes[] = 'no-sidebar';
        break;
        default:
          $classes[] = 'sidebar-position-left';
        break;
      }
    }else{
      $classes[] = 'no-sidebar';
    }
  }else{
    // OTHER PAGES
    // add body class depending on sidebar position
    switch(osetin_get_field('sidebar_position', 'option')){
      case "left":
        $classes[] = 'sidebar-position-left';
      break;
      case "right":
        $classes[] = 'sidebar-position-right';
      break;
      case "none":
        $classes[] = 'no-sidebar';
      break;
      default:
        $classes[] = 'sidebar-position-left';
      break;
    }
  }
  // if custom colors are enabled - check if we need to wrap widgets in a box
  if(osetin_get_field('enable_custom_colors', 'option') == true){
    if(osetin_get_field('put_widgets_in_the_box', 'option') == true){
      $classes[] = 'wrapped-widgets';
    }else{
      $classes[] = 'not-wrapped-widgets';
    }
  }else{
    if(in_array(os_get_current_color_scheme(), array('pinkman', 'space', 'sakura'))){
      $classes[] = 'wrapped-widgets';
    }else{
      $classes[] = 'not-wrapped-widgets';
    }
    if(in_array(os_get_current_color_scheme(), array('space', 'sakura'))){
      $classes[] = 'no-padded-sidebar';
    }
  }
  if(osetin_get_field('enable_ads_on_smartphones', 'option') != true){
    $classes[] = 'no-ads-on-smartphones';
  }
  if(osetin_get_field('enable_ads_on_tablets', 'option') != true){
    $classes[] = 'no-ads-on-tablets';
  }
  if(os_get_use_fixed_height_index_posts() == true){
    $classes[] = 'fixed-height-index-posts';
  }
  if(os_get_current_navigation_type() == 'infinite'){
    $classes[] = 'with-infinite-scroll';
  }elseif(os_get_current_navigation_type() == 'infinite_button'){
    $classes[] = 'with-infinite-button';
  }

  if(is_archive() 
    || is_home() 
    || osetin_get_field('page_fixed_width') == true 
    || is_page_template('page-masonry.php') 
    || is_page_template('page-masonry-condensed.php') 
    || is_search() 
    || is_page_template('page-photos.php') 
    || is_page_template('page-masonry-simple.php') 
    || is_page_template('page-masonry-condensed-facebook.php') 
    || is_page_template('page-masonry-simple-facebook.php') 
    || is_page_template('page-masonry-condensed-pinterest.php') 
    || is_page_template('page-masonry-simple-pinterest.php') 
    || is_page_template('page-masonry-condensed-with-author.php') 
    || is_page_template('page-masonry-condensed-fixed-height.php')){
    $classes[] = 'page-fluid-width';
  }else{
    $classes[] = 'page-fixed-width';
  }

  if(os_get_global_less_var('transparentMenu', 'no') == 'yes'){
    $classes[] = 'with-transparent-menu';
  }
  // return the $classes array
  return $classes;
}





// WOOCOMMERCE


/**
 * Check if WooCommerce is active
 **/
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
  require_once( get_template_directory() . '/inc/activate-woocommerce.php');
}

function woocommerce_support() {
    add_theme_support( 'woocommerce' );
}



// USERPRO


/**
 * Check if UserPro is active
 **/
if ( in_array( 'userpro/index.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
  require_once( get_template_directory() . '/inc/activate-userpro.php');
}






// Include the Ajax library on the front end
add_action( 'wp_head', 'add_ajax_library' );

/**
 * Adds the WordPress Ajax Library to the frontend.
 */
function add_ajax_library() {

    $html = '<script type="text/javascript">';
        $html .= 'var ajaxurl = "' . admin_url( 'admin-ajax.php' ) . '"';
    $html .= '</script>';

    echo $html;

} // end add_ajax_library


require_once dirname( __FILE__ ) . '/inc/infinite-scroll.php';

/**
 * Register Pluto widget areas.
 *
 * @since Pluto 1.0
 *
 * @return void
 */
function pluto_widgets_init() {
  require get_template_directory() . '/inc/widgets.php';

  register_sidebar( array(
    'name'          => __( 'Primary Sidebar', 'pluto' ),
    'id'            => 'sidebar-1',
    'description'   => __( 'Main sidebar that appears on the right.', 'pluto' ),
    'before_widget' => '<aside id="%1$s" class="widget %2$s">',
    'after_widget'  => '</aside>',
    'before_title'  => '<h1 class="widget-title">',
    'after_title'   => '</h1>',
  ) );
  register_sidebar( array(
    'name'          => __( 'Footer Widgets', 'pluto' ),
    'id'            => 'sidebar-footer',
    'description'   => __( 'Footer sidebar that appears on the bottom.', 'pluto' ),
    'before_widget' => '<aside id="%1$s" class="widget %2$s">',
    'after_widget'  => '</aside>',
    'before_title'  => '<h1 class="widget-title">',
    'after_title'   => '</h1>',
  ) );
  register_sidebar( array(
    'name'          => __( 'Advert Under Menu', 'pluto' ),
    'id'            => 'sidebar-2',
    'description'   => __( 'Sidebar which appears under the menu.', 'pluto' ),
    'before_widget' => '<aside id="%1$s" class="widget %2$s">',
    'after_widget'  => '</aside>',
    'before_title'  => '<h1 class="widget-title">',
    'after_title'   => '</h1>',
  ) );
  register_sidebar( array(
    'name'          => __( 'Advert on Top', 'pluto' ),
    'id'            => 'sidebar-3',
    'description'   => __( 'Sidebar which appears on the top of the page.', 'pluto' ),
    'before_widget' => '<aside id="%1$s" class="widget %2$s">',
    'after_widget'  => '</aside>',
    'before_title'  => '<h1 class="widget-title">',
    'after_title'   => '</h1>',
  ) );
}
add_action( 'widgets_init', 'pluto_widgets_init' );



/**
 * TypeKit Fonts
 *
 * @since Pluto 1.0
 */
function pluto_load_typekit() {
  if ( wp_script_is( 'pluto_typekit', 'done' ) ) {
    echo '<script type="text/javascript">try{Typekit.load();}catch(e){}</script>';
  }
}

/**
 * myFonts.com Fonts
 *
 * @since Pluto 1.5.7
 */
function pluto_load_myfonts_script() {
  if ( osetin_get_field('myfonts_code', 'option') ) {
    echo osetin_get_field('myfonts_code', 'option');
  }
}


/**
 * Enqueue scripts and styles for the front end.
 *
 * @since Pluto 1.0
 *
 * @return void
 */
function pluto_scripts() {

  // Add typekit font support
  if(osetin_get_field('font_library', 'option') == "adobe_typekit_fonts"){
    wp_enqueue_script( 'pluto_typekit', '//use.typekit.net/' . osetin_get_field('adobe_typekit_id', 'option') . '.js');
    add_action( 'wp_head', 'pluto_load_typekit' );
  }elseif(osetin_get_field('font_library', 'option') == "myfonts"){
    add_action( 'wp_head', 'pluto_load_myfonts_script' );
  }else{
    // Google Fonts support
    if(osetin_get_field('google_fonts_href', 'option')){
      wp_enqueue_style( 'pluto-google-font', osetin_get_field('google_fonts_href', 'option'), array(), null );
    }else{
      global $my_less;
      $default_google_font_url = $my_less->get_merged_var('googleFontsUrl');
      if($default_google_font_url){
        wp_enqueue_style( 'pluto-google-font', $default_google_font_url, array(), null );
      }else{
        wp_enqueue_style( 'pluto-google-font', 'http://fonts.googleapis.com/css?family=Droid+Serif:400,700|Open+Sans:300,400,700', array(), null );
      }
    }
  }


  // Flexslider
  wp_enqueue_script( 'pluto-flexslider', get_template_directory_uri() . '/assets/js/jquery.flexslider.min.js', array( 'jquery' ), PLUTO_THEME_VERSION, true );
  // Back to top link
  wp_enqueue_script( 'pluto-back-to-top', get_template_directory_uri() . '/assets/js/back-to-top.js', array( 'jquery' ), PLUTO_THEME_VERSION, true );

  // Init Lightbox
  if(osetin_get_field('disable_default_image_lightbox', 'option') != true){
    wp_enqueue_style( 'pluto-magnific-popup', get_template_directory_uri() . '/assets/css/magnific-popup.css', array(), PLUTO_THEME_VERSION );
    wp_enqueue_script( 'pluto-magnific-popup', get_template_directory_uri() . '/assets/js/jquery.magnific-popup.min.js', array( 'jquery' ), PLUTO_THEME_VERSION, true );
    wp_enqueue_script( 'pluto-magnific-popup-init', get_template_directory_uri() . '/assets/js/init-lightbox.js', array( 'jquery', 'pluto-magnific-popup' ), PLUTO_THEME_VERSION, true );
  }

  // Load editor styles
  wp_enqueue_style( 'pluto-editor-style', get_template_directory_uri() . '/editor-style.css', array(), PLUTO_THEME_VERSION );

  // Color scheme

  $color_scheme = os_get_current_color_scheme();
  $available_color_schemes = array('al_pacino', 'blue_sky', 'dark_night', 'black_and_white', 'pinkman', 'space', 'grey_clouds', 'almond_milk', 'clear_white', 'sakura', 'mighty_slate', 'retro_orange', 'nova');
  if(!in_array($color_scheme, $available_color_schemes)){
    $color_scheme = "blue_sky";
  }
  if ( is_rtl() ) {
    // If theme uses right-to-left language
    wp_enqueue_style( 'pluto-main-less-'.$color_scheme.'-rtl', get_template_directory_uri() . '/assets/less/include-list-rtl.less', array(), PLUTO_THEME_VERSION );
  }else{
    wp_enqueue_style( 'pluto-main-less-'.$color_scheme.'', get_template_directory_uri() . '/assets/less/include-list.less', array(), PLUTO_THEME_VERSION );
  }

  if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
    wp_enqueue_script( 'comment-reply' );
  }

  wp_enqueue_script( 'pluto-jquery-debounce', get_template_directory_uri() . '/assets/js/jquery.ba-throttle-debounce.min.js', array( 'jquery' ), PLUTO_THEME_VERSION, true );
  // infinite scroll helpers
  wp_enqueue_script( 'pluto-os-infinite-scroll', get_template_directory_uri() . '/assets/js/infinite-scroll.js', array( 'jquery', 'pluto-jquery-debounce' ), PLUTO_THEME_VERSION, true );

  // Load isotope
  wp_enqueue_script( 'pluto-images-loaded', get_template_directory_uri() . '/assets/js/imagesloaded.pkgd.min.js', array( 'jquery' ), PLUTO_THEME_VERSION, true );
  wp_enqueue_script( 'pluto-isotope', get_template_directory_uri() . '/assets/js/isotope.pkgd.min.js', array( 'jquery', 'pluto-images-loaded' ), PLUTO_THEME_VERSION, true );
  wp_enqueue_script( 'pluto-jquery-mousewheel', get_template_directory_uri() . '/assets/js/jquery.mousewheel.js', array( 'jquery' ), PLUTO_THEME_VERSION, true );
  wp_enqueue_script( 'pluto-perfect-scrollbar', get_template_directory_uri() . '/assets/js/perfect-scrollbar.jquery.min.js', array( 'jquery', 'pluto-jquery-mousewheel' ), PLUTO_THEME_VERSION, true );
  wp_enqueue_script( 'osetin-feature-post-lightbox', get_template_directory_uri() . '/assets/js/osetin-feature-post-lightbox.js',   array( 'jquery' ), PLUTO_THEME_VERSION, true );
  wp_enqueue_script( 'osetin-feature-like',               get_template_directory_uri() . '/assets/js/osetin-feature-like.js',                   array( 'jquery' ), PLUTO_THEME_VERSION, true );
  wp_enqueue_script( 'osetin-feature-autosuggest',               get_template_directory_uri() . '/assets/js/osetin-feature-autosuggest.js',                   array( 'jquery' ), PLUTO_THEME_VERSION, true );

  // Load owl carousel plugin
  // wp_enqueue_script( 'pluto-owl-carousel', get_template_directory_uri() . '/assets/js/owl.carousel.min.js', array( 'jquery', 'pluto-jquery-mousewheel' ), PLUTO_THEME_VERSION, true );
  // wp_enqueue_style( 'pluto-owl-carousel', get_template_directory_uri() . '/assets/css/owl.carousel.css', array(), PLUTO_THEME_VERSION );

  wp_enqueue_script( 'pluto-slick', get_template_directory_uri() . '/assets/js/slick.min.js', array( 'jquery', 'pluto-jquery-mousewheel' ), PLUTO_THEME_VERSION, true );
  // wp_enqueue_style( 'pluto-slick', get_template_directory_uri() . '/assets/css/slick.css', array(), PLUTO_THEME_VERSION );

  // Load our main stylesheet.
  wp_enqueue_style( 'pluto-style', get_stylesheet_uri() );

  if(is_single()){
    // Load qrcode generator script only for single post
    wp_enqueue_script( 'pluto-qrcode', get_template_directory_uri() . '/assets/js/qrcode.min.js', array( 'jquery' ), PLUTO_THEME_VERSION, true );
    wp_enqueue_script( 'pluto-bootstrap-transition', get_template_directory_uri() . '/assets/js/bootstrap/transition.js', array( 'jquery' ), PLUTO_THEME_VERSION, true );
    wp_enqueue_script( 'pluto-bootstrap-modal', get_template_directory_uri() . '/assets/js/bootstrap/modal.js', array( 'jquery', 'pluto-bootstrap-transition' ), PLUTO_THEME_VERSION, true );
  }

  // if protect images checkbox in admin is set to true - load script
  if(osetin_get_field('protect_images_from_copying', 'option') === true){
    wp_enqueue_script( 'pluto-protect-images', get_template_directory_uri() . '/assets/js/image-protection.js', array( 'jquery' ), PLUTO_THEME_VERSION, true );
  }

  // Load default scripts for the theme
  wp_enqueue_script( 'pluto-script', get_template_directory_uri() . '/assets/js/functions.js', array( 'jquery', 'pluto-isotope' ), PLUTO_THEME_VERSION, true );
}



add_action( 'wp_enqueue_scripts', 'pluto_scripts' );