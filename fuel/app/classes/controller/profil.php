<?php

class Controller_Profil extends Controller_Front
{
	/**
	 * AJAX ONLY
	 */
	public function get_api ($context){
		switch ($context){
			case 'equipefav':
				if (!is_numeric(\Input::get('equipe'))){
					return 'KO';
				}

				$equipe = \Model_Equipe::find(\Input::get('equipe'));
				if (empty($equipe)) return 'KO';

				$user = \Model\Auth_User::find(\Auth::get('id'));

				$user->equipe_fav = $equipe->id;

				$equipe->championnat = str_replace(' ', '_', strtolower($equipe->championnat->nom));
				$array = $this->object_to_array($equipe);

				if ($user->save()) return json_encode($array);
				break;
		}
	}

	/**
	 * Index
	 *	Affiche le profil de l'user connecté
	 */
	public function action_index (){
		$this->verifAutorisation();

		/**
		 *
		 * STATS
		 *
		 */
		
		$query = \DB::query('SELECT * FROM defis
			JOIN matchs ON defis.id_match = matchs.id
			WHERE (
				id_joueur1 = '.\Auth::get('id').'
				AND match_valider1 = 1
				AND match_valider2 = 1
			) OR (
				id_joueur2 = '.\Auth::get('id').'
				AND match_valider1 = 1
				AND match_valider2 = 1
			)
			ORDER BY matchs.created_at DESC
		')->execute();

		$victoires = $nuls = $defaites = 0;
		$i = 1;
		$derniers_matchs = array();

		foreach ($query as $result){
			//DEFIEUR
			if ($result['id_joueur1'] == \Auth::get('id')){
				if (intval($result['score_joueur1']) > intval($result['score_joueur2'])) $victoires += 1;
				else if (intval($result['id_joueur1']) == intval($result['score_joueur2'])) $nuls += 1;
				else $defaites += 1;
			}
			//DEFIER
			else {
				if (intval($result['score_joueur2']) > intval($result['score_joueur1'])) $victoires += 1;
				else if (intval($result['score_joueur2']) == intval($result['score_joueur1'])) $nuls += 1;
				else $defaites += 1;
			}
			if ($i <= 5){
				if ($result['id_joueur1'] == \Auth::get('id')){
					$derniers_matchs[] = array(
						'equipe1' => \Model_Equipe::find($result['id_equipe1']),
						'equipe2' => \Model_Equipe::find($result['id_equipe2']),
						'match' => $result,
						'status' => $this->statusMatch('defieur', $result['score_joueur1'], $result['score_joueur2']),
					);
				} else {
					$derniers_matchs[] = array(
						'equipe1' => \Model_Equipe::find($result['id_equipe1']),
						'equipe2' => \Model_Equipe::find($result['id_equipe2']),
						'match' => $result,
						'status' => $this->statusMatch('defier', $result['score_joueur1'], $result['score_joueur2']),
					);
				}
			}
			$i++;
		}

		$stats = array(
			'victoires' => $victoires,
			'nuls' => $nuls,
			'defaites' => $defaites,
		);

		/**
		 * AMIS
		 *
		 */
		$liste_amis = array();
		$amis = \Model_Amis::query()->where('id_user1', '=', \Auth::get('id'))->get();
		if (!empty($amis)){
			
			foreach ($amis as $am){
				$liste_amis[] = array(
					'users' => $am->user_inverse,
					'photouser' => $this->photo($am->user_inverse->id),
				);
			}
		}

		/**
		 *
		 * DETERMINATION EQUIPE FAVORITE
		 *
		 */
		$equipe_fav = \Model_Equipe::find(\Auth::get('equipe_fav'));
		if (empty($equipe_fav)) $equipe_fav = null;



		// Pays qui ont un championnat
		$query = \DB::query('SELECT DISTINCT pays.nom, pays.id, drapeau FROM pays JOIN championnat ON championnat.id_pays = pays.id ORDER BY pays.nom')->as_object('Model_Pays')->execute();
		$pays = array();
		foreach ($query as $result){
			$pays[] = $result;
		}

		$championnats = \Model_Championnat::find('all');



        return $this->view('profil/index', array('photo_user' => $this->photo(\Auth::get('id')), 'liste_amis' => $liste_amis, 'stats' => $stats, 'derniers_matchs' => $derniers_matchs, 'equipe_fav' => $equipe_fav, 'pays' => $pays, 'championnats' => $championnats));
	}


	/**
	 * statusMatch
	 * indique si le match est gagné, perdu, ou nul
	 *
	 * @param int $score1
	 * @param int $score2
	 * @return String
	 */
	public function statusMatch ($ordre, $score1, $score2){
		if ($ordre == 'defieur'){
			if ($score1 > $score2) return 'V';
			elseif ($score1 == $score2) return 'N';
			else return 'D';
		} else {
			if ($score1 > $score2) return 'D';
			elseif ($score1 == $score2) return 'N';
			else return 'V';
		}
		
	}


	/**
	 * View
	 * Voir un profil
	 *
	 * @param int $id
	 */
	public function action_view ($id){
		$this->verifAutorisation();

		$user = \Model\Auth_User::find($id);
		if (empty($user)){
			\Messages::error('Ce membre n\'existe pas');
			\Response::redirect('/');
		}

		if ($user->id == \Auth::get('id')){
			\Response::redirect('/profil');
		}
		
		/**
		 *
		 * STATS
		 *
		 */
		
		$query = \DB::query('SELECT * FROM defis
			JOIN matchs ON defis.id_match = matchs.id
			WHERE (
				id_joueur1 = '.$user->id.'
				AND match_valider1 = 1
				AND match_valider2 = 1
			) OR (
				id_joueur2 = '.$user->id.'
				AND match_valider1 = 1
				AND match_valider2 = 1
			)
			ORDER BY matchs.created_at DESC
		')->execute();

		$victoires = $nuls = $defaites = 0;
		$i = 1;
		$derniers_matchs = array();

		foreach ($query as $result){
			//DEFIEUR
			if ($result['id_joueur1'] == $user->id){
				if (intval($result['score_joueur1']) > intval($result['score_joueur2'])) $victoires += 1;
				else if (intval($result['id_joueur1']) == intval($result['score_joueur2'])) $nuls += 1;
				else $defaites += 1;
			}
			//DEFIER
			else {
				if (intval($result['score_joueur2']) > intval($result['score_joueur1'])) $victoires += 1;
				else if (intval($result['score_joueur2']) == intval($result['score_joueur1'])) $nuls += 1;
				else $defaites += 1;
			}
			// DERNIERS MATCHS
			if ($i <= 5){
				if ($result['id_joueur1'] == $user->id){
					$derniers_matchs[] = array(
						'equipe1' => \Model_Equipe::find($result['id_equipe1']),
						'equipe2' => \Model_Equipe::find($result['id_equipe2']),
						'match' => $result,
						'status' => $this->statusMatch('defieur', $result['score_joueur1'], $result['score_joueur2']),
					);
				} else {
					$derniers_matchs[] = array(
						'equipe1' => \Model_Equipe::find($result['id_equipe1']),
						'equipe2' => \Model_Equipe::find($result['id_equipe2']),
						'match' => $result,
						'status' => $this->statusMatch('defier', $result['score_joueur1'], $result['score_joueur2']),
					);
				}
			}
			$i++;
		}

		$stats = array(
			'victoires' => $victoires,
			'nuls' => $nuls,
			'defaites' => $defaites,
		);


		/**
		 *
		 * AMIS
		 *
		 */
		$ami = \Model_Amis::find('all', array(
			'where' => array(
				array('id_user1', \Auth::get('id')),
				array('id_user2', $user->id),
			),
		));

		if (!empty($ami)){
			$ami = current($ami);
			if ($ami->valider == 0) $ami = 0;
			else $ami = 1;
		} else $ami = false;

		$ami_inverse = \Model_Amis::find('all', array(
			'where' => array(
				array('id_user1', $user->id),
				array('id_user2', \Auth::get('id')),
			),
		));

		if (!empty($ami_inverse)){
			$ami_inverse = current($ami_inverse);
			if ($ami_inverse->valider == 0) $ami_inverse = 1;
			else $ami_inverse = 0;
		} else $ami_inverse = 0;

		/**
		 * LISTE DES AMIS
		 */
		$liste_amis = array();
		$amis = \Model_Amis::find('all', array(
			'where' => array(
				array('id_user1', $user->id),
				array('valider', 1),
			),
		));
		if (!empty($amis)){
			foreach ($amis as $am){
				$liste_amis[] = array(
					'users' => $am->user_inverse,
					'photouser' => $this->photo($am->user_inverse->id),
				);
			}
		}

		/**
		 *
		 * DEFIS EN COURS POUR BOUTON 'DEFIER'
		 *
		 */
		$status = \Model_Status::query()->where('code', '=', 0)->get();
		if (!empty($status)) $status = current($status);
			
		$defi = \Model_Defis::find('all', array(
			'where' => array(
				array('id_joueur_defieur', \Auth::get('id')),
				array('id_joueur_defier', $user->id),
				array('status_demande', $status->id),
			),
		));

		/**
		 *
		 * DETERMINATION EQUIPE FAVORITE
		 *
		 */
		$equipe_fav;
		if (!empty($user->equipe_fav)){
			$equipe_fav = \Model_Equipe::find($user->equipe_fav);
		}
		else $equipe_fav = null;


		return $this->view('profil/view', array('user' => $user, 'photo_user' => $this->photo($user->id), 'defi' => (!empty($defi)) ? 1 : 0, 'ami' => $ami, 'ami_inverse' => $ami_inverse, 'equipe_fav' => $equipe_fav, 'liste_amis' => $liste_amis, 'stats' => $stats, 'derniers_matchs' => $derniers_matchs));
	}
}