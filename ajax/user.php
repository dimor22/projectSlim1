<?php
require '../vendor/autoload.php';
//
///**
// * IDIORM - ORM
// */
require_once '../vendor/idiorm/idiorm.php';
require_once '../db_config.php';


$userId = $_POST['userId'];
$action = $_POST['action'];

$user = ORM::for_table('users')->where('id', $userId)->find_array();

echo json_encode($user);

