<?php

class Model_Equipe extends \Orm\Model
{
	protected static $_properties = array(
		'id',
		'nom' => array(
			'label' => 'Nom',
			'default' => '',
			'null' => false,
			'validation' => array('required'),
		),
		'nom_court' => array(
			'label' => 'Nom court',
			'default' => '',
			'null' => true,
		),
		'logo' => array(
			'label' => 'Logo',
			'default' => '',
			'null' => false,
			'validation' => array('required'),
			'form' => array('type' => 'file'),
		),
		'id_championnat' => array(
			'label' => 'Championnat',
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

	protected static $_table_name = 'equipes';

	// Relation Equipe >> Championnat
	protected static $_has_one = array(
	    'championnat' => array(
	        'key_from' => 'id_championnat',
	        'model_to' => 'Model_Championnat',
	        'key_to' => 'id',
	        'cascade_save' => true,
	        'cascade_delete' => false,
	    )
	);
}