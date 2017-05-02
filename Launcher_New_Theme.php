<?php
	//Ajout d'un nouveau thème
	require_once('../../../wp-config.php');
	/* récupérer les paramètres de la requete */
	/* "Theme" */
	$q = $_REQUEST["q"];

	global $wpdb;
	$wpdb->insert('wp_system_recommandation_themes', array('ID'=>NULL, 'Name'=>$q), array('%d','%s'));	
