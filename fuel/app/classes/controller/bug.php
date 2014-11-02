<?php 

class Controller_Bug extends \Controller_Front
{
	public function action_index (){
		return $this->view('bug/index', array());
	}

	public function action_add (){
		if (\Input::post('add')){
			$mail = \Email::forge();
			$mail->from(\Auth::get_email(), \Auth::get('username'));
			$mail->to(\Config::get('bug.mail.address'), \Config::get('bug.mail.name'));

			$mail->subject('BUG FIFAGOAL : '.$this->secure(\Input::post('sujet')));

			$mail->body($this->secure(\Input::post('message')));

			if (\Input::post('image') && file_exists(DOCROOT . \Config::get('bug.path') . '/' . $this->secure(\Input::post('image')))){
				$mail->attach(DOCROOT . \Config::get('bug.path') . '/' . $this->secure(\Input::post('image')));
			}

			$mail->priority(\Email::P_HIGH);

			try {
				$mail->send();
			} catch (\EmailValidationFailedException $e){
				\Messages::error('Il y a une erreur empêchant la validation du mail');
				\Response::redirect('/bug');
			} catch (\EmailSendingFailedException $e){
				\Messages::error('Une erreur est survenue pendant l\'envoi du mail');
				\Response::redirect('/bug');
			}

			\Messages::success('Le message a bien été transmit. Un administrateur s\'en chargera dans les plus brefs délais.');
			\Response::redirect('/');
		}

		\Response::redirect_back();
	}

	/**
	 * Upload des images en AJAX (POST only)
	 */
	public function post_uploadImage (){
		if (!empty($_FILES)){

			$uploadConfig = array(
				'path' => DOCROOT . \Config::get('bug.path'),
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
						\Response::redirect('/');
					}
				}
			}

			foreach (\Upload::get_files() as $file){
				return $file['saved_as'];
			}
		}
	}
}