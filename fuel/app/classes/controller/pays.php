<?php

class Controller_Pays extends Controller 
{
	public function action_index (){
		$this->verifAutorisation();

		try {
			$pays = \Cache::get('listPays');
		}
		catch (\CacheNotFoundException $e){
			$pays = \Model_Pays::find('all');
			if (!empty($pays)) \Cache::set('listPays', $pays);
		}
		
		$view = $this->view('pays/index', array('pays' => $pays));
		return $view;
	}

	public function action_add ($id = null){
		$this->verifAutorisation();

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

			\Cache::delete('listPays');
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
		$this->verifAutorisation();

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

		\Cache::delete('listPays');
		\Response::redirect('/pays');
	}

	public function action_import (){
		$this->verifAutorisation();

		if (\Input::method() == 'POST' || \Input::get('current')){
			if (\Input::method() == 'POST'){
				$file = \Input::file('file');
				$name = $this->processUploadCSV($file);
				if (empty($name)){
					\Messages::error('Pas de fichier uploadé');
					\Response::redirect('/pays');
				}

				$fichier = DOCROOT . \Config::get('upload.tmp.path') . '/' . $name;
				if (file_exists($fichier)) $file_content = \File::read($fichier, true);
				$current_line = 0;
			}
			else {
				$name = \Input::get('name');
				$file_content = \File::read(DOCROOT . \Config::get('upload.tmp.path') . '/' . $name, true);
				$current_line = \Input::get('current_line');
			}

			// Conversion CSV vers PHP
			$donnees = \Format::forge($file_content, 'csv')->to_array();

			// Nombre de logne du fichier
			$number_of_line = count($donnees);
			$line = 0;

			//Fetch les lignes
			for ($i = $current_line; $i < $number_of_line; $i++){
				$data = $donnees[$i];

				$pays;

				$pays = \Model_Pays::query()->where('nom', '=', $data['Nom'])->get();


				if (empty($pays)){
					$pays = \Model_Pays::forge();
					$pays->nom = $data['Nom'];
					$pays->drapeau = str_replace(' ', '', $data['Nom']) . '.png';
					$pays->save();

					try {
						$drapeau = file_get_contents($data['Drapeau'], FILE_USE_INCLUDE_PATH);
					}
					catch (PhpErrorException $e){
						\Messages::error($e->getMessage());
					}

					//Détermination du nom du fichier et de son chemin d'accès
					file_exists(DOCROOT . \Config::get('upload.pays.path')) or \File::create_dir(DOCROOT . \Config::get('upload.pays.path'), 'pays');

					$nom_drapeau = DOCROOT . \Config::get('upload.pays.path') . DS . str_replace(' ', '', $data['Nom'] . '.png');

					// Création de l'image
					$fp = fopen($nom_drapeau, 'w+');
					fwrite($fp, $drapeau);
					fclose($fp);
				}

				// Quand on a interprété 20 ligne, raffraichissement de la page pour éviter erreur TimeExecution
				if ($line >= 20){
					echo "Chargement en cours ...";
					\Response::redirect('/pays/import?current=true&name='.$name.'&current_line='.$i, 'refresh');
				}

				$line++;
			}//FOR

			//Suppression fichier CSV
			$fichier = DOCROOT . \Config::get('upload.tmp.path') . DS . $name;
			if (file_exists($fichier)) unlink($fichier);

			\Messages::success('Import terminé avec succès');
			\Cache::delete('listPays');
			\Response::redirect('/pays');
		}//IF POST

		$view = $this->view('pays/import', array());
		return $view;
	}

	/**
	 * processUploadCSV
	 * Upload des fichiers CSV pour l'import de données dans A/R
	 *
	 * @param String $file
	 */
	public function processUploadCSV ($file){
		$uploadConfig = array(
			'path' => DOCROOT . \Config::get('upload.tmp.path'),
			'normalize' => true,
			'ext_whitelist' => array('csv'),
		);
		
		\Upload::process($uploadConfig);

		
		if (\Upload::is_valid()){
			\Upload::save();
		}


		foreach (\Upload::get_errors() as $file){
			foreach ($file['errors'] as $error){
				if ($error['error'] !==  UPLOAD_ERR_NO_FILE){
					\Messages::error($error['message']);
					\Response::redirect('/pays');
				}
			}
		}

		foreach (\Upload::get_files() as $file){
			return $file['saved_as'];
		}
	}

	/**
	 * Verif Autorisation
	 * Vérifie que l'utilisateur est connecté et est un admin
	 */
	public function verifAutorisation (){
		if (!\Auth::check() || !\Auth::member(6)){
			\Response::redirect('/');
		}
	}
}