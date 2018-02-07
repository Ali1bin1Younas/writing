<?php
$template_type = str_replace('_', '-', osetin_get_field('archive_layout_type', 'option'));

$likes_type = 'regular';
if(in_array(osetin_get_field('archive_layout_type', 'option'), array("page_masonry_condensed_facebook", "page_masonry_simple_facebook"))){
  $likes_type = 'facebook';
}
if(in_array(osetin_get_field('archive_layout_type', 'option'), array("page_masonry_condensed_pinterest", "page_masonry_simple_pinterest"))){
  $likes_type = 'pinterest';
}

$forse_fixed_height = ((osetin_get_field('archive_layout_type', 'option') == "page_masonry_condensed_fixed_height") || os_get_use_fixed_height_index_posts() == true) ? true : false;
$layout_mode = ((osetin_get_field('archive_layout_type', 'option') == "page_masonry_condensed_fixed_height") || os_get_use_fixed_height_index_posts() == true) ? 'fitRows' : 'masonry';
$template_part = (osetin_get_field('archive_layout_type', 'option') == "page_masonry") ? 'content' : 'v3-content';


if(in_array(osetin_get_field('archive_layout_type', 'option'), array('page_masonry_simple_facebook', 'page_masonry_simple'))){
  $layout_type = 'v3-simple';
  $isotope_class = 'v3 isotope-simple';
  $forse_hide_element_read_more = true;
  $forse_hide_element_date = true;
}elseif(in_array(osetin_get_field('archive_layout_type', 'option'), array('page_masonry', 'page_masonry_simple'))){
  $layout_type = 'v1';
  $isotope_class = 'v1';
}else{
  $layout_type = 'v3';
  $isotope_class = 'v3 isotope-condensed';
}

if(osetin_get_field('archive_layout_type', 'option') == 'page_masonry_condensed_with_author'){
  $show_author_face = true;
  $isotope_class.= ' isotope-with-author';
}else{
  $show_author_face = false;
}
?>