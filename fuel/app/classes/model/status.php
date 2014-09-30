<?php

class Model_Status extends \Orm\Model
{
	protected static $_properties = array(
		'id',
		'nom' => array(
			'label' => 'Nom',
			'default' => '',
			'null' => false,
			'validation' => array('required'),
		),
		'code' => array(
			'label' => 'Code',
			'default' => '',
			'null' => false,
			'validation' => array('required'),
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

	protected static $_table_name = 'status';

	// Relation Status >> Defis
	protected static $_has_many = array(
	    'defis' => array(
	        'key_from' => 'id',
	        'model_to' => 'Model_Defis',
	        'key_to' => 'status_demande',
	        'cascade_save' => true,
	        'cascade_delete' => false,
	    )
	);
}