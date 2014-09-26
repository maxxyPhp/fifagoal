<?php

class Controller_Selection extends \Controller
{
	/**
	 * Index
	 * Liste les équipes
	 */
	public function action_index (){
		$this->verifAutorisation();
		
		$selections = \Model_Selection::find('all');

		$view = $this->view('selection/index', array('selections' => $selections));
		return $view;
	}

	/**
	 * View
	 * Prépare la vue à afficher
	 *
	 * @param String $content
	 * @param Array $array
	 * @return View $view
	 */
	public function view ($content, $array){
		$view = View::forge('layout');

        //local view variables, lazy rendering
        $view->head = View::forge('home/head', array('title' => \Config::get('application.title'), 'description' => \Config::get('application.description')));
        $view->header = View::forge('home/header', array('site_title' => \Config::get('application.title')));
        $view->content = View::forge($content, $array);
        $view->footer = View::forge('home/footer', array('title' => \Config::get('application.title')));

        // return the view object to the Request
        return $view;
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

	/**
	 * Add
	 * Ajoute ou modifie une selection
	 *
	 * @param int $id
	 */
	public function action_add ($id = null){
		$this->verifAutorisation();
		
		$isUpdate = ($id !== null) ? true : false;

		if ($isUpdate){
			$selection = \Model_Selection::find($id);
			if (empty($selection)){
				\Messages::error('Cette selection n\'existe pas');
				\Response::redirect('/selection');
			}
		}
		else $selection = \Model_Selection::forge();

		$pays = \Model_Pays::find('all');

		if (\Input::post('add')){
			$selection->nom = htmlspecialchars(\Input::post('nom'));
			$selection->logo = \Input::post('logo');
			$selection->id_pays = (is_numeric(\Input::post('id_pays'))) ? \Input::post('id_pays') : 0;
			if ($selection->save()){
				($isUpdate) ? \Messages::success('Selection modifiée avec succès') : \Messages::success('Selection créée avec succès');
			}
			else \Messages::error('Une erreur est survenue');

			\Response::redirect('/selection');
		}

		$view = $this->view('selection/add', array('isUpdate' => $isUpdate, 'selection' => $selection, 'pays' => $pays));
		return $view;
	}

	/**
	 * Upload des logos avec Uploadify (POST only)
	 */
	public function post_uploadLogo (){
		if (!empty($_FILES)){

			$uploadConfig = array(
				'path' => DOCROOT . \Config::get('upload.selections.path'),
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
						\Response::redirect('/selection');
					}
				}
			}

			foreach (\Upload::get_files() as $file){
				return $file['saved_as'];
			}
		}
	}

	/**
	 * Delete
	 * Supprime une selection
	 *
	 * @param int $id
	 */
	public function action_delete ($id){
		$this->verifAutorisation();

		$selection = \Model_Selection::find($id);
		if (empty($selection)){
			\Messages::error('Cette selection n\'existe pas');
			\Response::redirect('/selection');
		}

		$fichier = DOCROOT . \Config::get('upload.selections.path') .'/'. $selection->logo;
		if (file_exists($fichier)) unlink($fichier);

		if ($selection->delete()){
			\Messages::success('Selection supprimée avec succès');
		}
		else {
			\Messages::error('Une erreur est survenue lors de la suppression de la selection');
		}

		\Response::redirect('/selection');
	}

	/**
	 * Import
	 * Importer des championnats depuis un fichier CSV
	 */
	public function action_import (){
		$this->verifAutorisation();

		if (\Input::method() == 'POST' || \Input::get('current')){
			if (\Input::method() == 'POST'){
				$file = \Input::file('file');
				$name = $this->processUploadCSV($file);
				if (empty($name)){
					\Messages::error('Pas de fichier uploadé');
					\Response::redirect('/selection');
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

				$selection; $pays;

				/**
				 *
				 * TRAITEMENT DU PAYS
				 *
				 */
				$pays = \Model_Pays::query()->where('nom', '=', $data['Pays'])->get();
				if (empty($pays)){
					$pays = \Model_Pays::forge();
					$pays->nom = $data['Pays'];
					$pays->drapeau = '';
					$pays->save();
				} else $pays = current($pays);

				/**
				 *
				 * TRAITEMENT DE LA SELECTION
				 *
				 */
				$selection = \Model_Selection::query()->where('nom', '=', $data['Nom'])->get();

				if (empty($selection)){
					$selection = \Model_Selection::forge();
					$selection->nom = $data['Nom'];
					$selection->logo = str_replace(' ', '', $data['Nom']) . '.png';
					$selection->id_pays = $pays->id;
					$selection->save();

					try {
						$logo = file_get_contents($data['Logo'], FILE_USE_INCLUDE_PATH);
					}
					catch (PhpErrorException $e){
						\Messages::error($e->getMessage());
					}

					//Détermination du nom du fichier et de son chemin d'accès
					file_exists(DOCROOT . \Config::get('upload.selections.path')) or \File::create_dir(DOCROOT . \Config::get('upload.selections.path'), 'selection');

					$nom_logo = DOCROOT . \Config::get('upload.selections.path') . DS . str_replace(' ', '', $data['Nom'] . '.png');

					// Création de l'image
					$fp = fopen($nom_logo, 'w+');
					fwrite($fp, $logo);
					fclose($fp);
				}

				// Quand on a interprété 20 ligne, raffraichissement de la page pour éviter erreur TimeExecution
				if ($line >= 20){
					echo "Chargement en cours ...";
					\Response::redirect('/selection/import?current=true&name='.$name.'&current_line='.$i, 'refresh');
				}

				$line++;
			}//FOR

			//Suppression fichier CSV
			$fichier = DOCROOT . \Config::get('upload.tmp.path') . DS . $name;
			if (file_exists($fichier)) unlink($fichier);

			\Messages::success('Import terminé avec succès');
			\Response::redirect('/selection');
		}//IF POST

		$view = $this->view('selection/import', array());
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
					\Response::redirect('/selection');
				}
			}
		}

		foreach (\Upload::get_files() as $file){
			return $file['saved_as'];
		}
	}
}