<?php
	//Ajout d'un nouveau thème
	require_once('../../../wp-config.php');
	/* récupérer les paramètres de la requete */
	/* "Thème1;Thème2...." */
	$q = $_REQUEST["q"];
	$param = explode("/", $q);
	$Nbparam = count($param);
	if($Nbparam == 1){
		$i = $param[0];
	}else{
		$i = $param[$Nbparam - 1];
	}
	global $wpdb;

	$all_room = $wpdb->get_results( "SELECT ID FROM {$wpdb->prefix}system_recommandation_salles");
	$ID_Room = $all_room[$i]->ID;
	
	$themes = explode("/", $q);

	if($Nbparam != 1 ){
		$wpdb->query("UPDATE wp_system_recommandation_salles SET theme = '" . $themes[0] . "' WHERE ID = " . $ID_Room);
	}else{
		$wpdb->query("UPDATE wp_system_recommandation_salles SET theme = '' WHERE ID = " . $ID_Room);
	}
