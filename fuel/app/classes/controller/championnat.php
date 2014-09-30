<?php

class Controller_Championnat extends Controller
{
	/**
	 * Index
	 * Liste les championnats
	 */
	public function action_index (){
		$this->verifAutorisation();

		try {
			$championnat = \Cache::get('listChampionnat');
		} 
		catch (\CacheNotFoundException $e){
			$championnats = \Model_Championnat::find('all');
			if (!empty($championnats)) \Cache::set('listChampionnat', $championnats);
		}
		

		$view = $this->view('championnat/index', array('championnats' => $championnats));
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
	 * Add
	 * Ajoute ou modifie un championnat
	 *
	 * @param int $id
	 */
	public function action_add ($id = null){
		$this->verifAutorisation();

		$isUpdate = ($id !== null) ? true : false;

		if ($isUpdate){
			$championnat = \Model_Championnat::find($id);
			if (empty($championnat)){
				\Messages::error('Ce championnat n\'existe pas');
				\Response::redirect('/championnat');
			}
		}
		else $championnat = \Model_Championnat::forge();

		$pays = \Model_Pays::find('all');

		if (\Input::post('add')){
			$championnat->nom = htmlspecialchars(\Input::post('nom'));
			$championnat->logo = \Input::post('logo');
			$championnat->id_pays = (is_numeric(\Input::post('id_pays'))) ? \Input::post('id_pays') : 0;
			if ($championnat->save()){
				($isUpdate) ? \Messages::success('Championnat modifié avec succès') : \Messages::success('Championnat créé avec succès');
			}
			else \Messages::error('Une erreur est survenue');

			\Cache::delete('listChampionnat');
			\Response::redirect('/championnat');
		}

		$view = $this->view('championnat/add', array('isUpdate' => $isUpdate, 'championnat' => $championnat, 'pays' => $pays));
		return $view;
	}

	/**
	 * Upload des logos avec Uploadify (POST only)
	 */
	public function post_uploadLogo (){
		if (!empty($_FILES)){
			$uploadConfig = array(
				'path' => DOCROOT . \Config::get('upload.championnat.path'),
				'normalize' => true,
				'ext_whitelist' => array('jpg', 'jpeg', 'png', 'bmp', 'gif', 'pdf'),
				// 'new_name' => str_replace(' ', '_', strtolower($_FILES['name'])),
			);
			
			\Upload::process($uploadConfig);

			if (\Upload::is_valid()){
				\Upload::save();
			} 

			foreach (\Upload::get_errors() as $file){
				foreach ($file['errors'] as $error){
					if ($error['error'] !== UPLOAD_ERR_NO_FILE){
						\Messages::error($error['message']);
						\Response::redirect('/championnat');
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
	 * Supprime un championnat
	 *
	 * @param int $id
	 */
	public function action_delete ($id){
		$this->verifAutorisation();

		$championnat = \Model_Championnat::find($id);
		if (empty($championnat)){
			\Messages::error('Ce championnat n\'existe pas');
			\Response::redirect('/championnat');
		}

		$fichier = DOCROOT . \Config::get('upload.championnat.path') .'/'. $championnat->logo;
		if (file_exists($fichier)) unlink($fichier);

		if ($championnat->delete()){
			\Messages::success('Championnat supprimé avec succès');
		}
		else {
			\Messages::error('Une erreur est survenue lors de la suppression du championnat');
		}

		\Cache::delete('listChampionnat');
		\Response::redirect('/championnat');
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
					\Response::redirect('/championnat');
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
				$pays = \Model_Pays::query()->where('nom', '=', $data['Pays'])->get();

				if (empty($pays)){
					$pays = \Model_Pays::forge();
					$pays->nom = $data['Pays'];
					$pays->drapeau = '';
					$pays->save();
				} else $pays = current($pays);

				$championnat = \Model_Championnat::query()->where('nom', '=', $data['Nom'])->get();
				if (empty($championnat)){
					$championnat = \Model_Championnat::forge();
					$championnat->nom = $data['Nom'];
					$championnat->logo = str_replace(' ', '_', strtolower($data['Nom']) . '.png');
					$championnat->id_pays = $pays->id;
					$championnat->save();

					try {
						$logo = file_get_contents($data['Logo'], FILE_USE_INCLUDE_PATH);
					}
					catch (PhpErrorException $e){
						\Messages::error($e->getMessage());
					}

					//Détermination du nom du fichier et de son chemin d'accès
					file_exists(DOCROOT . \Config::get('upload.championnat.path')) or \File::create_dir(DOCROOT . 'upload', 'championnat');

					$nom_logo = DOCROOT . \Config::get('upload.championnat.path') . DS . str_replace(' ', '_', strtolower($data['Nom']) . '.png');

					// Création de l'image
					$fp = fopen($nom_logo, 'w+');
					fwrite($fp, $logo);
					fclose($fp);
				}

				// Quand on a interprété 20 ligne, raffraichissement de la page pour éviter erreur TimeExecution
				if ($line >= 20){
					echo "Chargement en cours ...";
					\Response::redirect('/championnat/import?current=true&name='.$name.'&current_line='.$i, 'refresh');
				}

				$line++;
			}//FOR

			//Suppression fichier CSV
			$fichier = DOCROOT . \Config::get('upload.tmp.path') . DS . $name;
			if (file_exists($fichier)) unlink($fichier);

			\Messages::success('Import terminé avec succès');
			\Cache::delete('listChampionnat');
			\Response::redirect('/championnat');
		}//IF POST

		$view = $this->view('championnat/import', array());
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
					\Response::redirect('/championnat');
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