<?php

/**
 * ORM CONFIG FILE
 */

// Autodetect remote or local host
if ($_SERVER['SERVER_NAME'] == 'localhost') {
	// Local CONSTANT variables
	require_once 'development.php';
} else {
	// Production CONSTANT variables
	require_once 'production.php';
}
ORM::configure([
	'connection_string' => 'mysql:host='. DB_HOST .';dbname='. DB_NAME,
	'username' => DB_USER,
	'password' => DB_PWD
]);
ORM::configure('return_result_sets', true); // returns result sets
ORM::configure('logging', true); // access with ORM::get_last_query() or ORM::get_query_log()