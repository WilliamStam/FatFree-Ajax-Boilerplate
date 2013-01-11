<?php
/**
 * User: William
 * Date: 2013/01/10 - 1:42 PM
 */
namespace controllers\data;
class data {
	protected $f3;

	function __construct() {
		$this->f3 = \Base::instance();
	}

	function __destruct() {

	}
}
