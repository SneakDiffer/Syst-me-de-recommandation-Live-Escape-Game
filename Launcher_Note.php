<?php
	require_once('../../../wp-config.php');
	/* récupérer les paramètres de la requete */
	/* "Thème1;Thème2...." */
	$q = $_REQUEST["q"];
	$param = explode("/", $q);
	
	global $wpdb;

	$all_room = $wpdb->get_results( "SELECT ID FROM {$wpdb->prefix}system_recommandation_salles");
	$ID_Room = $all_room[$param[1]]->ID;
	$notes = explode(";", $param[0]);
	
	for($i = 0; $i < count($notes); $i ++){
			$id_critere = $i + 1;
				$wpdb->query("UPDATE {$wpdb->prefix}system_recommandation_notes SET note = '" . $notes[$i] . "' WHERE id_salle = " . $ID_Room . " AND id_critere = " . $id_critere);
	}