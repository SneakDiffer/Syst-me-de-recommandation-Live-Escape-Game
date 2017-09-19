<?php
	//Modifie les coefficients de modification des notes
	require_once('../../../wp-config.php');
	/* récupérer les paramètres de la requete */
	/* "coeff1;coeff2;coeff3;coeff4" */
	$q = $_REQUEST["q"];

	$param = explode(";", $q);
	$ID = 1;
	$wpdb->query("UPDATE  wp_system_recommandation_configuration SET increment_feedback_choix = '" . $param[0] . "' WHERE ID = " .$ID);
	$wpdb->query("UPDATE  wp_system_recommandation_configuration SET nb_max_feedback_choix_jour = '" . $param[1] . "' WHERE ID = " .$ID);
	$wpdb->query("UPDATE  wp_system_recommandation_configuration SET increment_feedback_saisienote = '" . $param[2] . "' WHERE ID = " .$ID);
	$wpdb->query("UPDATE  wp_system_recommandation_configuration SET nb_max_feedback_saisienote_jour = '" . $param[3] . "' WHERE ID = " .$ID);