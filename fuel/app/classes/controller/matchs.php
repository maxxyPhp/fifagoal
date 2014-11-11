<?php 

class Controller_Matchs extends \Controller_Front
{
	/**
	 * AJAX ONLY
	 */
	public function get_api ($context){
		switch ($context){
			case 'defier':
				if (!is_numeric(\Input::get('defier'))){
					return 'KO';
				}

				$defier = \Model\Auth_User::find(\Input::get('defier'));
				if (empty($defier)) return 'KO';

				$status = \Model_Status::query()->where('nom', '=', 'En attente')->get();
				if (!empty($status)){
					$status = current($status);
				} else return 'KO';

				$defis = \Model_Defis::forge();
				$defis->id_joueur_defieur = \Auth::get('id');
				$defis->id_joueur_defier = $defier->id;
				$defis->status_demande = $status->id;
				$defis->save();

				/**
				 * NOTIFICATION
				 */
				$this->newNotify($defier->id, $this->modelMessage('defi', \Auth::get('username')));

				return json_encode('OK');
				break;

			case 'addComment':
				if (!is_numeric(\Input::get('match'))){
					return 'KO';
				}

				$match = \Model_Matchs::find(\Input::get('match'));
				if (empty($match)) return 'KO';

				$commentaire = \Model_Commentaires::forge();
				$commentaire->id_user = \Auth::get('id');
				$commentaire->id_match = $match->id;
				$commentaire->commentaire = htmlspecialchars(\Input::get('content'));
				$commentaire->save();

				/**
				 *
				 * NOTIFICATIONS
				 */
				if (\Auth::get('id') != $match->id_joueur1){
					$this->newNotify($match->id_joueur1, $this->modelMessage('addComment', \Auth::get('username'), $match->id));
				}

				if (\Auth::get('id') != $match->id_joueur2){
					$this->newNotify($match->id_joueur2, $this->modelMessage('addComment', \Auth::get('username'), $match->id));
				}


				$array = array(
					'user' => \Model\Auth_User::find(\Auth::get('id')),
					'photouser' => $this->photo(\Auth::get('id')),
					'commentaire' => $commentaire->commentaire,
				);

				return json_encode($this->object_to_array($array));
				break;

			case 'like':
				if (!is_numeric(\Input::get('match'))){
					return 'KO';
				}

				$user = \Model\Auth_User::find(\Auth::get('id'));

				$match = \Model_Matchs::find(\Input::get('match'));
				if (empty($match)) return 'KO';

				$like = \Model_Like::forge();
				$like->id_user = $user->id;
				$like->id_match = $match->id;
				
				if ($like->save()) return json_encode('OK');

				break;

			case 'playerswhoslike':
				if (!is_numeric(\Input::get('match'))){
					return 'KO';
				}

				$match = \Model_Matchs::find(\Input::get('match'));
				if (empty($match)) return 'KO';

				$array = array();
				foreach ($match->like as $like){
					$array[] = array(
						'user' => $like->user,
						'photouser' => $this->photo ($like->user->id),
					);
				}

				return json_encode($this->object_to_array($array));
				break;
		}
	}



	/**
	 * Add
	 * Ajoute un match
	 */
	public function action_add (){
		$this->verifAutorisation();

		if (\Input::post('add')){
			// var_dump($_POST);die();
			if (!is_numeric(\Input::post('joueur1')) || !is_numeric(\Input::post('joueur2')) || !is_numeric(\Input::post('defi')) || !is_numeric(\Input::post('createur'))){
				\Messages::error('Problèmes avec les joueurs');
				\Response::redirect('/defis');
			}

			$defi = \Model_Defis::find(\Input::post('defi'));
			if (empty($defi)){
				\Messages::error('Problème de défi');
				\Response::redirect('/defis');
			}

			$defieur = \Model\Auth_User::find(\Input::post('joueur1'));
			if (empty($defieur)){
				\Messages::error('Le joueur 1 n\'existe pas');
				\Response::redirect('/defis');
			}
			$defier = \Model\Auth_User::find(\Input::post('joueur2'));
			if (empty($defier)){
				\Messages::error('Le joueur 2 n\'existe pas');
				\Response::redirect('/defis');
			}
			
			if ($defi->id_joueur_defieur != $defieur->id || $defi->id_joueur_defier != $defier->id){
				\Messages::error('Les joueurs ne correspondent pas au défi initial');
				\Response::redirect('/defis');
			}

			$equipe1 = \Model_Equipe::find(\Input::post('id_equipe_defieur'));
			if (empty($equipe1)){
				\Messages::error('L\'équipe de '.$defieur->username.' n\'existe pas');
				\Response::redirect('/defis');
			}

			$equipe2 = \Model_Equipe::find(\Input::post('id_equipe_defier'));
			if (empty($equipe2)){
				\Messages::error('L\'équipe de '.$defier->username.' n\'existe pas');
				\Response::redirect('/defis');
			}

			$match = \Model_Matchs::forge();
			$match->id_joueur1 = $defieur->id;
			$match->id_joueur2 = $defier->id;
			$match->id_equipe1 = $equipe1->id;
			$match->id_equipe2 = $equipe2->id;
			$match->score_joueur1 = \Input::post('score_joueur_1');
			$match->score_joueur2 = \Input::post('score_joueur_2');
			if (\Input::post('prolongation')) $match->prolongation = 1;
			$match->save();

			if (\Input::post('createur') == $defier->id){
				$defi->match_valider2 = 1;
				$defi->match_valider1 = 0;
			} elseif (\Input::post('createur') == $defieur->id){
				$defi->match_valider1 = 1;
				$defi->match_valider2 = 0;
			}

			/**
			 * Gestion des buteurs
			 *
			 */
			if (\Input::post('buteurs-dom')){
				$i = 1;
				foreach (\Input::post('buteurs-dom') as $but){
					$joueur = \Model_Joueur::find($but);
					if (!empty($joueur)){
						$buteur = \Model_Buteurs::forge();
						$buteur->id_match = $match->id;
						$buteur->id_joueur = $joueur->id;
						if (\Input::post('minute_dom_buteur')[$i]){
							$buteur->minute = \Input::post('minute_dom_buteur')[$i];
						}
						$buteur->save();
					}
					$i++;
				}
			}

			if (\Input::post('buteurs-ext')){
				$i = 1;
				foreach (\Input::post('buteurs-ext') as $but){
					$joueur = \Model_Joueur::find($but);
					if (!empty($joueur)){
						$buteur = \Model_Buteurs::forge();
						$buteur->id_match = $match->id;
						$buteur->id_joueur = $joueur->id;
						if (\Input::post('minute_ext_buteur')[$i]){
							$buteur->minute = \Input::post('minute_ext_buteur')[$i];
						}
						$buteur->save();
					}
					$i++;
				}
			}

			/**
			 *
			 * GESTION DES TAB
			 *
			 */
			if (\Input::post('tab')){
				if (\Input::post('mode_tab') == 'score'){
					$tab = \Model_Tab::forge();
					$tab->id_match = $match->id;
					$tab->score_joueur1 = \Input::post('tab_joueur_1');
					$tab->score_joueur2 = \Input::post('tab_joueur_2');
					$tab->save();

					$match->id_tab = $tab->id;
					$match->save();
				} else {
					if (\Input::post('tireurs-dom')){
						$tab = \Model_Tab::forge();
						$tab->id_match = $match->id;
						$tab->score_joueur1 = count(\Input::post('tireurs_dom_reussite'));
						$tab->score_joueur2 = count(\Input::post('tireurs_ext_reussite'));
						$tab->save();

						$match->id_tab = $tab->id;
						$match->save();

						foreach (\Input::post('tireurs-dom') as $i => $idj){
							$joueur = \Model_Joueur::find($idj);
							if (empty($joueur)){
								\Messages::error('Un des tireurs de penaltys n\' existe pas');
								\Response::redirect_back();
							}

							$jtab = \Model_Joueurstab::forge();
							$jtab->id_joueur = $joueur->id;
							$jtab->id_tab = $tab->id;
							$jtab->ordre = $i;
							if (!empty(\Input::post('tireurs_dom_reussite')[$i])){
								$jtab->reussi = 1;
							} else $jtab->reussi = 0;
							$jtab->save();
						}

						foreach (\Input::post('tireurs-ext') as $i => $idj){
							$joueur = \Model_Joueur::find($idj);
							if (empty($joueur)){
								\Messages::error('Un des tireurs de penaltys n\' existe pas');
								\Response::redirect_back();
							}

							$jtab = \Model_Joueurstab::forge();
							$jtab->id_joueur = $joueur->id;
							$jtab->id_tab = $tab->id;
							$jtab->ordre = $i;
							if (!empty(\Input::post('tireurs_ext_reussite')[$i])){
								$jtab->reussi = 1;
							} else $jtab->reussi = 0;
							$jtab->save();
						}
					}// IF TIREURS DOM
				}// ELSE
			}// IF TAB
			

			$defi->id_match = $match->id;

			if ($defi->save()){
				/**
				 * NOTIFICATION
				 */
				if (\Auth::get('id') == $defi->id_joueur_defier){
					$this->newNotify($defi->id_joueur_defieur, $this->modelMessage('addRapport', \Auth::get('username'), $match->id));
				} elseif (\Auth::get('id') == $defi->id_joueur_defieur){
					$this->newNotify($defi->id_joueur_defier, $this->modelMessage('addRapport', \Auth::get('username'), $match->id));
				}

				\Messages::success('Le rapport du match a bien été enregistré. Votre adversaire recevra une notification pour le valider');
				\Response::redirect('/defis');
			}
		}//ADD

		if (!is_numeric(\Input::post('defi'))){
			\Messages::error('Données erronées');
			\Response::redirect('/defis');
		}

		/**
		 *
		 * ZONE USER
		 *
		 */
		$defi = \Model_Defis::find(\Input::post('defi'));
		if (empty($defi)){
			\Messages::error('Ce défi n\'existe pas');
			\Response::redirect('/defis');
		}


		/**
		 *
		 * ZONE FOOT
		 *
		 */
		// Pays qui ont un championnat
		$query = \DB::query('SELECT DISTINCT pays.nom, pays.id, drapeau FROM pays JOIN championnat ON championnat.id_pays = pays.id ORDER BY pays.nom')->as_object('Model_Pays')->execute();
		$pays = array();
		foreach ($query as $result){
			$pays[] = $result;
		}

		$championnats = \Model_Championnat::query()->where('actif', '=', 1)->get();

		return $this->view('matchs/add', array('defi' => $defi, 'photo_defieur' => $this->photo($defi->defieur->id), 'photo_defier' => $this->photo ($defi->defier->id), 'pays' => $pays, 'championnats' => $championnats));
	}


	/**
	 *
	 * View
	 * Affiche le rapport d'un match
	 *
	 * @param int $id
	 */
	public function action_view ($id){
		$this->verifAutorisation();

		$match = \Model_Matchs::find($id);
		if (empty($match)){
			\Messages::error('Ce match n\'existe pas');
			\Response::redirect('/defis');
		}

		/**
		 *
		 * ZONE USER
		 *
		 */

		// Verification si match pas encore validé que seuls les joueurs puissent y accéder
		if ($match->defi->match_valider1 == 0 || $match->defi->match_valider2 == 0){
			if ((\Auth::get('id') != $match->joueur1->id) && (\Auth::get('id') != $match->joueur2->id)){
				\Response::redirect('/');
			}
		}

		$derniers_matchs_1 = '';
		$stat1 = \DB::query("SELECT * FROM matchs WHERE id_joueur1 = ".$match->joueur1->id." OR id_joueur2 = ".$match->joueur1->id." ORDER BY updated_at LIMIT 5")->as_object('Model_Matchs')->execute();
		foreach ($stat1 as $result){
			$derniers_matchs_1 .= $this->derniersMatchs ($result, $match->joueur1);
		}

		$derniers_matchs_2 = '';
		$stat2 = \DB::query("SELECT * FROM matchs WHERE id_joueur1 = ".$match->joueur2->id." OR id_joueur2 = ".$match->joueur2->id." ORDER BY updated_at LIMIT 5")->as_object('Model_Matchs')->execute();
		foreach ($stat2 as $result){
			$derniers_matchs_2 .= $this->derniersMatchs ($result, $match->joueur2);
		}

		/**
		 * BUTEURS
		 *
		 */
		//Trie des buteurs par minute
		$minute = array();
		foreach ($match->buteurs as $key => $row):
			$minute[$key] = $row['minute'];
		endforeach;

		array_multisort($minute, SORT_ASC, $match->buteurs);

		/**
		 *
		 * TIREURS
		 *
		 */
		if ($match->id_tab != 0){
			$ordre = array();
			foreach ($match->tab->tireurs as $key => $row):
				$ordre[$key] = $row['ordre'];
			endforeach;

			array_multisort($ordre, SORT_ASC, $match->tab->tireurs);
		}

		/**
		 *
		 * LIKE
		 *
		 */
		$jaime = false;
		foreach ($match->like as $l){
			if ($l->id_user == \Auth::get('id')){
				$jaime = true;
				break;
			}
		}


		/**
		 *
		 * COMMENTAIRES
		 *
		 */
		if ($match->defi->match_valider1 != 0 && $match->defi->match_valider2 != 0){
			//Trie des commentaires par date décroissante
			$created = array();
			foreach ($match->commentaires as $key => $row):
				$created[$key] = $row['created_at'];
			endforeach;

			array_multisort($created, SORT_DESC, $match->commentaires);

			$array_comments = array();
			foreach ($match->commentaires as $commentaire){
				$array_comments[] = array(
					'commentaire' => $commentaire,
					'photouser' => $this->photo($commentaire->user->id),
				);
			}

			if ($match->id_tab != 0){
				return $this->view('matchs/view', array('match' => $match, 'photo_defieur' => $this->photo ($match->joueur1->id), 'photo_defier' => $this->photo ($match->joueur2->id), 'buteurs' => $match->buteurs, 'tireurs' => $match->tab->tireurs, 'derniers_matchs_1' => $derniers_matchs_1, 'derniers_matchs_2' => $derniers_matchs_2, 'match_valider' => true, 'jaime' => $jaime, 'commentaires' => $array_comments));
			}
			else return $this->view('matchs/view', array('match' => $match, 'photo_defieur' => $this->photo ($match->joueur1->id), 'photo_defier' => $this->photo ($match->joueur2->id), 'buteurs' => $match->buteurs, 'tireurs' => '', 'derniers_matchs_1' => $derniers_matchs_1, 'derniers_matchs_2' => $derniers_matchs_2, 'match_valider' => true, 'jaime' => $jaime, 'commentaires' => $array_comments));
		}
		else {
			if ($match->id_tab){
				return $this->view('matchs/view', array('match' => $match, 'photo_defieur' => $this->photo ($match->joueur1->id), 'photo_defier' => $this->photo ($match->joueur2->id), 'buteurs' => $match->buteurs, 'tireurs' => $match->tab->tireurs, 'derniers_matchs_1' => $derniers_matchs_1, 'derniers_matchs_2' => $derniers_matchs_2, 'match_valider' => false, 'jaime' => $jaime, 'commentaires' => array()));
			} else return $this->view('matchs/view', array('match' => $match, 'photo_defieur' => $this->photo ($match->joueur1->id), 'photo_defier' => $this->photo ($match->joueur2->id), 'buteurs' => $match->buteurs, 'tireurs' => '', 'derniers_matchs_1' => $derniers_matchs_1, 'derniers_matchs_2' => $derniers_matchs_2, 'match_valider' => false, 'jaime' => $jaime, 'commentaires' => array()));
		}

		
	}


	/**
	 * derniers Matchs
	 * Détermine si un match est gagné, perdu, ou nul
	 *
	 * @param Object $result : L'objet contenant les scores
	 * @param Object $joueur : Le joueur
	 * @return String : Le HTML avec le résultat
	 */
	function derniersMatchs ($result, $joueur){
		if ($result->id_joueur1 == $joueur->id){
			if ($result->score_joueur1 > $result->score_joueur2){
				return '<span class="label label-success">V</span>';
			} elseif ($result->score_joueur1 == $result->score_joueur2){
				return '<span class="label label-default">N</span>';
			} else return '<span class="label label-danger">D</span>';
		} else {
			if ($result->score_joueur1 > $result->score_joueur2){
				return '<span class="label label-danger">D</span>';
			} elseif ($result->score_joueur1 == $result->score_joueur2){
				return '<span class="label label-default">N</span>';
			} else return '<span class="label label-success">V</span>';
		}
	}


	/**
	 * Modif
	 * Modifier un match
	 *
	 * @param int $id
	 */
	public function action_modif ($id){
		$this->verifAutorisation();

		if (\Input::post('add')){
			// var_dump($_POST);die();
			if (!is_numeric(\Input::post('joueur1')) || !is_numeric(\Input::post('joueur2')) || !is_numeric(\Input::post('defi')) || !is_numeric(\Input::post('match')) || !is_numeric(\Input::post('modifieur'))){
				\Messages::error('Un problème a été détecté.');
				\Response::redirect('/defis');
			}

			$defi = \Model_Defis::find(\Input::post('defi'));
			if (empty($defi)){
				\Messages::error('Problème de défi');
				\Response::redirect('/defis');
			}

			$defieur = \Model\Auth_User::find(\Input::post('joueur1'));
			if (empty($defieur)){
				\Messages::error('Le joueur 1 n\'existe pas');
				\Response::redirect('/defis');
			}
			$defier = \Model\Auth_User::find(\Input::post('joueur2'));
			if (empty($defier)){
				\Messages::error('Le joueur 2 n\'existe pas');
				\Response::redirect('/defis');
			}
			
			if ($defi->id_joueur_defieur != $defieur->id || $defi->id_joueur_defier != $defier->id){
				\Messages::error('Les joueurs ne correspondent pas au défi initial');
				\Response::redirect('/defis');
			}

			$match = \Model_Matchs::find(\Input::post('match'));
			if (empty($match)){
				\Messages::error('Ce match n\'existe pas');
				\Response::redirect('/defis');
			}

			$equipe1 = \Model_Equipe::find(\Input::post('id_equipe_defieur'));
			if (empty($equipe1)){
				\Messages::error('L\'équipe de '.$defieur->username.' n\'existe pas');
				\Response::redirect('/defis');
			}

			$equipe2 = \Model_Equipe::find(\Input::post('id_equipe_defier'));
			if (empty($equipe2)){
				\Messages::error('L\'équipe de '.$defier->username.' n\'existe pas');
				\Response::redirect('/defis');
			}

			$match->id_equipe1 = $equipe1->id;
			$match->id_equipe2 = $equipe2->id;
			$match->score_joueur1 = \Input::post('score_joueur_1');
			$match->score_joueur2 = \Input::post('score_joueur_2');
			if (\Input::post('prolongation')) $match->prolongation = 1;
			$match->save();

			if (\Input::post('modifieur') == $defier->id){
				$defi->match_valider2 = 1;
				$defi->match_valider1 = 0;
			} elseif (\Input::post('modifieur') == $defieur->id){
				$defi->match_valider1 = 1;
				$defi->match_valider2 = 0;
			}

			/**
			 * Gestion des buteurs
			 *
			 */
			// var_dump(\Input::post('buteurs-dom'));die();
			if (\Input::post('buteurs-dom')){
				$i = 1;
				foreach (\Input::post('buteurs-dom') as $but){
					$j = \Model_Buteurs::find('all', array(
						'where' => array(
							array('id_match', $match->id),
							array('id_joueur', $but),
							array('minute', \Input::post('minute_dom_buteur')[$i]),
						),
					));
					if (!empty($j)){
						$j = current($j);
						if (\Input::post('minute_dom_buteur')[$i]){
							$j->minute = \Input::post('minute_dom_buteur')[$i];
						}
						$j->save();
					} else {
						$joueur = \Model_Joueur::find($but);
						if (!empty($joueur)){
							$buteur = \Model_Buteurs::forge();
							$buteur->id_match = $match->id;
							$buteur->id_joueur = $joueur->id;
							if (\Input::post('minute_dom_buteur')[$i]){
								$buteur->minute = \Input::post('minute_dom_buteur')[$i];
							}
							$buteur->save();
						}
					}
					$i++;
				}
			}

			if (\Input::post('buteurs-ext')){
				$i = 1;
				foreach (\Input::post('buteurs-ext') as $but){
					$j = \Model_Buteurs::find('all', array(
						'where' => array(
							array('id_match', $match->id),
							array('id_joueur', $but),
							array('minute', \Input::post('minute_ext_buteur')[$i]),
						),
					));
					if (!empty($j)){
						$j = current($j);
						if (\Input::post('minute_ext_buteur')[$i]){
							$j->minute = \Input::post('minute_ext_buteur')[$i];
						}
						$j->save();
					} else {
						$joueur = \Model_Joueur::find($but);
						if (!empty($joueur)){
							$buteur = \Model_Buteurs::forge();
							$buteur->id_match = $match->id;
							$buteur->id_joueur = $joueur->id;
							if (\Input::post('minute_ext_buteur')[$i]){
								$buteur->minute = \Input::post('minute_ext_buteur')[$i];
							}
							$buteur->save();
						}
					}
					$i++;
				}
			}

			// Suppression des anciens buteurs, s'il y en a
			if (\Input::post('buteurs-dom') && \Input::post('buteurs-ext')){
				$array_buteurs = array_merge(\Input::post('buteurs-dom'), \Input::post('buteurs-ext'));
				$this->deleteOldButeurs($match, $array_buteurs);
			} else if (\Input::post('buteurs-dom')){
				$this->deleteOldButeurs($match, \Input::post('buteurs-dom'));
			} else if (\Input::post('buteurs-ext')){
				$this->deleteOldButeurs($match, \Input::post('buteurs-ext'));
			}

			/**
			 *
			 * GESTION DES TAB
			 *
			 */
			if (\Input::post('tab')){
				// var_dump(\Input::post('mode_tab'));die();
				if (\Input::post('mode_tab') == 'score'){
					$tab = \Model_Tab::query()->where('id_match', '=', $match->id)->get();
					if (empty($tab)){
						$tab = \Model_Tab::forge();
						$tab->id_match = $match->id;
						$tab->score_joueur1 = \Input::post('tab_joueur_1');
						$tab->score_joueur2 = \Input::post('tab_joueur_2');
						$tab->save();

						$match->id_tab = $tab->id;
						$match->save();
					} else {
						$tab = current($tab);
						$tab->score_joueur1 = \Input::post('tab_joueur_1');
						$tab->score_joueur2 = \Input::post('tab_joueur_2');
						$tab->save();
					}
				} elseif (\Input::post('mode_tab') == 'detaille'){
					if (\Input::post('tireurs-dom')){
						$tab = \Model_Tab::query()->where('id_match', '=', $match->id)->get();
						if (empty($tab)){
							$tab = \Model_Tab::forge();
							$tab->id_match = $match->id;
							$tab->score_joueur1 = count(\Input::post('tireurs_dom_reussite'));
							$tab->score_joueur2 = count(\Input::post('tireurs_ext_reussite'));
							$tab->save();

							$match->id_tab = $tab->id;
							$match->save();
						} else {
							$tab = current($tab);
							$tab->score_joueur1 = count(\Input::post('tireurs_dom_reussite'));
							$tab->score_joueur2 = count(\Input::post('tireurs_ext_reussite'));
							$tab->save();
						}

						

						foreach (\Input::post('tireurs-dom') as $i => $idj){
							if (count(\Input::post('tireurs_dom')) <= 11){
								$jtab = \Model_Joueurstab::find('all', array(
									'where' => array(
										array('id_joueur', $idj),
										array('id_tab', $tab->id),
										array('ordre', $i),
									),
								));

								if (empty($jtab)){
									$joueur = \Model_Joueur::find($idj);
									if (empty($joueur)){
										\Messages::error('Un des tireurs de penaltys n\' existe pas');
										\Response::redirect_back();
									}

									$jtab = \Model_Joueurstab::forge();
									$jtab->id_joueur = $joueur->id;
									$jtab->id_tab = $tab->id;
									$jtab->ordre = $i;
									if (!empty(\Input::post('tireurs_dom_reussite')[$i])){
										$jtab->reussi = 1;
									} else $jtab->reussi = 0;
									$jtab->save();
								} else {
									$jtab = current($jtab);
									$jtab->ordre = $i;
									if (!empty(\Input::post('tireurs_dom_reussite')[$i])){
										$jtab->reussi = 1;
									} else $jtab->reussi = 0;
									$jtab->save();
								}
							}
							
						}

						foreach (\Input::post('tireurs-ext') as $i => $idj){
							if (count(\Input::post('tireurs_ext')) <= 11){
								$jtab = \Model_Joueurstab::find('all', array(
									'where' => array(
										array('id_joueur', $idj),
										array('id_tab', $tab->id),
										array('ordre', $i),
									),
								));

								if (empty($jtab)){
									$joueur = \Model_Joueur::find($idj);
									if (empty($joueur)){
										\Messages::error('Un des tireurs de penaltys n\' existe pas');
										\Response::redirect_back();
									}

									$jtab = \Model_Joueurstab::forge();
									$jtab->id_joueur = $joueur->id;
									$jtab->id_tab = $tab->id;
									$jtab->ordre = $i;
									if (!empty(\Input::post('tireurs_ext_reussite')[$i])){
										$jtab->reussi = 1;
									} else $jtab->reussi = 0;
									$jtab->save();
								} else {
									$jtab = current($jtab);
									$jtab->ordre = $i;
									if (!empty(\Input::post('tireurs_ext_reussite')[$i])){
										$jtab->reussi = 1;
									} else $jtab->reussi = 0;
									$jtab->save();
								}
							}//IF COUNT < 11
						}// FOREACH
					}//IF TIREURS-DOM
					// Suppression des anciens tireurs
					$array_tireurs = array_merge(\Input::post('tireurs-dom'), \Input::post('tireurs-ext'));
					$this->deleteOldTireurs($match, $array_tireurs);
				}		

			}//IF TAB
			

			if ($defi->save()){
				if (\Input::post('modifieur') == $defier->id){
					$this->newNotify($defieur->id, $this->modelMessage('modifRapport', \Auth::get('username'), $match->id));
				} elseif (\Input::post('modifieur') == $defieur->id){
					$this->newNotify($defier->id, $this->modelMessage('modifRapport', \Auth::get('username'), $match->id));
				}
				
				\Messages::success('Le rapport du match a bien été modifié. Votre adversaire recevra une notification pour le valider');
				\Response::redirect('/defis');
			}
		}//ADD
		

		$match = \Model_Matchs::find($id);
		if (empty($match)){
			\Messages::error('Ce match n\'existe pas');
			\Response::redirect('/defis');
		}

		// Verification si match pas encore validé que seuls les joueurs puissent y accéder
		if ($match->defi->match_valider1 == 0 || $match->defi->match_valider2 == 0){
			if ((\Auth::get('id') != $match->defi->defieur->id) && (\Auth::get('id') != $match->defi->defier->id)){
				\Response::redirect('/');
			}
		}


		/**
		 *
		 * ZONE FOOT
		 *
		 */
		// Pays qui ont un championnat
		$query = \DB::query('SELECT DISTINCT pays.nom, pays.id, drapeau FROM pays JOIN championnat ON championnat.id_pays = pays.id ORDER BY pays.nom')->as_object('Model_Pays')->execute();
		$pays = array();
		foreach ($query as $result){
			$pays[] = $result;
		}

		$championnats = \Model_Championnat::find('all');

		/**
		 *
		 * BUTEURS
		 *
		 */
		$minute = array();
		foreach ($match->buteurs as $key => $row):
			$minute[$key] = $row['minute'];
		endforeach;

		array_multisort($minute, SORT_ASC, $match->buteurs);

		/**
		 *
		 * TIREURS
		 *
		 */
		if ($match->id_tab != 0){
			$ordre = array();
			$nb_tireurs_dom = $nb_tireurs_ext = 0;
			foreach ($match->tab->tireurs as $key => $row):
				$ordre[$key] = $row['ordre'];
				if ($row->joueur->equipe->id == $match->id_equipe1){
					$nb_tireurs_dom += 1;
				} else $nb_tireurs_ext += 1;
			endforeach;

			array_multisort($ordre, SORT_ASC, $match->tab->tireurs);
		}


		return $this->view('matchs/modif', array('match' => $match, 'photo_defieur' => $this->photo($match->defi->defieur->id), 'photo_defier' => $this->photo($match->defi->defier->id), 'pays' => $pays, 'championnats' => $championnats, 'buteurs' => (!empty($match->buteurs)) ? $match->buteurs : '', 'tireurs' => (!empty($match->tab->tireurs)) ? $match->tab->tireurs : '', 'nb_tireurs_dom' => (!empty($nb_tireurs_dom)) ? $nb_tireurs_dom : '', 'nb_tireurs_ext' => (!empty($nb_tireurs_ext)) ? $nb_tireurs_ext : ''));
	}


	/**
	 * deleteOldButeurs
	 * Supprime les anciens buteurs de match
	 *
	 * @param Object $match
	 * @param Array $buteurs
	 */
	public function deleteOldButeurs ($match, $buteurs){
		$notIn = '';
		foreach ($buteurs as $key => $buteur){
			if ($key+1 == count($buteurs)){
				$notIn .= $buteur;
			} else $notIn .= $buteur.', ';
		}


		$query = \DB::query("SELECT * FROM buteurs
			WHERE id_match = ".$match->id."
			AND id_joueur NOT IN (".$notIn.")
		")->as_object('Model_Buteurs')->execute();


		foreach ($query as $result){
			$result->delete();
		}
	}

	/**
	 * deleteOldTireurs
	 * Supprime les anciens tireurs de match
	 *
	 * @param Object $match
	 * @param Array $tireurs
	 */
	public function deleteOldTireurs ($match, $tireurs){
		$notIn = '';
		foreach ($tireurs as $key => $tireur){
			if ($key+1 == count($tireurs)){
				$notIn .= $tireur;
			} else $notIn .= $tireur.',';
		}

		$query = \DB::query("SELECT * FROM joueurs_tab
			WHERE id_tab = ".$match->id_tab."
			AND id_joueur NOT IN (".$notIn.")
		")->as_object('Model_Joueurstab')->execute();

		foreach ($query as $result){
			$result->delete();
		}
	}

}