<?php
/*
 * Date: 2011/08/04
 * Time: 8:26 AM
 */

class timer {
	private $startTimer;
	private $endTimer;
	private $totalTime;
	private $force;

	function __construct($force = false) {
		$this->force = $force;
		$this->f3 = Base::instance();
		if (!isset($GLOBALS["timer"])) {
			$GLOBALS["timer"] = array();
		}
		$this->_start();
	}

	private function _start() {
		$mtime = microtime();
		$mtime = explode(" ", $mtime);
		$this->startTimer = $mtime[1] + $mtime[0];
	}

	function stop($msg = "", $arguments = "") {


		$mtime = microtime();
		$mtime = explode(" ", $mtime);
		$mtime = $mtime[1] + $mtime[0];
		$this->endTimer = $mtime;


		$this->totalTime($msg, $arguments);
		if ($this->endTimer && $this->startTimer) {
			return $this->endTimer - $this->startTimer;
		} else return "0";
	}

	private function totalTime($msg = "", $arguments = "") {
		if (($this->endTimer && $this->startTimer) && (!$this->f3->get("nonotifications") || $this->force)) {
			if (is_array($msg)) {
				$key = key($msg);
				$class = $msg[$key]['Class'];
				$method = $msg[$key]['Method'];
				$str = $key . " - " . $msg[$key]['Class'] . " - " . $msg[$key]['Method'];

				if (!isset($GLOBALS["models"][$class])) {
					$GLOBALS["models"][$class] = array(
						"k"=> $class,
						"m"=> array(),
						"t"=>0
					);
				}
				if (!isset($GLOBALS["models"][$class]['m'][$method])) {
					$GLOBALS["models"][$class]['m'][$method] = array(
						"l"=> $method,
						"c"=> 0,
						"t"=>0,
						"m"=>array()
					);
				}
				$t = $this->endTimer - $this->startTimer;
				$GLOBALS["models"][$class]['m'][$method]['c'] = $GLOBALS["models"][$class]['m'][$method]['c'] + 1;
				$GLOBALS["models"][$class]['m'][$method]['t'] = $GLOBALS["models"][$class]['m'][$method]['t'] + $t;
				$GLOBALS["models"][$class]['t'] = isset($GLOBALS["models"][$class]['t'])?$GLOBALS["models"][$class]['t']:0;
				$GLOBALS["models"][$class]['t'] = $GLOBALS["models"][$class]['t'] + $t;


				$trace = debug_backtrace();
				//$trace = array_shift($trace[0]);


				if (isset($trace[2])) {
					$trace[2] = bt_loop($trace[2]);
				}
				if (isset($trace[3])) {
					$trace[2]['file'] = $trace[2]['file'] . " *";
					$trace[3] = bt_loop($trace[3]);
				}







				/*'file' => string '/home/squale/developpement/tests/temp/temp.php' (length = 46)
  'line' => int 29
  'function' => string '__construct' (length = 11)
  'class' => string 'Foo' (length = 3)
  'object' =>
    object(Foo)[1]
  'type' => string '->' (length = 2)
  'args' => */


				$GLOBALS["models"][$class]['m'][$method]['m'][] = array(
					"msg"=> $str,
					"arg"=> $trace[2],
					"tim"=> $t,
					"bt"=> (isset($trace[3]))? $trace[3]:""
				);


			} else {
				$str = ($msg) ? $msg . ": " : "";
				array_push($GLOBALS['timer'], array(
					                            "msg"=> $str,
					                            "arg"=> $arguments,
					                            "tim"=> ($this->endTimer - $this->startTimer)
				                            )
				);
			}

		}


	}
}

