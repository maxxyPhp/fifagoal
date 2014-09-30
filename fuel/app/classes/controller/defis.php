<?php

class Controller_Defis extends \Controller_Front 
{
	public function action_index (){
		$en_cours = \Model_Status::query()->where('nom', '=', 'En attente')->get();
		if (!empty($en_cours)){
			$en_cours = current($en_cours);

			$defis = \Model_Defis::find('all', array(
				'where' => array(
					array('id_joueur_defier', \Auth::get('id')),
					array('status_demande', $en_cours->code),
				),
			));

			if (!empty($defis)) $defis = current($defis);

			foreach ($defis as $defi){
				//RECUP USER DEFIEUR, PHOTO,
			}
		}

		return $this->view('defis/index', array('defis' => $defis));
	}
}