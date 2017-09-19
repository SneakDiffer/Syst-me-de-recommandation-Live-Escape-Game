<?php
	//Supprime une salle
	require_once('../../../wp-config.php');
	/* récupérer les paramètres de la requete */
	/* "Nom_salle" */
	$q = $_REQUEST["q"];
	global $wpdb;

	$id_room = $wpdb->get_var("SELECT ID FROM wp_system_recommandation_salles WHERE Name = '$q'");

	//Suppression des notes reliées à la salle
	$notes_ID = $wpdb->get_results( "SELECT ID FROM wp_system_recommandation_notes WHERE id_salle = '$id_room'");
	foreach ( $notes_ID as $note ){
		$wpdb->delete('wp_system_recommandation_notes', array('ID'=>$note->ID), array('%d'));
	}

	$wpdb->delete('wp_system_recommandation_salles', array('ID'=>$id_room), array('%d'));