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
				$array[] = array(
					'photouser' => $this->photo($defi->defieur->id),
					'defi' => $defi,
				);
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
				$array_acp[] = array(
					'photouser' => $this->photo($acp->defieur->id),
					'defi' => $acp,	
				);
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
				$array_ref[] = array(
					'photouser' => $this->photo($ref->defieur->id),
					'defi' => $ref,
				);
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
			$array_env[] = array(
				'photouser' => $this->photo($env->defier->id),
				'defi' => $env,
			);
		}


		/** DEFIS TERMINES */

		$defis_termines = \DB::query("SELECT * FROM defis
			WHERE (id_joueur_defieur = ".\Auth::get('id')."
			OR id_joueur_defier = ".\Auth::get('id').")
			AND id_match <> 0
		")->as_object('Model_Defis')->execute();

		$array_termines = $array_avalider = array();
		foreach ($defis_termines as $ter){
			if ($ter->id_joueur_defier == \Auth::get('id') && $ter->match_valider1 == 1 && $ter->match_valider2 == 0){
				$array_avalider[] = array(
					'photouser' => $this->photo($ter->id_joueur_defieur),
					'defi' => $ter,
				);
			} else if ($ter->id_joueur_defieur == \Auth::get('id') && $ter->match_valider1 == 0 && $ter->match_valider2 == 1){
				$array_avalider[] = array(
					'photouser' => $this->photo($ter->id_joueur_defier),
					'defi' => $ter,
				);
			} else {
				$array_termines[] = array(
					'photouser' => $this->photo($ter->defier->id),
					'defi' => $ter,
				);
			}
		}//FOREACH

		/* DEFIS EN ATTENTE DE VALIDATION */
		
		
		

		return $this->view('defis/index', array('new' => $new, 'defis' => $array, 'defis_acp' => $array_acp, 'defis_avalider' => $array_avalider, 'defis_ref' => $array_ref, 'defis_lances' => $array_env, 'defis_termines' => $array_termines));
	}
}