<?php
  
$locale = "en_US";
$domain = $locale;

$locales_dir = dirname(__FILE__).'/../languages/locale';

//if (isset($_GET['locale']) && !empty($_GET['locale']))
//  $locale = $_GET['locale'];

putenv('LANGUAGE='.$locale);
setlocale(LC_ALL, $locale.'.utf8');

bind_textdomain_codeset($domain, 'UTF-8');
bindtextdomain($domain, $locales_dir);
bind_textdomain_codeset($domain, 'UTF-8');
textdomain($domain);

echo _('test2');

?>