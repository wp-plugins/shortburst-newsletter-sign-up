<?php
/*
Plugin Name: ShortBurst Newsletter Sign Up
Plugin URI: http://www.pauleycreative.co.uk
Description: Submit newsletter sign up data to your <a href="http://www.shortburst.co.uk" target="_blank">ShortBurst</a> mailing list.
Author: Duncan Morley
Version: 1.0
Author URI: http://twitter.com/DuncanMorley
*/

// Add function to widgets_init that'll load our widget.
add_action( 'widgets_init', 'shortburst_load' );

// Register our widget
function shortburst_load() {
	register_widget( 'Shortburst' );
}

class Shortburst extends WP_Widget {

	// Widget setup
	function Shortburst() {
		
		// Get directory of plugin
		$plugin_url = plugin_dir_url(__FILE__);
		
		// instruction to only load if it is not the admin area
		if ( ! is_admin()) {
			// register your script location, dependencies and version
			wp_register_script('shortburst', $plugin_url.'js/scripts.js', 'jquery', 1.0 );
			// register your script location, dependencies and version
			wp_register_style('shortburst', $plugin_url.'css/style.css', null, 1.0 );
			// enqueue the script
			wp_enqueue_script('jquery');
			wp_enqueue_script('shortburst');
			// enqueue the style
			wp_enqueue_style('shortburst');
		}
		
		// Widget settings
		$widget_ops = array(
			'classname' => 'shortburst',
			'description' => __('Submit newsletter sign up data to ShortBurst', 'shortburst')
		);

		// Widget control settings
		$control_ops = array(
			'width' => 300,
			'height' => 350,
			'id_base' => 'shortburst'
		);

		// Create the widget
		$this->WP_Widget( 'shortburst', __( 'ShortBurst Newsletter Sign Up', 'shortburst' ), $widget_ops, $control_ops );
	}

	// How to display the widget on the screen
	function widget( $args, $instance ) {
		extract( $args );

		/* Our variables from the widget settings */
		$title = apply_filters('widget_title', $instance['title'] );
		$text = $instance['text'];
		$input = $instance['input'];
		$button = $instance['button'];
		$error = $instance['error'];
		$shortburst = $instance['shortburst'];
		$thanks = $instance['thanks'];
		
		// Get directory of plugin
		$plugin_url = plugin_dir_url(__FILE__);

		/* Before widget (defined by themes) */
		echo $before_widget;

		/* Display the widget title if one was input (before and after defined by themes). */
		if ( $title )
			echo $before_title . $title . $after_title; ?>
			
			<div class="widget-inner">
				<?php
				// Display any introduction text
				if ( $text ) echo wpautop($text); ?>
				
				<div id="sbError" style="display:none;"><?php if ( $error ) echo $error; ?></div>
				<form id="sbForm" method="post" action="<?php echo $plugin_url ?>process.php">
				
					<input type="hidden" name="hidFormInfo" value="<?php if ( $shortburst ) echo $shortburst; ?>">
					<input type="hidden" name="thankYouPage" value="<?php if ( $thanks ) echo $thanks; ?>">
					
					<input type="text" name="txtField_0" class="requiredAndIgnorePlaceholder email" value="<?php if ( $input ) echo $input; ?>" onblur="if (this.value == ''){this.value = '<?php if ( $input ) echo $input; ?>'; }" onfocus="if (this.value == '<?php if ( $input ) echo $input; ?>') {this.value = ''; }" />
					<input type="submit" value="<?php echo $button; ?>" />
				</form>
			</div>

		<?php
		/* After widget (defined by themes). */
		echo $after_widget;
	}

	/*** Update the widget settings */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		/* Strip tags for title and name to remove HTML (important for text inputs). */
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['text'] = strip_tags( $new_instance['text'] );
		$instance['input'] = strip_tags( $new_instance['input'] );
		$instance['button'] = strip_tags( $new_instance['button'] );
		$instance['error'] = strip_tags( $new_instance['error'] );
		$instance['shortburst'] = strip_tags( $new_instance['shortburst'] );
		$instance['thanks'] = $new_instance['thanks'];

		return $instance;
	}

	/**
	 * Displays the widget settings controls on the widget panel.
	 * Make use of the get_field_id() and get_field_name() function
	 * when creating your form elements. This handles the confusing stuff.
	 */
	function form( $instance ) {

		/* Set up some default widget settings. */
		$defaults = array(
			'title' => __('Newsletter sign up', 'shortburst' ),
			'input' => __('Enter email address', 'shortburst' ),
			'button' => __('Sign up', 'shortburst' ),
			'error' => __('You have entered an invalid email address. Please try again.', 'shortburst' )
		);
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'shortburst'); ?></label>
			<input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:98%;" />
		</p>
		
		<!-- Introduction Text: Text Area -->
		<p>
			<label for="<?php echo $this->get_field_id('text'); ?>"><?php _e('Introduction text:', 'shortburst'); ?></label>
			<textarea id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name( 'text' ); ?>" style="width:100%;"><?php echo $instance['text']; ?></textarea>
		</p>
		
		<!-- Input Text: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id('input'); ?>"><?php _e('Email input text:', 'shortburst'); ?></label>
			<input id="<?php echo $this->get_field_id('input'); ?>" name="<?php echo $this->get_field_name( 'input' ); ?>" value="<?php echo $instance['input']; ?>" style="width:98%;" />
		</p>
		
		<!-- Button Text: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id('button'); ?>"><?php _e('Button:', 'shortburst'); ?></label>
			<input id="<?php echo $this->get_field_id('button'); ?>" name="<?php echo $this->get_field_name( 'button' ); ?>" value="<?php echo $instance['button']; ?>" style="width:98%;" />
		</p>
		
		<!-- Error Message: Text Area -->
		<p>
			<label for="<?php echo $this->get_field_id('error'); ?>"><?php _e('Error message:', 'shortburst'); ?></label>
			<textarea id="<?php echo $this->get_field_id('error'); ?>" name="<?php echo $this->get_field_name( 'error' ); ?>" style="width:100%;"><?php echo $instance['error']; ?></textarea>
		</p>
		
		<!-- ShortBurst Form ID: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id('shortburst'); ?>"><?php _e('ShortBurst form ID:', 'shortburst'); ?></label>
			<input id="<?php echo $this->get_field_id('shortburst'); ?>" name="<?php echo $this->get_field_name( 'shortburst' ); ?>" value="<?php echo $instance['shortburst']; ?>" style="width:98%;" />
		</p>
		
		<!-- Thank you page URL: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id('thanks'); ?>"><?php _e('Thank you page URL:', 'shortburst'); ?></label>
			<input id="<?php echo $this->get_field_id('thanks'); ?>" name="<?php echo $this->get_field_name( 'thanks' ); ?>" value="<?php echo $instance['thanks']; ?>" style="width:98%;" />
		</p>

	<?php
	}
}

?>