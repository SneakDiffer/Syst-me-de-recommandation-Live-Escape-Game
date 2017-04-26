<?php
	require_once('../../../wp-config.php');
	/* récupérer les paramètres de la requete */
	/* "Critere" */
	$q = $_REQUEST["q"];
	global $wpdb;
	//Ajout du nouveau critère
	$wpdb->insert('wp_system_recommandation_criteres', array('ID'=>NULL, 'Name'=>$q), array('%d','%s'));

	//Ajout des notes par défaut dans les salles
	$id_critere = $wpdb->get_var("SELECT ID FROM wp_system_recommandation_criteres WHERE Name = '$q'");
	$id_salles = $wpdb->get_results( "SELECT ID FROM wp_system_recommandation_salles");

	foreach ( $id_salles as $salle ){
		$wpdb->insert('wp_system_recommandation_notes', array('ID'=>NULL, 'note'=>50, 'id_critere'=>$id_critere , 'id_salle'=>$salle->ID ), array('%d','%f','%d','%d'));
	}