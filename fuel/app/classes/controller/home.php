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
			/**
			 *
			 * PHOTO
			 *
			 */
			$photouser = $this->photo(\Auth::get('id'));

			/**
			 *
			 * DERNIERS MATCHS
			 *
			 */
			$matchs = \Model_Matchs::query()->order_by('created_at', 'desc')->limit(10)->get();
			
			$array = array();
			foreach ($matchs as $match){
				if ($match->defi->match_valider1 == 1 && $match->defi->match_valider2 == 1){
					$array[] = array(
						'match' => $match,
						'photouser1' => $this->photo($match->defi->defieur->id),
						'photouser2' => $this->photo($match->defi->defier->id),
					);
				}
			}

			
        	return $this->view('home/content', array('photo' => $photouser, 'matchs' => $array));
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