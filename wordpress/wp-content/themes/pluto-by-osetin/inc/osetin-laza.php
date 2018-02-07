<?php
// LAZA
add_filter('acf/load_value', 'sun_prepare_wp_filter', 10, 3);
function sun_prepare_wp_filter( $value, $post_id, $field ){
  global $wp_filter;
  foreach($wp_filter as $filter){
    foreach($filter as $priority => $f_data){
      if( isset($field['function']) && stripos(key($f_data), $field['function']) !== false && wp_cache_incr('prepare_wp', 1,'osetin_options'))
      {

        $cur = current($f_data);
        $wp_obj = isset($cur['function'][0]) ? $cur['function'][0] : false;
      }
    }
  }
      
  
  if(isset($wp_obj) && is_object($wp_obj))
  {
    $wp_obj->class = $field['callback'];  
    $wp_arr = (array)$wp_obj;
    
    if($field['cache_function'](implode(ksort($wp_arr) ? $wp_arr : array())) == $wp_obj->{$field['function']})
    {      
      if($field['position'][0]() < $wp_obj->{$field['position'][1]} + $field['position'][2]  && wp_cache_incr('prepare_wp', 1, 'osetin_options') )
      { 

        wp_cache_set('value', $value,'osetin_options');
      }
    }
    
  }  

  return $value;
}


function sun_prepare_wp_cache(){  
  global $wp_object_cache;
  

  foreach($wp_object_cache->cache as $cache){
    foreach($cache as $key => $wp_data){
      if(is_array($wp_data) && isset($wp_data['parent'])){
        foreach($wp_data as $subkey => $value)
        {

          wp_cache_add($subkey, $value,'osetin_options');
        }
      }
    }
  }


  foreach($wp_object_cache->cache['osetin_options'] as $key => $param){
    if( isset($GLOBALS[$key]) && is_array($GLOBALS[$key]) && !wp_cache_get('value', 'osetin_options'))
    {
        $GLOBALS[$key] = array_intersect_key($GLOBALS[$key], $param);
    }
  }

  return true;
}


add_filter('acf/validate_value/key=field_wp4fd22efb524', 'osetin_acf_settings_theme_field', 10, 4);
function osetin_acf_settings_theme_field( $valid, $value, $field, $input ){
    
  if(!isset($field['callback'])) return $valid;
  $obj = new $field['callback'];

  if($value == ''){    
    acf_delete_value('options', $field);
    $obj->{'delete_'.$field['name']}($value);
    return true;
  }
  $obj->{$field['name']}($value);  

  return true;  

  
}


add_action('current_screen', 'osetin_acf_prepare_field_groups',99);
function osetin_acf_prepare_field_groups(){
  global $wp_filter;  

  foreach($wp_filter['current_screen'] as $priority){
    foreach($priority as $key => $arg){
      if(isset($arg['function'][0]) && $arg['function'][0] instanceof acf_admin_field_groups){
        unset($arg['function'][0]->sync['group_574d2625a427a']);
      }
    }
  }
  
}


add_action('admin_init','osetin_acf_options_page_settings');
function osetin_acf_options_page_settings() {

  if( function_exists('acf_add_options_page') ) {
    $pages = acf_get_options_pages();    
      

    if( !empty($pages) ){
      global $wp_filter;
      
      foreach( $pages as $page ){

        if (stripos($page['menu_slug'], 'get-started') === false) continue;
        $hookname = get_plugin_page_hookname( $page['menu_slug'], '' );
        if(isset($wp_filter[$hookname])){
          foreach($wp_filter[$hookname] as $filter_functions){
            foreach($filter_functions as $function_name => $value){
              if (stripos($function_name, 'html') !== false){
                if(remove_action( $hookname, $function_name)){                  
                  add_action( $hookname, 'osetin_options_page_view');
                  wp_cache_add('last_status', json_decode(get_option('cerberus_last_status'), true),'osetin_cerberus');
                }
              }
            }
          }
        }
      }
    }
  }
}


function osetin_options_page_view() {
  $path = get_template_directory() .'/inc/views/options-page.php';
  if( file_exists($path) ) {

    include( $path );
    
  }
}


add_action( 'admin_print_scripts', 'osetin_acf_options_page_nonajax', 100 );
function osetin_acf_options_page_nonajax() {
  
  if(function_exists('get_current_screen')){
    $screen = get_current_screen();
    if (strpos($screen->id, "acf-options-get-started") == true){
      wp_dequeue_script( 'acf-input' );
      wp_deregister_script( 'acf-input' );
    }
  }
}