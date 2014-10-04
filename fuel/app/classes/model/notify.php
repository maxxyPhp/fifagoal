<?php

class Model_Notify extends \Orm\Model
{
	protected static $_properties = array(
		'id',
		'id_user' => array(
			'label' => 'User',
			'default' => '',
			'null' => false,
			'form' => array('type' => 'select'),
		),
		'message' => array(
			'label' => 'Message',
			'default' => '',
			'null' => false,
			'validation' => array('required'),
		),
		'new' => array(
			'label' => 'Nouveau',
			'default' => '',
			'null' => true,
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
		'order_by' => array('id_user' => 'asc'),
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

	protected static $_table_name = 'notifications';

	// Relation Notifications >> User
	protected static $_belongs_to = array(
		'user' => array(
			'key_from' => 'id_user',
			'model_to' => 'Model\Auth_User',
			'key_to' => 'id',
			'cascade_save' => true,
			'cascade_delete' => false,
		),
	);
}