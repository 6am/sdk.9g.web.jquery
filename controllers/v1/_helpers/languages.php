<?php

$url=substr($_SERVER["REQUEST_URI"],1);
if(substr($url,-1,1)=="/") {
	$url= substr($url,0,-1);
}
$sc=explode("/",$url);

switch($sc[0]) {
	default:
		$locale="en_US";
		define("COUNTRY","United States");
		break;
	case "br":
		$locale="pt_BR";
		define("COUNTRY","Brasil");
		break;
	case "ca":
		$locale="fr_CA";
		define("COUNTRY","Canada");
		break;
}

$locales_dir = ROOT.'languages/locale';
if (isset($_GET['locale']) && !empty($_GET['locale'])) {
	$locale = $_GET['locale'];
	$_COOKIE["locale"]["lang"] = $locale;
	header("location: ".explode("?",$_SERVER["REQUEST_URI"])[0]);
	exit;
}

$domain = $locale;
putenv('LANGUAGE='.$locale);
setlocale(LC_ALL, $locale.'.utf8');

bind_textdomain_codeset($domain, 'UTF-8');
bindtextdomain($domain, $locales_dir);
bind_textdomain_codeset($domain, 'UTF-8');
textdomain($domain);

?>