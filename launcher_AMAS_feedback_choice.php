<?php
	/* récupérer les paramètres de la requete */
	/* "numChoix;idSalle_choisi;expertise;1;idSalle1;2;idSalle2;3;idSalle3;poid[0];poid[1];...;poid[i];...;poid[nombre de critères]" */
	$q = $_REQUEST["q"];
	/* en faire des paramètres pour nos fonctions */
	$param = explode(";", $q);
	/* et les stocker */
	$choix = $param[0];
	$idSalle_choisie = $param[1];
	$expertise = $param[2];
	/* les propositions fournies à l'utilisateur */
	$idSalleProposees = array();
	$idSalleProposees[0] = $param[3];
	$idSalleProposees[1] = $param[5];
	$idSalleProposees[2] = $param[7];
	/* liste des poids sur critères */
	$listePoid = array();
	$current = 0;
	for ($j = 9; $j < count($param); $j++) {
		$listePoid[$current] = $param[$j];
		$current++;
	}

	/* inclure le fichier pour créer un amas */
	include_once ('AMAS_plugin.php');
	/* si choix 1 : OK, pas de modification à faire */
	if ($choix == "1") {
		echo "Pas de modification de la BDD";
	} 
	elseif ($choix == "2") { /* on va modifier la base de données */
		// todo
	}
	elseif ($choix == "3") { /* on va modifier la base de données */
		// todo
	}
?>