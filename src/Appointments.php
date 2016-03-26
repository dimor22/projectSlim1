<?php
/**
 * Class Appointments
 *
 * @date      3/25/16
 * @author    dave
 * @copyright Copyright (c) Infostream Group
 */

/**
 * Class Appointments
 *
 * Long description of class goes here. What is this for?
 *
 * <h4>Example</h4>
 *
 * <code>
 * // Example code goes here
 * </code>
 *
 * @see  Display a link to the documentation for an element here
 * @link Display a hyperlink to a URL in the documentation here
 */

require_once '../vendor/idiorm/idiorm.php';
require_once '../db_config.php';


class Appointments {

	// Days off Saturday and Sunday
	private $daysOff = ['6','7'];

	// Default 2 hour blocks
	private $timeBlock = 2;

	// Range from 9 AM to 9 PM
	private $dayRange = ['start' => '9', 'finish' => '9'];

	// process request params
	public function process_request( $params ) {
		switch ($params['action']){
			case "lookup":
				lookup_times($params['date']);
			case "save":
				save_appt($params);
				break;
			case "delete":
				delete_appt($params['id']);
				break;
			case "edit":
				edit_appt($params['id']);
				break;
			case "get":
				get_appt($params['id']);
				break;
			case "getAll":
				get_all_appts();
				break;
		}
	}

	// look up available times
	public function lookup_times($date){

	}
	// save appt
	public function save_appt($appt){
		$appt = ORM::for_table('appts')->create();
		$appt->date = $appt['date'];
		$appt->time = $appt['time'];
		$appt->product = $appt['product'];
		$appt->name = $appt['name'];
		$appt->address = $appt['address'];
		$appt->city = $appt['city'];
		$appt->state = $appt['state'];
		$appt->zip = $appt['zip'];
		$appt->phone = $appt['phone'];
		$appt->email = $appt['email'];
		$appt->save();

		return true;
	}
	// delete appt
	public function delete_appt($id){

	}
	// edit appt
	public function edit_appt($id){

	}
	// get appt
	public function get_appt($id){

	}
	// edit all appts
	public function get_all_appts(){

	}
}

$processAppt = new Appointments();
$processAppt->processAppt($_REQUEST);