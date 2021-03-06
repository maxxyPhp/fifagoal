<?php

class Controller_Poste extends \Controller_Gestion
{
	/**
	 * Index
	 * Liste les équipes
	 */
	public function action_index (){
		$this->verifAutorisation();

		try {
			$postes = \Cache::get('listPostes');
		}
		catch (\CacheNotFoundException $e){
			$postes = \Model_Poste::find('all');
			if (!empty($postes)) \Cache::set('listPostes', $postes);
		}
		

		$view = $this->view('poste/index', array('postes' => $postes));
		return $view;
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
			$poste->couleur = htmlspecialchars(\Input::post('couleur'));
			if ($poste->save()){
				($isUpdate) ? \Messages::success('Poste modifié avec succès') : \Messages::success('Poste créé avec succès');
			}
			else \Messages::error('Une erreur est survenue');

			\Cache::delete('listPostes');
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

		\Cache::delete('listPostes');
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
				$name = $this->processUploadCSV($file, '/poste');
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
			\Cache::delete('listPostes');
			\Response::redirect('/poste');
		}//IF POST

		$view = $this->view('poste/import', array());
		return $view;
	}
}