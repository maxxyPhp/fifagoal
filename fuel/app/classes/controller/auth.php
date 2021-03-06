<?php

/**
 * Auth
 * Gère les connexions/inscriptions
 */
class Controller_Auth extends \Controller_Front
{
	public function action_index (){

		// Already logged in
		if (\Auth::check())
		{
			\Response::redirect_back();
		}

		if (\Input::post('login')){
			if (\Auth::instance()->login($this->secure(\Input::param('username')), $this->secure(\Input::param('password')))){
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

	/**
	 * Signin
	 * Inscription d'un user
	 */
	public function action_signin (){
		if (\Input::post('register')){
			if (!filter_var(\Input::post('email'), FILTER_VALIDATE_EMAIL) || ($this->secure(\Input::post('password')) != $this->secure(\Input::post('confirm'))) || !$this->testDate(\Input::post('naissance'))){
				\Response::redirect('/auth/signin');
			}

			try {
				$date = DateTime::createFromFormat('d/m/Y', \Input::post('naissance'));
				$timestamp = $date->getTimestamp();
				
				$created = \Auth::create_user(htmlspecialchars($this->secure(\Input::post('username'))), $this->secure(\Input::post('password')), \Input::post('email'), 3, array('naissance' => $timestamp));

				if ($created){
					/* Notification */
					$this->newNotify($created, $this->modelMessage('bienvenue', $this->secure(\Input::post('username'))));
					/* Connexion */
					\Auth::login($this->secure(\Input::post('username')), $this->secure(\Input::post('password')));
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
        $view->head = View::forge('home/head', array('title' => 'FIFAGOAL', 'description' => 'Application de gestion et de report de matchs joués sur le jeu vidéo de football FIFA', 'notifs' => 0));
        $view->header = View::forge('home/header', array('site_title' => 'FIFAGOAL'));
        $view->content = View::forge('auth/signin');
        $view->footer = View::forge('home/footer', array('title' => 'FIFAGOAL'));


        return $view;
	}

	/**
	 * Logout
	 * Déconnecte un user
	 */
	public function action_logout (){
		\Auth::dont_remember_me();
		\Auth::logout();
		\Messages::success('Déconnecté');
		\Response::redirect('/');
	}

	/**
	 * testDate
	 * Teste la validité d'une date
	 *
	 * @param String $value
	 * @return Boolean 
	 */
	function testDate($value){
  		return preg_match('#^([0-9]{2})([/-])([0-9]{2})\2([0-9]{4})$#', $value);
 	}
}