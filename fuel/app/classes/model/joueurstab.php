<?php

class Model_Joueurstab extends \Orm\Model
{
	protected static $_properties = array(
		'id',
		'id_joueur' => array(
			'label' => 'Joueur',
			'default' => '',
			'null' => false,
			'validation' => array('required'),
			'form' => array('type' => 'select'),
		),
		'id_tab' => array(
			'label' => 'Match',
			'default' => '',
			'null' => false,
			'validation' => array('required'),
			'form' => array('type' => 'select'),
		),
		'ordre' => array(
			'label' => 'Ordre',
			'default' => '',
			'null' => false,
			'validation' => array('required'),
		),
		'reussi' => array(
			'label' => 'Reussi',
			'default' => '',
			'null' => false,
			'validation' => array('required'),
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

	protected static $_table_name = 'joueurs_tab';

	protected static $_belongs_to = array(
	    'joueur' => array(
	        'key_from' => 'id_joueur',
	        'model_to' => 'Model_Joueur',
	        'key_to' => 'id',
	        'cascade_save' => true,
	        'cascade_delete' => false,
	    ),
	    'tab' => array(
	        'key_from' => 'id_tab',
	        'model_to' => 'Model_Tab',
	        'key_to' => 'id',
	        'cascade_save' => true,
	        'cascade_delete' => false,
	    ),
	);
}