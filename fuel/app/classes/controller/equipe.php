<?php

class Controller_Equipe extends \Controller
{
	/**
	 * Get Api
	 * AJAX ONLY
	 *
	 * @param String $context
	 */
	public function get_api ($context){
		switch ($context){
			case 'getEquipes':
				if (!is_numeric(\Input::get('id_championnat'))){
					return 'KO';
				}

				$equipes = \Model_Equipe::query()->where('id_championnat', '=', \Input::get('id_championnat'))->order_by('nom')->get();

				foreach ($equipes as $equipe){
					$array[] = $this->object_to_array($equipe);
				}
				
				return json_encode($array);
				break;

			case 'getJoueurs':
				if (!is_numeric(\Input::get('id_equipe'))){
					return 'KO';
				}

				$joueurs = \Model_Joueur::query()->where('id_equipe', '=', \Input::get('id_equipe'))->order_by('nom')->get();

				foreach ($joueurs as $joueur){
					$array[] = $this->object_to_array($joueur);
				}

				return json_encode($array);
				break;

			case 'getEquipe':
				if (!is_numeric(\Input::get('id_equipe'))){
					return 'KO';
				}

				$equipe = \Model_Equipe::find(\Input::get('id_equipe'));
				$championnat = \Model_Championnat::find($equipe->id_championnat);

				$equipe->championnat = str_replace(' ', '_', strtolower($championnat->nom));

				foreach ($equipe as $eq){
					$array[] = $this->object_to_array($eq);
				}

				return json_encode($array);
				break;
		}
	}

	/**
	 * Object To Array
	 * Transforme un objet en tableau multidimensionnel
	 *
	 * @param $data
	 * @return $data
	 */
	function object_to_array($data){
	    if(is_array($data) || is_object($data)){
	        $result = array();
	 
	        foreach($data as $key => $value) {
	            $result[$key] = $this->object_to_array($value);
	        }
	 
	        return $result;
	    }
	 
	    return $data;
	}

	/**
	 * Index
	 * Liste les équipes
	 */
	public function action_index (){
		$this->verifAutorisation();

		$equipes = \Model_Equipe::find('all');

		$view = $this->view('equipe/index', array('equipes' => $equipes));
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
			$equipe = \Model_Equipe::find($id);
			if (empty($equipe)){
				\Messages::error('Cette equipe n\'existe pas');
				\Response::redirect('/equipe');
			}
		}
		else $equipe = \Model_Equipe::forge();

		$championnats = \Model_Championnat::find('all');

		if (\Input::post('add')){
			$equipe->nom = htmlspecialchars(\Input::post('nom'));
			$equipe->logo = \Input::post('logo');
			$equipe->id_championnat = (is_numeric(\Input::post('id_championnat'))) ? \Input::post('id_championnat') : 0;
			if ($equipe->save()){
				($isUpdate) ? \Messages::success('Equipe modifiée avec succès') : \Messages::success('Equipe créée avec succès');
			}
			else \Messages::error('Une erreur est survenue');

			\Response::redirect('/equipe');
		}

		$view = $this->view('equipe/add', array('isUpdate' => $isUpdate, 'equipe' => $equipe, 'championnats' => $championnats));
		return $view;
	}

	/**
	 * Upload des logos avec Uploadify (POST only)
	 */
	public function post_uploadLogo (){
		if (!empty($_FILES)){

			$uploadConfig = array(
				'path' => DOCROOT . \Config::get('upload.equipes.path'),
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
						\Response::redirect('/equipe');
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
	 * Supprime une équipe
	 *
	 * @param int $id
	 */
	public function action_delete ($id){
		$this->verifAutorisation();

		$equipe = \Model_Equipe::find($id);
		if (empty($equipe)){
			\Messages::error('Cette equipe n\'existe pas');
			\Response::redirect('/equipe');
		}

		$fichier = DOCROOT . \Config::get('upload.equipes.path') .'/'. $equipe->logo;
		if (file_exists($fichier)) unlink($fichier);

		if ($equipe->delete()){
			\Messages::success('Equipe supprimée avec succès');
		}
		else {
			\Messages::error('Une erreur est survenue lors de la suppression de l\'equipe');
		}

		\Response::redirect('/equipe');
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
					\Response::redirect('/equipe');
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

				$championnat; $pays;

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
				 * TRAITEMENT DU CHAMPIONNAT
				 *
				 */
				$championnat = \Model_Championnat::query()->where('nom', '=', $data['Championnat'])->get();

				if (empty($championnat)){
					$championnat = \Model_Championnat::forge();
					$championnat->nom = $data['Nom'];
					$championnat->logo = '';
					$championnat->id_pays = $pays->id;
					$championnat->save();
				} else $championnat = current($championnat);

				/**
				 *
				 * TRAITEMENT DE L'EQUIPE
				 *
				 */

				$equipe = \Model_Equipe::query()->where('nom', '=', $data['Nom'])->get();
				if (empty($equipe)){
					$equipe = \Model_Equipe::forge();
					$equipe->nom = $data['Nom'];
					$equipe->nom_court = (isset($data['Nom_court'])) ? strtoupper($data['Nom_court']) : '';
					$equipe->logo = str_replace(' ', '_', strtolower($data['Nom'])) . '.png';
					$equipe->id_championnat = $championnat->id;
					$equipe->save();

					try {
						$logo = file_get_contents($data['Logo'], FILE_USE_INCLUDE_PATH);
					}
					catch (PhpErrorException $e){
						\Messages::error($e->getMessage());
					}

					//Détermination du nom du fichier et de son chemin d'accès
					file_exists(DOCROOT . \Config::get('upload.equipes.path')) or \File::create_dir(DOCROOT . 'upload', 'equipes');
					file_exists(DOCROOT . \Config::get('upload.equipes.path') . '/' . str_replace(' ', '_', strtolower($championnat->nom))) or \File::create_dir(DOCROOT . \Config::get('upload.equipes.path'), str_replace(' ', '_', strtolower($championnat->nom)));

					$nom_logo = DOCROOT . \Config::get('upload.equipes.path') . DS . str_replace(' ', '_', strtolower($championnat->nom)) . DS . str_replace(' ', '_', strtolower($data['Nom']) . '.png');

					// Création de l'image
					$fp = fopen($nom_logo, 'w+');
					fwrite($fp, $logo);
					fclose($fp);
				}

				// Quand on a interprété 20 ligne, raffraichissement de la page pour éviter erreur TimeExecution
				if ($line >= 20){
					echo "Chargement en cours ...";
					\Response::redirect('/equipe/import?current=true&name='.$name.'&current_line='.$i, 'refresh');
				}

				$line++;
			}//FOR

			//Suppression fichier CSV
			$fichier = DOCROOT . \Config::get('upload.tmp.path') . DS . $name;
			if (file_exists($fichier)) unlink($fichier);

			\Messages::success('Import terminé avec succès');
			\Response::redirect('/equipe');
		}//IF POST

		$view = $this->view('equipe/import', array());
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
					\Response::redirect('/equipe');
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