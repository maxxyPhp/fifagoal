<?php

class Controller_Auth extends \Controller_Front
{
	public function action_index (){

		// Already logged in
		if (\Auth::check())
		{
			\Response::redirect_back();
		}

		if (\Input::post('login')){
			if (\Auth::instance()->login(\Input::param('username'), \Input::param('password'))){
				if (\Input::post('remember_me', false)){
					\Auth::remember_me();
				}
				else {
					\Auth::dont_remember_me();
				}

				/**
				 *
				 * NETTOYAGE NOTIFICATIONS
				 *
				 */
				$notifys = \Model_Notify::query()->where('id_user', '=', \Auth::get('id'))->order_by('created_at', 'desc')->get();
				$i = 1;
				foreach ($notifys as $notify){
					if ($i > 5) $notify->delete();
					$i++;
				}

				\Response::redirect('/');
			}
			else {
				\Messages::error(__('login.failure'));
			}

		}

		// create the layout view
        $view = View::forge('layout');

        //local view variables, lazy rendering
        $view->head = View::forge('home/head', array('title' => 'FIFAGOAL', 'description' => 'Application de gestion et de report de matchs joués sur le jeu vidéo de football FIFA'));
        $view->header = View::forge('home/header', array('site_title' => 'FIFAGOAL'));
        $view->content = View::forge('auth/login');
        $view->footer = View::forge('home/footer', array('title' => 'FIFAGOAL'));


        return $view;

	}

	public function action_signin (){
		if (\Input::post('register')){
			if (!filter_var(\Input::post('email'), FILTER_VALIDATE_EMAIL) || (\Input::post('password') != \Input::post('confirm'))){
				\Response::redirect('/auth/signin');
			}
			try {
				$created = \Auth::create_user(htmlspecialchars(\Input::post('username')), \Input::post('password'), \Input::post('email'), 3, array('fullname' => htmlspecialchars(\Input::post('fullname')), 'naissance' => \Input::post('naissance')));

				if ($created){
					/* Notification */
					$this->newNotify($created, $this->modelMessage('bienvenue', \Input::post('username')));
					/* Connexion */
					\Auth::login(\Input::post('username'), \Input::post('password'));
					\Response::redirect('/');
				}
				else {
					\Messages::error('Une erreur est survenue lors de la création du compte');
					\Response::redirect('/');
				}
			}
			catch (\SimpleUserUpdateException $e){
				if ($e->getCode() == 2){
					\Messages::error('Le mail est invalide');
					\Response::redirect_back();
				}

				elseif ($e->getCode() == 3){
					\Messages::error('Le pseudo est déjà pris');
					\Response::redirect_back();
				}

				else {
					\Messages::error($e->getMessage());
				}
			}
		}

        $view = View::forge('layout');

        //local view variables, lazy rendering
        $view->head = View::forge('home/head', array('title' => 'FIFAGOAL', 'description' => 'Application de gestion et de report de matchs joués sur le jeu vidéo de football FIFA'));
        $view->header = View::forge('home/header', array('site_title' => 'FIFAGOAL'));
        $view->content = View::forge('auth/signin');
        $view->footer = View::forge('home/footer', array('title' => 'FIFAGOAL'));


        return $view;
	}

	public function action_logout (){
		\Auth::dont_remember_me();
		\Auth::logout();
		\Messages::success('Déconnecté');
		\Response::redirect('/');
	}
}