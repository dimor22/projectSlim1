<?php

/**
 * ORM CONFIG FILE
 */
ORM::configure([
	'connection_string' => 'mysql:host=localhost;dbname=slimdb1',
	'username' => 'root',
	'password' => 'root'
]);
ORM::configure('return_result_sets', true); // returns result sets
ORM::configure('logging', true); // access with ORM::get_last_query() or ORM::get_query_log()