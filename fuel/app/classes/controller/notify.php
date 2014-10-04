<?php

class Controller_Notify extends \Controller_Front {
	public function get_api ($context){
		switch ($context){
			case 'viewNotify':
				if (!is_numeric(\Input::get('user'))){
					return 'KO';
				}

				$user = \Model\Auth_User::find(\Input::get('user'));
				if (empty($user)) return 'KO';

				$notifys = \Model_Notify::query()->where('id_user', '=', $user->id)->get();
				if (!empty($notifys)){
					foreach ($notifys as $notify){
						$notify->new = 0;
						$notify->save();
					}
				}

				return json_encode('OK');
				break;
		}
	}
}