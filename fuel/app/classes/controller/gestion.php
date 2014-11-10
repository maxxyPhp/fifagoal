<?php

class Controller_Gestion extends \Controller
{
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
	 * View
	 * Prépare la vue à afficher
	 *
	 * @param String $content
	 * @param Array $array
	 * @return View $view
	 */
	public function view ($content, $array){
		/**
		 *
		 * USER
		 *
		 */
		$photouser;
		if (\Auth::check()){
			$photouser = \Model_Photousers::query()->where('id_users', '=', \Auth::get('id'))->get();
			(!empty($photouser)) ? $photouser = current($photouser) : $photouser = null;
		}

		$view = View::forge('layout');

        //local view variables, lazy rendering
        $view->head = View::forge('home/head', array('title' => \Config::get('application.title'), 'description' => \Config::get('application.description'), 'notifs' => 0));
        $view->header = View::forge('home/header', array('site_title' => \Config::get('application.title'), 'defis' => '', 'news' => '', 'notifys' => '', 'photouser' => $photouser));
        $view->content = View::forge($content, $array);
        $view->footer = View::forge('home/footer', array('title' => \Config::get('application.title')));

        // return the view object to the Request
        return $view;
	}

	/**
	 * processUploadCSV
	 * Upload des fichiers CSV pour l'import de données dans A/R
	 *
	 * @param String $file
	 * @param String $redirect
	 */
	public function processUploadCSV ($file, $redirect){
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
					\Response::redirect($redirect);
				}
			}
		}

		foreach (\Upload::get_files() as $file){
			return $file['saved_as'];
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
	 * Secure
	 * Sécurise les données entrantes d'un formulaire
	 *
	 * @param String $string
	 * @return String $string
	 */
	public static function secure($string)
	{
		// On regarde si le type de string est un nombre entier (int)
		if(ctype_digit($string))
		{
			$string = intval($string);
		}
		// Pour tous les autres types
		else
		{
			$string = mysql_real_escape_string($string);
			$string = addcslashes($string, '%_');
		}
			
		return $string;

	}
	// Données sortantes
	public static function html($string)
	{
		return htmlentities($string);
	}
}