<?php

class Controller_Pays extends Controller 
{
	public function action_index (){
		$pays = \Model_Pays::find('all');

		$view = $this->view('pays/index', array('pays' => $pays));
		return $view;
	}

	public function action_add ($id = null){
		$isUpdate = ($id !== null) ? true : false;

		if ($isUpdate){
			$pays = \Model_Pays::find($id);
			if (empty($pays)){
				\Messages::error('Le pays n\'existe pas');
				\Response::redirect('/pays');
			}
		}
		else $pays = \Model_Pays::forge();


		if (\Input::post('add')){
			$pays->nom = htmlspecialchars(\Input::post('nom'));
			$pays->drapeau = \Input::post('drapeau');
			if ($pays->save()){
				if ($isUpdate){
					\Messages::success('Pays modifié avec succès');
				}
				else {
					\Messages::success('Pays créé avec succès');
				}
			}
			else {
				\Messages::error('Une erreur est surveneue');
			}
			\Response::redirect('/pays');
		}

		$view = $this->view('pays/add', array('isUpdate' => $isUpdate, 'pays' => $pays));
		return $view;
	}


	public function view ($content, $array){
		$view = View::forge('layout');

        //local view variables, lazy rendering
        $view->head = View::forge('home/head', array('title' => 'FIFAGOAL', 'description' => 'Application de gestion et de report de matchs joués sur le jeu vidéo de football FIFA'));
        $view->header = View::forge('home/header', array('site_title' => 'FIFAGOAL'));
        $view->content = View::forge($content, $array);
        $view->footer = View::forge('home/footer', array('title' => 'FIFAGOAL'));

        // return the view object to the Request
        return $view;
	}

	/**
	 * Upload des logos avec Uploadify (POST only)
	 */
	public function post_uploadDrapeau (){
		if (!empty($_FILES)){

			$uploadConfig = array(
				'path' => DOCROOT . \Config::get('upload.pays.path'),
				'normalize' => true,
				'ext_whitelist' => array('jpg', 'jpeg', 'png', 'bmp', 'gif', 'pdf'),
			);
			
			\Upload::process($uploadConfig);

			if (\Upload::is_valid()){
				\Upload::save();
			} 

			foreach (\Upload::get_errors() as $file){
				foreach ($file['errors'] as $error){
					if ($error['error'] !== UPLOAD_ERR_NO_FILE){
						\Messages::error($error['message']);
						\Response::redirect('/pays');
					}
				}
			}

			foreach (\Upload::get_files() as $file){
				return $file['saved_as'];
			}
		}
	}

	public function action_delete ($id){
		$pays = \Model_Pays::find($id);
		if (empty($pays)){
			\Messages::error('Ce pays n\'existe pas');
			\Response::redirect('/pays');
		}

		$fichier = DOCROOT . \Config::get('upload.pays.path') .'/'. $pays->drapeau;
		if (file_exists($fichier)) unlink($fichier);

		if ($pays->delete()){
			\Messages::success('Pays supprimé avec succès');
		}
		else {
			\Messages::error('Une erreur est survenue lors de la suppression du pays');
		}

		\Response::redirect('/pays');
	}
}