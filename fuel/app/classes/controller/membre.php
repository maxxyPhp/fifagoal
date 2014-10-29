
<?php 

class Controller_Membre extends \Controller_Front
{
	public function get_api ($context){
		switch ($context){
			case 'addFriend':
				if (!is_numeric(\Input::get('user'))){
					return 'KO';
				}

				// USER INVITE A DEVENIR AMI
				$user = \Model\Auth_User::find(\Input::get('user'));
				if (empty($user)) return 'KO';

				$ami = \Model_Amis::forge();
				$ami->id_user1 = \Auth::get('id');
				$ami->id_user2 = $user->id;
				$ami->valider = 0;
				
				if ($ami->save()){
					$this->newNotify($user->id, $this->modelMessage('addFriend', \Auth::get('username'), \Auth::get('id')));
					return json_encode('OK');
				}

				break;

			case 'validFriend':
				if (!is_numeric(\Input::get('user'))){
					return 'KO';
				}

				// USER DEMANDANT A ETRE AMI
				$user = \Model\Auth_User::find(\Input::get('user'));
				if (empty($user)) return 'KO';

				$photouser = \Model_Photousers::query()->where('id_users', '=', \Auth::get('id'))->get();
				(!empty($photouser)) ? $photouser = current($photouser) : $photouser = null;

				$ami = \Model_Amis::find('all', array(
					'where' => array(
						array('id_user1', $user->id),
						array('id_user2', \Auth::get('id')),
					),
				));

				if (!empty($ami)){
					$ami = current($ami);
				} else return 'KO';

				$ami->valider = 1;
				$ami->save();

				$ami_inverse = \Model_Amis::forge();
				$ami_inverse->id_user1 = \Auth::get('id');
				$ami_inverse->id_user2 = $user->id;
				$ami_inverse->valider = 1;

				if ($ami_inverse->save()){
					$this->newNotify($user->id, $this->modelMessage('validFriend', \Auth::get('username'), \Auth::get('id')));
					$photouser = $this->object_to_array($photouser);
					return json_encode($photouser);
				}

				break;
		}
	}

	/**
	 * Index
	 * Affiche la liste des membres
	 */
	public function action_index (){
		$users = \Model\Auth_User::find('all', array(
			'where' => array(
				array('id', '!=', 0),
				array('id', '!=', 1),
				array('id', '!=', \Auth::get('id')),
			),
		));

		$array = array();
		foreach ($users as $user){
			$derniers_matchs = '';
			$stat1 = \DB::query("SELECT * FROM matchs WHERE id_joueur1 = ".$user->id." OR id_joueur2 = ".$user->id." ORDER BY updated_at LIMIT 5")->as_object('Model_Matchs')->execute();
			foreach ($stat1 as $result){
				$derniers_matchs .= $this->derniersMatchs ($result, $user);
			}

			$status = \Model_Status::query()->where('code', '=', 0)->get();
			if (!empty($status)) $status = current($status);

			$defis = \Model_Defis::find('all', array(
				'where' => array(
					array('id_joueur_defieur', \Auth::get('id')),
					array('id_joueur_defier', $user->id),
					array('status_demande', $status->id),
				),
			));

			$array[] = array(
				'user' => $user,
				'photo' => $this->photo($user->id),
				'derniers_matchs' => $derniers_matchs,
				'defis' => (!empty($defis)) ? 1 : 0,
			);

			
		}

		return $this->view('membre/index', array('users' => $array));
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
}