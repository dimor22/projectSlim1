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
				$this->lookup_times($params['date']);
			case "save":
				$this->save_appt($params);
				break;
			case "delete":
				$this->delete_appt($params['id']);
				break;
			case "edit":
				$this->edit_appt($params['id']);
				break;
			case "get":
				$this->get_appt($params['id']);
				break;
			case "getAll":
				$this->get_all_appts();
				break;
		}
	}

	// look up available times
	public function lookup_times($date){
		$times = [];
		$appts = ORM::for_table('appts')->where('date', $date)->find_many();
		if ($appts->count() > 0 ){
//			for ( $i = 0; $i < $appts->count(); $i++) {
//				$times[] = $appts->time;
//			}
			foreach($appts as $appt) {
				$times[] = $appt->time;
			}
			echo json_encode($times);
		} else {
			echo json_encode(['response'=> 'all available']);
		}
	}
	// save appt
	public function save_appt($params){
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
$processAppt->process_request($_REQUEST);