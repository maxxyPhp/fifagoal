<?php

class Model_Joueur extends \Orm\Model
{
	protected static $_properties = array(
		'id',
		'nom' => array(
			'label' => 'Nom',
			'default' => '',
			'null' => false,
			'validation' => array('required'),
		),
		'prenom' => array(
			'label' => 'Prénom',
			'default' => '',
			'null' => true,
		),
		'id_poste' => array(
			'label' => 'Poste',
			'default' => '',
			'null' => false,
			'validation' => array('required'),
			'form' => array('type' => 'select'),
		),
		'photo' => array(
			'label' => 'Photo',
			'default' => '',
			'null' => false,
			'form' => array('type' => 'file'),
		),
		'id_equipe' => array(
			'label' => 'Equipe',
			'default' => '',
			'null' => true,
			'form' => array('type' => 'select'),
		),
		'id_selection' => array(
			'label' => 'Selection',
			'default' => '',
			'null' => true,
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

	protected static $_table_name = 'joueurs';

	// Relation Joueur >> Equipe
	protected static $_has_one = array(
	    'equipe' => array(
	        'key_from' => 'id_equipe',
	        'model_to' => 'Model_Equipe',
	        'key_to' => 'id',
	        'cascade_save' => true,
	        'cascade_delete' => false,
	    ),
	);

	// Relation Joueur >> Selection
	protected static $_belongs_to = array(
	    'selection' => array(
	        'key_from' => 'id_selection',
	        'model_to' => 'Model_Selection',
	        'key_to' => 'id',
	        'cascade_save' => true,
	        'cascade_delete' => false,
	    ),
	    'poste' => array(
	        'key_from' => 'id_poste',
	        'model_to' => 'Model_Poste',
	        'key_to' => 'id',
	        'cascade_save' => true,
	        'cascade_delete' => false,
	    )
	);

	// Relation Joueurs >> Pays
	protected static $_many_many = array(
	    'pays' => array(
	        'key_from' => 'id',
	        'key_through_from' => 'id_joueur', // column 1 from the table in between, should match a posts.id
	        'table_through' => 'joueurs_pays', // both models plural without prefix in alphabetical order
	        'key_through_to' => 'id_pays', // column 2 from the table in between, should match a users.id
	        'model_to' => 'Model_Pays',
	        'key_to' => 'id',
	        'cascade_save' => true,
	        'cascade_delete' => false,
	    )
	);
}