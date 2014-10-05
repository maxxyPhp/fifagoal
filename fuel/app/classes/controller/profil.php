<?php

class Controller_Profil extends Controller_Front
{
	public function action_index (){
		if (!\Auth::check())
		{
			\Response::redirect_back('/');
		}

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



        return $this->view('profil/index', array('photo_user' => $photo_user, 'stats' => $stats, 'derniers_matchs' => $derniers_matchs));
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
}