<?php
	/* récupérer les paramètres de la requete */
	/* "numChoix;idSalle_choisi;expertise;1;idSalle1;2;idSalle2;3;idSalle3;poid[0];poid[1];...;poid[i];...;poid[nombre de critères]" */
	$q = $_REQUEST["q"];
	/* en faire des paramètres pour nos fonctions */
	$param = explode(";", $q);
	/* et les stocker */
	$numChoix = $param[0];
	$idSalle_choisie = $param[1];
	$expertise = $param[2];
	/* les propositions fournies à l'utilisateur */
	$idSalleProposees = array();
	$idSalleProposees[0] = $param[4];
	$idSalleProposees[1] = $param[6];
	$idSalleProposees[2] = $param[8];
	/* liste des poids sur critères */
	$listePoid = array();
	$current = 0;
	for ($j = 9; $j < count($param); $j++) {
		$listePoid[$current] = $param[$j];
		$current++;
	}
	
	/*$date = get_the_date();
	$filename = "C:\instant wordpress\InstantWP_4.5\iwpserver\htdocs\wordpress\wp-content\plugins\Systeme-de-recommandation-de-Live-Escape-Game\\trace.txt";
	$f = fopen($filename, 'a+');
	fputs($f, $date);
	fputs($f, "\n");
	fclose($f);*/

	/* si choix 1 : OK, pas de modification à faire */
	if ($numChoix == "1") {
		echo "RAS";
	} 
	else { /* on va modifier la base de données */
		/* inclure le fichier pour créer un amas */
		include_once ('AMAS_plugin.php');
			if (class_exists('agent_interface')) {
			$amas = new agent_interface;
		}
		/* traiter la requete */
		$ret = $amas->agent_interface_traiter_feedback_choix($idSalle_choisie, $expertise, $listePoid, $idSalleProposees);
		echo $ret;
		unset($amas);
	}