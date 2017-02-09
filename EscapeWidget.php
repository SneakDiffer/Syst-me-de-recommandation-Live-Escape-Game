<?php

	class Escape_widget extends WP_Widget
	{
		public function __construct()
		{
			parent::__construct('ID_widget','Escape_Room_Widget',array('description' => 'Un widget de système de recommandation'));
		}

		public function widget($args,$instance)
		{
			echo apply_filters('widget_title', $instance['title']);
			?>

			<html>
<!-- 			<link rel="stylesheet" href="style.css" />
 -->			<form>
            
<!-- 		<input type="range" name="ageInputName" id="ageInputId" value="50" min="1" max="100" step ="0.1" oninput="ageOutputId.value = ageInputId.value">
 			<output name="ageOutputName" id="ageOutputId">50</output>
 -->		
			<label for="zero_newsletter_email">Votre email :</label>
			<label for="zero_newsletter_email">Votre email :</label>

			</form>
			</html>
	    <?php
		}

		//Allows to define a title to the widget
		public function form($instance)
		{
			$instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
			$title = sanitize_text_field( $instance['title'] );
			?>
			<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>
			<?php
		}
	}

?>