<?php

class Controller_Auth extends \Controller
{
	public function action_index (){
		 $this->login();
	}

	public function login (){
		// Already logged in
		if (\Auth::check())
		{
			var_dump('check');die();
			\Response::redirect_back();
		}

		\Config::load('opauth');
		$this->data['oauthList'] = \Config::get('Stategy');
		\Config::load('config');

		/**
		 * Generate login form
		 */
		$login = \Fieldset::forge('loginform', array('form_attributes' => array('class' => 'form-horizontal')));
		$login->form()->add_csrf();
		$login->add_model('Model\\Auth_User');

		//We only need username and password
		$login->disable('group_id')->disable('email');

		//Remember me checkbox
		$login->add('remember_me', __('login.form.remember_me'), array('type' => 'checkbox', 'value' => true));
		$login->add('login', '', array('type' => 'submit', 'value' => __('login.form.login'), 'class' => 'btn btn-primary'));

		$data['login_form'] = $login;

		// var_dump($login);die();
		$view = View::forge('layout');

        $view->head = View::forge('home/head', array('title' => 'FIFAGOAL', 'description' => 'Application de gestion et de report de matchs joués sur le jeu vidéo de football FIFA'));
        $view->header = View::forge('home/header', array('site_title' => 'FIFAGOAL'));
        $view->content = View::forge('auth/login', array('data' => $data));
        $view->footer = View::forge('home/footer', array('site_title' => 'FIFAGOAL'));
        // var_dump($view);die();
        // return the view object to the Request
        return $view->render();

		// $this->theme->set_partial('content', 'auth/login')->set($this->data, null, false);
	}
}