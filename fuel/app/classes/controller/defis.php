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


					$array_acp[] = array(
						'defieur' => $defieur,
						'photouser' => ($photouser != null) ? $photouser->photo : null,
						'defi' => $acp,
					);
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
		

		return $this->view('defis/index', array('new' => $new, 'defis' => $array, 'defis_acp' => $array_acp, 'defis_ref' => $array_ref));
	}
}