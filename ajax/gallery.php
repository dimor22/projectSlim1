<?php

require_once '../vendor/idiorm/idiorm.php';
require_once '../db_config.php';


$galleryPhotoId = $_POST['galleryPhoto'];

$galleryPhoto = ORM::for_table('photos')->where('id', $galleryPhotoId)->find_array();

echo json_encode($galleryPhoto);

