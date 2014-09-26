<?php

class Model_Selection extends \Orm\Model
{
	protected static $_properties = array(
		'id',
		'nom' => array(
			'label' => 'Nom',
			'default' => '',
			'null' => false,
			'validation' => array('required'),
		),
		'logo' => array(
			'label' => 'Logo',
			'default' => '',
			'null' => false,
			'form' => array('type' => 'file'),
		),
		'id_pays' => array(
			'label' => 'Pays',
			'default' => '',
			'null' => false,
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
		'oder_by' => array('nom' => 'asc'),
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

	protected static $_table_name = 'selections';

	// Relation Selection >> Pays
	protected static $_belongs_to = array(
	    'pays' => array(
	        'key_from' => 'id_pays',
	        'model_to' => 'Model_Pays',
	        'key_to' => 'id',
	        'cascade_save' => true,
	        'cascade_delete' => false,
	    )
	);

	// Relation Selection >> Joueur
	protected static $_has_many = array(
	    'joueurs' => array(
	        'key_from' => 'id',
	        'model_to' => 'Model_Joueur',
	        'key_to' => 'id_selection',
	        'cascade_save' => true,
	        'cascade_delete' => false,
	    )
	);
}