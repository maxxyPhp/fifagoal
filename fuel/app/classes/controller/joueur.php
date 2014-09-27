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
}