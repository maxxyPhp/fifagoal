<?php

class Controller_Search extends \Controller_Front {
	public function action_index (){
		$search = htmlspecialchars($this->wd_remove_accents(\Input::post('search')));

		(!empty($search)) ? \Session::set('recherche', $search) : $search = \Session::get('recherche');

		$requetes = explode(' ', $search);
		// var_dump($requetes);die();

		$tusers = $tchampionnats = $tequipes = $tjoueurs = $tselections = array();
		foreach ($requetes as $requete){
			$users = \Model\Auth_User::query()->where('username', 'like', '%'.$requete.'%')->get();

			$championnats = \Model_Championnat::query()->where('nom', 'like', '%'.$requete.'%')->get();

			$equipes = \Model_Equipe::query()->where('nom', 'like', '%'.$requete.'%')->get();

			$joueurs = \Model_Joueur::query()
				->or_where_open()
					->or_where('nom', 'like', '%'.$requete.'%')
					->or_where('prenom', 'like', '%'.$requete.'%')
				->or_where_close()
				->get();

			$selections = \Model_Selection::query()->where('nom', 'like', '%'.$requete.'%')->get();

			foreach ($users as $user){
				if (preg_match('/'.$requete.'/i', $user->username)) $tusers[] = $user;
			}

			foreach ($championnats as $champ){
				if (preg_match('/'.$requete.'/i', $champ->nom)) $tchampionnats[] = $champ;
			}

			foreach ($equipes as $equipe){
				if (preg_match('/'.$requete.'/i', $equipe->nom)) $tequipes[] = $equipe;
			}

			foreach ($joueurs as $joueur){
				if (preg_match('/'.$requete.'/i', $joueur->nom)) $tjoueurs[] = $joueur;
				else if (preg_match('/'.$requete.'/i', $joueur->prenom)) $tjoueurs[] = $joueur;
			}

			foreach ($selections as $selection){
				if (preg_match('/'.$requete.'/i', $selection->nom)) $tselections[] = $selection;
			}
		}

		return $this->view('search/index', array('users' => array_unique($tusers, SORT_REGULAR), 'championnats' => array_unique($tchampionnats, SORT_REGULAR), 'equipes' => array_unique($tequipes, SORT_REGULAR), 'joueurs' => array_unique($tjoueurs, SORT_REGULAR), 'selections' => array_unique($tselections, SORT_REGULAR)));
	}



	/**
	 * wd_remove_accents
	 * Remplace les caractères accentués par ces semblables non accentués
	 *
	 * @param String $str
	 * @return String $str
	 */
	function wd_remove_accents($str, $charset='utf-8'){
	    $str = htmlentities($str, ENT_NOQUOTES, $charset);
	    
	    $str = preg_replace('#&([A-za-z])(?:acute|cedil|caron|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $str);
	    $str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str); // pour les ligatures e.g. '&oelig;'
	    $str = preg_replace('#&[^;]+;#', '', $str); // supprime les autres caractères
	    
	    return $str;
	}
}