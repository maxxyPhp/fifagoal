<?php

class Controller_Joueur extends \Controller
{
	/**
	 * Index
	 * Liste les équipes
	 */
	public function action_index (){
		$this->verifAutorisation();

		
		$joueurs = \Model_Joueur::find('all');

		$view = $this->view('joueur/index', array('joueurs' => $joueurs));
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
	 * Ajoute ou modifie un joueur
	 *
	 * @param int $id
	 */
	public function action_add ($id = null){
		$this->verifAutorisation();
		
		$isUpdate = ($id !== null) ? true : false;

		if ($isUpdate){
			$joueur = \Model_Joueur::find($id);
			if (empty($joueur)){
				\Messages::error('Ce joueur n\'existe pas');
				\Response::redirect('/joueur');
			}

			$championnat_joueur = current(\Model_Championnat::query()->where('id', '=', $joueur->equipe->championnat->id)->get());
			$equipes_championnat = \Model_Equipe::query()->where('id_championnat', '=', $championnat_joueur->id)->order_by('nom')->get();
		}
		else {
			$joueur = \Model_Joueur::forge();	
		}

		// $equipes = \Model_Equipe::find('all');
		$selections = \Model_Selection::find('all');
		$postes = \Model_Poste::find('all');
		$championnats = \Model_Championnat::find('all');

		// Pays qui ont un championnat
		$query = \DB::query('SELECT DISTINCT pays.nom, pays.id, drapeau FROM pays JOIN championnat ON championnat.id_pays = pays.id ORDER BY pays.nom')->as_object('Model_Pays')->execute();
		$pays = array();
		foreach ($query as $result){
			$pays[] = $result;
		}
		


		if (\Input::post('add')){
			// var_dump($_FILES);die();
			$id_equipe = \Input::post('id_equipe');
			$id_selection = \Input::post('id_selection');
			if ((!empty($id_equipe) && !is_numeric($id_equipe)) || (!empty($id_selection) && !is_numeric($id_selection))){
				\Messages::error('Il y a une erreur dans le formulaire');
					\Response::redirect('/joueur/add');
			}

			$equipe = \Model_Equipe::find($id_equipe);
			$championnat = \Model_Championnat::find($equipe->id_championnat);

			
			/* Creation/modif joueur */
			$joueur->nom = htmlspecialchars(\Input::post('nom'));
			$joueur->prenom = (\Input::post('prenom')) ? htmlspecialchars(\Input::post('prenom')) : '';
			$joueur->id_poste = \Input::post('id_poste');
			($_FILES['photo']) ? $joueur->photo = $this->processUpload(\Input::post('nom'), \Input::post('prenom'), $championnat, $equipe) : '';
			$joueur->id_equipe = (\Input::post('id_equipe')) ? \Input::post('id_equipe') : 0;
			$joueur->id_selection = (\Input::post('id_selection')) ? \Input::post('id_selection') : 0;


			if ($joueur->save()){
				($isUpdate) ? \Messages::success('Joueur modifié avec succès') : \Messages::success('Joueur créé avec succès');
			}
			else \Messages::error('Une erreur est survenue');

			\Response::redirect('/joueur');
		}


		if ($isUpdate){
			$view = $this->view('joueur/add', array('pays' => $pays, 'isUpdate' => $isUpdate, 'selections' => $selections, 'joueur' => $joueur, 'postes' => $postes, 'championnats' => $championnats, 'championnat_joueur' => $championnat_joueur, 'equipes_championnat' => $equipes_championnat));
		}
		else $view = $this->view('joueur/add', array('pays' => $pays, 'isUpdate' => $isUpdate, 'selections' => $selections, 'joueur' => $joueur, 'postes' => $postes, 'pays' => $pays, 'championnats' => $championnats));
		return $view;
	}



	/**
	 * Process Upload
	 */
	public function processUpload ($nom, $prenom, $championnat, $equipe){
		$champ = str_replace(' ', '_', $championnat->nom);
		$eq = str_replace(' ', '_', $equipe->nom);
		$name ='';
		if (!empty($_FILES)){
			$uploadConfig = array(
				'path' => DOCROOT . \Config::get('upload.joueurs.path') . '/' . $champ . '/' . $eq,
				'normalize' => true,
				'ext_whitelist' => array('jpg', 'jpeg', 'png', 'bmp', 'gif', 'pdf'),
				'new_name' => $nom . '_' . $prenom,
			);
			
			\Upload::process($uploadConfig);

			if (\Upload::is_valid()){
				\Upload::save();
			} 

			foreach (\Upload::get_errors() as $file){
				foreach ($file['errors'] as $error){
					if ($error['error'] !== UPLOAD_ERR_NO_FILE){
						\Messages::error($error['message']);
						\Response::redirect('/joueur');
					}
				}
			}

			foreach (\Upload::get_files() as $file){
				$name = $champ . '/' . $eq . '/' . $file['saved_as'];
			}
		}

		return $name;
	}

	/**
	 * Delete
	 * Supprime un joueur
	 *
	 * @param int $id
	 */
	public function action_delete ($id){
		$this->verifAutorisation();

		$joueur = \Model_Joueur::find($id);
		if (empty($joueur)){
			\Messages::error('Ce joueur n\'existe pas');
			\Response::redirect('/joueur');
		}

		$fichier = DOCROOT . \Config::get('upload.joueurs.path') .'/'. $joueur->photo;
		if (file_exists($fichier)) unlink($fichier);

		if ($joueur->delete()){
			\Messages::success('Joueur supprimé avec succès');
		}
		else {
			\Messages::error('Une erreur est survenue lors de la suppression du joueur');
		}

		\Response::redirect('/joueur');
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
					\Response::redirect('/joueur');
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


				if (!empty($data['Selection'])){
					/**
					 *
					 * TRAITEMENT DU PAYS
					 *
					 */
					$pays = \Model_Pays::query()->where('nom', '=', $data['Selection'])->get();
					if (empty($pays)){
						$pays = \Model_Pays::forge();
						$pays->nom = $data['Selection'];
						$pays->drapeau = '';
						$pays->save();
					} else $pays = current($pays);

					/**
					 *
					 * TRAITEMENT DE LA SELECTION
					 *
					 */
					$selection = \Model_Selection::query()->where('nom', '=', $data['Selection'])->get();


					if (empty($selection)){
						$selection = \Model_Selection::forge();
						$selection->nom = $data['Selection'];
						$selection->logo = '';
						$selection->id_pays = $pays->id;
						$selection->save();
					} else $selection = current($selection);
				}

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
					\Response::redirect('/joueur/import?current=true&name='.$name.'&current_line='.$i, 'refresh');
				}

				$line++;
			}//FOR

			//Suppression fichier CSV
			$fichier = DOCROOT . \Config::get('upload.tmp.path') . DS . $name;
			if (file_exists($fichier)) unlink($fichier);

			\Messages::success('Import terminé avec succès');
			\Response::redirect('/joueur');
		}//IF POST

		$view = $this->view('joueur/import', array());
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
					\Response::redirect('/joueur');
				}
			}
		}

		foreach (\Upload::get_files() as $file){
			return $file['saved_as'];
		}
	}
}