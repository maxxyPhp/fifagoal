<?php

class Controller_Poste extends \Controller
{
	/**
	 * Index
	 * Liste les équipes
	 */
	public function action_index (){
		$this->verifAutorisation();

		$postes = \Model_Poste::find('all');

		$view = $this->view('poste/index', array('postes' => $postes));
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
	 * Ajoute ou modifie un poste
	 *
	 * @param int $id
	 */
	public function action_add ($id = null){
		$this->verifAutorisation();
		
		$isUpdate = ($id !== null) ? true : false;

		if ($isUpdate){
			$poste = \Model_Poste::find($id);
			if (empty($poste)){
				\Messages::error('Ce poste n\'existe pas');
				\Response::redirect('/poste');
			}
		}
		else $poste = \Model_Poste::forge();

		if (\Input::post('add')){
			$poste->nom = htmlspecialchars(\Input::post('nom'));
			if ($poste->save()){
				($isUpdate) ? \Messages::success('Poste modifié avec succès') : \Messages::success('Poste créé avec succès');
			}
			else \Messages::error('Une erreur est survenue');

			\Response::redirect('/poste');
		}

		$view = $this->view('poste/add', array('isUpdate' => $isUpdate, 'poste' => $poste));
		return $view;
	}

	/**
	 * Delete
	 * Supprime une équipe
	 *
	 * @param int $id
	 */
	public function action_delete ($id){
		$this->verifAutorisation();

		$poste = \Model_Poste::find($id);
		if (empty($poste)){
			\Messages::error('Ce poste n\'existe pas');
			\Response::redirect('/poste');
		}

		if ($poste->delete()){
			\Messages::success('Poste supprimé avec succès');
		}
		else {
			\Messages::error('Une erreur est survenue lors de la suppression du poste');
		}

		\Response::redirect('/poste');
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
					\Response::redirect('/poste');
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

				/**
				 *
				 * TRAITEMENT DU POSTE
				 *
				 */

				$poste = \Model_Poste::query()->where('nom', '=', $data['Nom'])->get();
				if (empty($poste)){
					$poste = \Model_Poste::forge();
					$poste->nom = $data['Nom'];
					$poste->save();
				}

				// Quand on a interprété 20 ligne, raffraichissement de la page pour éviter erreur TimeExecution
				if ($line >= 20){
					echo "Chargement en cours ...";
					\Response::redirect('/poste/import?current=true&name='.$name.'&current_line='.$i, 'refresh');
				}

				$line++;
			}//FOR

			//Suppression fichier CSV
			$fichier = DOCROOT . \Config::get('upload.tmp.path') . DS . $name;
			if (file_exists($fichier)) unlink($fichier);

			\Messages::success('Import terminé avec succès');
			\Response::redirect('/poste');
		}//IF POST

		$view = $this->view('poste/import', array());
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
					\Response::redirect('/poste');
				}
			}
		}

		foreach (\Upload::get_files() as $file){
			return $file['saved_as'];
		}
	}
}