<?php

require_once '../vendor/idiorm/idiorm.php';
require_once '../db_config.php';
require_once '../src/app_config.php';


$before = $_POST['before'];
$after = $_POST['after'];
$category = $_POST['category'];

$newBeaf = ORM::for_table('beaf')->create();
$newBeaf->before = $before;
$newBeaf->after = $after;
$newBeaf->category = $category;
$newBeaf->save();


