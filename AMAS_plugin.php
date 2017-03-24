<?php
	class agent_interface {
		
		public function allocation_all_salles() {
			/* récupérer la liste des salles */
			require_once('../../../wp-config.php');
			global $wpdb;
			$results = $wpdb->get_results( "SELECT ID FROM {$wpdb->prefix}system_recommandation_salles");
			$list_agent_salle = array();
			/* pour toutes les salles */
			$i = 0;
			foreach ($results as $salle) {
				/* créer un agent salle */
				$list_agent_salle[$i] = new agent_salle;
				$i++;
			}
			return $list_agent_salle;
		}

		public function agent_interface_traiter_requete($listePoid) {
			/* créer des agents salle */
			$list_agent_salle = $this->allocation_all_salles();
			/* récupérer la liste des salles */
			global $wpdb;
			$list_idSalle = $wpdb->get_results( "SELECT Name, ID FROM {$wpdb->prefix}system_recommandation_salles");
			/* et il faudrait alors que l'on puisse apeller la fonction agent interface depuis le widget */
			/* initialiser une liste de résultat */
			$stack = array();
			/* pour toutes les salles */
			$i = 0;
			foreach ( $list_idSalle as $idSalle) {
				/* stocker la note */
				$stack[$i] = $list_agent_salle[$i]->agent_salle_get_note_salle($idSalle->ID, $listePoid);
				$i++;
			}

			/* ne garder que les 3 meilleurs résultat */
			$ret_tmp = $this->agent_interface_get_three_max_res($stack);

			/* construire le resultat retour */
			$ret = array();
			for ($j = 0; $j < 3; $j++) {
				/* récupérer le nom et le lien de la salle courante */
				$result = $wpdb->get_results( "SELECT Name, lien FROM {$wpdb->prefix}system_recommandation_salles WHERE ID = " . $ret_tmp[$j][0]);
				/* les stocker */
				$ret[$j] = array();
				$ret[$j][0] = $ret_tmp[$j][0];
				$ret[$j][1] = $ret_tmp[$j][1];
				$ret[$j][2] = $result[0]->Name;
				$ret[$j][3] = $result[0]->lien;
			}

			/* détruire les agents de salles */
			unset($list_agent_salle);
			/* retourner la liste trié contenant 3 tuples (idSalle, note) */
			return $ret;
		}

		public function agent_interface_get_three_max_res($results) {
			$ret = array();
			/* trouver les trois meilleurs résultats */
			$nb_result = 0;
			while ($nb_result < 3) {
				/* trouver le meilleurs résultats */
				$maximum = 0;
				/* sauvegarder l'indice du max */
				$ind_to_del = 0;
				/* pour tout les résultats */
				for ($i = 0; $i <= count($results); $i ++) {
					/* si c'est un max temporaire */
					if ($results[$i][1] > $maximum) {
						/* sauvegarder sa valeur dans le résultat final */
						$ret[$nb_result] = $results[$i];
						/* sa valeur et son indice pour la suite des boucles */
						$maximum = $results[$i][1];
						$ind_to_del = $i;
					}
				}
				/* supprimer la valeur max et passer au résultat suivant */
				unset($results[$ind_to_del]);
				$nb_result ++;
			}

			return $ret;
		}

		public function agent_interface_traiter_feedback_choix($idSalle_choisie, $expertise, $listePoid, $idSalleProposees) {
			/* calcul d'une marge dynamique */
			$marge = $this->agent_interface_get_dynamix_marge($expertise);
			/* allocation des agents salles choisie */
			//$list_agent_salle = $this->allocation_salle_ciblee($idSalleProposees);
			/* allocation de l'agent salle choisie */
			$agent_salle = new agent_salle;
			/* appel de l'agent salle pour modifier les notes */
			$ret = $agent_salle->agent_salle_modify_note_salle($idSalle_choisie, $listePoid, $expertise, $marge);
			/* détruire l'agent salle */
			unset($agent_salle);
			/* retourner les nouvelles notes (debug) */
			return $ret;
		}

		public function allocation_salle_ciblee($list_idSalle) {
			$list_agent_salle = array();
			$i = 0;
			foreach ($list_idSalle as $idSalle) {
				$list_agent_salle[$i] = new agent_salle;
				$i++;
			}
			return $list_agent_salle;
		}

		public function agent_interface_get_dynamix_marge($expertise) {
			/* retourne une marge entre 10 et 20 selon l'expertise */
			return 20 - ($expertise / 10);
		}
	}

	class agent_salle {
		public function allocation_all_criteres() {
			/* récupérer la liste des critères */
			require_once('../../../wp-config.php');
			global $wpdb;
			$list_critere = $wpdb->get_results("SELECT ID FROM {$wpdb->prefix}system_recommandation_criteres");
			$list_agent_critere = array();
			/* pour tout les critères */
			$i = 0;
			foreach ($list_critere as $critere) {
				/* créer un agent critère */
				$list_agent_critere[$i] = new agent_critere;
				$i++;
			}
			return $list_agent_critere;
		}

		public function agent_salle_get_note_salle($idSalle, $listePoid) {
			/* créer les agents critère */
			$list_agent_critere = $this->allocation_all_criteres();
			/* récupérer la liste des critères */
			global $wpdb;
			$results = $wpdb->get_results("SELECT ID FROM {$wpdb->prefix}system_recommandation_criteres");
			/* pour tout les critères */
			$note = 0;
			$i = 0;
			foreach ( $results as $idCritere ) {
				/* mise à jours de la note de la salle */
				$note = $note + $list_agent_critere[$i]->agent_critere_get_note_critere($idSalle, $idCritere->ID, $listePoid[$i]);
				$i = $i + 1;
			}
			$ret = array();
			$ret[0] = $idSalle;
			$ret[1] = $note;
			/* détruire les agents critère */
			unset($list_agent_critere);
			/* retourner la note calculé et le nom de la salle */
			return $ret;
		}

		public function agent_salle_modify_note_salle($idSalle, $listePoid, $expertise, $marge) {
			/* créer les agents critère */
			$list_agent_critere = $this->allocation_all_criteres(); 
			/* récupérer la liste des critères */
			global $wpdb;
			$idCritere = $wpdb->get_results("SELECT ID FROM {$wpdb->prefix}system_recommandation_criteres");
			/* pour tout les critères */
			$i = 0;
			$ret = "";
			/* pour tout les critères */
			foreach ($list_agent_critere as $agent_critere) {
				/* appel de l'agent critères pour modifier sa notes au besoin */
				$newNote = $agent_critere->agent_critere_modify_note($idSalle, $idCritere[$i]->ID, $listePoid[$i], $marge, $expertise);
				/* stockage de la nouvelle note */
				$ret = $ret . ":SEP:" . $newNote;
				$i++;
			}
			/* retourner les nouvelles notes (debug) */
			return $ret;
		}
	}

	class agent_critere {
		public function agent_critere_get_note_critere($idSalle, $idCritere, $poid) {
			global $wpdb;
			$result = $wpdb->get_results("SELECT note FROM {$wpdb->prefix}system_recommandation_notes WHERE id_salle = " . $idSalle . " AND id_critere = " . $idCritere);
			/* retourner la note modulé par le poid */
			return $result[0]->note * $poid; 
		}

		public function agent_critere_modify_note($idSalle, $idCritere, $poid, $marge, $expertise) {
			/* incrément dynamique selon l'expertise */
			$increment = 1 * ($expertise / 100);
			/* récupérer la note actuelle du critère */
			global $wpdb;
			$result = $wpdb->get_results("SELECT note FROM {$wpdb->prefix}system_recommandation_notes WHERE id_salle = " . $idSalle . " AND id_critere = " . $idCritere);
			$newNote = $result[0]->note;
			/* si note + marge < note souhaitée */
			if ($result[0]->note + $marge < $poid) {
				/* augmenter la note de incrément */
				$newNote = $result[0]->note + $increment;
				$wpdb->query("UPDATE {$wpdb->prefix}system_recommandation_notes SET note = " . $newNote . " WHERE id_salle = " . $idSalle . " AND id_critere = " . $idCritere);
			} 
			/* sinon si note > note souhaitée + marge */
			elseif ($result[0]->note > $poid + $marge) {
				/* baisser la note de incrément */
				$newNote = $result[0]->note - $increment;
				$wpdb->query("UPDATE {$wpdb->prefix}system_recommandation_notes SET note = " . $newNote . " WHERE id_salle = " . $idSalle . " AND id_critere = " . $idCritere);
			}
			/* retourner la nouvelle note (debug) */
			return $newNote;
		}
	}