<?php

require_once '../vendor/idiorm/idiorm.php';
require_once '../db_config.php';


$params = $_REQUEST;

$appt = ORM::for_table('appts')->create();
$appt->date = $params['date'];
$appt->time = $params['time'];
$appt->product = $params['product'];
$appt->name = $params['name'];
$appt->address = $params['address'];
$appt->city = $params['city'];
$appt->state = $params['state'];
$appt->zip = $params['zip'];
$appt->phone = $params['phone'];
$appt->email = $params['email'];
$appt->save();

echo json_encode($testimonial);

