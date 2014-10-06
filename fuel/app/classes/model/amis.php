<?php

class Model_Amis extends \Orm\Model
{
	protected static $_properties = array(
		'id',
		'id_user1',
		'id_user2',
		'valider' => array(
			'label' => 'Valider',
			'default' => '',
			'null' => false,
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

	protected static $_table_name = 'amis';

	// Relation Amis >> User
	protected static $_belongs_to = array(
	    'user' => array(
	        'key_from' => 'id_user1',
	        'model_to' => 'Model\Auth_User',
	        'key_to' => 'id',
	        'cascade_save' => true,
	        'cascade_delete' => false,
	    ),
	    'user_inverse' => array(
	        'key_from' => 'id_user2',
	        'model_to' => 'Model\Auth_User',
	        'key_to' => 'id',
	        'cascade_save' => true,
	        'cascade_delete' => false,
	    )
	);
}