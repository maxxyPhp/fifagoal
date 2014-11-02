<?php

class Controller_Contact extends \Controller_Front
{
	public function action_index (){
		return $this->view('contact/index', array());
	}

	public function action_add (){
		if (\Input::post('add')){
			$mail = \Email::forge();
			$mail->from(\Auth::get_email(), \Auth::get('username'));
			$mail->to(\Config::get('contact.mail.address'), \Config::get('contact.mail.name'));

			$mail->subject('Contact FIFAGOAL : '.$this->secure(\Input::post('sujet')));

			$mail->body($this->secure(\Input::post('message')));

			$mail->priority(\Email::P_HIGH);

			try {
				$mail->send();
			} catch (\EmailValidationFailedException $e){
				\Messages::error('Il y a une erreur empêchant la validation du message');
				\Response::redirect('/contact');
			} catch (\EmailSendingFailedException $e){
				\Messages::error('Une erreur est survenue pendant l\'envoi du message');
				\Response::redirect('/contact');
			}

			\Messages::success('Le message a bien été transmit.');
			\Response::redirect('/');
		}

		\Response::redirect('/');
	}
}