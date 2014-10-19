<?php

class Controller_Classement extends \Controller_Front
{
	public function action_index (){
		$this->verifAutorisation();

		// try {
		// 	$array = \Cache::get('classementPlayers');
		// }
		// catch (\CacheNotFoundException $e){
			$users = \Model\Auth_User::query()->where('id', '>', 2)->get();
			$array = array();
			foreach ($users as $user){
				/**
				 * VICTOIRES
				 */
				$query = \DB::query("SELECT * FROM defis
					JOIN matchs On defis.id_match = matchs.id
					WHERE (
						id_joueur1 = ".$user->id."
						AND match_valider1 = 1
						AND match_valider2 = 1
					)
					OR (
						id_joueur2 = ".$user->id."
						AND match_valider1 = 1
						AND match_valider2 = 1
					)			
				")->execute();


				$victoires = $nuls = $defaites = $bonus = $malus = $butsm = $butse = 0;
				foreach ($query as $result){
					// DEFIEUR
					if ($result['id_joueur1'] == $user->id){
						if (intval($result['score_joueur1']) > intval($result['score_joueur2'])){
							$victoires += 1;
							if (intval($result['score_joueur1']) - intval($result['score_joueur2']) >= 3) $bonus += 1;
						}
						else if (intval($result['score_joueur1']) == intval($result['score_joueur2'])) $nuls += 1;
						else {
							$defaites += 1;
							if (intval($result['score_joueur2']) - intval($result['score_joueur1']) >= 3) $malus += 1;
						}
						$butsm += intval($result['score_joueur1']);
						$butse += intval($result['score_joueur2']);
					}
					// DEFIER
					else {
						if (intval($result['score_joueur1']) > intval($result['score_joueur2'])){
							$defaites += 1;
							if (intval($result['score_joueur1']) - intval($result['score_joueur2']) >= 3) $malus += 1;
						}
						else if (intval($result['score_joueur1']) == intval($result['score_joueur2'])) $nuls += 1;
						else {
							$victoires += 1;
							if (intval($result['score_joueur2']) - intval($result['score_joueur1']) >= 3) $bonus += 1;
						}
						$butsm += intval($result['score_joueur2']);
						$butse += intval($result['score_joueur1']);
					}
				}

				// $points = 3*$victoires + $nuls;

				// var_dump($points);die();

				$array[] = array(
					'user' => $user,
					'photo' => $this->photo($user->id),
					'points' => 3*$victoires + $nuls + $bonus - $malus,
					'victoires' => $victoires,
					'nuls' => $nuls,
					'defaites' => $defaites,
					'bonus' => $bonus,
					'malus' => $malus,
					'butsm' => $butsm,
					'butse' => $butse,
				);
			}

			$points = array();
			foreach ($array as $key => $row):
				$points[$key] = $row['points'];
				$diff[$key] = $row['butsm'] - $row['butse'];
			endforeach;

			array_multisort($points, SORT_DESC, $diff, SORT_ASC, $array);

			// \Cache::set('classementPlayers', $array);
		// }

		return $this->view('classement/index', array('users' => $array));

	}
}