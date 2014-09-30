<?php

class Controller_Status extends \Controller_Front
{
	public function action_migrate (){
		$data = array(
			0 => array(
				'nom' => 'En attente',
				'code' => 0,
			),
			1 => array(
				'nom' => 'Accepté',
				'code' => 1,
			),
			2 => array(
				'nom' => 'Refusé',
				'code' => 2,
			),
		);

		foreach ($data as $d){
			$status = \Model_Status::query()->where('nom', '=', $d['nom']);
			if (empty($status)){
				\Model_Status::forge($d)->save();
			}
		}

		\Response::redirect_back();
	}
}