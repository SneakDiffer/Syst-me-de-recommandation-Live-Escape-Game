<?php	
class configuration_plugin {
	
	function my_plugin_menu() {
		add_options_page( 'Configuration systeme d\'aide a la decision', 'Configuration systeme d\'aide a la decision', 'manage_options', None, array( $this, 'my_plugin_options' ) );
		add_action('admin_enqueue_scripts', array(&$this, 'enqueueScripts'));
	}
	/*Importation du script */
	public function enqueueScripts()
	{	
			$path = plugins_url();
			$path_to_scripts = $path . "/Systeme-de-recommandation-de-Live-Escape-Game/ScriptConfiguration.js";
	        wp_enqueue_script('ScriptConfiguration.js', $path_to_scripts);
	}

	function my_plugin_options() {
		global $wpdb;

		?>
		<h1 class="wp-heading-inline">Veuillez configurer votre plugin</h1><br/><br/><br/>

		<!-- Table de gestion des salles -->
		<table id="ID_table_configuration" style="width:100%; height:200px;overflow:auto;">
		<thead>
			<tr>
		       <td><a class="row-title">Nom salle</a></td>
				<?php
				//Affichage des critères présent dans la bdd
				$critere_colum = $wpdb->get_results( "SELECT Name FROM {$wpdb->prefix}system_recommandation_criteres");
				foreach ( $critere_colum as $critere ) 
				{
				?>
					<td><a class="row-title"><?php echo $critere->Name ?></a></td>
				<?php
				}
				?>
		       <td><a class="row-title">Thème </a></td>
		       <td><a class="row-title">Lien </a></td>
		   	</tr>
		   	<?php
		   	$all_room = $wpdb->get_results( "SELECT Name FROM {$wpdb->prefix}system_recommandation_salles");
		   	$all_theme = $wpdb->get_results("SELECT Name FROM wp_system_recommandation_themes");
		   	//Convertion en array simple
		   	$All_Theme = array();
		   	foreach ( $all_theme as $themes){
		   		array_push($All_Theme,$themes->Name);
		   	}
		   	?>

		   	<!-- Script de gestion des listes thèmes -->
			<script  type='text/javascript'>	
		    InitEnable(<?php echo count($all_room) + 1 ?>); 
			</script>

			<?php
		   	$i = 0;	//Indice de salle
		   	//Pour chaque salle on affiche le nom, les notes, les thèmes et le lien
		   	foreach ( $all_room as $room ) 
			{
					$score_salle = $wpdb->get_results("select c.name, n.note from wp_system_recommandation_salles as s, wp_system_recommandation_notes as n,wp_system_recommandation_criteres as c where s.Name = '$room->Name' and s.id = n.id_salle and c.id = n.id_critere ORDER BY c.ID");
					$link_salle = $wpdb->get_results("SELECT lien FROM wp_system_recommandation_salles WHERE Name = '$room->Name'");
					$themes_salle = $wpdb->get_var("SELECT theme FROM wp_system_recommandation_salles WHERE Name = '$room->Name'");
					$theme_salle = explode(";", $themes_salle);
					$theme_note_checked = array_diff($All_Theme, $theme_salle);

					?>
					<tr>
						<!-- Affichage nom -->
						<td><input type="text" id="Salle<?php echo $i ?>" value='<?php echo $room->Name ?>'></td>

						<!-- Affichage notes -->
						<?php
						$critere = 0;
						foreach ( $score_salle as $score ) 
						{
						?>
							<td>
								<input type="text" id="<?php echo $critere ?><?php echo $i ?>" value='<?php echo $score->note ?>'>
							</td>
							<?php
							$critere = $critere + 1;
						}
						?>

						<!-- Affichage thèmes -->
						<td >
							<div class="selectBox" onclick="showCheckboxes('<?php echo $i?>')" >
								<select>
									<option>Gestion des thèmes</option>
								</select>
								<div class="overSelect" ></div>
							</div>
							<div id="checkboxes<?php echo $i ?>" style="display: none;">
								<?php
								foreach ($theme_note_checked as $themes) {
									?>
									<input type="checkbox" id="<?php echo $themes?><?php echo $i ?>" /><?php echo $themes ?></br>
									<?php
								}
								//Gestion d'une chaine vide
								if($theme_salle[0] != "")
								{
									foreach ($theme_salle as $themes) {
										?>
										<input type="checkbox" checked="checked" id="<?php echo $themes?><?php echo $i ?>" /><?php echo $themes ?></br>
										<?php
									}
								}
								?>
						 	</div>
						</td>

						<!-- Affichage Lien -->
						<?php
						foreach ( $link_salle as $link ) 
						{
							?>
							<td><input type="text" id="Lien<?php echo $i ?>" value='<?php echo $link->lien ?>'></td>
							<?php
						}
						?>
						<td><input type="submit" class="button" id="Supprimer<?php echo $i ?>" value="Supprimer" onclick="Suppression_Salle('<?php echo plugins_url();?>','<?php echo $room->Name?>')"></td>
					</tr>
					
				<?php
				$i += 1;//Salle suivante
			}
			$ID_nouvelle_salle = $i;
		   	?>
		   	<!-- Ligne pour ajouter une salle -->
		   	<tr>
		   		<?php
		   		//+ 3 car colonne nomsalle, Theme, Lien
		   		for ($i = 0; $i < count($critere_colum) + 3;$i++){
		   			//Gestion des thèmes
		   			if($i == count($critere_colum) + 1){
		   				?>
		   				<td>
		   					<div class="selectBox" onclick="showCheckboxes('<?php echo $ID_nouvelle_salle?>')">
						      <select>
						        <option>Gestion des thèmes</option>
						      </select>
						      <div class="overSelect" ></div>
						      </div>
						    <div id="checkboxes<?php echo $ID_nouvelle_salle ?>" style="display: none;">
						    	<?php
						    	$j = 0;
		                        foreach ($All_Theme as $themes) {
									?>
									<input type="checkbox" id="Ajout_Salle_Theme<?php echo $j ?>" /><?php echo $themes ?></br>
							        <?php
							        $j = $j + 1;
								}
								?>
					 		</div>
		   				</td>
		   			<?php
		   			}else{
		   				?><td><input type="text" id="Ajout_Salle<?php echo $i ?>"/></td>
		   			<?php
		   			}
		   		}
		   	?>
		   	</tr>
		</thead> 
		</table>

		<!-- Boutons permettant la gestion des salles -->
		<script type="text/javascript" src="ScriptConfiguration.js"></script>
		<input type="submit"  class="button" value="Modifier les notes" onclick="Gestion_Note('<?php echo plugins_url();?>')">
		<input type="submit"  class="button" value="Modifier les thèmes" onclick="Gestion_Theme('<?php echo plugins_url();?>',<?php echo htmlspecialchars(json_encode($All_Theme)) ?>)">
		<input type="submit"  class="button" value="Modifier les liens" onclick="Gestion_Lien('<?php echo plugins_url();?>')">
		<input type="submit" class="button" value="Ajouter la salle" onclick="Gestion_Salle('<?php echo plugins_url();?>',<?php echo count($critere_colum) + 1 ?>,<?php echo htmlspecialchars(json_encode($All_Theme)) ?>)"/>
		<br/><br/><br/>

		<!-- Gestion des thèmes (Ajout / Suppression ) -->
		<a class="row-title">Gestion des thèmes </a><br/>
		<select ID="ListeGestionTheme" size="10" multiple style="width:50%;">
		  <?php
		  foreach($all_theme as $themes){
		  	?>
		  	<option><?php echo $themes->Name ?></option>
		  	<?php
		  }?>
		</select>

		<table>
		<thead>
			<tr>
				<td>
			<input type="text" id="Nouveau_theme">
				</td>
				<td>
			<input type="submit"  class="button" value="Supprimer le thème" onclick="Suppression_theme('<?php echo plugins_url();?>')">
				</td>
				<td>
			<input type="submit"  class="button" value="Ajouter le thème" onclick="Ajout_theme('<?php echo plugins_url();?>')">
				</td>
			</tr>
		</thead> 
		</table>
		<br/><br/>

		<!-- Gestion des critères ( Ajout / Suppression ) -->
		<a class="row-title">Gestion des critères </a><br/>
		<select ID="ListeGestionCritere" size="10" multiple style="width:50%;">
		  <?php
		  foreach($critere_colum as $critere){
		  	?>
		  	<option><?php echo $critere->Name ?></option>
		  	<?php
		  }?>
		</select>

		<table>
		<thead>
			<tr>
				<td>
			<input type="text" id="Nouveau_Critere">
				</td>
				<td>
			<input type="submit"  class="button" value="Supprimer le critère" onclick="Suppression_critere('<?php echo plugins_url();?>')">
				</td>
				<td>
			<input type="submit"  class="button" value="Ajouter le critère" onclick="Ajout_critere('<?php echo plugins_url();?>')">
				</td>
			</tr>
		</thead> 
		</table><br/>

		<!-- Gestion des feedback choix d'une salle -->
		<a class="row-title">Gestion des feedbacks choix </a><br/>

		<?php
		$feedbacks = $wpdb->get_results("SELECT ID,id_salle,Date,IP,Modifications FROM wp_system_recommandation_log_feedback_choix ");
		?>
		<div style="width:90%; height:200px; overflow:auto;">
			<table style="width:90%;">
				<tr>
					<td><a class="row-title">IP </a></td>
					<td><a class="row-title">Date </a></td>
			    	<td><a class="row-title">Salle</a></td>
					<?php
					foreach ( $critere_colum as $critere ) 
					{?>
						<td><a class="row-title"><?php echo $critere->Name ?></a></td>
					<?php
					}?>
				</tr>

				<?php
				//Pour chaque feedbacks on remplis le tableau
				foreach ( $feedbacks as $feedback ){
					$salle = $wpdb->get_var("SELECT Name FROM wp_system_recommandation_salles WHERE ID = '$feedback->id_salle'");
					?>
					<tr>
					<td><?php echo $feedback->IP ?></td>
					<td><?php echo $feedback->Date ?></td>
					<td><?php echo $salle ?></td>
					<?php
					$changement_note = explode(";;", $feedback->Modifications);
					$nb_changement = 0;
					for($i=0;$i<count($critere_colum);$i++){
						$poid = explode(";",$changement_note[$i]);
						//Il n'y a pas eu de changement sur ce critère
						if(count($poid) == 1){
							?><td></td><?php
						}else{
							//On rajoute un + si c'est une note positive. Question d'esthétisme
							$positif = explode("-",$poid[1]);
							if (count($positif) == 1){
								?><td>+<?php echo $poid[1] ?></td><?php
							}else{
								?><td><?php echo $poid[1] ?></td><?php
							}
							$nb_changement += 1;
						}
						
					}
					?>
					<td><input type="submit"  class="button" value="Retirer la modification" onclick="Suppression_feedback_choix('<?php echo plugins_url();?>','<?php echo $feedback->ID?>')"></td>
					</tr>
					<?php
				}?>
			</table>  
		</div><br/>

		<!-- Gestion des feedbacks suite à la saisie de notes -->
		<a class="row-title">Gestion des feedbacks saisie de notes </a><br/>
		<?php
		$feedbacks = $wpdb->get_results("SELECT ID,id_salle,Date,IP,Modifications FROM wp_system_recommandation_log_feedback_saisienotes ");
		?>
		<div style="width:90%; height:200px; overflow:auto;">
			<table style="width:90%;">
				<tr>
					<td><a class="row-title">IP </a></td>
					<td><a class="row-title">Date </a></td>
				    <td><a class="row-title">Salle</a></td>
					<?php
					foreach ( $critere_colum as $critere ) 
					{?>
						<td><a class="row-title"><?php echo $critere->Name ?></a></td>
					<?php
					}?>
				</tr>
				<!-- Pour chaque feedback on remplis le tableau -->
				<?php
				foreach ( $feedbacks as $feedback ){
					$salle = $wpdb->get_var("SELECT Name FROM wp_system_recommandation_salles WHERE ID = '$feedback->id_salle'");
					?>
					<tr>
					<td><?php echo $feedback->IP ?></td>
					<td><?php echo $feedback->Date ?></td>
					<td><?php echo $salle ?></td>
					<?php
					$changement_note = explode(";;", $feedback->Modifications);
					for($i=0;$i<count($critere_colum);$i++){
						$poid = explode(";",$changement_note[$i]);
						//Il n'y a pas eu de changement sur ce critère
						if(count($poid) == 1){
							?><td></td><?php
						}else{
							//On rajoute un + si c'est une note positive. Question d'esthétisme
							$positif = explode("-",$poid[1]);
							if (count($positif) == 1){
								?><td>+<?php echo $poid[1] ?></td><?php
							}else{
								?><td><?php echo $poid[1] ?></td><?php
							}
						}	
					}
					?>
					<td><input type="submit"  class="button" value="Retirer la modification" onclick="Suppression_feedback_saisienotes('<?php echo plugins_url();?>','<?php echo $feedback->ID?>')"></td>
					</tr>
					<?php
				}?>
    		</table>  
   		</div>

   		<!-- Gestion des coefficients de modification des notes -->
		<?php
		$coeff1 = $wpdb->get_var("SELECT increment_feedback_choix FROM wp_system_recommandation_configuration WHERE ID = 1");
		$coeff2 = $wpdb->get_var("SELECT nb_max_feedback_choix_jour FROM wp_system_recommandation_configuration WHERE ID = 1");
		$coeff3 = $wpdb->get_var("SELECT increment_feedback_saisienote FROM wp_system_recommandation_configuration WHERE ID = 1");
		$coeff4 = $wpdb->get_var("SELECT nb_max_feedback_saisienote_jour FROM wp_system_recommandation_configuration WHERE ID = 1");
		?>
		<table>
			<tr>
				<td>L'incrément du feedback choix d'une salle ? </td>
				<td>Le nombre maximum de feedback choix par jours ? </td>
				<td>L'incrément du feedback saisie de note ? </td>
				<td>Le nombre maximum de feedback saisie de note par jours ? </td>
			</tr>
			<tr>
				<td><input type="text" id="increment_feedback_choix" value='<?php echo $coeff1 ?>'></td>
				<td><input type="text" id="nb_max_feedback_choix_jour" value='<?php echo $coeff2 ?>'></td>
				<td><input type="text" id="increment_feedback_saisienote" value='<?php echo $coeff3 ?>'></td>
				<td><input type="text" id="nb_max_feedback_saisienote_jour" value='<?php echo $coeff4 ?>'></td>
			</tr>
		</table>
		<input type="submit"  class="button" value="Mettre à jours les coefficients" onclick="Maj_coefficients('<?php echo plugins_url();?>')">
		
		<?php
	}
}