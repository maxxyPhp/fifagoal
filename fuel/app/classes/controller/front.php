<?php

class Controller_Front extends \Controller
{
	/**
	 * Verif Autorisation
	 * Vérifie que l'utilisateur est connecté et est un admin
	 */
	public function verifAutorisation (){
		if (!\Auth::check()){
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
		setlocale (LC_TIME, 'fr_FR.utf8','fra');
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

		/**
		 *
		 * DEFIS
		 *
		 */
		$demande = 0;
		if (\Auth::check()){	
			$en_cours = \Model_Status::query()->where('nom', '=', 'En attente')->get();
			if (!empty($en_cours)){
				$en_cours = current($en_cours);

				$defis = \Model_Defis::find('all', array(
					'where' => array(
						array('id_joueur_defier', \Auth::get('id')),
						array('status_demande', $en_cours->id),
					),
				));

				if (!empty($defis)) $demande = count($defis);
			}
		}

		/**
		 *
		 * NOTIFICATIONS
		 *
		 */
		$notifys = array();
		$query = \DB::query('SELECT * FROM notifications WHERE id_user = '.\Auth::get('id').' ORDER BY created_at DESC LIMIT 5')
				->as_object('Model_Notify')
				->execute();

		$news = 0;
		foreach ($query as $result){
			$notifys[] = $result;
			if ($result->new == 1) $news++; 
		}
		
		$view = View::forge('layout');

        //local view variables, lazy rendering
        $view->head = View::forge('home/head', array('title' => \Config::get('application.title'), 'description' => \Config::get('application.description')));
        if (\Auth::check()){
        	$view->header = View::forge('home/header', array('site_title' => \Config::get('application.title'), 'defis' => $demande, 'photouser' => $photouser, 'notifys' => $notifys, 'news' => $news));
        } else {
        	$view->header = View::forge('home/header', array('site_title' => \Config::get('application.title'), 'defis' => '', 'photouser' => '', 'notifys' => '', 'news' => ''));
        }
        $view->content = View::forge($content, $array);
        $view->footer = View::forge('home/footer', array('title' => \Config::get('application.title')));

        // return the view object to the Request
        return $view;
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
	 * newNotify
	 * Crée une nouvelle notification
	 *
	 * @param int $id_user
	 * @param String $message
	 */
	public function newNotify ($id_user, $message){
		$notify = \Model_Notify::forge();
		$notify->id_user = $id_user;
		$notify->message = $message;
		$notify->new = 1;
		$notify->save();
	}

	/**
	 * modelMessage
	 * Prépare le message d'une notification
	 *
	 * @param String $model
	 * @param String $username
	 * @param int $id
	 * @return String message
	 */
	public function modelMessage ($model, $username, $id = null){
		switch ($model){
			case 'defi':
				return '<a href="/defis"><h5><i class="fa fa-gamepad"></i> <strong>'.$username.'</strong> vous défie !</h5></a>';
				break;

			case 'accepteDefi':
				return '<a href="/defis"><h5><i class="fa fa-gamepad"></i> <strong>'.$username.'</strong> accepte votre défi !</h5></a>';
				break;

			case 'addRapport':
				return '<a href="/matchs/view/'.$id.'"><h5><i class="fa fa-file-text-o"></i> <strong>'.$username.'</strong> vient de créer le rapport de votre match !</h5></a>';
				break;

			case 'validRapport':
				return '<a href="/matchs/view/'.$id.'"><h5><i class="fa fa-check"></i> <strong>'.$username.'</strong> a validé le rapport de votre match !</h5></a>';
				break;

			case 'addComment':
				return '<a href="/matchs/view/'.$id.'"><h5><i class="fa fa-comment-o"></i> <strong>'.$username.'</strong> vient de commenter un de vos rapports de matchs !</h5></a>';
				break;

			case 'addFriend':
				return '<a href="/profil/view/'.$id.'"><h5><i class="fa fa-coffee"></i> <strong>'.$username.'</strong> vous demande en coéquipier !</h5></a>';
				break;

			case 'validFriend':
				return '<a href="/profil/view/'.$id.'"><h5><i class="fa fa-beer"></i> <strong>'.$username.'</strong> accepte votre demande de coéquipier !</h5></a>';
				break;

			case 'birthday':
				return '<a href="/profil/view/'.$id.'"><h5><i class="fa fa-birthday-cake"></i> <strong>Joyeux Anniversaire !</strong><br>L\'équipe de FIFAGOAL vous souhaite une excellente journée !</h5></a>';
				break;

			case 'bienvenue':
				return '<a href="/profil"><h5><i class="fa fa-star"></i> <strong>Bienvenue '.$username.'</strong><br>Complète ton profil dès maintenant !</h5></a>';
				break;

			case 'admin':
				return '<a href="/"><h5><i class="fa fa-graduation-cap"></i> <strong>'.$username.'</strong> vient de vous faire passer Admin !</h5></a>';
				break;

			default:
				break;
		}
	}

	/**
	 * Verif Autorisation
	 * Vérifie que l'utilisateur est connecté et est un admin
	 */
	public function verifAutorisationAdmin (){
		if (!\Auth::check() || !\Auth::member(6)){
			\Response::redirect('/');
		}
	}

	/**
	 * Photo
	 * Récupère la photo de profil d'un user
	 * @param int $id
	 */
	public function photo ($id){
		$photouser = \Model_Photousers::query()->where('id_users', '=', $id)->get();
		if ( !empty($photouser)){
			return current($photouser);
		} else return null;
	}
}