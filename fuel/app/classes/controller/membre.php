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
			$photouser = \Model_Photousers::query()->where('id_users', '=', $user->id)->get();
			if (!empty($photouser)){
				$photouser = current($photouser);

				$array[] = array(
					'id' => $user->id,
					'username' => $user->username,
					'photo' => $photouser->photo,
				);
			}
			else {
				$array[] = array(
					'id' => $user->id,
					'username' => $user->username,
					'photo' => null,
				);
			}
			
		}

		return $this->view('membre/index', array('users' => $array));
	}
}