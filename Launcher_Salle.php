<?php
	//Aout d'une nouvelle salle
	require_once('../../../wp-config.php');
	/* récupérer les paramètres de la requete */
	/* "NomSalle;NoteCritère1;NoteCritère2 ....;Thème;Lien;" */
	$q = $_REQUEST["q"];
	$param = explode("/", $q);
	$input_text = explode(";", $param[0]);

	global $wpdb;

	//On ajoute la salle avec son thème et son lien à la base de données
	$wpdb->insert('wp_system_recommandation_salles', array('ID'=>NULL, 'Name'=>$input_text[0], 'lien'=>$input_text[count($input_text)-2], 'theme'=>$param[1]), array('%d','%s','%s','%s'));	
	$id_room = $wpdb->get_var("SELECT ID FROM wp_system_recommandation_salles WHERE Name = '$input_text[0]'");

	//On ajoute les notes de chaque critère dans la base de données notes
	$critere_colum = $wpdb->get_results( "SELECT Name FROM wp_system_recommandation_criteres");
	$nb_note = $wpdb->get_results( "SELECT ID FROM wp_system_recommandation_notes");
	
	$i = 1;
	foreach ( $critere_colum as $name ) 
	{
		$ID_critere = $wpdb->get_var("SELECT ID FROM wp_system_recommandation_criteres WHERE Name = '$name->Name'");
		$wpdb->insert('wp_system_recommandation_notes', array('ID'=>NULL, 'note'=>$input_text[$i], 'id_critere'=>$ID_critere, 'id_salle'=>$id_room), array('%d','%f','%d','%d'));
		$i = $i + 1;
	}