<?php



date_default_timezone_set('Africa/Johannesburg');
setlocale(LC_MONETARY, 'en_ZA');
//ini_set('memory_limit', '256M');


$GLOBALS["models"] = array();
$GLOBALS["output"] = array();
$GLOBALS["render"] = "";
if (session_id() == "") {
	$SID = @session_start();
} else $SID = session_id();
if (!$SID) {
	session_start();
	$SID = session_id();
}
$cfg = array();
require_once('config.default.inc.php');
if (file_exists("config.inc.php")){
	require_once('config.inc.php');
}



$GLOBALS['cfg'] = $cfg;
$f3 = require('lib/f3/base.php');
require_once('inc/class.timer.php');
$pageExecute = new timer(true);


//test_array(array("HTTP_HOST"  => $_SERVER['HTTP_HOST'], "REQUEST_URI"=> $_SERVER['REQUEST_URI']));


require_once('inc/functions.php');
require_once('inc/class.pagination.php');
require_once('lib/Twig/Autoloader.php');
Twig_Autoloader::register();
require_once('inc/class.msg.php');
require_once('inc/class.template.php');
require_once('inc/class.email.php');
require_once('inc/class.store.php');


$f3->set('AUTOLOAD', './|lib/|controllers/');
$f3->set('PLUGINS', 'lib/f3/');
//$f3->set('CACHE', TRUE);
$f3->set('DEBUG', 2);

//$f3->set('EXTEND', TRUE);
//$f3->set('UI', 'ui/');
//$f3->set('TEMP', 'temp/');

$f3->set('DB', new DB\SQL('mysql:host=' . $cfg['DB']['host'] . ';dbname=' . $cfg['DB']['database'] . '', $cfg['DB']['username'], $cfg['DB']['password']));
$f3->set('cfg', $cfg);

$version = '0.0.0';
$version = date("YmdH");
$minVersion = preg_replace("/[^0-9]/", "", $version);


$f3->set('version', $version);
$f3->set('v', $minVersion);

ob_start();

$ttl = 0;
if (strpos($_SERVER['HTTP_HOST'], "dev.") === true || isLocal()) {
	$ttl = 0;
}
$ttl = 0;


$user = array(
	"ID" => "1",
	"last_activity"=> date("Y-m-d H:i:s")
);


$f3->route('GET /min/css/@filename', 'general->css_min', $ttl);
$f3->route('GET /min/css*', 'general->css_min', $ttl);
$f3->route('GET /min/js/@filename', 'general->js_min', $ttl);
$f3->route('GET /min/js*', 'general->js_min', $ttl);


$f3->route('GET /', function ($f3, $params) {
		$f3->chain('controllers\front\home->page'); // usage $f3->chain('access; last_page;  controllers\ab\controller_admin_dates->page');
	}
);
$f3->route('GET /logout', function ($f3, $params) {
		$f3->reroute("/?logged_out"); // usage $f3->chain('access; last_page;  controllers\ab\controller_admin_dates->page');
	}
);


$f3->route('GET /data/keepalive', function ($f3, $params) use ($user) {



		$last_activity = new DateTime($user['last_activity']);
		$now = new DateTime('now');

		$interval = $last_activity->diff($now);
		$diff = (($interval->h * 60) * 60) + ($interval->i * 60) + ($interval->s);

		//$interval['diff']=$diff;


		if (isset($_GET['keepalive']) && $_GET['keepalive']) {
			$f3->get("DB")->exec("UPDATE users SET last_activity = now() WHERE ID = '" . $user['ID'] . "'");
			$diff = 0;
			// upadate the last_activity
		}
		$t = array(
			"ID"   => $user['ID'],
			"idle" => $diff
		);

		test_array($t);

	}
);

// --------------------------------------------------------------------------------
// Ajax Stuff
// --------------------------------------------------------------------------------

$f3->route("GET|POST /data/@function", function ($f3, $params) {
		$f3->call("controllers\\data\\data->" . $params['function']);
	}
);

$f3->route("GET|POST /data/@class/@function", function ($f3, $params) {
		$f3->call("controllers\\data\\" . $params['class'] . "->" . $params['function']);
	}
);
$f3->route("GET|POST /data/@folder/@class/@function", function ($f3, $params) {
		$f3->call("controllers\\data\\" . $params['folder'] . "\\" . $params['class'] . "->" . $params['function']);
	}
);
$f3->route("GET|POST /save/@function", function ($f3, $params) {
		$f3->call("controllers\\save\\save->" . $params['function']);
	}
);
$f3->route("GET|POST /save/@class/@function", function ($f3, $params) {
		$f3->call("controllers\\save\\" . $params['class'] . "->" . $params['function']);
	}
);
// --------------------------------------------------------------------------------


$f3->route('GET /php', function () {
		phpinfo();
		exit();
	}
);


$f3->run();


$GLOBALS["render"] = ob_get_contents();
$pageSize = ob_get_length();

ob_end_clean();

$models = $GLOBALS['models'];
//test_array($models);
$t = array();
foreach ($models as $model) {

	$c = array();
	foreach ($model['m'] as $method) {
		$c[] = $method;
	}


	$model['m'] = $c;
	$t[] = $model;
}
$models = $t;

$totaltime = $pageExecute->stop("Page Execute");
$GLOBALS["output"]['timer'] = $GLOBALS['timer'];
$GLOBALS["output"]['models'] = $models;
$GLOBALS["output"]['page'] = array(
	"page" => $_SERVER['REQUEST_URI'],
	"time" => $totaltime,
	"size" => ($pageSize)
);


//ob_start("ob_gzhandler");
if (((isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') || $f3->get("showjson")) || !$f3->get("__runTemplate")) {
	ob_start('ob_gzhandler');
	header("Content-Type: application/json");
	echo json_encode($GLOBALS["output"]);
	exit();
} else {
	$timersbottom = '<script type="text/javascript">updatetimerlist(' . json_encode($GLOBALS["output"]) . ');</script>';
	if (strpos($GLOBALS["render"], "<!--print version-->")) {
		echo $GLOBALS["render"];
	} else {
		echo str_replace("</body>", $timersbottom . '</body>', $GLOBALS["render"]);
	}
	exit();
}







