<?php

	require_once('../../../wp-config.php');
	/* récupérer les paramètres de la requete */
	/* "Theme" */
	$q = $_REQUEST["q"];
	global $wpdb;

	//Suppression du thème dans la base de données
	$theme_ID = $wpdb->get_results( "SELECT ID,Name FROM wp_system_recommandation_themes");
	foreach ( $theme_ID as $theme ){
		if($theme->Name == $q){
			$ID = $theme->ID;
			$wpdb->delete('wp_system_recommandation_themes', array('ID'=>$ID, 'Name'=>$q), array('%d','%s'));
			break;
		}
	}

	//Suppression du thème dans les salles
	$all_room = $wpdb->get_results( "SELECT ID,theme FROM wp_system_recommandation_salles");
	foreach ($all_room as $room){
		$nouveaux_themes = "";
		$theme_salle = explode(";", $room->theme);
		for($i = 0; $i < count($theme_salle); $i++){
			if($theme_salle[$i] != $q){
				$nouveaux_themes .= $theme_salle[$i];
				$nouveaux_themes .= ";";
			}
		}
		//On supprime le dernier ;
		if(count($nouveaux_themes) != 0){
			$nouveaux_themes = substr($nouveaux_themes, 0, -1);
		}
		$wpdb->update( 'wp_system_recommandation_salles', array( 'theme' =>  $nouveaux_themes ), array( 'ID' => $room->ID ), array( '%s'),array( '%d' ));
	}
	