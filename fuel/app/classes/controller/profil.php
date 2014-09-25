<?php

class Controller_Profil extends Controller
{
	public function action_index (){
		if (!\Auth::check())
		{
			\Response::redirect_back('/');
		}

		// create the layout view
        $view = View::forge('layout');

        //local view variables, lazy rendering
        $view->head = View::forge('home/head', array('title' => 'FIFAGOAL', 'description' => 'Application de gestion et de report de matchs joués sur le jeu vidéo de football FIFA'));
        $view->header = View::forge('home/header', array('site_title' => 'FIFAGOAL'));
        $view->content = View::forge('profil/index');
        $view->footer = View::forge('home/footer', array('title' => 'FIFAGOAL'));


        return $view;
	}
}