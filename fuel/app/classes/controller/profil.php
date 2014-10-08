<?php

class Controller_Profil extends Controller_Front
{
	public function action_index (){
		$this->verifAutorisation();

		$photo_user = \Model_Photousers::find('all', array(
			'where' => array(
				array('id_users', \Auth::get('id')),
			),
		));

		if (!empty($photo_user)) $photo_user = current($photo_user);

		/**
		 *
		 * VICTOIRES
		 *
		 */

		$victoires = 0;

		$query = \DB::query('SELECT COUNT(*) as nb FROM defis
			JOIN matchs ON defis.id_match = matchs.id
			WHERE id_joueur1 = '.\Auth::get('id').'
			AND score_joueur1 > score_joueur2
			AND match_valider1 = 1
			AND match_valider2 = 1
		')->execute();

		foreach ($query as $result){
			$victoires += intval($result['nb']);
		}

		$query = \DB::query('SELECT COUNT(*) as nb FROM defis
			JOIN matchs ON defis.id_match = matchs.id
			WHERE id_joueur2 = '.\Auth::get('id').'
			AND score_joueur2 > score_joueur1
			AND match_valider1 = 1
			AND match_valider2 = 1
		')->execute();


		foreach ($query as $result){
			$victoires += intval($result['nb']);
		}

		/**
		 *
		 * NULS
		 *
		 */
		$nuls = 0;

		$query = \DB::query('SELECT COUNT(*) as nb FROM defis
			JOIN matchs ON defis.id_match = matchs.id
			WHERE id_joueur2 = '.\Auth::get('id').'
			OR id_joueur1 = '.\Auth::get('id').'
			AND score_joueur2 = score_joueur1
			AND match_valider1 = 1
			AND match_valider2 = 1
		')->execute();


		foreach ($query as $result){
			$nuls += intval($result['nb']);
		}

		/**
		 *
		 * DEFAITES
		 *
		 */
		$defaites = 0;

		$query = \DB::query('SELECT COUNT(*) as nb FROM defis
			JOIN matchs ON defis.id_match = matchs.id
			WHERE id_joueur1 = '.\Auth::get('id').'
			AND score_joueur1 < score_joueur2
			AND match_valider1 = 1
			AND match_valider2 = 1
		')->execute();


		foreach ($query as $result){
			$defaites += intval($result['nb']);
		}

		$query = \DB::query('SELECT COUNT(*) as nb FROM defis
			JOIN matchs ON defis.id_match = matchs.id
			WHERE id_joueur2 = '.\Auth::get('id').'
			AND score_joueur2 < score_joueur1
			AND match_valider1 = 1
			AND match_valider2 = 1
		')->execute();


		foreach ($query as $result){
			$defaites += intval($result['nb']);
		}

		$stats = array(
			'victoires' => $victoires,
			'nuls' => $nuls,
			'defaites' => $defaites,
		);


		/**
		 *
		 * DERNIERS MATCHS
		 *
		 */
		$derniers_matchs = array();

		$query = \DB::query('SELECT id_equipe1, id_equipe2, score_joueur1, score_joueur2, matchs.created_at FROM matchs
			JOIN defis ON defis.id_match = matchs.id
			WHERE id_joueur1 ='.\Auth::get('id').'
			AND match_valider1 = 1
			AND match_valider2 = 1
			ORDER BY matchs.created_at DESC
			LIMIT 3
		')->as_object('Model_Matchs')->execute();

		foreach ($query as $result){
			$derniers_matchs[] = array(
				'equipe1' => $result->equipe1,
				'equipe2' => $result->equipe2,
				'score1' => $result->score_joueur1,
				'score2' => $result->score_joueur2,
				'status' => $this->statusMatch($result->score_joueur1, $result->score_joueur2),
			);
		}

		$query = \DB::query('SELECT id_equipe1, id_equipe2, score_joueur1, score_joueur2, matchs.created_at FROM matchs
			JOIN defis ON defis.id_match = matchs.id
			WHERE id_joueur2 ='.\Auth::get('id').'
			AND match_valider1 = 1
			AND match_valider2 = 1
			ORDER BY matchs.created_at DESC
			LIMIT 3
		')->as_object('Model_Matchs')->execute();

		foreach ($query as $result){
			$derniers_matchs[] = array(
				'equipe1' => $result->equipe1,
				'equipe2' => $result->equipe2,
				'score1' => $result->score_joueur1,
				'score2' => $result->score_joueur2,
				'status' => $this->statusMatch($result->score_joueur2, $result->score_joueur1),
			);
		}

		$liste_amis = array();
		$amis = \Model_Amis::query()->where('id_user1', '=', \Auth::get('id'))->get();
		if (!empty($amis)){
			
			foreach ($amis as $am){
				$users = \Model\Auth_User::find($am->id_user2);
				$photouser = \Model_Photousers::query()->where('id_users', '=', $users->id)->get();
				(!empty($photouser)) ? $photouser = current($photouser) : $photouser = null;

				$liste_amis[] = array(
					'users' => $users,
					'photouser' => $photouser,
				);
			}
		}



        return $this->view('profil/index', array('photo_user' => $photo_user, 'liste_amis' => $liste_amis, 'stats' => $stats, 'derniers_matchs' => $derniers_matchs));
	}


	/**
	 * statusMatch
	 * indique si le match est gagné, perdu, ou nul
	 *
	 * @param int $score1
	 * @param int $score2
	 * @return String
	 */
	public function statusMatch ($score1, $score2){
		if ($score1 > $score2) return 'V';
		elseif ($score1 == $score2) return 'N';
		else return 'D';
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

		$photo_user = \Model_Photousers::query()->where('id_users', $user->id)->get();
		(!empty($photo_user)) ? $photo_user = current($photo_user) : $photo_user = null;
		

		/**
		 *
		 * VICTOIRES
		 *
		 */

		$victoires = 0;

		$query = \DB::query('SELECT COUNT(*) as nb FROM defis
			JOIN matchs ON defis.id_match = matchs.id
			WHERE id_joueur1 = '.$user->id.'
			AND score_joueur1 > score_joueur2
			AND match_valider1 = 1
			AND match_valider2 = 1
		')->execute();

		foreach ($query as $result){
			$victoires += intval($result['nb']);
		}

		$query = \DB::query('SELECT COUNT(*) as nb FROM defis
			JOIN matchs ON defis.id_match = matchs.id
			WHERE id_joueur2 = '.$user->id.'
			AND score_joueur2 > score_joueur1
			AND match_valider1 = 1
			AND match_valider2 = 1
		')->execute();


		foreach ($query as $result){
			$victoires += intval($result['nb']);
		}

		/**
		 *
		 * NULS
		 *
		 */
		$nuls = 0;

		$query = \DB::query('SELECT COUNT(*) as nb FROM defis
			JOIN matchs ON defis.id_match = matchs.id
			WHERE id_joueur2 = '.$user->id.'
			OR id_joueur1 = '.$user->id.'
			AND score_joueur2 = score_joueur1
			AND match_valider1 = 1
			AND match_valider2 = 1
		')->execute();


		foreach ($query as $result){
			$nuls += intval($result['nb']);
		}

		/**
		 *
		 * DEFAITES
		 *
		 */
		$defaites = 0;

		$query = \DB::query('SELECT COUNT(*) as nb FROM defis
			JOIN matchs ON defis.id_match = matchs.id
			WHERE id_joueur1 = '.$user->id.'
			AND score_joueur1 < score_joueur2
			AND match_valider1 = 1
			AND match_valider2 = 1
		')->execute();


		foreach ($query as $result){
			$defaites += intval($result['nb']);
		}

		$query = \DB::query('SELECT COUNT(*) as nb FROM defis
			JOIN matchs ON defis.id_match = matchs.id
			WHERE id_joueur2 = '.$user->id.'
			AND score_joueur2 < score_joueur1
			AND match_valider1 = 1
			AND match_valider2 = 1
		')->execute();


		foreach ($query as $result){
			$defaites += intval($result['nb']);
		}

		$stats = array(
			'victoires' => $victoires,
			'nuls' => $nuls,
			'defaites' => $defaites,
		);


		/**
		 *
		 * DERNIERS MATCHS
		 *
		 */
		$derniers_matchs = array();

		$query = \DB::query('SELECT id_equipe1, id_equipe2, score_joueur1, score_joueur2, matchs.created_at FROM matchs
			JOIN defis ON defis.id_match = matchs.id
			WHERE id_joueur1 ='.$user->id.'
			AND match_valider1 = 1
			AND match_valider2 = 1
			ORDER BY matchs.created_at DESC
			LIMIT 3
		')->as_object('Model_Matchs')->execute();

		foreach ($query as $result){
			$derniers_matchs[] = array(
				'equipe1' => $result->equipe1,
				'equipe2' => $result->equipe2,
				'score1' => $result->score_joueur1,
				'score2' => $result->score_joueur2,
				'status' => $this->statusMatch($result->score_joueur1, $result->score_joueur2),
			);
		}

		$query = \DB::query('SELECT id_equipe1, id_equipe2, score_joueur1, score_joueur2, matchs.created_at FROM matchs
			JOIN defis ON defis.id_match = matchs.id
			WHERE id_joueur2 ='.$user->id.'
			AND match_valider1 = 1
			AND match_valider2 = 1
			ORDER BY matchs.created_at DESC
			LIMIT 3
		')->as_object('Model_Matchs')->execute();

		foreach ($query as $result){
			$derniers_matchs[] = array(
				'equipe1' => $result->equipe1,
				'equipe2' => $result->equipe2,
				'score1' => $result->score_joueur1,
				'score2' => $result->score_joueur2,
				'status' => $this->statusMatch($result->score_joueur2, $result->score_joueur1),
			);
		}

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

		$liste_amis = array();
		// $amis = \Model_Amis::query()->where('id_user1', '=', $user->id)->get();
		$amis = \Model_Amis::find('all', array(
			'where' => array(
				array('id_user1', $user->id),
				array('valider', 1),
			),
		));
		if (!empty($amis)){
			
			foreach ($amis as $am){
				$users = \Model\Auth_User::find($am->id_user2);
				$photouser = \Model_Photousers::query()->where('id_users', '=', $users->id)->get();
				(!empty($photouser)) ? $photouser = current($photouser) : $photouser = null;

				$liste_amis[] = array(
					'users' => $users,
					'photouser' => $photouser,
				);
			}
		}

		return $this->view('profil/view', array('user' => $user, 'photo_user' => $photo_user, 'ami' => $ami, 'ami_inverse' => $ami_inverse, 'liste_amis' => $liste_amis, 'stats' => $stats, 'derniers_matchs' => $derniers_matchs));
	}
}