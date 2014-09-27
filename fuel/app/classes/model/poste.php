<?php

class Model_Poste extends \Orm\Model
{
	protected static $_properties = array(
		'id',
		'nom' => array(
			'label' => 'Nom',
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
	
	protected static $_table_name = 'poste';

	// Realtion Poste >> Joueur
	protected static $_has_many = array(
	    'joueurs' => array(
	        'key_from' => 'id',
	        'model_to' => 'Model_Joueur',
	        'key_to' => 'id_poste',
	        'cascade_save' => true,
	        'cascade_delete' => false,
	    )
	);
}