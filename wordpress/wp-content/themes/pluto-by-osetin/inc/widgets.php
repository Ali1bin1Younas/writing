<?php


/**
 * Adds Osetin_Categories_Widget widget.
 */
class Osetin_Categories_Widget extends WP_Widget {

  /**
   * Register widget with WordPress.
   */
  function __construct() {
    parent::__construct(
      'osetin_categories_widget', // Base ID
      __( 'Osetin Categories with Images', 'osetin' ), // Name
      array( 'description' => __( 'Catogories Table Widget', 'osetin' ), ) // Args
    );
  }

  /**
   * Front-end display of widget.
   *
   * @see WP_Widget::widget()
   *
   * @param array $args     Widget arguments.
   * @param array $instance Saved values from database.
   */
  public function widget( $args, $instance ) {
    echo $args['before_widget'];
    if ( ! empty( $instance['title'] ) ) {
      echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
    }
    $limit = osetin_get_field('limit', 'widget_'.$args['widget_id']);
    $include_child_categories = osetin_get_field('include_child_categories', 'widget_'.$args['widget_id']);
    $specific_ids = osetin_get_field('specific_ids', 'widget_'.$args['widget_id'], false, true);
    $attr_string = '';
    if($limit) $attr_string.= ' limit="'.$limit.'"';
    if($include_child_categories) $attr_string.= ' include_child_categories="true"';
    if($specific_ids) $attr_string.= ' specific_ids="'.implode($specific_ids, ',').'"';
    echo do_shortcode('[osetin_categories_icons'.$attr_string.']');
    echo $args['after_widget'];
  }

  /**
   * Back-end widget form.
   *
   * @see WP_Widget::form()
   *
   * @param array $instance Previously saved values from database.
   */
  public function form( $instance ) {
    $title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'New title', 'text_domain' );
    ?>
    <p>
    <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
    </p>
    <?php
  }

  /**
   * Sanitize widget form values as they are saved.
   *
   * @see WP_Widget::update()
   *
   * @param array $new_instance Values just sent to be saved.
   * @param array $old_instance Previously saved values from database.
   *
   * @return array Updated safe values to be saved.
   */
  public function update( $new_instance, $old_instance ) {
    $instance = array();
    $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

    return $instance;
  }

} // class Osetin_Categories_Widget

register_widget( 'Osetin_Categories_Widget' );