<?php 

class Controller_Matchs extends \Controller_Front
{
	public function get_api ($context){
		switch ($context){
			case 'defier':
				if (!is_numeric(\Input::get('defier'))){
					return 'KO';
				}

				$defier = \Model\Auth_User::find(\Input::get('defier'));
				if (empty($defier)) return 'KO';

				$status = \Model_Status::query()->where('nom', '=', 'En attente')->get();
				if (!empty($status)){
					$status = current($status);
				} else return 'KO';

				$defis = \Model_Defis::forge();
				$defis->id_joueur_defieur = \Auth::get('id');
				$defis->id_joueur_defier = $defier->id;
				$defis->status_demande = $status->code;
				$defis->save();

				return json_encode('OK');
				break;
		}
	}

	public function action_index (){
		return $this->view('matchs/index', array('users' => $array));
	}

}