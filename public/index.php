<?php

    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    $loginReload="";
    $domain=substr($_SERVER["HTTP_HOST"],0);

    define('APPLICATION_VERSION', "v1");
    define('APPLICATION_START', microtime(true));
    define('ROOT', $_SERVER["DOCUMENT_ROOT"].'/../');
    define('DIR', ROOT."/controllers/".APPLICATION_VERSION);
	define('CACHE', ROOT.'/_cache/');
    define('CACHE_TIME', 0);
    define('DATA', ROOT.'/_data/');
    require_once DIR ."/_helpers/languages.php";

    require_once __DIR__ . '/ngc/9G/NGC.php';
    require_once __DIR__ . '/ngc/libs/klein/vendor/autoload.php';
    require_once DIR ."/_helpers/init.php";
    require_once DIR ."/_helpers/pages.php";

    if(!isset($globals)) $globals = array();

    $temp["route_uri"] = str_replace("/".APPLICATION_VERSION,"controllers/".APPLICATION_VERSION."",$_SERVER["REQUEST_URI"]);
    $temp["route"] = explode("/",$temp["route_uri"]);
    $temp["route_cleaned"]="";

    foreach($temp["route"] as $value) {
		if(is_numeric($value) || substr(explode("?",$value)[0],-5,5)==".json" 
		   || substr(explode("?",$value)[0],-4,4)==".png" 
		   || substr(explode("?",$value)[0],-4,4)==".jpg" 
		   || substr(explode("?",$value)[0],-4,4)==".gif" 
		   || substr(explode("?",$value)[0],-5,5)==".jpeg" 
		   || substr(explode("?",$value)[0],-4,4)==".css"
		   || substr(explode("?",$value)[0],-3,3)==".js"
		   || substr(explode("?",$value)[0],-4,4)==".map"
		   || $value=="new") {
			$donttrack=true;
			$temp["route_cleaned"].= "";
		}else {
			$temp["route_cleaned"].= "/".$value;
		}
    }

//    if(!isset($_SESSION["userinfo"]) && $_SERVER["REQUEST_URI"]!="/login") header("location:/login");
//    $route_file = DIR ."/".$temp["route"][1]."/routes.php";
    $route_file = DIR ."/routes.php"; //FIXED ROUTE

    if(!file_exists($route_file)) header("location: /404");

    //setting tracking navigation
	if(!isset($donttrack)) {
		if (!isset($array)) $array = array();
		$array['url'] = $_SERVER['REQUEST_URI'];
		$array['alias'] = explode("?",$_SERVER['REQUEST_URI'])[0];
		$array['creationdate'] = date('Y-m-d H:i:s',time());
		if(strlen($array['url']) > 3) $_SESSION['pages']['history'][] = $array;
		$_SESSION['pages']['current']=$array;
	}
    require $route_file;

    try {

        $route->dispatch();
        define('APPLICATION_END', microtime(true));
        exit;

    } catch(Exception $e) {

        echo($e->getMessage());

        $route->response()->header('Access-Control-Allow-Origin', '*');
        $route->response()->header('Access-Control-Allow-Methods', 'GET');
        $route->response()->code(400);

        if($route->request()->param('callback') !== NULL)
        {
            return $route->response()->json(['message' => $e->getMessage()], $route->request()->param('callback'));
        }
        else
        {
            return $route->response()->json(['message' => $e->getMessage()]);
        }

    }
?>