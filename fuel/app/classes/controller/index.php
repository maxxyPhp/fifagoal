<?php

class Controller_Index extends Controller {
	public function action_index (){
		return Response::forge(View::forge('index/index'));
	}

	public function get_api($context = ''){
        switch ($context){
            case 'sendMail':
            	$mail = \Input::get('email');
            	$sujet = \Input::get('sujet');
            	$message = \Input::get('message');
            	var_dump($mail);
            	var_dump($sujet);
            	var_dump($message);
            	die();
            	return $this->response($param);

            break;
        }
    }
}