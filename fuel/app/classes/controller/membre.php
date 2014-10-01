<?php 

class Controller_Membre extends \Controller_Front
{
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
			$status = \Model_Status::query()->where('code', '=', 0)->get();
			if (!empty($status)) $status = current($status);

			$defis = \Model_Defis::find('all', array(
				'where' => array(
					array('id_joueur_defieur', \Auth::get('id')),
					array('id_joueur_defier', $user->id),
					array('status_demande', $status->id),
				),
			));

			$photouser = \Model_Photousers::query()->where('id_users', '=', $user->id)->get();
			if (!empty($photouser)){
				$photouser = current($photouser);

				$array[] = array(
					'id' => $user->id,
					'username' => $user->username,
					'photo' => $photouser->photo,
					'defis' => (!empty($defis)) ? 1 : 0,
				);
			}
			else {
				$array[] = array(
					'id' => $user->id,
					'username' => $user->username,
					'photo' => null,
					'defis' => (!empty($defis)) ? 1 : 0,
				);
			}
			
		}

		return $this->view('membre/index', array('users' => $array));
	}
}