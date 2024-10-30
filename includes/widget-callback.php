<?php 
/**
 * Widget Class
 */
add_action('widgets_init', create_function('', 'return register_widget("Cshcb_Widget_Callback");'));

class Cshcb_Widget_Callback extends WP_Widget {
    /** constructor -- name this the same as the class above */
    function __construct() {
        parent::__construct(false, $name = 'Csh Callback Request');	
    }


    /** @see WP_Widget::form -- do not rename this */
    function form($instance) {
        $title = "";
        if ( isset( $instance[ 'title' ] ) ) {
            $title  = esc_attr($instance['title']);
        }
        $header_text = "";
        if ( isset( $instance[ 'header_text' ] ) ) {
            $header_text  = esc_attr($instance['header_text']);
        }
        $header_desc = "";
        if ( isset( $instance[ 'header_desc' ] ) ) {
            $header_desc  = esc_attr($instance['header_desc']);
        }	

        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>">Title:</label> 
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('header_text'); ?>">Header Text</label> 
            <input class="widefat" id="<?php echo $this->get_field_id('header_text'); ?>" name="<?php echo $this->get_field_name('header_text'); ?>" type="text" value="<?php echo $header_text; ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('header_desc'); ?>">Header Description</label> 
            <input class="widefat" id="<?php echo $this->get_field_id('header_desc'); ?>" name="<?php echo $this->get_field_name('header_desc'); ?>" type="text" value="<?php echo $header_desc; ?>" />
        </p>
        <?php 
    }


    /** @see WP_Widget::update -- do not rename this */
    function update($new_instance, $old_instance) {   
        $instance = $old_instance;
        $instance['title']  = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        $instance['header_text']  = ( ! empty( $new_instance['header_text'] ) ) ? strip_tags( $new_instance['header_text'] ) : '';
        $instance['header_desc'] = ( ! empty( $new_instance['header_desc'] ) ) ? strip_tags( $new_instance['header_desc'] ) : '';
        return $instance;
    }


    /** @see WP_Widget::widget -- do not rename this */
    function widget($args, $instance) { 
        extract( $args );
        $title  = apply_filters('widget_title', $instance['title']);
        echo $before_widget;
        if ( $title ) echo $before_title . $title . $after_title;
        // -----widget style------
        $widget_form_css = get_stylesheet_directory().'/pl_templates/csh-callback/layouts/widget/widget-form.css';
        if (file_exists( $widget_form_css )) {
            wp_register_style('cshcb_widget_form', get_stylesheet_directory_uri().'/pl_templates/csh-callback/layouts/widget/widget-form.css');
        }else {
            wp_register_style('cshcb_widget_form', CSHCB_PLUGIN_LAYOUTS_URL . 'widget/widget-form.css');
        }

        // Load template.
        $template_file = get_stylesheet_directory().'/pl_templates/csh-callback/layouts/widget/widget-form.php';
        if (file_exists( $template_file )) {
            require $template_file;
        }else {
            require CSHCB_PLUGIN_LAYOUTS_DIR . '/widget/widget-form.php';
        }
        echo $after_widget;
    }
} // end class Cshcb_Widget_Callback

?>
