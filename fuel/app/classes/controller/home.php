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
        return $this->view('home/content', array('username' => 'Pippo', 'title' => 'Home'));
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