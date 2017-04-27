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
			//Display all the room on the widget
			?>
			<html>
			<script type="text/javascript" src="/scripts.js"></script>
				<div class="tooltip">Aide
  					<span class="tooltiptext">- Selectionnez une salle <br>- Renseigner les notes<br>- Renseignez votre expertise<br>- Valider le formulaire de saisie de note</span>
				</div>
				<div id="choix_salle">
					<p>Selectionnez une salle</p>
				    <SELECT id="selectBox" onchange="choix_Salle()">
				    	<OPTION></OPTION>
				    <?php
						global $wpdb;
						$result = $wpdb->get_results( "SELECT Name FROM {$wpdb->prefix}system_recommandation_salles");
						$i = 0;
                        foreach ($result as $salle) {
						?>
						<OPTION><?php echo $salle->Name ?></OPTION>
		                   <?php
						}
						?>
					</SELECT> 
				</div>
				<div id="id_saisieNote" style="visibility:hidden; display:none">
				<p id="selected_salle"></p>
				<table id="tab_saisieNote" style="width:100%">
					<tr>
						<td><label>Critères </label></td>
 						<td style="text-align:left">Pas important</td>
 						<td style="text-align:right">Important</td>
 						<td style="text-align:right">Valeur</td>
					</tr>
						<?php
						global $wpdb;

						$result = $wpdb->get_results( "SELECT Name FROM {$wpdb->prefix}system_recommandation_criteres");
						$i = 0;
                        foreach ( $result as $row ) 
						{
							?>
							<tr>
								<td><label><?php echo $row->Name ?></label></td>
		                		<td colspan="2"><input type="range" style="width:90%" id="<?php echo $i ?>IDsaisieNote" value="50" min="1" max="100" step ="0.1" oninput="show_range_value('<?php echo $i ?>IDsaisieNote', '<?php echo $i ?>_2_value')"></input> </td>
		                		<td id="<?php echo $i ?>_2_value" style="text-align:right">50</td>
		                   </tr>
		                   <?php
		                   $i += 1;
						}
						?>
    			</table>
    			<table id="tab_expertise_2" style="width:100%">
    				<tr><td>expertise</td><td style="text-align:left">faible</td><td style="text-align:right">elevée</td><td style="text-align:right">Valeur</td></tr>
					<tr>
						<td>expertise</td>
						<td colspan="2"><input type="range" style="width:90%" id="id_expertise_2" value="50" min="1" max="100" step ="0.1" oninput="show_range_value('id_expertise_2', 'expertise_2_value')"></input></td>
						<td id="expertise_2_value" style="text-align:right">50</td>
					</tr>   				
    			</table>
    			<input type="submit" style="text-align:center" value="Envoyer le formulaire" onclick="launch_amas_feedback_saisieNotes('<?php echo plugins_url();?>')"/>
    			</div>
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