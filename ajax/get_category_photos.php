<?php

require_once '../vendor/idiorm/idiorm.php';
require_once '../db_config.php';

$category = $_POST['category'];
$photos = ORM::for_table('photos')->where('album', $category)->find_array();
echo json_encode($photos);

