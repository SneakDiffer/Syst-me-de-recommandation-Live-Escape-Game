<?php
	class agent_interface {

		public function getListIdSalle($listeTheme) {
			$listIdSalle = array();
			$cur = 0;
			/* récupérer la liste des salles en fonction des thèmes */
			require_once('../../../wp-config.php');
			global $wpdb;
			//$filename = "C:\instant wordpress\InstantWP_4.5\iwpserver\htdocs\wordpress\wp-content\plugins\Systeme-de-recommandation-de-Live-Escape-Game\\trace.txt";
			//$f = fopen($filename, 'a+');
			$list_salle = $wpdb->get_results( "SELECT ID, theme FROM {$wpdb->prefix}system_recommandation_salles");
			/* pour toute les salles */
			foreach ($list_salle as $salle) {
				/* récupérer les thèmes de la salles courantes */
				$list_themes = explode(";",$salle->theme);
				/* pour tout les thèmes de la salle */
				foreach ($list_themes as $theme) {
					if (in_array($theme, $listeTheme)) {
						if (!in_array($salle->ID, $listIdSalle)) {
							$listIdSalle[$cur] = $salle->ID;
							$cur++;
							//fputs($f, $salle->ID);
							//fputs($f, "\n");
						}
					}
				}
			}
			//fclose($f);
			return $listIdSalle;
		}
		
		public function allocation_salles($listIdSalle) {
			$list_agent_salle = array();
			/* pour toutes les salles */
			$i = 0;
			foreach ($listIdSalle as $salle) {
				/* créer un agent salle */
				$list_agent_salle[$i] = new agent_salle;
				$i++;
			}
			return $list_agent_salle;
		}

		public function agent_interface_traiter_requete($listePoid, $listeTheme) {
			global $wpdb;
			/* récupérer les id des salles concerné par les thèmes */
			$listIdSalle = $this->getListIdSalle($listeTheme);
			/* créer des agents salle */
			$list_agent_salle = $this->allocation_salles($listIdSalle);
			/* initialiser une liste de résultat */
			$stack = array();
			/* pour toutes les salles */
			$i = 0;
			foreach ($listIdSalle as $idSalle) {
				/* stocker la note */
				$stack[$i] = $list_agent_salle[$i]->agent_salle_get_note_salle($idSalle, $listePoid);
				$i++;
			}
			/* ne garder que les 3 meilleurs résultat */
			$ret_tmp = $this->agent_interface_get_three_max_res($stack);
			/* construire le resultat retour */
			$ret = array();
			for ($j = 0; $j < count($ret_tmp); $j++) {
				/* récupérer le nom et le lien de la salle courante */
				$result = $wpdb->get_results("SELECT Name, lien FROM {$wpdb->prefix}system_recommandation_salles WHERE ID = " . $ret_tmp[$j][0]);
				/* les stocker */
				$ret[$j] = array();
				$ret[$j][0] = $ret_tmp[$j][0];
				$ret[$j][1] = $ret_tmp[$j][1];
				$ret[$j][2] = $result[0]->Name;
				$ret[$j][3] = $result[0]->lien;
			}
			/* détruire les agents de salles */
			unset($list_agent_salle);
			/* retourner la liste trié contenant 3 tuples (idSalle, note, nom, lien) */
			return $ret;
		}

		public function agent_interface_get_three_max_res($results) {
			$ret = array();
			/* trouver les trois meilleurs résultats */
			if (count($results) == 1) {
				$ret[0] = $results[0];
			} elseif (count($results) == 2) {
				if ($results[0][1] > $results[0][1]) {
					$ret[0] = $results[0];
					$ret[1] = $results[1];
				} else {
					$ret[0] = $results[1];
					$ret[1] = $results[0];
				}
			} else {
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
			}

			return $ret;
		}

		public function agent_interface_traiter_feedback_choix($idSalle_choisie, $expertise, $listePoid, $idSalleProposees) {
			$nb_Max_Feedback_par_jour = 2;
			$nb_feedback = $this->agent_interface_log_feedback_choix($idSalle_choisie);
			if ($nb_feedback < $nb_Max_Feedback_par_jour) {
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
				return "RAS";
			} else {
				return "";
			}
		}

		public function agent_interface_log_feedback_choix($idSalle_choisie) {
			require_once('../../../wp-config.php');
			global $wpdb;
			/* récupérer l'ip */
			$ip = $this->GetIP();
			/* et la date */
			$d = getdate();
			$date = $d['mday'] . "." . $d['mon'] . "." . $d['year'] . "-" . $d['hours'] . ":" . $d['minutes'] . ":" . $d['seconds'];
			/* inserer dans la BDD une nouvelle ligne pour log le feedback */
			$wpdb->insert('wp_system_recommandation_log_feedback_choix', array('ID'=>NULL, 'id_salle'=>$idSalle_choisie, 'Date'=>$date, 'IP'=>$ip), array('%d','%s','%s','%s'));
			/* savoir combien de fois cet utilisateur à utilisé le feedback */
			$result = $wpdb->get_results("SELECT * FROM wp_system_recommandation_log_feedback_choix");
			$nb_feedback = 0;
			/* pour chaque resultat */ 
			foreach ($result as $feedback) {
				/* si c'est l'ip de l'utilisateur */
				if ($feedback->IP == $ip) {
					/* si c'est la salle concernée */
					if ($feedback->id_salle == $idSalle_choisie) {
						/* récupérer la date */
						$tmp = explode("-", $feedback->Date);
						/* récupérer les jours mois année */
						$tmp2 = explode(".", $tmp[0]);
						/* compter le nombre de feedback du jour */
						if ($tmp2[0] == $d['mday'] && $tmp2[1] == $d['mon'] && $tmp2[2] == $d['year']) {
							$nb_feedback++;
						}
					}
				}
			}
			return $nb_feedback; 
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

		public function GetIP() {
		    foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key) {
		        if (array_key_exists($key, $_SERVER) === true) {
		            foreach (array_map('trim', explode(',', $_SERVER[$key])) as $ip) {
		                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
		                    return $ip;
		                }
		            }
		        }
		    }
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