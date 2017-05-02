<?php
	/* récupérer les paramètres de la requete */
	/* "nomSalle;expertise;poid[0];poid[1];...;poid[i];...;poid[nombre de critères]" */
	$q = $_REQUEST["q"];
	/* en faire des paramètres pour nos fonctions */
	$param = explode(";", $q);
	/* et les stocker */
	$nomSalle = $param[0];
	$expertise = $param[1];
	/* liste des poids sur critères */
	$listePoid = array();
	$current = 0;
	for ($j = 2; $j < count($param); $j++) {
		$listePoid[$current] = $param[$j];
		$current++;
	}
	/*$filename = "C:\instant wordpress\InstantWP_4.5\iwpserver\htdocs\wordpress\wp-content\plugins\Systeme-de-recommandation-de-Live-Escape-Game\\trace_2.txt";
	$f = fopen($filename, 'a+');
	fputs($f, $nomSalle);
	fputs($f, "\n");
	fputs($f, $expertise);
	fputs($f, "\n");
	foreach ($listePoid as $poid) {
		fputs($f, $poid);
		fputs($f, "\n");
	}
	fclose($f);*/
	/* inclure le fichier pour créer un amas */
	include_once ('AMAS_plugin.php');
	if (class_exists('agent_interface')) {
		$amas = new agent_interface;
	}
	/* traiter la requete */
	$ret = $amas->agent_interface_traiter_feedback_saisieNotes($nomSalle, $expertise, $listePoid);
	echo $ret;
	unset($amas);