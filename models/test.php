<?php
/**
 * User: William
 * Date: 2013/01/10 - 1:34 PM
 */
namespace models;
class test extends models{
	private $classname;

	public function __construct() {
		parent::__construct();


	}


	function get(){
		$timer = new \timer(); $f3 = \Base::instance();

		// ----------------- models stuff here -----------------
			$return = array(
				"Date"=> date("Y-m-d (H:i:s)"),
				"class"=> __CLASS__,
				"method"=> __FUNCTION__,
				"F3"=>array(
					"package"=> $f3->get("PACKAGE")
				),
			);
		// ----------------- models stuff here -----------------



		$timer->stop(array( "Models" => array("Class"  => __CLASS__,"Method" => __FUNCTION__)), func_get_args());
		return $return;
	}
	public static function getAll(){
		$timer = new \timer(); $f3 = \Base::instance();

		// ----------------- models stuff here -----------------
			$return = array(
				"class"  => __CLASS__,
				"method" => __FUNCTION__,
				"F3" => array(
					"version" => $f3->get("VERSION")
				),

			);
		// ----------------- models stuff here -----------------

		$timer->stop(array( "Models" => array("Class"  => __CLASS__,"Method" => __FUNCTION__)), func_get_args());
		return $return;
	}



}
