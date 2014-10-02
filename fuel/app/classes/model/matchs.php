<?php

class Model_Matchs extends \Orm\Model
{
	protected static $_properties = array(
		'id',
		'id_joueur1' => array(
			'label' => 'Joueur1',
			'default' => '',
			'null' => false,
			'validation' => array('required'),
			'form' => array('type' => 'select'),
		),
		'id_joueur2' => array(
			'label' => 'Joueur2',
			'default' => '',
			'null' => false,
			'validation' => array('required'),
			'form' => array('type' => 'select'),
		),
		'id_equipe1' => array(
			'label' => 'Equipe1',
			'default' => '',
			'null' => false,
			'validation' => array('required'),
			'form' => array('type' => 'select'),
		),
		'id_equipe2' => array(
			'label' => 'Equipe2',
			'default' => '',
			'null' => false,
			'validation' => array('required'),
			'form' => array('type' => 'select'),
		),
		'score_joueur1' => array(
			'label' => 'Score joueur 1',
			'default' => '',
			'null' => false,
			'validation' => array('required'),
			'form' => array('type' => 'select'),
		),
		'score_joueur2' => array(
			'label' => 'Score joueur 2',
			'default' => '',
			'null' => false,
			'validation' => array('required'),
			'form' => array('type' => 'select'),
		),
		'commentaire' => array(
			'label' => 'Commentaire',
			'default' => '',
			'null' => true,
			'form' => array('type' => 'textarea'),
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

	protected static $_table_name = 'matchs';

	// Relation Matchs >> Defis
	protected static $_has_one = array(
	    'defis' => array(
	        'key_from' => 'id',
	        'model_to' => 'Model_Defis',
	        'key_to' => 'id_match',
	        'cascade_save' => true,
	        'cascade_delete' => false,
	    )
	);

	protected static $_belongs_to = array(
	    'equipe1' => array(
	        'key_from' => 'id_equipe1',
	        'model_to' => 'Model_Equipe',
	        'key_to' => 'id',
	        'cascade_save' => true,
	        'cascade_delete' => false,
	    ),
	    'equipe2' => array(
	        'key_from' => 'id_equipe2',
	        'model_to' => 'Model_Equipe',
	        'key_to' => 'id',
	        'cascade_save' => true,
	        'cascade_delete' => false,
	    ),
	);
}