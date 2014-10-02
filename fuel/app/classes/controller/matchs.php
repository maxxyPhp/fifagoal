<?php 

class Controller_Matchs extends \Controller_Front
{
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
		}
	}

	public function action_index (){
		//return $this->view('matchs/index', array('users' => $array));
	}

	public function action_add (){
		$this->verifAutorisation();

		if (\Input::post('add')){
			// METTRE ID DEFI DANS FORM
			// VERIFIER ID JOUERS AVEC CEUX DU DEFI
			if (!is_numeric(\Input::post('joueur1')) || !is_numeric(\Input::post('joueur2')) || !is_numeric(\Input::post('defi'))){
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

}