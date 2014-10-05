<?php

class Controller_Users extends Controller_Front
{
	public function get_api ($context){
		switch ($context){
			case 'verifyUsername':
				$user = \Auth\Model\Auth_User::find('all', array(
					'where' => array(
						array('username', htmlspecialchars(\Input::get('username'))),
					),
				));

				if (empty($user)){
					return "false";
				}
				else {
					return "true";
				}
				break;

			case 'verifyName':
				$name = htmlspecialchars(\Input::get('name'));
				if (ctype_alpha($name)){
					return "true";
				} else return "false";
				break;
		}
	}

	/**
	 * Index
	 * Affiche la liste des users
	 */
	public function action_index (){
		$this->verifAutorisationAdmin();

		$users = \Auth\Model\Auth_User::find('all');

		return $this->view('users/index', array('users' => $users));
	}

	/**
	 * Admin
	 * Transforme un user en admin
	 */
	public function action_admin (){
		$this->verifAutorisationAdmin();
		$id = $this->param('id');
		
		$user = \Auth\Model\Auth_User::find($id);
		if (empty($user)){
			\Messages::error('User inexistant');
			\Response::redirect_back();
		}

		$user->group_id = 6;

		if ($user->save()){
			$this->newNotify($user->id, $this->modelMessage('admin', \Auth::get('username')));

			\Messages($user->username.' devient admin');
			\Response::redirect('/users');
		}
		else {
			\Messages::error('Une erreur est survenue');
			\Response::redirect_back();
		}
	}

	/**
	 * Delete
	 * Supprime un user
	 */
	public function action_delete (){
		$id = $this->param('id');

		$user = \Auth\Model\Auth_User::find($id);
		if (empty($user)){
			\Response::redirect_back();
		}

		if (\Auth::delete_user($user->username)){
			\Response::redirect('/users');
		}
		else {
			\Response::redirect('/');
		}
	}

	public function action_change (){
		$this->verifAutorisation();
		$id = $this->param('id');

		if (\Input::post('changer')){
			if (htmlspecialchars(\Input::post('newpass')) == htmlspecialchars(\Input::post('confirmnewpass'))){
				if (\Auth::change_password(\Input::post('oldpass'), htmlspecialchars(\Input::post('newpass')))){
					\Messages::success('Mot de passe changé avec succès');
					\Response::redirect('/profil');
				} 
				else {
					\Messages::error('Une erreur a empêché le changement de mot de passe');
					\Response::redirect_back();
				}
			}
		}

		return $this->view('users/change', array());
	}

	/**
	 * Upload des logos avec Uploadify (POST only)
	 */
	public function post_uploadPhoto (){
		if (!empty($_FILES)){
			// Recherche si déjà une photo de profil
			$photo_user = \Model_Photousers::find('all', array(
				'where' => array(
					array('id_users', \Auth::get('id')),
				),
			));

			// Si oui, on supprime l'ancienne
			if (!empty($photo_user)){
				$photo_user = current($photo_user);
				$fichier = DOCROOT . \Config::get('users.photo.path') . DS . $photo_user->photo;
				if (file_exists($fichier)) unlink($fichier);
			}
			// Sinon on en crée une nouvelle
			else {
				$photo_user = \Model_Photousers::forge();
			}

			$uploadConfig = array(
				'path' => DOCROOT . \Config::get('users.photo.path'),
				'normalize' => true,
				'ext_whitelist' => array('jpg', 'jpeg', 'png', 'bmp', 'gif', 'pdf'),
				'new_name' => \Auth::get('username'),
			);
			
			\Upload::process($uploadConfig);

			if (\Upload::is_valid()){
				\Upload::save();
			} 

			foreach (\Upload::get_errors() as $file){
				foreach ($file['errors'] as $error){
					if ($error['error'] !== UPLOAD_ERR_NO_FILE){
						\Messages::error($error['message']);
						\Response::redirect('/profil');
					}
				}
			}

			foreach (\Upload::get_files() as $file){
				$photo_user->photo = $file['saved_as'];
				$photo_user->id_users = \Auth::get('id');
				$photo_user->save();
				return $file['saved_as'];
			}
		}
	}
}