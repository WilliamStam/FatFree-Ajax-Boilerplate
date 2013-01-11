<?php
/*
 * Date: 2012/02/23
 * Time: 12:55 PM
 */

class general {
	function __construct() {
		$this->f3 = \base::instance();
	}

	function css_min() {
		ob_start("ob_gzhandler");
		$file = (isset($_GET['file'])) ? $_GET['file'] : "";
		header("Content-Type: text/css");
		$expires = 60 * 60 * 24 * 14;
		header("Pragma: public");
		header("Cache-Control: maxage=" . $expires);
		header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $expires) . ' GMT');
		if ($file) {

			$fileDetails = pathinfo(($file));
			$base = "." . $fileDetails['dirname'] . "/";
			$file = $fileDetails['basename'];

			//echo $base."\n".$file;
			$t = file_get_contents($base . $file);

		} else {

			$files = array(
				//"/ui/_css/ui-lightness/jquery-ui.css",
				"/ui/_css/bootstrap.css",
				"/ui/_css/jquery.jscrollpane.css",
				"/ui/_css/ui.daterangepicker.css",
				"/ui/_css/select2.css",
				"/ui/_css/style.css",
			);


			$t = "";
			$base = array();
			foreach ($files as $file) {
				$fileDetails = pathinfo(($file));
				$base = "." . $fileDetails['dirname'] . "/";
				$file = $fileDetails['basename'];

				$t .= file_get_contents($base . $file);

			}


		}

		//exit($this->minify($t,"css"));
		exit($t);


	}

	function js_min() {
		ob_start("ob_gzhandler");

		$expires = 60 * 60 * 24 * 14;
		header("Pragma: public");
		header("Cache-Control: maxage=" . $expires);
		header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $expires) . ' GMT');

		$file = (isset($_GET['file'])) ? $_GET['file'] : "";
		//$file = $this->f3->get('PARAMS.filename');
		header("Content-Type: application/javascript");
		$t = "";
		if ($file) {
			$fileDetails = pathinfo(($file));
			$base = "." . $fileDetails['dirname'] . "/";
			$file = $fileDetails['basename'];
			$t = file_get_contents($base . $file);
		} else {
			$files = array(
				"/ui/_js/plugins/jquery.jscrollpane.js",
				"/ui/_js/libs/jquery-ui.js",


				"/ui/_js/libs/bootstrap.js",

				// ------ //
				"/ui/_js/plugins/date.js",
				"/ui/_js/plugins/jquery.hotkeys.js",

				"/ui/_js/plugins/jquery.daterangepicker.js",
				"/ui/_js/plugins/jquery.mousewheel.js",
				"/ui/_js/plugins/mwheelIntent.js",
				"/ui/_js/plugins/jquery.jqote2.js",
				"/ui/_js/plugins/jquery.ba-bbq.js",
				"/ui/_js/plugins/jquery.cookie.js",
				"/ui/_js/plugins/jquery.autologout.js",
				"/ui/_js/plugins/select2.js",




			);


			$t = "";
			foreach ($files as $file) {
				$fileDetails = pathinfo(($file));
				$base = "." . $fileDetails['dirname'] . "/";
				$file = $fileDetails['basename'];

				$t .= file_get_contents($base . $file);

			}


		}

		exit($t);
		//exit($this->minify($t, "js"));


	}




}
