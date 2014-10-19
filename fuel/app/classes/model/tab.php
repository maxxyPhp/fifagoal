<?php

class Model_Tab extends \Orm\Model
{
	protected static $_properties = array(
		'id',
		'id_match' => array(
			'label' => 'Match',
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

	protected static $_table_name = 'tab';

	// Relation Tab >> Tireurs
	protected static $_has_many = array(
	    'tireurs' => array(
	        'key_from' => 'id',
	        'model_to' => 'Model_Joueurstab',
	        'key_to' => 'id_joueur',
	        'cascade_save' => true,
	        'cascade_delete' => false,
	    ),
	);

	// Relation Tab >> Matchs
	protected static $_has_one = array(
	    'match' => array(
	        'key_from' => 'id_match',
	        'model_to' => 'Model_Matchs',
	        'key_to' => 'id',
	        'cascade_save' => true,
	        'cascade_delete' => false,
	    ),
	);
}