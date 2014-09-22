<?php
/**
 * Fuel is a fast, lightweight, community driven PHP5 framework.
 *
 * @package    Fuel
 * @version    1.7
 * @author     Fuel Development Team
 * @license    MIT License
 * @copyright  2010 - 2013 Fuel Development Team
 * @link       http://fuelphp.com
 */

/**
 * The Welcome Controller.
 *
 * A basic controller example.  Has examples of how to set the
 * response body and status.
 *
 * @package  app
 * @extends  Controller
 */
class Controller_Welcome extends Controller
{

	/**
	 * The basic welcome message
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_index()
	{
		return Response::forge(View::forge('perso/index'));
	}
	
	public function get_api($context = ''){
        switch ($context){
            case 'sendMail':
            var_dump('ok');die();
            	\Package::load('email');
            	$email_data['nom'] = $nom = htmlspecialchars(\Input::get('nom'));
            	$email_data['mail'] = $mail = htmlspecialchars(\Input::get('mail'));
            	$email_data['sujet'] = $sujet = htmlspecialchars(\Input::get('sujet'));
            	$email_data['message'] = $message = htmlspecialchars(\Input::get('message'));

            	$email = \Email::forge();
            	$email->from($mail, $nom);
            	$email->to('maximilien.beaussart@gmail.com', 'BEAUSSART Maximilien');
            	$email->subject($sujet);
            	$email->html_body(\View::forge('email/template', array('email_data' => $email_data))->render());

            	try {
            		$email->send();
            	} catch (\EmailValidationFailedException $e){
            		// \Messages::error('Erreur de validation de l\'email.');
            		\Response::redirect_back();
            	} catch (\EmailSendingFailedException $e){
            		// \Messages::error('Erreur lors de l\'envoi de l\'email');
            		\Response::redirect_back();
            	}

            	return $this->response('ok');

            break;
        }
    }

	/**
	 * The 404 action for the application.
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_404()
	{
		return Response::forge(ViewModel::forge('welcome/404'), 404);
	}
}
