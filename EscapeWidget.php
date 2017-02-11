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
			//Display all the criteron on the widget
			?>
			<html>
				<table>
					<tr>
						<td><label>Critères </label></td>
 						<td style="text-align:left;">Pas important</td>
 						<td style="text-align:right;">Important</td>
					</tr>
						<?php
						global $wpdb;
						$colum = $wpdb->get_col("DESC {$wpdb->prefix}Recommendation_system", 0);
						$size_array = count($colum);

						for ($i = 3; $i < $size_array; $i ++)
						{
							?>
							<tr>
								<td><label><?php echo $colum[$i] ?></label></td>
		                		<td colspan="2"><input type="range" id="<?php echo $i ?>ID" value="50" min="1" max="100" step ="0.1" oninput="<?php echo $i ?>Output.value = <?php echo $i ?>Input.value"></input> </td>
<!-- 		                		<td><output name="ScoreOutputName" id="ScoreOutputId<?php echo $i ?>">50</output></td>
 -->		                   </tr>
		                   <?php
						}
						?>
    			</table>
    			<div style="text-align:center">  <input type="submit"/> </div>  
			</html>
	    <?php
		}

		//Allows to define a title to the widget
		public function form($instance)
		{
			$instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
			$title = sanitize_text_field( $instance['title'] );
			?>
			<p ><label  for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>
			<?php
		}
	}

?>