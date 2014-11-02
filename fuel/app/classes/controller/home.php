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

			$arr_but = array();
			$query = \DB::query("SELECT COUNT(*) AS nb, joueurs.id, joueurs.nom as nomj, prenom, photo, equipes.nom as nome, championnat.nom as nomc FROM buteurs
				JOIN joueurs ON joueurs.id = buteurs.id_joueur
				JOIN equipes ON equipes.id = joueurs.id_equipe
				JOIN championnat ON championnat.id = equipes.id_championnat
				GROUP BY id_joueur
				ORDER BY nb desc
				LIMIT 5
			")->as_object('Model_Joueur')->execute();

			foreach ($query as $result){
				$arr_but[] = $result;
			}

			

			if (date('d/m') == date('d/m', \Auth::get('naissance'))){
				$this->newNotify(\Auth::get('id'), $this->modelMessage('birthday', ''));
			}
			
        	return $this->view('home/content', array('photo' => $photouser, 'matchs' => $array, 'buteurs' => $arr_but));
        } else {
        	$view = View::forge('layout_default');

	        $view->head = View::forge('home/head', array('title' => \Config::get('application.title'), 'description' => \Config::get('application.description'), 'notifs' => 0));
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