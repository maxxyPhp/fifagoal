<?php

class Model_Photousers extends \Orm\Model
{
	protected static $_properties = array(
		'id',
		'photo' => array(
			'label' => 'Photo',
			'default' => '',
			'null' => false,
			'validation' => array('required'),
		),
		'id_users' => array(
			'label' => 'User',
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
		'oder_by' => array('id_user' => 'asc'),
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

	protected static $_table_name = 'photo_user';

	// Relation Photo user >> Users
	protected static $_belongs_to = array(
	    'users' => array(
	        'key_from' => 'id_user',
	        'model_to' => 'Model\Auth_User',
	        'key_to' => 'id',
	        'cascade_save' => true,
	        'cascade_delete' => false,
	    )
	);
}