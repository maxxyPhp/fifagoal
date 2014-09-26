<?php

class Model_Pays extends \Orm\Model
{
	protected static $_properties = array(
		'id',
		'nom' => array(
			'label' => 'Nom',
			'default' => '',
			'null' => false,
			'validation' => array('required'),
		),
		'drapeau' => array(
			'label' => 'Drapeau',
			'default' => '',
			'null' => false,
			'validation' => array('required'),
			'form' => array('type' => 'file'),
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
		'order_by' => array('nom' => 'asc'),
	);
	
	//Maj lors de la création et la modification des données 
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
	
	protected static $_table_name = 'pays';

	// Realtion Pays >> Championnat
	protected static $_has_many = array(
	    'championnat' => array(
	        'key_from' => 'id',
	        'model_to' => 'Model_Championnat',
	        'key_to' => 'id_pays',
	        'cascade_save' => true,
	        'cascade_delete' => true,
	    )
	);
}