<?php

require_once '../vendor/idiorm/idiorm.php';
require_once '../db_config.php';
require_once '../src/app_config.php';


$before = $_POST['before'];
$after = $_POST['after'];
$category = $_POST['category'];

//$newBeaf = ORM::for_table('beaf')->create();
//$newBeaf->before = $before;
//$newBeaf->after = $after;
//$newBeaf->category = $category;
//$newBeaf->save();

header( 'Location: http://localhost:8888/projectSlim1/admin/photos' ) ;

//$beafsDisplay = [];
//$beafs = ORM::for_table('beaf')->find_result_set();
//for ($i=0; $i < count($beafs); $i++){
//	$beafsDisplay[$i]['before'] =  $beafs[$i]->before;
//	$beafsDisplay[$i]['after'] =  $beafs[$i]->after;
//	$beafsDisplay[$i]['category'] =  $beafs[$i]->category;
//	$beafsDisplay[$i]['id'] =  $beafs[$i]->id;
//}
//echo json_encode('beaf saved');

