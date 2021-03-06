<?php
return array(
	'_root_'  => 'home/index',  // The default route
	'_404_'   => 'home/404',    // The main 404 route
	
	'hello(/:name)?' => array('welcome/hello', 'name' => 'hello'),
	'auth' => array('auth/index', 'name' => 'auth'),

	'users/admin/:id' => 'users/admin',
	'users/delete/:id' => 'users/delete',
);