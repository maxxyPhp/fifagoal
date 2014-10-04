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

				return json_encode('OK');
				break;

			case 'addComment':
				if (!is_numeric(\Input::get('user')) || !is_numeric(\Input::get('match'))){
					return 'KO';
				}

				$user = \Model\Auth_User::find(\Input::get('user'));
				if (empty($user)) return 'KO';

				$match = \Model_Matchs::find(\Input::get('match'));
				if (empty($match)) return 'KO';

				$content = htmlspecialchars(\Input::get('content'));
				$commentaire = \Model_Commentaires::forge();
				$commentaire->id_user = $user->id;
				$commentaire->id_match = $match->id;
				$commentaire->commentaire = $content;
				$commentaire->save();

				$photouser = \Model_Photousers::query()->where('id_users', '=', $user->id)->get();
				(!empty($photouser)) ? $photouser = current($photouser) : $photouser = null;

				$array = array(
					'user' => $user,
					'photouser' => $photouser,
					'commentaire' => $commentaire->commentaire,
				);

				$array = $this->object_to_array($array);

				return json_encode($array);
				break;
		}
	}

	public function action_index (){
		//return $this->view('matchs/index', array('users' => $array));
	}



	/**
	 * Add
	 * Ajoute un match
	 */
	public function action_add (){
		$this->verifAutorisation();

		if (\Input::post('add')){
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
			$match->save();

			if (\Input::post('createur') == $defier->id){
				$defi->match_valider2 = 1;
				$defi->match_valider1 = 0;
			} elseif (\Input::post('createur') == $defieur->id){
				$defi->match_valider1 = 1;
				$defi->match_valider2 = 0;
			}


			$defi->id_match = $match->id;

			if ($defi->save()){
				\Messages::success('Le rapport du match a bien été enregistré. Votre adversaire recevra une notification pour le valider');
				\Response::redirect('/defis');
			}
		}

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

		$defieur = \Model\Auth_User::find($defi->id_joueur_defieur);
		$defier = \Model\Auth_User::find($defi->id_joueur_defier);

		$photo_defieur = \Model_Photousers::query()->where('id_users', '=', $defieur->id)->get();
		(!empty($photo_defieur)) ? $photo_defieur = current($photo_defieur) : $photo_defieur = null;

		$photo_defier = \Model_Photousers::query()->where('id_users', '=', $defier->id)->get();
		(!empty($photo_defier)) ? $photo_defier = current($photo_defier) : $photo_defier = null;


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

		return $this->view('matchs/add', array('defi' => $defi, 'defieur' => $defieur, 'defier' => $defier, 'photo_defieur' => $photo_defieur, 'photo_defier' => $photo_defier, 'pays' => $pays, 'championnats' => $championnats));
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
		$defieur = \Model\Auth_User::find($match->id_joueur1);
		$defier = \Model\Auth_User::find($match->id_joueur2);

		// Verification si match pas encore validé que seuls les joueurs puissent y accéder
		if ($match->defis->match_valider1 == 0 || $match->defis->match_valider2 == 0){
			if ((\Auth::get('id') != $defieur->id) && (\Auth::get('id') != $defier->id)){
				\Response::redirect('/');
			}
		}


		$photo_defieur = \Model_Photousers::query()->where('id_users', '=', $defieur->id)->get();
		(!empty($photo_defieur)) ? $photo_defieur = current($photo_defieur) : $photo_defieur = null;

		$photo_defier = \Model_Photousers::query()->where('id_users', '=', $defier->id)->get();
		(!empty($photo_defier)) ? $photo_defier = current($photo_defier) : $photo_defier = null;

		$derniers_matchs_1 = '';
		$stat1 = \DB::query("SELECT * FROM matchs WHERE id_joueur1 = ".$defieur->id." OR id_joueur2 = ".$defieur->id." ORDER BY updated_at LIMIT 5")->as_object('Model_Matchs')->execute();
		foreach ($stat1 as $result){
			$derniers_matchs_1 .= $this->derniersMatchs ($result, $defieur);
		}

		$derniers_matchs_2 = '';
		$stat2 = \DB::query("SELECT * FROM matchs WHERE id_joueur1 = ".$defier->id." OR id_joueur2 = ".$defier->id." ORDER BY updated_at LIMIT 5")->as_object('Model_Matchs')->execute();
		foreach ($stat2 as $result){
			$derniers_matchs_2 .= $this->derniersMatchs ($result, $defier);
		}

		/**
		 *
		 * ZONE FOOT
		 *
		 */
		$equipe1 = \Model_Equipe::find($match->id_equipe1);
		if (empty($equipe1)){
			\Messages::error('L\'équipe domicile n\'existe pas');
			\Response::redirect('/defis');
		}
		$championnat1 = \Model_Championnat::find($equipe1->id_championnat);
		if (!empty($championnat1)) $championnat1 = str_replace(' ', '_', strtolower($championnat1->nom));

		$equipe2 = \Model_Equipe::find($match->id_equipe2);
		if (empty($equipe2)){
			\Messages::error('L\'équipe extérieure n\'existe pas');
			\Response::redirect('/defis');
		}
		$championnat2 = \Model_Championnat::find($equipe2->id_championnat);
		if (!empty($championnat2)) $championnat2 = str_replace(' ', '_', strtolower($championnat2->nom));


		/**
		 *
		 * COMMENTAIRES
		 *
		 */
		if ($match->defis->match_valider1 != 0 && $match->defis->match_valider2 != 0){
			$commentaires = \Model_Commentaires::query()->where('id_match', '=', $match->id)->order_by('created_at', 'desc')->get();

			$array_comments = array();
			foreach ($commentaires as $commentaire){
				$user = \Model\Auth_User::find($commentaire->id_user);
				if (!empty($user)){
					$photouser = \Model_Photousers::query()->where('id_users', '=', $user->id)->get();
					(!empty($photouser)) ? $photouser = current($photouser) : $photouser = null;

					$array_comments[] = array(
						'user' => $user,
						'photouser' => $photouser,
						'commentaire' => $commentaire,
					);
				}
			}
			return $this->view('matchs/view', array('match' => $match, 'defieur' => $defieur, 'defier' => $defier, 'photo_defieur' => $photo_defieur, 'photo_defier' => $photo_defier, 'equipe1' => $equipe1, 'equipe2' => $equipe2, 'championnat1' => $championnat1, 'championnat2' => $championnat2, 'derniers_matchs_1' => $derniers_matchs_1, 'derniers_matchs_2' => $derniers_matchs_2, 'match_valider' => true, 'commentaires' => $array_comments));
		}
		else {
			return $this->view('matchs/view', array('match' => $match, 'defi' => $match->defis, 'defieur' => $defieur, 'defier' => $defier, 'photo_defieur' => $photo_defieur, 'photo_defier' => $photo_defier, 'equipe1' => $equipe1, 'equipe2' => $equipe2, 'championnat1' => $championnat1, 'championnat2' => $championnat2, 'derniers_matchs_1' => $derniers_matchs_1, 'derniers_matchs_2' => $derniers_matchs_2, 'match_valider' => false, 'commentaires' => ''));
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

	public function action_modif ($id){
		$this->verifAutorisation();

		if (\Input::post('add')){
			if (!is_numeric(\Input::post('joueur1')) || !is_numeric(\Input::post('joueur2')) || !is_numeric(\Input::post('defi')) || !is_numeric(\Input::post('match')) || !is_numeric(\Input::post('modifieur'))){
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
			$match->save();

			if (\Input::post('modifieur') == $defier->id){
				$defi->match_valider2 = 1;
				$defi->match_valider1 = 0;
			} elseif (\Input::post('modifieur') == $defieur->id){
				$defi->match_valider1 = 1;
				$defi->match_valider2 = 0;
			}

			if ($defi->save()){
				\Messages::success('Le rapport du match a bien été modifié. Votre adversaire recevra une notification pour le valider');
				\Response::redirect('/defis');
			}
		}
		
		$match = \Model_Matchs::find($id);
		if (empty($match)){
			\Messages::error('Ce match n\'existe pas');
			\Response::redirect('/defis');
		}

		$defi = \Model_Defis::query()->where('id_match', '=', $match->id)->get();
		if (!empty($defi)){
			$defi = current($defi);
		} else {
			\Messages::error('Problème de défi');
			\Response::redirect('/defis');
		}

		/**
		 *
		 * ZONE USER
		 *
		 */
		$defieur = \Model\Auth_User::find($match->id_joueur1);
		$defier = \Model\Auth_User::find($match->id_joueur2);

		// Verification si match pas encore validé que seuls les joueurs puissent y accéder
		if ($match->defis->match_valider1 == 0 || $match->match_valider2 == 0){
			if ((\Auth::get('id') != $defieur->id) && (\Auth::get('id') != $defier->id)){
				\Response::redirect('/');
			}
		}


		$photo_defieur = \Model_Photousers::query()->where('id_users', '=', $defieur->id)->get();
		(!empty($photo_defieur)) ? $photo_defieur = current($photo_defieur) : $photo_defieur = null;

		$photo_defier = \Model_Photousers::query()->where('id_users', '=', $defier->id)->get();
		(!empty($photo_defier)) ? $photo_defier = current($photo_defier) : $photo_defier = null;


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

		$equipe1 = \Model_Equipe::find($match->id_equipe1);
		if (empty($equipe1)){
			\Messages::error('L\'équipe domicile n\'existe pas');
			\Response::redirect('/defis');
		}

		$equipe2 = \Model_Equipe::find($match->id_equipe2);
		if (empty($equipe2)){
			\Messages::error('L\'équipe extérieure n\'existe pas');
			\Response::redirect('/defis');
		}

		return $this->view('matchs/modif', array('match' => $match, 'defi' => $defi, 'defieur' => $defieur, 'defier' => $defier, 'photo_defieur' => $photo_defieur, 'photo_defier' => $photo_defier, 'equipe1' => $equipe1, 'equipe2' => $equipe2, 'pays' => $pays, 'championnats' => $championnats, 'match_valider' => false));
	}

}