<?php

class Model_Defis extends \Orm\Model
{
	protected static $_properties = array(
		'id',
		'id_joueur_defieur' => array(
			'label' => 'Joueur défieur',
			'default' => '',
			'null' => false,
			'validation' => array('required'),
			'form' => array('type' => 'select'),
		),
		'id_joueur_defier' => array(
			'label' => 'Joueur2',
			'default' => '',
			'null' => false,
			'validation' => array('required'),
			'form' => array('type' => 'select'),
		),
		'status_demande' => array(
			'label' => 'Status de la demande',
			'default' => '',
			'null' => false,
			'validation' => array('required'),
			'form' => array('type' => 'select'),
		),
		'id_match' => array(
			'label' => 'Match',
			'default' => '',
			'null' => true,
			'form' => array('type' => 'select'),
		),
		'match_valider1' => array(
			'label' => 'Match validé',
			'default' => '',
			'null' => true,
			'form' => array('type' => 'checkbox'),
		),
		'match_valider2' => array(
			'label' => 'Match validé',
			'default' => '',
			'null' => true,
			'form' => array('type' => 'checkbox'),
		),
		'created_at' => array(
			'form' => array('type' => false),
			'default' => 0,
			'null' => false,
		),
		'updated_at' => array(
			'form' => array('type' => false),
			'default' => 0,
			'null' => false,
		),
	);

	protected static $_conditions = array(
		'order_by' => array('id' => 'asc'),
	);

	protected static $_observers = array(
		'\Orm\Observer_CreatedAt' => array(
			'events' => array('before_insert'),
			'mysql_timestamp' => false,
		),
		'\Orm\Observer_UpdatedAt' => array(
			'events' => array('before_update'),
			'mysql_timestamp' => false,
		),
	);

	protected static $_table_name = 'defis';

	// Relation Defis >> Status
	protected static $_belongs_to = array(
	    'status' => array(
	        'key_from' => 'status_demande',
	        'model_to' => 'Model_Status',
	        'key_to' => 'id',
	        'cascade_save' => true,
	        'cascade_delete' => false,
	    ),
	    //Relation Defis >> Matchs
	    'match' => array(
	        'key_from' => 'id_match',
	        'model_to' => 'Model_Matchs',
	        'key_to' => 'id',
	        'cascade_save' => true,
	        'cascade_delete' => false,
	    ),
	    'defieur' => array(
	        'key_from' => 'id_joueur_defieur',
	        'model_to' => 'Model\Auth_User',
	        'key_to' => 'id',
	        'cascade_save' => true,
	        'cascade_delete' => false,
	    ),
	    'defier' => array(
	        'key_from' => 'id_joueur_defier',
	        'model_to' => 'Model\Auth_User',
	        'key_to' => 'id',
	        'cascade_save' => true,
	        'cascade_delete' => false,
	    ),
	);
}
