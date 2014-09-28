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

	// Relation Pays >> Selection
	protected static $_has_one = array(
	    'selection' => array(
	        'key_from' => 'id',
	        'model_to' => 'Model_Selection',
	        'key_to' => 'id_pays',
	        'cascade_save' => true,
	        'cascade_delete' => false,
	    )
	);

	// Relation Pays >> Joueur
	protected static $_many_many = array(
	    'joueurs' => array(
	        'key_from' => 'id',
	        'key_through_from' => 'id_pays', // column 1 from the table in between, should match a posts.id
	        'table_through' => 'joueurs_pays', // both models plural without prefix in alphabetical order
	        'key_through_to' => 'id_joueur', // column 2 from the table in between, should match a users.id
	        'model_to' => 'Model_Joueur',
	        'key_to' => 'id',
	        'cascade_save' => true,
	        'cascade_delete' => false,
	    )
	);
}