<?php

class Controller_Users extends Controller
{
	public function action_index (){
		if (!\Auth::check() || !\Auth::member(100)){
			\Response::redirect('/');
		}

		$users = \Auth\Model\Auth_User::find('all');
		// var_dump($users);die();

		$view = \View::forge('layout');

		$view->head = \View::forge('home/head', array('title' => 'FIFAGOAL', 'description' => 'Application de gestion et de report de matchs joués sur le jeu vidéo de football FIFA'));
		$view->header = \View::forge('home/header', array('site_title' => 'FIFAGOAL'));
		$view->content = \View::forge('users/index', array('users' => $users));
		$view->footer = \View::forge('home/footer', array('title' => 'FIFAGOAL'));

		return $view;
	}

	public function action_admin (){
		$id = $this->param('id');
		
		$user = \Auth\Model\Auth_User::find($id);
		if (empty($user)){
			\Messages::error('User inexistant');
			\Response::redirect_back();
		}

		$user->group_id = 6;

		if ($user->save()){
			// \Messages($user->username.' devient admin');
			\Response::redirect('/users');
		}
		else {
			// \Messages::error('Une erreur est survenue');
			\Response::redirect_back();
		}
	}
}