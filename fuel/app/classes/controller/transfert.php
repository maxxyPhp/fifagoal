<?php 

class Controller_Transfert extends \Controller 
{
	public function get_api ($context){
		switch ($context){
			case 'transferer':
				if (!is_numeric(\Input::get('equipe')) || !is_numeric(\Input::get('joueur'))){
					return 'KO';
				}

				$joueur = \Model_Joueur::find(\Input::get('joueur'));
				if (empty($joueur)) return 'KO';
					
				$equipe = \Model_Equipe::find(\Input::get('equipe'));
				if (empty($equipe)) return 'KO';

				$old_equipe = $joueur->equipe;

				$joueur->id_equipe = $equipe->id;
				$joueur->save();

				$chemin_photo = DOCROOT . \Config::get('upload.joueurs.path') . '/' . str_replace(' ', '_', strtolower($old_equipe->championnat->nom)) . '/' . str_replace(' ', '_', strtolower($old_equipe->nom)) . '/' . $joueur->photo;

				if (file_exists($chemin_photo)){
					if (!file_exists(DOCROOT . \Config::get('upload.joueurs.path') . '/' . str_replace(' ', '_', strtolower($equipe->championnat->nom)))){
						\File::create_dir(DOCROOT . 'upload/joueurs', str_replace(' ', '_', strtolower($equipe->championnat->nom)));
					}

					if (!file_exists(DOCROOT . \Config::get('upload.joueurs.path') . '/' . str_replace(' ', '_', strtolower($equipe->championnat->nom)) . '/' . str_replace(' ','_', strtolower($equipe->nom)))){
						\File::create_dir(DOCROOT . \Config::get('upload.joueurs.path') . '/' . str_replace(' ', '_', strtolower($equipe->championnat->nom)), str_replace(' ','_', strtolower($equipe->nom)));
					}

					try {
						\File::copy($chemin_photo, DOCROOT . \Config::get('upload.joueurs.path') . '/' . str_replace(' ', '_', strtolower($equipe->championnat->nom)) . '/' . str_replace(' ','_', strtolower($equipe->nom)) . '/' . $joueur->photo);
					}
					catch (\FileAccessException $e){
						var_dump('access');
					}

					try {
						\File::delete($chemin_photo);
					}
					catch (\InvalidPathException $e){
						var_dump('path'); 
					}
				}

				return json_encode('OK');

				break;
		}
	}

	public function action_index (){
		return $this->view('transfert/index', array());
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
	 * Ajoute ou modifie un joueur
	 *
	 * @param int $id
	 */
	public function action_add ($id = null){
		$this->verifAutorisation();

		// Pays qui ont un championnat
		$query = \DB::query('SELECT DISTINCT pays.nom, pays.id, drapeau FROM pays JOIN championnat ON championnat.id_pays = pays.id ORDER BY pays.nom')->as_object('Model_Pays')->execute();
		$pays = array();
		foreach ($query as $result){
			$pays[] = $result;
		}

		$championnats = \Model_Championnat::find('all');

		$view = $this->view('transfert/add', array('pays' => $pays, 'championnats' => $championnats));
		return $view;
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
					\Response::redirect('/transfert');
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

				$championnat; $pays; $equipe; $selection = '';


				/**
				 *
				 * TRAITEMENT DU CHAMPIONNAT
				 *
				 */
				$championnat = \Model_Championnat::query()->where('nom', '=', $data['Championnat'])->get();

				if (empty($championnat)){
					$championnat = \Model_Championnat::forge();
					$championnat->nom = $data['Championnat'];
					$championnat->logo = '';
					$championnat->id_pays = 0;
					$championnat->save();
				} else $championnat = current($championnat);

				/**
				 *
				 * TRAITEMENT DE L'EQUIPE
				 *
				 */

				$equipe = \Model_Equipe::query()->where('nom', '=', $data['Equipe'])->get();
				if (empty($equipe)){
					$equipe = \Model_Equipe::forge();
					$equipe->nom = $data['Equipe'];
					$equipe->nom_court = '';
					$equipe->logo = '';
					$equipe->id_championnat = 0;
					$equipe->save();
				} else $equipe = current($equipe);

				/**
				 *
				 * TRAITEMENT DU POSTE
				 *
				 */
				$poste = \Model_Poste::query()->where('nom', '=', strtoupper($data['Poste']))->get();
				if (empty($poste)){
					$poste = \Model_Poste::forge();
					$poste->nom = strtoupper($data['Poste']);
					$poste->save();
				} else $poste = current($poste);

				/**
				 *
				 * TRAITEMENT DU JOUEUR
				 *
				 */
				$joueur = \Model_Joueur::find('all', array(
					'where' => array(
						array('nom', strtolower($data['Nom'])),
						array('id_poste', $poste->id),
						array('id_equipe', $equipe->id),
					),
				));


				if (empty($joueur)){
					$joueur = \Model_Joueur::forge();
					$joueur->nom = strtolower($data['Nom']);
					$joueur->prenom = isset($data['Prenom']) ? strtolower($data['Prenom']) : '';
					$joueur->id_poste = $poste->id;
					$joueur->photo = !empty($data['Photo']) ? str_replace(' ', '_', strtolower($data['Nom']) . '_' . strtolower($data['Prenom'])) . '.png' : '';
					$joueur->id_equipe = $equipe->id;
					$joueur->id_selection = (!empty($selection)) ? $selection->id : 0;
					$joueur->save();

					$nationalites = explode('|', $data['Nationalite']);
					foreach ($nationalites as $nationalite){
						$pays = \Model_Pays::query()->where('nom', '=', $nationalite)->get();
						if (!empty($pays)){
							$joueur->pays = $pays;
							$joueur->save();
						}
					}

					if (!empty($data['Photo'])){
						try {
							$photo = file_get_contents($data['Photo'], FILE_USE_INCLUDE_PATH);
						}
						catch (PhpErrorException $e){
							\Messages::error($e->getMessage());
						}

						//Détermination du nom du fichier et de son chemin d'accès
						if (!file_exists(DOCROOT . \Config::get('upload.joueurs.path'))){
							\File::create_dir(DOCROOT . 'upload', 'joueurs');
						}

						
						if (!file_exists(DOCROOT . \Config::get('upload.joueurs.path') . DS . str_replace(' ', '_', strtolower($championnat->nom)))){
							\File::create_dir(DOCROOT . \Config::get('upload.joueurs.path'), str_replace(' ', '_', strtolower($championnat->nom)));
						}
						
		
						if (!file_exists(DOCROOT . \Config::get('upload.joueurs.path') . DS . str_replace(' ', '_', strtolower($championnat->nom)) . DS . str_replace(' ', '_', strtolower($equipe->nom)))){
							\File::create_dir(DOCROOT . \Config::get('upload.joueurs.path') . DS . str_replace(' ', '_', strtolower($championnat->nom)), str_replace(' ', '_', strtolower($equipe->nom)));
						}


						$nom_photo = DOCROOT . \Config::get('upload.joueurs.path') . DS . str_replace(' ', '_', strtolower($championnat->nom)) . DS . str_replace(' ', '_', strtolower($equipe->nom)) . DS . str_replace(' ', '_', strtolower($data['Nom']) . '_' . strtolower($data['Prenom']) . '.png');

						// Création de l'image
						$fp = fopen($nom_photo, 'w+');
						fwrite($fp, $photo);
						fclose($fp);
					}
				}

				// Quand on a interprété 20 ligne, raffraichissement de la page pour éviter erreur TimeExecution
				if ($line >= 20){
					echo "Chargement en cours ...";
					\Response::redirect('/transfert/import?current=true&name='.$name.'&current_line='.$i, 'refresh');
				}

				$line++;
			}//FOR

			//Suppression fichier CSV
			$fichier = DOCROOT . \Config::get('upload.tmp.path') . DS . $name;
			if (file_exists($fichier)) unlink($fichier);

			\Messages::success('Import terminé avec succès');
			\Response::redirect('/transfert');
		}//IF POST

		$view = $this->view('transfert/import', array());
		return $view;
	}

	/**
	 * processUploadCSV
	 * Upload des fichiers CSV pour l'import de données
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
					\Response::redirect('/transfert');
				}
			}
		}

		foreach (\Upload::get_files() as $file){
			return $file['saved_as'];
		}
	}
}