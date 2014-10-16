<?php

class Controller_Defis extends \Controller_Front 
{
	public function get_api ($context){
		switch ($context){
			case 'attendre':
				if (!is_numeric(\Input::get('defis'))){
					return 'KO';
				}

				$defis = \Model_Defis::find(\Input::get('defis'));
				if (empty($defis)) return 'KO';

				$status = \Model_Status::query()->where('code', '=', 0)->get();
		
				if (!empty($status)) $status = current($status);

				$defis->status_demande = $status->id;
				$defis->save();

				return json_encode('OK');
				break;


			case 'accepter':
				if (!is_numeric(\Input::get('defis'))){
					return 'KO';
				}

				$defis = \Model_Defis::find(\Input::get('defis'));
				if (empty($defis)) return 'KO';

				$status = \Model_Status::query()->where('code', '=', 1)->get();
		
				if (!empty($status)) $status = current($status);

				$defis->status_demande = $status->id;
				$defis->save();

				$defier = \Model\Auth_User::find($defis->id_joueur_defier);

				/**
				 *
				 * NOTIFICATION
				 */
				$message = $this->modelMessage('accepteDefi', $defier->username);
				$this->newNotify($defis->id_joueur_defieur, $message);

				return json_encode('OK');
				break;

			case 'refuser':
				if (!is_numeric(\Input::get('defis'))){
					return 'KO';
				}

				$defis = \Model_Defis::find(\Input::get('defis'));
				if (empty($defis)) return 'KO';

				$status = \Model_Status::query()->where('code', '=', 2)->get();
		
				if (!empty($status)) $status = current($status);

				$defis->status_demande = $status->id;
				$defis->save();

				return json_encode('OK');
				break;

			case 'validMatch':
				if (!is_numeric(\Input::get('user')) || !is_numeric(\Input::get('match'))){
					return 'KO';
				}

				$defi = \Model_Defis::find('all', array(
					'where' => array(
						array('id_match', \Input::get('match')),
					),
				));

				if (!empty($defi)){
					$defi = current($defi);
				} else return 'KO';

				$user = \Model\Auth_User::find(\Input::get('user'));
				if (empty($user)) return 'KO';

				/**
				 * NOTIFICATIONS
				 */
				$message = $this->modelMessage('validRapport', $user->username, $defi->id_match);

				if ($user->id == $defi->id_joueur_defier){
					$defi->match_valider2 = 1;

					$this->newNotify($defi->id_joueur_defieur, $message);
				}
				elseif ($user->id == $defi->id_joueur_defieur){
					$defi->match_valider1 = 1;

					$this->newNotify($defi->id_joueur_defier, $message);
				}

				$defi->save();


				return json_encode('OK');
				break;
		}
	}

	/**
	 * Index
	 * Affiche tout les défis de l'user
	 */
	public function action_index (){
		$this->verifAutorisation();


		/* DEFIS EN ATTENTE */
		$en_cours = \Model_Status::query()->where('nom', '=', 'En attente')->get();
		if (!empty($en_cours)){
			$en_cours = current($en_cours);

			$defis = \Model_Defis::find('all', array(
				'where' => array(
					array('id_joueur_defier', \Auth::get('id')),
					array('status_demande', $en_cours->id),
				),
				'order_by' => array('updated_at'),
			));

			
			$array = array();
			foreach ($defis as $defi){
				$defieur = \Model\Auth_User::find($defi->id_joueur_defieur);
				if (!empty($defieur)){
					$photouser = \Model_Photousers::query()->where('id_users', '=', $defieur->id)->get();
					(!empty($photouser)) ? $photouser = current($photouser) : $photouser = null;


					$array[] = array(
						'defieur' => $defieur,
						'photouser' => ($photouser != null) ? $photouser->photo : null,
						'defi' => $defi,
					);
				}
			}

			$defis_new = \Model_Defis::find('all', array(
				'where' => array(
					array('id_joueur_defier', \Auth::get('id')),
					array('status_demande', $en_cours->id),
					array('updated_at', 0),
				),
			));
			(!empty($defis_new)) ? $new = count($defis_new) : $new = 0;
		}

		/* DEFIS ACCEPTE */
		$status_acp = \Model_Status::query()->where('code', '=', 1)->get();
		if (!empty($status_acp)){
			$status_acp = current($status_acp);
			$defis_accepte = \Model_Defis::find('all', array(
				'where' => array(
					array('id_joueur_defier', \Auth::get('id')),
					array('status_demande', $status_acp->id),
				),
			));

			$array_acp = array();
			foreach ($defis_accepte as $acp){
				$defieur = \Model\Auth_User::find($acp->id_joueur_defieur);
				if (!empty($defieur)){
					$photouser = \Model_Photousers::query()->where('id_users', '=', $defieur->id)->get();
					(!empty($photouser)) ? $photouser = current($photouser) : $photouser = null;

					if ($acp->id_match != 0){
						$match = \Model_Matchs::find($acp->id_match);
						if (empty($match)) break;

						$equipe1 = \Model_Equipe::find($match->id_equipe1);
						if (empty($equipe1)) break;

						$equipe2 = \Model_Equipe::find($match->id_equipe2);
						if (empty($equipe2)) break;

						$championnat1 = \Model_Championnat::find($equipe1->id_championnat);
						if (empty($championnat1)) break;

						$championnat2 = \Model_Championnat::find($equipe2->id_championnat);
						if (empty($championnat2)) break;

						$array_acp[] = array(
							'defieur' => $defieur,
							'photouser' => ($photouser != null) ? $photouser->photo : null,
							'defi' => $acp,
							'match' => $match,
							'equipe1' => $equipe1,
							'equipe2' => $equipe2,
							'championnat1' => str_replace(' ', '_', strtolower($championnat1->nom)),
							'championnat2' => str_replace(' ', '_', strtolower($championnat2->nom)),
						);
					} else {
						$array_acp[] = array(
							'defieur' => $defieur,
							'photouser' => ($photouser != null) ? $photouser->photo : null,
							'defi' => $acp,
						);
					}
					
				}
			}
		}
		

		/* DEFIS REFUSE */
		$status_ref = \Model_Status::query()->where('code', '=', 2)->get();
		if (!empty($status_ref)){
			$status_ref = current($status_ref);
			$defis_refuses = \Model_Defis::find('all', array(
				'where' => array(
					array('id_joueur_defier', \Auth::get('id')),
					array('status_demande', $status_ref->id),
				),
			));

			$array_ref = array();
			foreach ($defis_refuses as $ref){
				$defieur = \Model\Auth_User::find($ref->id_joueur_defieur);
				if (!empty($defieur)){
					$photouser = \Model_Photousers::query()->where('id_users', '=', $defieur->id)->get();
					(!empty($photouser)) ? $photouser = current($photouser) : $photouser = null;


					$array_ref[] = array(
						'defieur' => $defieur,
						'photouser' => ($photouser != null) ? $photouser->photo : null,
						'defi' => $ref,
					);
				}
			}
		}

		/** DEFIS LANCES */
		$defis_lances = \Model_Defis::find('all', array(
			'where' => array(
				array('id_joueur_defieur', \Auth::get('id')),
				array('id_match', 0),
			),
		));


			
		$array_env = array();
		foreach ($defis_lances as $env){
			$status = \Model_Status::find($env->status_demande);

			$defier = \Model\Auth_User::find($env->id_joueur_defier);
			if (!empty($defier)){
				$photouser = \Model_Photousers::query()->where('id_users', '=', $defier->id)->get();
				(!empty($photouser)) ? $photouser = current($photouser) : $photouser = null;

				$array_env[] = array(
					'defier' => $defier,
					'photouser' => ($photouser != null) ? $photouser->photo : null,
					'defi' => $env,
					'status' => $status->code,
				);
			}
		}

		/** DEFIS TERMINES */
		// $defis_termines = \Model_Defis::find('all', array(
		// 	'where' => array(
		// 		array('id_joueur_defieur', \Auth::get('id')),
		// 		array('id_match', null, \DB::expr('IS NOT NULL')),
		// 	),
		// ));

		$defis_termines = \DB::query("SELECT * FROM defis
			WHERE (id_joueur_defieur = ".\Auth::get('id')."
			OR id_joueur_defier = ".\Auth::get('id').")
			AND id_match <> 0
		")->as_object('Model_Defis')->execute();

		$array_termines = $array_avalider = array();
		foreach ($defis_termines as $ter){
			if ($ter->id_joueur_defier == \Auth::get('id') && $ter->match_valider1 == 1 && $ter->match_valider2 == 0){
				$photo = $this->photo($ter->id_joueur_defieur);
				$array_avalider[] = array(
					'photouser' => $photo,
					'defi' => $ter,
				);
			} else if ($ter->id_joueur_defieur == \Auth::get('id') && $ter->match_valider1 == 0 && $ter->match_valider2 == 1){
				$photo = $this->photo($ter->id_joueur_defier);
				$array_avalider[] = array(
					'photouser' => $photo,
					'defi' => $ter,
				);
			} else {
				$defier = \Model\Auth_User::find($ter->id_joueur_defier);
				if (!empty($defier)){
					$photouser = \Model_Photousers::query()->where('id_users', '=', $ter->defier->id)->get();
					(!empty($photouser)) ? $photouser = current($photouser) : $photouser = null;

					$array_termines[] = array(
						'photouser' => ($photouser != null) ? $photouser->photo : null,
						'defi' => $ter,
					);
				}
			}
		}//FOREACH

		/* DEFIS EN ATTENTE DE VALIDATION */
		
		
		

		return $this->view('defis/index', array('new' => $new, 'defis' => $array, 'defis_acp' => $array_acp, 'defis_avalider' => $array_avalider, 'defis_ref' => $array_ref, 'defis_lances' => $array_env, 'defis_termines' => $array_termines));
	}
}