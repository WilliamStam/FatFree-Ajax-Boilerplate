<?php
class msg {
	function __construct() {

	}

	function error($code) {
		$t = array(
			// -- publication errors
			"P01"=> array(
				"description"=> "No publication Specified",
				"resolution" => "",
				"action"     => ""
			),
			// -- publication errors
			"D01"=> array(
				"description"=> "No Date Specified",
				"resolution" => "",
				"action"     => ""
			),

			// -- User errors
			"U01"=> array(
				"description"=> "User not logged in",
				"resolution" => "Please login again",
				"action"     => "1"
			),
			// -- Booking errors
			"B01"=> array(
				"description"=> "No ID specified",
				"resolution" => "Click on a record",
				"action"     => ""
			)
		);
		return $t[$code];
	}
	function warning($code){

	}
}