<?php
	//Modification d'un lien
	require_once('../../../wp-config.php');
	/* récupérer les paramètres de la requete */
	/* "Lien" */
	$q = $_REQUEST["q"];
	$param = explode(";", $q);
	global $wpdb;

	$all_room = $wpdb->get_results( "SELECT ID FROM {$wpdb->prefix}system_recommandation_salles");
	$ID_Room = $all_room[$param[1]]->ID;

	$wpdb->query("UPDATE wp_system_recommandation_salles SET lien = '" . $param[0] . "' WHERE ID = " . $ID_Room);