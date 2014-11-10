<?php

class Controller_Ligue extends \Controller_Front
{
	public function action_index (){
		$championnats = \Model_Championnat::query()->where('actif', '=', 1)->get();

		return $this->view('ligue/index', array('championnats' => $championnats));
	}

	public function action_view ($id){
		$championnat = \Model_Championnat::find($id);

		if (empty($championnat)){
			\Messages::error('Cette ligue n\'existe pas');
			\Response::redirect('/');
		}

		if ($championnat->actif == 0){
			\Response::redirect('/');
		}

		return $this->view('ligue/view', array('championnat' => $championnat));
	}
}