<?php

	class FeedBack_widget extends WP_Widget
	{
		public function __construct()
		{
			parent::__construct('ID_FeedBack_widget','FeedBack_widget',array('description' => 'Un widget de Feed Back utilisateur'));
		}

		public function widget($args,$instance)
		{
			echo apply_filters('widget_title', $instance['title']);
			//Display all the criteron on the widget
			?>
			<html>
				<table>
					<tr>
						<td><label>Salles </label></td>
 						<td style="text-align:left;">Faible</td>
 						<td style="text-align:right;">Elevé</td>
					</tr>
						<?php
						global $wpdb;
						$result = $wpdb->get_results( "SELECT nom_salle FROM {$wpdb->prefix}Recommendation_system");
						$i = 0;
                        foreach ( $result as $row ) 
						{
						?>
							<tr>
								<td><label><?php echo $row->nom_salle ?></label></td>
		                		<td colspan="2"><input type="range" id="<?php echo $i ?>ID" value="50" min="1" max="100" step ="0.1" oninput="<?php echo $i ?>Output.value = <?php echo $i ?>Input.value"></input> </td>
		            		</tr>
		                   <?php
		                   $i += 1;
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