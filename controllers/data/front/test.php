<?php
/**
 * User: William
 * Date: 2013/01/10 - 1:41 PM
 */
namespace controllers\data\front;
use models\models;

class test extends \controllers\data\data {
	function testing(){
		$testObject = new \models\test();

		$return = array(
			"N" => $testObject->get(),
			"S"     => \models\test::getAll()

		);
		return $GLOBALS["output"]['data'] = $return;
	}
}
