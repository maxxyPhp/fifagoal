<?php

namespace Model;

class Auth_User extends \Auth\Model\Auth_User
{

    /**
     * @var array	model properties
     */
    protected static $_properties = array(
        'id',
        'username' => array(
            'label' => 'auth_model_user.name',
            'default' => 0,
            'null' => false,
            'validation' => array('required', 'max_length' => array(255))
        ),
        'email' => array(
            'label' => 'auth_model_user.email',
            'default' => 0,
            'null' => false,
            'validation' => array('required', 'valid_email')
        ),
        'group_id' => array(
            'label' => 'auth_model_user.group_id',
            'default' => 0,
            'null' => false,
            'form' => array('type' => 'select'),
            'validation' => array('required', 'is_numeric')
        ),
        'password' => array(
            'label' => 'auth_model_user.password',
            'default' => 0,
            'null' => false,
            'form' => array('type' => 'password'),
            'validation' => array('min_length' => array(8), 'match_field' => array('confirm'))
        ),
        'last_login' => array(
            'form' => array('type' => false),
        ),
        'previous_login' => array(
            'form' => array('type' => false),
        ),
        'login_hash' => array(
            'form' => array('type' => false),
        ),
        'user_id' => array(
            'default' => 0,
            'null' => false,
            'form' => array('type' => false),
        ),
        'created_at' => array(
            'default' => 0,
            'null' => false,
            'form' => array('type' => false),
        ),
        'updated_at' => array(
            'default' => 0,
            'null' => false,
            'form' => array('type' => false),
        ),
    );

    // Relation Users >> Photo users
    protected static $_has_one = array(
        'photouser' => array(
            'key_from' => 'id',
            'model_to' => 'Model_Photousers',
            'key_to' => 'id_users',
            'cascade_save' => true,
            'cascade_delete' => false,
        )
    );

}
