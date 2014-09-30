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


        return $this->view('profil/index', array('photo_user' => $photo_user));
	}
}