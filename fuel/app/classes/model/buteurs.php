<?php 

class Model_Buteurs extends \Orm\Model
{
	protected static $_properties = array(
		'id',
		'id_match',
		'id_joueur',
		'minute' => array(
			'label' => 'Minute',
			'default' => '',
			'null' => true,
			'form' => array('type' => 'number'),
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

	protected static $_table_name = 'buteurs';

	protected static $_belongs_to = array(
		'joueur' => array(
			'key_from' => 'id_joueur',
			'model_to' => 'Model_Joueur',
			'key_to' => 'id',
			'cascade_save' => true,
			'cascade_delete' => false,
		),
		'match' => array(
			'key_from' => 'id_match',
			'model_to' => 'Model_Matchs',
			'key_to' => 'id',
			'cascade_save' => true,
			'cascade_delete' => false,
		),
	);
}