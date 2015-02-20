<?php
/*
Plugin Name: Show post 
Plugin URI: http://URI_Of_Page_Describing_Plugin_and_Updates
Description: this plugin will show the posts in sidebar related to active post categories
Version: 1.0
Author: manoj dhiman
Author URI: www.blog2knowledge.com
License: A "Slug" license name e.g. GPL2
*/


class wp_my_plugin extends WP_Widget {

	// constructor
    function wp_my_plugin() {
        parent::WP_Widget(false, $name = __('Display Posts', 'wp_widget_plugin') );
    }
	
	

	
	
	// widget form creation
function form($instance) {

// Check values
if( $instance) {
     $title = esc_attr($instance['title']);
     $text = esc_attr($instance['text']);
     $textarea = esc_textarea($instance['textarea']);
} else {
     $title = '';
     $text = '';
     $textarea = '';
}
?>

<p>
<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Widget Title', 'wp_widget_plugin'); ?></label>
<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
</p>

<p>
<label for="<?php echo $this->get_field_id('text'); ?>"><?php _e('Text:', 'wp_widget_plugin'); ?></label>
<input class="widefat" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>" type="text" value="<?php echo $text; ?>" />
</p>
<?php
}

	// update widget
function update($new_instance, $old_instance) {
      $instance = $old_instance;
      // Fields
      $instance['title'] = strip_tags($new_instance['title']);
      $instance['text'] = strip_tags($new_instance['text']);
     return $instance;
}

	// display widget
function widget($args, $instance) 
{
	if(is_category() || is_single())
	{
		extract( $args );
		// these are the widget options
		$title = apply_filters('widget_title', $instance['title']);
		$text = $instance['text'];
		$textarea = $instance['textarea'];
		echo $before_widget;
		// Display the widget
		echo '<div class="widget-text wp_widget_plugin_box">';
		// Check if title is set
		if ( $title ) 
		{
			echo $before_title . $title . $after_title;
		}
		// Check if text is set
		if( $text ) 
		{
			echo '<p class="wp_widget_plugin_text">'.$text.'</p>';
		}
		global $post;
		foreach(get_the_category() as $category)
		{
			$current = $category->cat_ID;
			$current_name = $category->cat_name;
		}
		$myposts = get_posts('numberposts=50&category='.$current);
		foreach($myposts as $post) : 
		$id=$post->ID;
		?>
		<li class="related-posts post-<?php echo $id; ?>">
			<a href="<?php the_permalink(); ?>">
				<?php the_title(); ?>
			</a>
		</li>
		<?php endforeach; 
		echo '</div>';
		echo $after_widget;
		}
	}
}

define('CSSURL', untrailingslashit(plugins_url('/', __FILE__)));
/**
 * Proper way to enqueue scripts and styles
 */
function theme_name_scripts() {
	wp_enqueue_style( 'style-name', CSSURL.'/css/style.css' );
}
add_action( 'wp_enqueue_scripts', 'theme_name_scripts' );
// register widget
add_action('widgets_init', create_function('', 'return register_widget("wp_my_plugin");'));

