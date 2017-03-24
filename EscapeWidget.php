<?php
	class Escape_widget extends WP_Widget
	{
		public function __construct()
		{
			parent::__construct('ID_Recommandation_widget','Recommandation_Widget',array('description' => 'Un widget de système de recommandation'));	
		}

		public function widget($args,$instance)
		{
			echo apply_filters('widget_title', $instance['title']);
			//Display all the criteron on the widget
			?>
			<html>
			<script type="text/javascript" src="/scripts.js"></script>
				<table id="tab_critere">
					<tr>
						<td><label>Critères </label></td>
 						<td style="text-align:left;">Pas important</td>
 						<td style="text-align:right;">Important</td>
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
		                		<td colspan="2"><input type="range" id="<?php echo $i ?>ID" value="50" min="1" max="100" step ="0.1" oninput="<?php echo $i ?>Output.value = <?php echo $i ?>Input.value"></input> </td>
		                   </tr>
		                   <?php
		                   $i += 1;
						}
						?>
    			</table>
    			<table id="tab_expertise">
					<tr>
						<td>Séléctionnez votre expertise</td>
					</tr> 
					<tr>
						<td><input type="range" id="id_expertise" value="50" min="1" max="100" step ="0.1" oninput="<?php echo $i ?>Output.value = <?php echo $i ?>Input.value"></input></td>
					</tr>   				
    			</table>
    			<div style="text-align:center"> <input type="submit" value="Lancer la recherche" onclick="launch_amas_requete('<?php echo plugins_url();?>')"/> </div> 
    			<!-- tableau des résultats caché à l'instanciation-->
    			<div style="visibility: hidden; display: none" id="div_results">
	    			<p>Suggestions: </p> 
	    			<table>
	    				<!-- Salle / Note / Choix / lien (toujours caché) -->
	    				<tr>
	    					<td style="text-align:left;">Salle </td>
	    					<td style="text-align:center"> Score </td>
	    					<td style="text-align:right;">choisir</td>
	    				</tr>
	    				<tr>
	    					<td style="text-align:left;" id="nomSalle_1"></td>
	    					<td style="text-align:center" id="note_1"></td>
	    					<!-- bouton, onclick ouvre la page du lien associé à sa ligne -->
	    					<td style="text-align:right;"><input type="radio" name="radio_buton_choice" value="choix 1" checked="checked"/></td>
	    					<!-- lien, et idSalle toujours caché -->
	    					<td style="visibility: hidden; display: none" id="lien_1"></td>
	    					<td style="visibility: hidden; display: none" id="idSalle_1"></td>
	    				</tr>
	    				<tr>
	    					<td style="text-align:left;" id="nomSalle_2"></td>
	    					<td style="text-align:center" id="note_2"></td>
	    					<!-- bouton, onclick ouvre la page du lien associé à sa ligne -->
	    					<td style="text-align:right;"><input type="radio" name="radio_buton_choice" value="choix 2"/></td>
	    					<!-- lien, et idSalle toujours caché -->
	    					<td style="visibility: hidden; display: none" id="lien_2"></td>
	    					<td style="visibility: hidden; display: none" id="idSalle_2"></td>
	    				</tr>
	    				<tr>
	    					<td style="text-align:left;" id="nomSalle_3"></td>
	    					<td style="text-align:center" id="note_3"></td>
	    					<!-- bouton, onclick ouvre la page du lien associé à sa ligne -->
	    					<td style="text-align:right;"><input type="radio" name="radio_buton_choice" value="choix 3"/></td>
	    					<!-- lien, et idSalle toujours caché -->
	    					<td style="visibility: hidden; display: none" id="lien_3"></td>
	    					<td style="visibility: hidden; display: none" id="idSalle_3"></td>
	    				</tr>
	    			</table>
	    			<p style="text-align:center;"><input type="submit" value="Choisir cette salle" onclick="launch_amas_feedback_choice('<?php echo plugins_url();?>')"/></p>
	    			<p id="retour_feedback"></p>
	    			<p id="log"></p>
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