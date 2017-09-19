<?php
	//Supprime un critère de la bdd
	require_once('../../../wp-config.php');
	/* récupérer les paramètres de la requete */
	/* "Critere" */
	$q = $_REQUEST["q"];
	global $wpdb;

	$id_critere = $wpdb->get_var("SELECT ID FROM wp_system_recommandation_criteres WHERE Name = '$q'");
	//On supprime les notes du critère
	$wpdb->delete('wp_system_recommandation_notes', array('id_critere'=>$id_critere), array('%d'));
	//on supprime le critère
	$wpdb->delete('wp_system_recommandation_criteres', array('ID'=>$id_critere), array('%d'));