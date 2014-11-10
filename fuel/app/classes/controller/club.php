<?php 

class Controller_Club extends \Controller_Front {
	/**
	 * Index
	 * Voir tout les clubs
	 * Redirige vers les ligues, car trop de club a afficher
	 */
	public function action_index (){
		\Response::redirect('/ligue');
	}

	/**
	 * View
	 * Voir les joueurs d'une équipe
	 *
	 * @param int $id
	 */
	public function action_view ($id){
		$equipe = \Model_Equipe::find($id);
		if (empty($equipe)){
			\Messages::error('Ce club n\'existe pas');
			\Response::redirect('/ligue');
		}

		if ($equipe->actif == 0){
			\Response::redirect('/ligue');
		}

		return $this->view('club/view', array('equipe' => $equipe));
	}
}