<?php
	//Modification de notes
	require_once('../../../wp-config.php');
	/* récupérer les paramètres de la requete */
	/* "notecritère1;notecritère2;.../nomsalle" */
	$q = $_REQUEST["q"];
	$param = explode("/", $q);
	
	global $wpdb;

	$ID_Room = $wpdb->get_var( "SELECT ID FROM {$wpdb->prefix}system_recommandation_salles WHERE Name = '$param[1]'");

	$notes = explode(";", $param[0]);
	$critere_colum = $wpdb->get_results( "SELECT ID FROM {$wpdb->prefix}system_recommandation_criteres");
	$all_critere = array();
	foreach ( $critere_colum as $critere){
		array_push($all_critere,$critere->ID);
	}
	
	for($i = 0; $i < count($notes); $i ++){
			$wpdb->query("UPDATE {$wpdb->prefix}system_recommandation_notes SET note = '" . $notes[$i] . "' WHERE id_salle = " . $ID_Room . " AND id_critere = " . $all_critere[$i]);
	}