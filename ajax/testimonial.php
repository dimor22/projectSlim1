<?php

require_once '../vendor/idiorm/idiorm.php';
require_once '../db_config.php';


$testimonialId = $_POST['testimonialId'];
$action = $_POST['action'];

$testimonial = ORM::for_table('testimonials')->where('id', $testimonialId)->find_array();

echo json_encode($testimonial);

