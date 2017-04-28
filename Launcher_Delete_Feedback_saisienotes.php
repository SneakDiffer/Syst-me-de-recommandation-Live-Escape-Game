<?php
	//Modifie un feedback et le supprime de la bdd
	require_once('../../../wp-config.php');
	/* récupérer les paramètres de la requete */
	/* "ID du log à supprimer" */
	$q = $_REQUEST["q"];

	$Modifications = $wpdb->get_var( "SELECT Modifications FROM wp_system_recommandation_log_feedback_saisienotes WHERE ID = '$q'");
	$Salle = $wpdb->get_var( "SELECT id_salle FROM wp_system_recommandation_log_feedback_saisienotes WHERE ID = '$q'");
	$changement_note = explode(";;", $Modifications);

	for($i=0;$i<count($changement_note);$i++){
		$idcritere_poid = explode(";",$changement_note[$i]);
		$note_actuelle = $wpdb->get_var( "SELECT note FROM wp_system_recommandation_notes WHERE id_critere = '$idcritere_poid[0]' AND id_salle = '$Salle'");
		$note_modifie = $note_actuelle - $idcritere_poid[1];
		$wpdb->query("UPDATE wp_system_recommandation_notes SET note = '" . $note_modifie . "' WHERE id_salle = " . $Salle . " AND id_critere = " . $idcritere_poid[0]);
	}
	$wpdb->delete('wp_system_recommandation_log_feedback_saisienotes', array('ID'=>$q), array('%d'));