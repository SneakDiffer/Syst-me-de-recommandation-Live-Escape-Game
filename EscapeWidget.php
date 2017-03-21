<html>
	<SCRIPT>
		function launch_amas_requete() {
			/* récupérer le bloc de résultats */
			var page = document.getElementById("div_results");
			/* le rendre visible */
			page.style.display='block';
			page.style.visibility='visible';
			/* créer une requete */
			var xhttp;
			if (window.XMLHttpRequest) {
				xhttp = new XMLHttpRequest();
				} else {
				// code for IE6, IE5
				xhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			/* fonction synchrone */
			xhttp.onreadystatechange = function() {
				/* si la réponse est correcte */
				if (this.readyState == 4 && this.status == 200) {
					/* récupérer la réponse et split sur ':SEP: */
					var res = this.responseText.split(':SEP:');
					/* mettre à jours l'affichage des résultats */
					document.getElementById("nomSalle_1").innerHTML = res[3];
					document.getElementById("note_1").innerHTML = res[4] + "%";
					document.getElementById("lien_1").innerHTML = res[5];
					document.getElementById("idSalle_1").innerHTML = res[6];
					document.getElementById("nomSalle_2").innerHTML = res[7];
					document.getElementById("note_2").innerHTML = res[8] + "%";
					document.getElementById("lien_2").innerHTML = res[9];
					document.getElementById("idSalle_2").innerHTML = res[10];
					document.getElementById("nomSalle_3").innerHTML = res[11];
					document.getElementById("note_3").innerHTML = res[12] + "%";
					document.getElementById("lien_3").innerHTML = res[13];
					document.getElementById("idSalle_3").innerHTML = res[14];
				}
			};
			/* récuperer le nombre de critères */
			$nb_criteres = document.getElementById("tab_critere").rows.length - 1;
			/* et créer le paramètre de la requete php */
			var poid = "";
			for ($i = 0; $i < $nb_criteres; $i++) {
				poid += document.getElementById($i+"ID").value+";";
			}
			/* récupérer le path vers les plugins */
			var pluginUrl = '<?php echo plugins_url();?>';
			/* créer la requete php : path + nomPlugin + nomFichier + parametre */
			var requete = pluginUrl + "/Systeme-de-recommandation-de-Live-Escape-Game/launcher_AMAS_requete.php?q=" + poid;
			/* envoyer la requete php */
			xhttp.open("GET", requete, true);
			xhttp.send();
		}

		function launch_amas_feedback_choice() {
			/* récupérer les radio bouton */
			var radio_buton_choice = document.getElementsByName("radio_buton_choice");
			/* trouver le choix de l'utilisateur */
			var choix = 1;
			var idSalle = "idSalle_1";
			var proposition = "";
			var tmp;
			for(var i = 0; i < radio_buton_choice.length; i++) {
				tmp = i + 1;
				proposition += tmp + ";" + document.getElementById("idSalle_" + tmp).innerHTML + ";";
				if(radio_buton_choice[i].checked == true) {
					choix = i + 1;
					/* récupérer l'id de la salle */
					idSalle = document.getElementById("idSalle_" + choix).innerHTML;
					/* ouvrir le lien de la salles */
					window.open(document.getElementById('lien_'+choix).innerHTML, '_blank', 'toolbar=0,location=0,menubar=0');
				}
			}
			/* récuperer le nombre de critères */
			$nb_criteres = document.getElementById("tab_critere").rows.length - 1;
			/* et commencer créer le paramètre de la requete php */
			var poid = "";
			for ($i = 0; $i < $nb_criteres; $i++) {
				poid += document.getElementById($i+"ID").value+";";
			}
			var expertise = document.getElementById("id_expertise").value;
			/* construction du paramètre */
			var param = choix + ";" + idSalle + ";" + expertise + ";" + proposition + poid;
			/* créer une requete */
			var xhttp;
			if (window.XMLHttpRequest) {
				xhttp = new XMLHttpRequest();
				} else {
				// code for IE6, IE5
				xhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			/* fonction synchrone */
			xhttp.onreadystatechange = function() {
				/* si la réponse est correcte */
				if (this.readyState == 4 && this.status == 200) {
					document.getElementById("retour_feedback").innerHTML = "IA dit : " + this.responseText;
				}
			};
			/* récupérer le path vers les plugins */
			var pluginUrl = '<?php echo plugins_url();?>';
			/* créer la requete php : path + nomPlugin + nomFichier + parametre */
			var requete = pluginUrl + "/Systeme-de-recommandation-de-Live-Escape-Game/launcher_AMAS_feedback_choice.php?q=" + param;
			/* envoyer la requete php */
			xhttp.open("GET", requete, true);
			xhttp.send();
		}
	</SCRIPT>
</html>
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
    			<div style="text-align:center"> <input type="submit" value="Lancer la recherche" onclick="launch_amas_requete()"/> </div> 
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
	    			<p style="text-align:center;"><input type="submit" value="Choisir cette salle" onclick="launch_amas_feedback_choice()"/></p>
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
?>