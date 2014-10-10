<?php

class Controller_Home extends Controller_Front
{

	/**
	 * The home page
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_index()
	{
		if (\Auth::check()){
        	return $this->view('home/content', array('username' => 'Pippo', 'title' => 'Home'));
        } else {
        	$view = View::forge('layout_default');

	        $view->head = View::forge('home/head', array('title' => \Config::get('application.title'), 'description' => \Config::get('application.description')));
	   		$view->content = View::forge('home/default', array('title' => \Config::get('application.title')));
	   		$view->footer = View::forge('home/footer', array('title' => \Config::get('application.title')));
	        return $view;
        }
	}

	/**
	 * The 404 action for the application.
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_404()
	{
		return $this->view('home/404', array());
	}
}