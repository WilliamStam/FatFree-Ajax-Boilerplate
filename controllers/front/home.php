<?php
/**
 * User: William
 * Date: 2013/01/10 - 1:16 PM
 */
namespace controllers\front;
class home {
	function __construct() {
		$this->f3 = \base::instance();
	}

	function page() {

		$tmpl = new \template("template.tmpl", "ui/front/",true);
		$tmpl->page = array(
			"template"    => "home",
			"meta"        => array(
				"title" => "Welcome to the framework",
			)
		);
		$tmpl->output();

	}
}
