<?php

class Controller_Migrations extends \Controller_Gestion
{
	public function action_index (){
		$this->verifAutorisation();

		
		/**
		 *
		 * PAYS
		 *
		 */
		if (!\DBUtil::table_exists('pays')){
			\DBUtil::create_table('pays', array(
				'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true, 'unsigned' => true),
				'nom' => array('constraint' => 255, 'type' => 'varchar'),
				'drapeau' => array('constraint' => 255, 'type' => 'varchar'),
				'created_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
				'updated_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
			), array('id'));
		}

		/**
		 *
		 * CHAMPIONNAT
		 *
		 */
		if (!\DBUtil::table_exists('championnat')){
			\DBUtil::create_table('championnat', array(
				'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true, 'unsigned' => true),
				'nom' => array('constraint' => 255, 'type' => 'varchar'),
				'logo' => array('constraint' => 255, 'type' => 'varchar'),
				'id_pays' => array('constraint' => 11, 'type' => 'int'),
				'created_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
				'updated_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
			), array('id'));
		}

		/**
		 *
		 * EQUIPES
		 *
		 */
		if (!\DBUtil::table_exists('equipes')){
			\DBUtil::create_table('equipes', array(
				'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true, 'unsigned' => true),
				'nom' => array('constraint' => 255, 'type' => 'varchar'),
				'nom_court' => array('constraint' => 255, 'type' => 'varchar'),
				'logo' => array('constraint' => 255, 'type' => 'varchar'),
				'id_championnat' => array('constraint' => 11, 'type' => 'int'),
				'created_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
				'updated_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
			), array('id'));
		}

		/**
		 *
		 * SELECTIONS
		 *
		 */
		if (!\DBUtil::table_exists('selections')){
			\DBUtil::create_table('selections', array(
				'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true, 'unsigned' => true),
				'nom' => array('constraint' => 255, 'type' => 'varchar'),
				'logo' => array('constraint' => 255, 'type' => 'varchar'),
				'id_pays' => array('constraint' => 11, 'type' => 'int'),
				'created_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
				'updated_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
			), array('id'));
		}

		/**
		 *
		 * POSTE
		 *
		 */
		if (!\DBUtil::table_exists('poste')){
			\DBUtil::create_table('poste', array(
				'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true, 'unsigned' => true),
				'nom' => array('constraint' => 255, 'type' => 'varchar'),
				'created_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
				'updated_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
			), array('id'));
		}

		/**
		 *
		 * JOUEURS
		 *
		 */
		if (!\DBUtil::table_exists('joueurs')){
			\DBUtil::create_table('joueurs', array(
				'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true, 'unsigned' => true),
				'nom' => array('constraint' => 255, 'type' => 'varchar'),
				'prenom' => array('constraint' => 255, 'type' => 'varchar', 'null' => true),
				'photo' => array('constraint' => 255, 'type' => 'varchar'),
				'id_equipe' => array('constraint' => 11, 'type' => 'int'),
				'id_poste' => array('constraint' => 11, 'type' => 'int'),
				'id_selection' => array('constraint' => 11, 'type' => 'int', 'null' => true),
				'created_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
				'updated_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
			), array('id'));
		}

		/**
		 *
		 * JOUEURS_PAYS
		 *
		 */
		if (!\DBUtil::table_exists('joueurs_pays')){
			\DBUtil::create_table('joueurs_pays', array(
				'id_joueur' => array('constraint' => 11, 'type' => 'int'),
				'id_pays' => array('constraint' => 11, 'type' => 'int'),
			), array('id'));
		}

		/**
		 *
		 * PHOTOUSER
		 *
		 */
		if (!\DBUtil::table_exists('photo_user')){
			\DBUtil::create_table('photo_user', array(
				'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true, 'unsigned' => true),
				'photo' => array('constraint' => 255, 'type' => 'varchar'),
				'id_users' => array('constraint' => 11, 'type' => 'int'),
				'created_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
				'updated_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
			), array('id'));
		}

		/**
		 *
		 * AMIS
		 *
		 */
		if (!\DBUtil::table_exists('amis')){
			\DBUtil::create_table('amis', array(
				'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true, 'unsigned' => true),
				'id_user1' => array('constraint' => 11, 'type' => 'int'),
				'id_user2' => array('constraint' => 11, 'type' => 'int'),
				'valider' => array('constraint' => 4, 'type' => 'tinyint'),
				'created_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
				'updated_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
			), array('id'));
		}

		/**
		 *
		 * STATUS
		 *
		 */
		if (!\DBUtil::table_exists('status')){
			\DBUtil::create_table('status', array(
				'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true, 'unsigned' => true),
				'nom' => array('constraint' => 255, 'type' => 'varchar'),
				'code' => array('constraint' => 11, 'type' => 'int'),
				'created_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
				'updated_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
			), array('id'));
		}

		/**
		 *
		 * DEFIS
		 *
		 */
		if (!\DBUtil::table_exists('defis')){
			\DBUtil::create_table('defis', array(
				'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true, 'unsigned' => true),
				'id_joueur_defieur' => array('constraint' => 11, 'type' => 'int'),
				'id_joueur_defier' => array('constraint' => 11, 'type' => 'int'),
				'status_demande' => array('constraint' => 11, 'type' => 'int'),
				'id_match' => array('constraint' => 11, 'type' => 'int', 'null' => true),
				'match_valider1' => array('constraint' => 4, 'type' => 'tinyint'),
				'match_valider2' => array('constraint' => 4, 'type' => 'tinyint'),
				'created_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
				'updated_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
			), array('id'));
		}

		/**
		 *
		 * MATCHS
		 *
		 */
		if (!\DBUtil::table_exists('matchs')){
			\DBUtil::create_table('matchs', array(
				'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true, 'unsigned' => true),
				'id_joueur1' => array('constraint' => 11, 'type' => 'int'),
				'id_joueur2' => array('constraint' => 11, 'type' => 'int'),
				'id_equipe1' => array('constraint' => 11, 'type' => 'int'),
				'id_equipe2' => array('constraint' => 11, 'type' => 'int'),
				'score_joueur1' => array('constraint' => 11, 'type' => 'int'),
				'score_joueur2' => array('constraint' => 11, 'type' => 'int'),
				'prolongation' => array('constraint' => 4, 'type' => 'tinyint'),
				'id_tab' => array('constraint' => 11, 'type' => 'int', 'null' => true),
				'created_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
				'updated_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
			), array('id'));
		}

		/**
		 *
		 * COMMENTAIRES_MATCHS
		 *
		 */
		if (!\DBUtil::table_exists('commentaires_matchs')){
			\DBUtil::create_table('commentaires_matchs', array(
				'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true, 'unsigned' => true),
				'id_user' => array('constraint' => 11, 'type' => 'int'),
				'id_match' => array('constraint' => 11, 'type' => 'int'),
				'commentaire' => array('constraint' => 255, 'type' => 'text'),
				'created_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
				'updated_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
			), array('id'));
		}

		/**
		 *
		 * LIKE
		 *
		 */
		if (!\DBUtil::table_exists('like')){
			\DBUtil::create_table('like', array(
				'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true, 'unsigned' => true),
				'id_user' => array('constraint' => 11, 'type' => 'int'),
				'id_match' => array('constraint' => 11, 'type' => 'int'),
				'created_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
				'updated_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
			), array('id'));
		}

		/**
		 *
		 * NOTIFICATIONS
		 *
		 */
		if (!\DBUtil::table_exists('notifications')){
			\DBUtil::create_table('notifications', array(
				'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true, 'unsigned' => true),
				'id_user' => array('constraint' => 11, 'type' => 'int'),
				'message' => array('constraint' => 255, 'type' => 'varchar'),
				'new' => array('constraint' => 4, 'type' => 'tinyint'),
				'created_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
				'updated_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
			), array('id'));
		}

		/**
		 *
		 * BUTEURS
		 *
		 */
		if (!\DBUtil::table_exists('buteurs')){
			\DBUtil::create_table('buteurs', array(
				'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true, 'unsigned' => true),
				'id_match' => array('constraint' => 11, 'type' => 'int'),
				'id_joueur' => array('constraint' => 11, 'type' => 'int'),
				'minute' => array('constraint' => 11, 'type' => 'int'),
				'created_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
				'updated_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
			), array('id'));
		}

		/**
		 *
		 * TAB
		 *
		 */
		if (!\DBUtil::table_exists('tab')){
			\DBUtil::create_table('tab', array(
				'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true, 'unsigned' => true),
				'id_match' => array('constraint' => 11, 'type' => 'int'),
				'score_joueur1' => array('constraint' => 11, 'type' => 'int'),
				'score_joueur2' => array('constraint' => 11, 'type' => 'int'),
				'created_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
				'updated_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
			), array('id'));
		}

		/**
		 *
		 * JOUEURSTAB
		 *
		 */
		if (!\DBUtil::table_exists('joueurs_tab')){
			\DBUtil::create_table('joueurs_tab', array(
				'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true, 'unsigned' => true),
				'id_joueur' => array('constraint' => 11, 'type' => 'int'),
				'id_tab' => array('constraint' => 11, 'type' => 'int'),
				'ordre' => array('constraint' => 11, 'type' => 'int'),
				'reussi' => array('constraint' => 4, 'type' => 'tinyint'),
				'created_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
				'updated_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
			), array('id'));
		}

		\Response::redirect('/');
	}
}