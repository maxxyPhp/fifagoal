<?php 

class Controller_Transfert extends \Controller_Gestion
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
				$name = $this->processUploadCSV($file, '/transfert');
				if (empty($name)){
					\Messages::error('Pas de fichier uploadé');
					\Response::redirect('/transfert');
				}

				$fichier = DOCROOT . \Config::get('upload.tmp.path') . '/' . $name;
				if (file_exists($fichier)) $file_content = \File::read($fichier, true);
				$current_line = 0;
				$error[] = array();
			}
			else {
				$name = \Input::get('name');
				$file_content = \File::read(DOCROOT . \Config::get('upload.tmp.path') . '/' . $name, true);
				$current_line = \Input::get('current_line');
				$error = \Input::get('error');
			}

			// Conversion CSV vers PHP
			$donnees = \Format::forge($file_content, 'csv')->to_array();
			// Nombre de logne du fichier
			$number_of_line = count($donnees);
			$line = 0;
			$error = array();

			//Fetch les lignes
			for ($i = $current_line; $i < $number_of_line; $i++){
				$data = $donnees[$i];

				$old_equipe; $new_equipe; $joueur;

				/**
				 *
				 * TRAITEMENT DE L'ANCIENNE EQUIPE
				 *
				 */

				$old_equipe = \Model_Equipe::query()->where('nom', '=', $data['Equipe'])->get();
				if (empty($old_equipe)){
					$error[] = $data['Nom'];
					break;
				} else $old_equipe = current($old_equipe);

				/**
				 *
				 * TRAITEMENT DU JOUEUR
				 *
				 */

				if (!empty($data['Prenom'])){
					$joueur = \Model_Joueur::find('all', array(
						'where' => array(
							array('nom', $data['Nom']),
							array('id_equipe', $old_equipe->id),
							array('prenom', $data['Prenom']),
						),
					));
				}
				else {
					$joueur = \Model_Joueur::find('all', array(
						'where' => array(
							array('nom', $data['Nom']),
							array('id_equipe', $old_equipe->id),
						),
					));
				}

				if (empty($joueur)){
					$error[] = $data['Nom'];
					break;
				} else $joueur = current($joueur);

				/**
				 *
				 * TRAITEMENT DE LA NOUVELLE EQUIPE
				 *
				 */

				$new_equipe = \Model_Equipe::query()->where('nom', '=', $data['New_equipe'])->get();
				if (empty($new_equipe)){
					$error[] = $data['Nom'];
					break;
				} else $new_equipe = current($new_equipe);

			
				$joueur->id_equipe = $new_equipe->id;
				$joueur->save();

				/**
				 *
				 * TRAITEMENT DE L'IMAGE
				 *
				 */
				$chemin_photo = DOCROOT . \Config::get('upload.joueurs.path') . '/' . str_replace(' ', '_', strtolower($old_equipe->championnat->nom)) . '/' . str_replace(' ', '_', strtolower($old_equipe->nom)) . '/' . $joueur->photo;

				if (file_exists($chemin_photo)){
					if (!file_exists(DOCROOT . \Config::get('upload.joueurs.path') . '/' . str_replace(' ', '_', strtolower($new_equipe->championnat->nom)))){
						\File::create_dir(DOCROOT . 'upload/joueurs', str_replace(' ', '_', strtolower($new_equipe->championnat->nom)));
					}

					if (!file_exists(DOCROOT . \Config::get('upload.joueurs.path') . '/' . str_replace(' ', '_', strtolower($new_equipe->championnat->nom)) . '/' . str_replace(' ','_', strtolower($new_equipe->nom)))){
						\File::create_dir(DOCROOT . \Config::get('upload.joueurs.path') . '/' . str_replace(' ', '_', strtolower($new_equipe->championnat->nom)), str_replace(' ','_', strtolower($new_equipe->nom)));
					}

					try {
						\File::copy($chemin_photo, DOCROOT . \Config::get('upload.joueurs.path') . '/' . str_replace(' ', '_', strtolower($new_equipe->championnat->nom)) . '/' . str_replace(' ','_', strtolower($new_equipe->nom)) . '/' . $joueur->photo);
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


				// Quand on a interprété 20 ligne, raffraichissement de la page pour éviter erreur TimeExecution
				if ($line >= 20){
					echo "Chargement en cours ...";
					\Response::redirect('/transfert/import?current=true&name='.$name.'&current_line='.$i.'&error='.$error, 'refresh');
				}

				$line++;
			}//FOR

			foreach ($error as $err){
				\Messages::error('Le joueur '.$err.' n\'a pas pu être transféré');
			}

			//Suppression fichier CSV
			$fichier = DOCROOT . \Config::get('upload.tmp.path') . DS . $name;
			if (file_exists($fichier)) unlink($fichier);

			\Messages::success('Import terminé avec succès');
			\Response::redirect('/transfert');
		}//IF POST

		$view = $this->view('transfert/import', array());
		return $view;
	}
}