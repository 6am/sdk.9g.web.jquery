<?php

	$route = new \Klein\Klein();

	// Check that the class exists before trying to use it
	if (class_exists('NGCloud')) $NGCloud = new NGCloud;

	//GRAB NGC SETTINGS
	$settingsfile =  @ fopen(ROOT."/config.php","r");
	if($settingsfile){
		//loop through the settings file and load variables into the session 
		while( !feof($settingsfile)) {
			$line=NULL;
			$key=NULL;
			$value=NULL;
			$line=fscanf($settingsfile,"%[^=]=%[^[]]",$key,$value);
			if ($line){
				$key=trim($key);
				$value=trim($value);
				if($key!="" and !strpos($key,"]")){	
					$startpos=strpos($value,"\"");
					$endpos=strrpos($value,"\"");
					if($endpos!=false)
						$value=substr($value,$startpos+1,$endpos-$startpos-1);
					define(strtoupper($key),$value);							
				}
			}
		}
		@ fclose($settingsfile);
	}

//	include_once(DIR ."/_helpers/session.php");
	include_once(DIR ."/_helpers/includes.php");

    include_once(DIR . "/_helpers/common_functions.php");

?>