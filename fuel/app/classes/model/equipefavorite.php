<?php

class Model_Equipefavorite extends \Orm\Model {
	protected static $_properties = array(
		'id',
		'id_user',
		'equipe_fav' => array(
			'label' => 'Equipe favorite',
		),
		'nom' => array(
			'label' => 'Nom',
		),
	);

	// protected static $_table_name = 'equipe_favorite';

	protected static $_belongs_to = array(
        'user' => array(
            'key_from' => 'id_user',
            'model_to' => 'Model\Auth_User',
            'key_to' => 'id',
            'cascade_save' => true,
            'cascade_delete' => true,
        )
    );	
}