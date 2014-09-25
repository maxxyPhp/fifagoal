<?php

class Controller_Profil extends Controller
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

		// create the layout view
        $view = View::forge('layout');

        //local view variables, lazy rendering
        $view->head = View::forge('home/head', array('title' => 'FIFAGOAL', 'description' => 'Application de gestion et de report de matchs joués sur le jeu vidéo de football FIFA'));
        $view->header = View::forge('home/header', array('site_title' => 'FIFAGOAL'));
        $view->content = View::forge('profil/index', array('photo_user' => $photo_user));
        $view->footer = View::forge('home/footer', array('title' => 'FIFAGOAL'));


        return $view;
	}
}