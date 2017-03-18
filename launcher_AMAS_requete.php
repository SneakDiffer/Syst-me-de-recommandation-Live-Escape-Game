<?php
	/* récupérer les paramètres de la requete */
	$q = $_REQUEST["q"];
	/* en faire des paramètres pour nos fonctions */
	$listePoid = explode(";", $q);

	$noteMax = 0;
	foreach ($listePoid as $poid) {
		$noteMax += $poid;
	}

	/* créer un agent interface */
	include_once ('AMAS_plugin.php');
	if (class_exists('agent_interface')) {
		$amas = new agent_interface;
	}
	/* traiter la requete */
	$results = $amas->agent_interface_traiter_requete($listePoid);

	/* retour des résultats */
	foreach ($results as $res) {
		/* ":SEP:nomSalle:SEP:noteSalle:SEP:lien" */
		echo ":SEP:" . $res[2] . ":SEP:" . round($res[1]/$noteMax, 2) . ":SEP:" . $res[3] ;
	}
	/* destruction de l'amas */
	unset($amas);
?>