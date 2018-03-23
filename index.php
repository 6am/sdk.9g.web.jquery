<?php

    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    $loginReload="";
    $domain=substr($_SERVER["HTTP_HOST"],0);

    define('APPLICATION_VERSION', "v1");
    define('APPLICATION_START', microtime(true));
    define('ROOT', $_SERVER["DOCUMENT_ROOT"]);
    define('DIR', ROOT."/controllers/".APPLICATION_VERSION);

    require_once __DIR__ . '/ngc/9G/NGC.php';
    require_once __DIR__ . '/ngc/libs/klein/vendor/autoload.php';
    require_once DIR ."/_helpers/init.php";



    if(!isset($globals)) $globals = array();

    $temp["route_uri"] = str_replace("/".APPLICATION_VERSION,"controllers/".APPLICATION_VERSION."",$_SERVER["REQUEST_URI"]);
    $temp["route"] = explode("/",$temp["route_uri"]);
    $temp["route_cleaned"]="";

    foreach($temp["route"] as $value) {
        $temp["route_cleaned"].=(is_numeric($value) || substr(explode("?",$value)[0],-5,5)==".json" || $value=="new" ? "" : "/".$value);
    }

    $route_file = DIR ."".$temp["route_cleaned"]."/routes.php";

    require $route_file;

    try {

        $route->dispatch();
        define('APPLICATION_END', microtime(true));
        exit;

    } catch(Exception $e) {

        logMessage($e->getMessage());

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