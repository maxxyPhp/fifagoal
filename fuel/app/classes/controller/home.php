<?php

class Controller_Home extends Controller
{
	public $template = '_layout/template';
	public $themeOverride = 'home';

	// public function before() {
 //        // Set template
 //        $this->theme = \Theme::instance();
 //    }

	/**
	 * The basic welcome message
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_index()
	{
		// create the layout view
        $view = View::forge('layout');

        //local view variables, lazy rendering
        $view->head = View::forge('home/head', array('title' => 'FIFAGOAL', 'description' => 'Application de gestion et de report de matchs joués sur le jeu vidéo de football FIFA'));
        $view->header = View::forge('home/header', array('site_title' => 'FIFAGOAL'));
        $view->content = View::forge('home/content', array('username' => 'Pippo', 'title' => 'Home'));
        $view->footer = View::forge('home/footer', array('title' => 'FIFAGOAL'));

        // return the view object to the Request
        return $view;
	}

	/**
	 * The 404 action for the application.
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_404()
	{
		return Response::forge(ViewModel::forge('welcome/404'), 404);
	}
}