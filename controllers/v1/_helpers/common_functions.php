<?php

define('DATE_FORMAT', 'English, UK');
define('TIME_FORMAT', '24 Hour');

//Currency
define('CURRENCY_SYM', 'R$');
define('CURRENCY_ACCURACY', '2');
define('DECIMAL_SYMBOL', '.');
define('THOUSANDS_SEPARATOR', ',');



//=================================================
//Most Common Functions of the Application go here.
//=================================================

function xmlEncode($str){
    $str=str_replace("&","&amp;",$str);
    $str=str_replace("<","&lt;",$str);
    $str=str_replace(">","&gt;",$str);
    return $str;
}

function goURL($url){
    if(headers_sent())
        $error = new appError("450","Could not redirect to: ".$url);
    header("Location: ".$url);
    exit;
}

function hasRights($roleid,$checkForAdmin = true){
    $hasrights=false;
    if(@$_SESSION["userinfo"]["admin"]==1 && $checkForAdmin)
        $hasrights=true;
    elseif($roleid==0)
        $hasrights=true;
    else
        foreach((array) @$_SESSION["userinfo"]["roles"] as $role)
            if($role==$roleid)
                $hasrights=true;

    return $hasrights;
}



// date/time functions
//=====================================================================
function stringToDate($datestring,$format=DATE_FORMAT){
    $thedate=NULL;
    if($datestring){
        switch($format){

            case "SQL":
                $temparray=explode("-",$datestring);
                if(count($temparray)>1)
                    $thedate=@mktime(0,0,0,(int) $temparray[1],(int) $temparray[2],(int) $temparray[0]);
                else
                    return false;
                break;

            case "English, US":
                $datestring="/".ereg_replace(",.","/",$datestring);
                $temparray=explode("/",$datestring);
                if(count($temparray)>1)
                    $thedate=mktime(0,0,0,(int) $temparray[1],(int) $temparray[2],(int) $temparray[3]);
                else
                    return false;
                break;

            case "English, UK":
                $datestring="/".@ereg_replace(",.","/",$datestring);
                $temparray=explode("/",$datestring);
                if(count($temparray)>1)
                    $thedate=@mktime(0,0,0,(int) $temparray[2],(int) $temparray[1],(int) $temparray[3]);
                else
                    return false;
                break;

            case "Dutch, NL":
                $datestring="-".ereg_replace(",.","-",$datestring);
                $temparray=explode("-",$datestring);
                if(count($temparray)>1)
                    $thedate=mktime(0,0,0,(int) $temparray[2],(int) $temparray[1],(int) $temparray[3]);
                else
                    return false;
                break;

        }
    }
    return $thedate;
}

function stringToTime($timestring,$format=TIME_FORMAT){
    $thetime=NULL;
    if($timestring){
        switch($format){

            case "24 Hour":
                $temparray=explode(":",$timestring);
                if(count($temparray)>1)
                    $thetime=mktime($temparray[0],$temparray[1],$temparray[2]);
                break;

            case "12 Hour":
                if(strpos($timestring,"AM")!==false){
                    $timestring=str_replace(" AM","",$timestring);
                    $addtime=0;
                }
                else {
                    $timestring=str_replace(" PM","",$timestring);
                    $addtime=12;
                }
                $timearray=explode(":",$timestring);
                if ($timearray[0]==12)
                    $timearray[0]=0;
                $timearray[0]= ((integer) $timearray[0]) + $addtime;
                $thetime=mktime($timearray[0],$timearray[1],0);
                break;
        }
    }
    return $thetime;
}

function dateToString($thedate,$format=DATE_FORMAT){
    $datestring="";
    if($thedate){
        switch($format){

            case "SQL":
                $datestring=strftime("%Y-%m-%d",$thedate);
                break;

            case "English, US":
                $datestring=strftime("%m/%d/%Y",$thedate);
                break;

            case "English, UK":
                $datestring=strftime("%d/%m/%Y",$thedate);
                break;

            case "Dutch, NL":
                $datestring=strftime("%d-%m-%Y",$thedate);
                break;
        }
    }
    return $datestring;
}

function timeToString($thetime,$format=TIME_FORMAT){
    $timestring="";
    if($thetime){
        switch($format){
            case "24 Hour":
                $timestring=strftime("%H:%M:%S",$thetime);
                break;
            case "12 Hour":
                $timestring=trim(strftime(HOUR_FORMAT.":%M %p",$thetime));
                break;
        }
    }
    return $timestring;
}

function formatFromSQLDate($sqldate,$format=DATE_FORMAT){
    $datestring="";
    if(($sqldate=="0000-00-00") || ($sqldate=="0000-00-00 00:00:00"))
        $datestring="";
    else if($sqldate!="")
        if($format=="SQL")
            $datestring=$sqldate;
        else
            $datestring=dateToString(stringToDate($sqldate,"SQL"),$format);
    return $datestring;
}

function formatFromSQLTime($sqltime,$format=TIME_FORMAT){
    $timestring="";
    if($sqltime!="")
        if($format=="24 Hour")
            $timestring=$sqltime;
        else
            $timestring=timeToString(stringToTime($sqltime,"24 Hour"),$format);
    return $timestring;
}

function dateFromSQLDatetime($sqldatetime){
    $thedatetime=false;
    $datetimearray=explode(" ",$sqldatetime);
    if(count($datetimearray)==2){
        $tempdatearray=explode("-",$datetimearray[0]);
        $temptimearray=explode(":",$datetimearray[1]);
        if(count($tempdatearray)>1 && count($temptimearray)>1)
            $thedatetime=mktime((int) $temptimearray[0],(int) $temptimearray[1],(int) $temptimearray[2],(int) $tempdatearray[1],(int) $tempdatearray[2],(int) $tempdatearray[0]);
    }
    return $thedatetime;
}

function formatFromSQLDatetime($sqldatetime,$dateformat=DATE_FORMAT,$timeformat=TIME_FORMAT){
    $datetimestring="";
    $timestring="";
    if($sqldatetime!=""){
        $datetimearray=explode(" ",$sqldatetime);

        $datestring=trim($datetimearray[0]);
        if($dateformat=="SQL")
            $datestring=$datestring;
        else
            $datestring=dateToString(stringToDate($datestring,"SQL"),$dateformat);
        if(isset($datetimearray[1])){
            $timestring=$datetimearray[1];
            if($timeformat=="24 Hour")
                $timestring=$timestring;
            else
                $timestring=timeToString(stringToTime($timestring,"24 Hour"),$timeformat);
        }
        $datetimestring=trim($datestring." ".$timestring);
    }
    if(($sqldatetime=="0000-00-00") || ($sqldatetime=="0000-00-00 00:00:00")) {
        $datetimestring="";
    }
    return $datetimestring;
}

function formatFromSQLTimestamp ($datetime,$dateformat=DATE_FORMAT,$timeformat=TIME_FORMAT) {
    if($datetime=="")
        return mktime();
    $hour=0;
    $minute=0;
    $second=0;
    $month=1;
    $day=1;
    $year=1974;
    settype($datetime, 'string');
    eregi('(....)(..)(..)(..)(..)(..)',$datetime,$matches);
    array_shift ($matches);
    foreach (array('year','month','day','hour','minute','second') as $var) {
        $$var = (int) array_shift($matches);
    }


    $thedatetime=mktime($hour,$minute,$second,$month,$day,$year);

    return trim(dateToString($thedatetime,$dateformat)." ".timeToString($thedatetime,$timeformat));
}

function sqlDateFromString($datestring,$format=DATE_FORMAT){
    $sqldate="0000-00-00";
    if($datestring){
        if($format=="SQL")
            $sqldate=$datestring;
        else
            $sqldate=dateToString(stringToDate($datestring,$format),"SQL");
    }
    return $sqldate;
}

function sqlTimeFromString($timestring,$format=TIME_FORMAT){
    $sqltime="0000-00-00";
    if($timestring){
        if($format=="24 Hour")
            $sqltime=$timestring;
        else
            $sqltime=timeToString(stringToTime($timestring,$format),"24 Hour");
    }
    return $sqltime;
}

// Currency functions
//=====================================================================
function numberToCurrency($number,$color=false,$colorpositive="green",$colornegative="red"){
    $currency="";
    if($number<0) $currency.="-";
    $currency.=CURRENCY_SYM.number_format(abs($number),CURRENCY_ACCURACY,DECIMAL_SYMBOL,THOUSANDS_SEPARATOR);
    if($number==0) $currency="-";
    if($color) {
        if($number<0) {
            $currency='<font color="'.$colornegative.'">'.$currency.'</font>';
        } else {
            $currency='<font color="'.$colorpositive.'">'.$currency.'</font>';
        }
    }
    return $currency;
}

function currencyToNumber($currency){
    $number=str_replace(CURRENCY_SYM,"",$currency);
    $number=str_replace(THOUSANDS_SEPARATOR,"",$number);
    $number=str_replace(DECIMAL_SYMBOL,".",$number);
    $number=((real) $number);

    return $number;
}





//============================================================================
function ordinal($number) {

    // when fed a number, adds the English ordinal suffix. Works for any
    // number, even negatives

    if ($number % 100 > 10 && $number %100 < 14):
        $suffix = "th";
    else:
        switch($number % 10) {

            case 0:
                $suffix = "th";
                break;

            case 1:
                $suffix = "st";
                break;

            case 2:
                $suffix = "nd";
                break;

            case 3:
                $suffix = "rd";
                break;

            default:
                $suffix = "th";
                break;
        }

    endif;

    return "${number}$suffix";

}


function addSlashesToArray($thearray){

    //This function prepares an array for SQL manipulation.

    if(get_magic_quotes_runtime() || get_magic_quotes_gpc()){

        foreach ($thearray as $key=>$value)
            if(is_array($value))
                $thearray[$key]= addSlashesToArray($value);
            else
                $thearray[$key] = mysql_real_escape_string(stripslashes($value));

    } else
        foreach ($thearray as $key=>$value)
            if(is_array($value))
                $thearray[$key]= addSlashesToArray($value);
            else
                $thearray[$key] = mysql_real_escape_string($value);

    return $thearray;

}//end function


function htmlQuotes($string){
    return htmlspecialchars($string,ENT_COMPAT,"UTF-8");
}


function htmlFormat($string,$quotes=false){
    $trans = get_html_translation_table(HTML_ENTITIES);
    $encoded = strtr($string, $trans);
    return $encoded;
}


function showSaveCancel($ids=1,$saveRights=false){
    ?>
    <div class="saveCancels">
        <? if(hasRights($saveRights)){ ?>
            <input <?php if($ids==1) {?>accesskey="s"<?php }?> title="Salvar (alt+s)" id="saveButton<?php echo $ids?>" name="command" type="submit" value="salvar" class="Buttons" />
        <? } ?>
        <input id="cancelButton<?php echo $ids?>" name="command" type="submit" value="voltar" class="Buttons" onclick="this.form.cancelclick.value=true;" <?php if($ids==1) {?>accesskey="x" <?php }?> title="(access key+x)" />
    </div>
    <?php
}


function getAddEditFile($db,$tabledefid,$addedit="edit"){
    $querystatement="SELECT ".$addedit."file AS thefile FROM tabledefs WHERE id=".((int) $tabledefid);
    $queryresult = $db->query($querystatement);

    $therecord=$db->fetchArray($queryresult);
    return APP_PATH.$therecord["thefile"];
}


function booleanFormat($bool){
    if($bool==1)
        return "X";
    else
        return"&middot;";
}


function formatVariable($value, $format=NULL){
    switch($format){
        case "real":
            $value = number_format($value,2);
            break;

        case "currency":
            $value=htmlQuotes(numberToCurrency($value));
            break;

        case "boolean":
            $value=booleanFormat($value);
            break;

        case "date":
            $value=formatFromSQLDate($value);
            break;

        case "time":
            $value=formatFromSQLTime($value);
            break;

        case "datetime":
            $value=formatFromSQLDatetime($value);
            break;

        case "filelink":
            $value="<div><button class=\"graphicButtons buttonDownload\" type=\"button\" onclick=\"document.location='".APP_PATH."servefile.php?i=".$value."'\" ><span>download</span></button> <small>".showDldMeter(filesize(getFileLink($value)))."</small></div>";
            //$value="<a href=\"".APP_PATH."servefile.php?i=".$value."\" style=\"display:block;\"><img src=\"".APP_PATH."common/stylesheet/".STYLESHEET."/image/button-download.png\" align=\"middle\" alt=\"view\" width=\"16\" height=\"16\" border=\"0\" /></a>";
            break;

        case "noencoding":
            $value=$value;
            break;
        default:
            $value=htmlQuotes($value);
    }
    return $value;
}
function showDldMeter($size){
    if($size<200000) $output='<img src="/common/image/dld_meter_1.png" height="16" alt="" class="graphicButtons" style="width:30px;">';
    if(($size>=200000) && ($size<400000)) $output='<img src="/common/image/dld_meter_1.png" height="16" alt="" class="graphicButtons" style="width:30px;">';
    if(($size>=400000) && ($size<600000)) $output='<img src="/common/image/dld_meter_2.png" height="16" alt="" class="graphicButtons" style="width:30px;">';
    if(($size>=600000) && ($size<800000)) $output='<img src="/common/image/dld_meter_3.png" height="16" alt="" class="graphicButtons" style="width:30px;">';
    if(($size>=800000) && ($size<1000000)) $output='<img src="/common/image/dld_meter_4.png" height="16" alt="" class="graphicButtons" style="width:30px;">';
    if(($size>=1000000) && ($size<1200000)) $output='<img src="/common/image/dld_meter_5.png" height="16" alt="" class="graphicButtons" style="width:30px;">';
    if(($size>=1200000)) $output='<img src="/common/image/dld_meter_6.png" height="16" alt="" class="graphicButtons" style="width:30px;">';
    return $output;
}
function getFileLink($id) {
    global $db;
    if(isset($id)) {
        $querystatement="
		SELECT files.file,files.code,files.type,files.name,files.roleid, attachments.recordid, attachments.tabledefid, year(files.creationdate) as Y, month(files.creationdate) as M FROM files 
		left join attachments on attachments.fileid=files.id WHERE files.id=".((integer)$id);

        @ $queryresult=$db->query($querystatement);
        if($queryresult) {
            if($db->numRows($queryresult)){
                $therecord=$db->fetchArray($queryresult);
                if(hasRights($therecord["roleid"])){

                    switch($therecord["tabledefid"]) {
                        default:
                            $output = ($_SERVER['DOCUMENT_ROOT']."/attachments/".$therecord["tabledefid"]."/".$therecord["recordid"]."/".$therecord["code"]);
                            break;
                        case "2":
                            $file = $_SERVER['DOCUMENT_ROOT']."/attachments/".$therecord["tabledefid"]."/".$therecord["recordid"]."/".$therecord["code"];
                            if(file_exists($file)) {
                                $output = ($file);
                            } else {
                                //get prospect id
                                $querystatement="SELECT id from `prospects_clients` where clientid=".$therecord["recordid"];
                                @ $prospectresult=$db->query($querystatement);
                                $prospect=$db->fetchArray($prospectresult);

                                //$file = $_SERVER['DOCUMENT_ROOT']."/attachments/28067/".$prospect["id"]."/".$therecord["code"];					
                                $file = $_SERVER['DOCUMENT_ROOT']."/attachments/".$therecord["tabledefid"]."/".$therecord["recordid"]."/".$therecord["code"];
                                $output = ($file);
                            }
                            break;
                        case "28104":
                            $output = ($_SERVER['DOCUMENT_ROOT']."/attachments/".$therecord["tabledefid"]."/".$therecord["Y"]."/".$therecord["M"]."/".$therecord["code"]);
                            //echo $_SERVER['DOCUMENT_ROOT']."/attachments/".$therecord["tabledefid"]."/".$therecord["Y"]."/".$therecord["M"]."/".$therecord["code"];
                            break;
                        case "28035":
                        case "28007":

                            $file = $_SERVER['DOCUMENT_ROOT']."/attachments/".$therecord["tabledefid"]."/".$therecord["Y"]."/".$therecord["recordid"]."/".$therecord["code"];
                            if(file_exists($file)) {
                                $output = ($file);

                            } else {

                                for ($Y = 2008; $Y <= 2012; $Y++) {
                                    $file = $_SERVER['DOCUMENT_ROOT']."/attachments/".$therecord["tabledefid"]."/".$Y."/".$therecord["recordid"]."/".$therecord["code"];
                                    if(file_exists($file)) {
                                        $output = ($file);
                                        $Y=2013;
                                    }
                                }

                            }
                            //echo $_SERVER['DOCUMENT_ROOT']."/attachments/".$therecord["tabledefid"]."/".$therecord["Y"]."/".$therecord["recordid"]."/".$therecord["code"];
                            break;
                    }
                }
            }
        }
    }
    return $output;
}

function human_filesize($bytes, $decimals = 2) {
    $sz = 'BKMGTP';
    $factor = floor((strlen($bytes) - 1) / 3);
    return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor];
}

//for windows servers, we have no define time constants and nl_langinfo function
//in a limited fashion; some windows servers still show that the function
//exists even though it's not implemented, thus the second check;

$nl_exists = function_exists("nl_langinfo");
if($nl_exists)
    $nl_exists = @ nl_langinfo(CODESET);

if(!$nl_exists){

    function nl_langinfo($constant){

        return $constant;

    }//end function

    function nl_setup(){

        $date = mktime(0,0,0,10,7,2007);

        for($i = 1; $i<=7; $i++){

            define("ABDAY_".$i, date("D", $date));
            define("DAY_".$i, date("l"), $date);

            $date = strtotime("tomorrow", $date);
        }//end for


        for($i = 1; $i<=12; $i++){

            $date = mktime(0, 0, 0, $i, 1, 2007);

            define("ABMON_".$i, date("M", $date));
            define("MON_".$i, date("F"), $date);

        }//end for

    }//end function

    nl_setup();

}//end if

function generatePassword ($length = 5)
{

    // start with a blank password
    $password = "";

    // define possible characters
    $possible = "0123456789bcdfghjkmnpqrstvwxyz";

    // set up a counter
    $i = 0;

    // add random characters to $password until $length is reached
    while ($i < $length) {

        // pick a random character from the possible ones
        $char = substr($possible, mt_rand(0, strlen($possible)-1), 1);

        // we don't want this character if it's already in the password
        if (!strstr($password, $char)) {
            $password .= $char;
            $i++;
        }

    }

    // done!
    return $password;

}
//connect to LUMICURSOS DB
function connectToLumiCursosDb() {
    //generate connection to LUMICURSOS DB
    $dbhost = '67.225.152.137';
    $dbuser = 'lumicursos';
    $dbpass = 'AUk13dere77kOEl';

    $conn = mysql_connect($dbhost, $dbuser, $dbpass) or die                      ('Error connecting to mysql');

    $dbname = 'lumicurs_db';
    mysql_select_db($dbname);
}
function sendSMS($msg,$phone) {
    $phone=str_replace("-","",$phone);
    $phone=trim($phone);
    $phone="55".$phone;
    $msg=substr($msg,0,150);

    if(@ mail("sms@wobo.com.br","+".$phone,$msg,
        "To: sms@wobo.com.br \n" .
        "From: no-reply@lumicd.com <no-reply@lumicd.com>\n" .
        "MIME-Version: 1.0\n" .
        "Content-type: text/html; charset=UTF-8"
    ))
        return true;
    else
        return false;

}


function getEmailTemplate($id) {
    global $db;
    $sqlstatement="
		SELECT * FROM `emailtemplatesclients` where id='".$id."'
	";
    $sqlquery = $db->query($sqlstatement);
    $therecord = $db->fetchArray($sqlquery);

    //check for errors
    if($db->numRows($sqlquery)==0){
        //send message to the admin telling this emailtemplate doesnt exists
        $message = "Email template #".$id." acionado no arquivo ".$_SERVER["REQUEST_URI"]." nao existe";
        $log = new phpbmsLog($message,"ERROR");
        exit;
    }

    $therecord["subject"] = utf8_decode($therecord["name"]);
    $therecord["testquery"]="";
    if($therecord["istested"]==0) {
        $therecord["testquery"] =" ORDER BY RAND() LIMIT 1";
    }
    $therecord["message"]=str_replace("&#034",'"',$therecord["message"]);
    return $therecord;

}

function sendEmailTemplate($message,$therecord,$msg,$fromemail="comunicados@slacoaching.com.br",$silent=false) {
    global $db;
    if($message["inactive"]==1) {
        echo "envio dessa mensagem esta INATIVADA<br>";
    } else {
        if($message["istested"]==0) {
            $msg = "Anexo id:".$message["id"]."<br>Nome real: ".$therecord["toname"]."<br>Email real: ".$therecord["toemail"]."<br>".$msg;
            $therecord["toemail"]="bruno@6am.com.br";
        }
        if(strlen($therecord["toemail"])>5) {
            if(!isset($therecord["tabledefid"])) $therecord["tabledefid"]=0;
            $code=str_pad($therecord["toid"], 3, "0", STR_PAD_LEFT).substr(time(),5);
            $therecord["toname"]=str_replace("'","`",$therecord["toname"]);




            $insertstatement="
				INSERT INTO emailstemplatesprocess 
				(`templateid`, `express`, `fromemail`, `name`, `subject`, `email`, `description`, `tabledefid`, `recordid`, `code`, `createdby`, `creationdate`) 
				VALUES 
				('".$message["id"]."','".$message["express"]."','".$fromemail."', '".$therecord["toname"]."', '".utf8_encode($message["subject"])."', '".$therecord["toemail"]."', '".$msg."', '".$therecord["tabledefid"]."', '".$therecord["toid"]."','".$code."', 3, now())
			";
            $insertquery = $db->query($insertstatement);


            /*
            include_once($_SERVER['DOCUMENT_ROOT'] . "/includes/NGC.php");
            $NGCloud= new NGCloud;
            $data["emailid"]=$db->insertId();
            $data['accesstoken'] = 'slacMAYCON10aioqc07ea8dd2e45be0';
            $data['awskey'] = 'AKIAJUOW5OQ5X4CVUOGQ';
            $data['awstoken'] = 'P4MN62732HBJ43nVw3Ch9vMsjYsJcMcGDW2nPePX';
            $data['from'] = $fromemail;
            $data['to'] = $therecord["toemail"];
            $data['subject'] = utf8_encode($message["subject"]);
            $data['body'] = (html_entity_decode($msg));
            $data['trackingopens'] = "http://www.slacoaching.com.br/emailopens";
            //$data['to'] = $therecord["toemail"];
            
            //$data['debug'] = '1';
            
            $result = json_decode($NGCloud->post('http://api.9g.com.br/v1/emails/ses', $data));
            $data["awsid"]=$result->MessageId;
            
            $insertstatement="insert into emails_ses (`emailid`, `messageid`) VALUES ('".$data["emailid"]."','".$data["awsid"]."')";
            $insertquery = $db->query($insertstatement);
            
            $upstatement="update emailstemplatesprocess set isposted=1, posteddate=now() where id=".$data["emailid"]."";
                $updatequery = $db->query($upstatement);		
            */

            /*
            if(@ mail($therecord["toemail"],$message["subject"],$msg,				
                    "To: ".$therecord["toemail"]."\n" .
                    "From:  ".$fromemail."<".$fromemail.">\n" .
                    "MIME-Version: 1.0\n" .
                    "Content-type: text/html; charset=UTF-8"
            )) {
                $upstatement="
                    update emailtemplatesclients set counter=(counter+1), lastsent=now() where id=".$message["id"]."
                ";
                $updatequery = $db->query($upstatement);			
                if(!$silent) echo $therecord["toname"] ." : ".$therecord["toemail"]." ENVIADO<br>";
                
            } else {
                if(!$silent) echo $therecord["toname"] ." : ".$therecord["toemail"]." FALHA<br>";
            }
            */
        }
        if($message["istested"]==0) {
            //dont do anything else, since this is not authorized yet
            //echo "exit";
            exit;
        }
    }
    return true;

}

function sendEmailTemplateLine($therecord,$msg,$fromemail="comunicados@slacoaching.com.br",$silent=false) {
    global $db;

    if(strlen($therecord["toemail"])>5) {
        if(@ mail($therecord["toemail"],utf8_decode($therecord["subject"]),$msg,
            "To: ".$therecord["toemail"]."\n" .
            "From:  ".$fromemail."<".$fromemail.">\n" .
            "MIME-Version: 1.0\n" .
            "Content-type: text/html; charset=UTF-8"
        )) {
            $upstatement="
				update emailstemplatesprocess set isposted=1, posteddate=now() where id=".$therecord["id"]."
			";
            $updatequery = $db->query($upstatement);
            if(!$silent) echo $therecord["toname"] ." : ".$therecord["toemail"]." ENVIADO<br>";

        } else {
            $upstatement="
				update emailstemplatesprocess set isposted=0, failed=1 where id=".$therecord["id"]."
			";
            $updatequery = $db->query($upstatement);
            if(!$silent) echo $therecord["toname"] ." : ".$therecord["toemail"]." FALHA<br>";
        }
    }
    return true;

}
function sendEmailLine($therecord,$msg,$fromemail="comunicados@slacoaching.com.br",$silent=false) {
    global $db;

    if(strlen($therecord["toemail"])>5) {
        if(@ mail($therecord["toemail"],$therecord["subject"],$msg,
            "To: ".$therecord["toemail"]."\n" .
            "From:  ".$fromemail."<".$fromemail.">\n" .
            "MIME-Version: 1.0\n" .
            "Content-type: text/html; charset=UTF-8"
        )) {
            $upstatement="
				update emails set isposted=1, posteddate=now() where id=".$therecord["id"]."
			";
            $updatequery = $db->query($upstatement);
            if(!$silent) echo $therecord["toname"] ." : ".$therecord["toemail"]." ENVIADO<br>";

        } else {
            $upstatement="
				update emails set isposted=0, failed=1 where id=".$therecord["id"]."
			";
            $updatequery = $db->query($upstatement);
            if(!$silent) echo $therecord["toname"] ." : ".$therecord["toemail"]." FALHA<br>";
        }
    }
    return true;

}

function addLetter($message,$therecord,$msg,$receivertabledefid=2,$fromemail="comunicados@slacoaching.com.br") {
    global $db;
    if($message["inactive"]==1) {
        echo "envio dessa mensagem esta INATIVADA<br>";
    } else {
        if($message["istested"]==0) {
            //send letter as email test only
            $msg = "Envio de carta para:<br>Nome real: ".$therecord["toname"]."<br>Email real: ".$therecord["toemail"]."<br>".$msg;
            mail("bruno@6am.com.br",$message["subject"],$msg,
                "To: bruno@6am.com.br\n" .
                "From:  ".$fromemail."<".$fromemail.">\n" .
                "MIME-Version: 1.0\n" .
                "Content-type: text/html; charset=UTF-8"
            );
            exit;
        } else {
            //create the letter
            $emailtemplatesid=$message["id"];
            $receiverid=$therecord["toid"];
            $receivername=$therecord["toname"];
            if(strlen($therecord["address3"])>1) {
                $address=''.$therecord["address1"].', '.$therecord["addressnumber"].'<br>'.$therecord["address3"].' - '.$therecord["city"].' / '.$therecord["state"].' - '.$therecord["postalcode"].'';
            } else {
                $address=''.$therecord["address1"].', '.$therecord["addressnumber"].'<br>'.$therecord["city"].' / '.$therecord["state"].' - '.$therecord["postalcode"].'';
            }
            $address=str_replace("'","`",$address);
            $address=str_replace("'","`",$address);


            //add the letter
            $querystatement = "insert into letterstoprint 
						(emailtemplatesid, message, receiverid, receivername, receivertabledefid, address, createdby, creationdate) values 
							(".$emailtemplatesid.",
							'".$msg."',
							'".$receiverid."',
							'".$receivername."',
							'".$receivertabledefid."',
							'".$address."',
							3,
							now()
							)";
            $queryresult=$db->query($querystatement);

            $upstatement="
				update emailtemplatesclients set counter=(counter+1), lastsent=now() where id=".$message["id"]."
			";
            $updatequery = $db->query($upstatement);

        }

    }
    return true;
}
function getHumanTime($time) {
    $h = floor($time / 3600);
    $m = floor(($time / 60) % 60);
    $s = $time % 60;
    if($h>0) {
        $output=$h."h ";
    }
    if($m>0) {
        $output.=$m."m ";
    }
    if($s>0) {
        $output.=$s."s ";
    }

    return $output;

}

function getMonthName($m,$short=false) {
    switch($m) {
        case "1":
            $month="Janeiro";
            break;
        case "2":
            $month="Fevereiro";
            break;
        case "3":
            $month="Março";
            break;
        case "4":
            $month="Abril";
            break;
        case "5":
            $month="Maio";
            break;
        case "6":
            $month="Junho";
            break;
        case "7":
            $month="Julho";
            break;
        case "8":
            $month="Agosto";
            break;
        case "9":
            $month="Setembro";
            break;
        case "10":
            $month="Outubro";
            break;
        case "11":
            $month="Novembro";
            break;
        case "12":
            $month="Dezembro";
            break;

    }

    if($short) $month=substr($month,0,3);
    $month=utf8_encode($month);
    return $month;

}



function getWeekDayName($d,$short=false) {
    switch($d) {
        case "Sunday":
            $weekday = "Domingo";
            break;
        case "Monday":
            $weekday = "Segunda-feira";
            break;
        case "Tuesday":
            $weekday = "Terca-feira";
            break;
        case "Wednesday":
            $weekday = "Quarta-feira";
            break;
        case "Thursday":
            $weekday = "Quinta-feira";
            break;
        case "Friday":
            $weekday = "Sexta-feira";
            break;
        case "Saturday":
            $weekday = "Sabado";
            break;
    }

    if($short) $weekday=substr($weekday,0,3);
    $weekday=utf8_encode($weekday);
    return $weekday;

}

function replace_accents($string)
{
    $b=array('à','á','â','ã','ä', 'ç', 'è','é','ê','ë', 'ì','í','î','ï', 'ñ', 'ò','ó','ô','õ','ö', 'ù','ú','û','ü', 'ý','ÿ', 'À','Á','Â','Ã','Ä', 'Ç', 'È','É','Ê','Ë', 'Ì','Í','Î','Ï', 'Ñ', 'Ò','Ó','Ô','Õ','Ö', 'Ù','Ú','Û','Ü', 'Ý');
    $c=array('a','a','a','a','a', 'c', 'e','e','e','e', 'i','i','i','i', 'n', 'o','o','o','o','o', 'u','u','u','u', 'y','y', 'A','A','A','A','A', 'C', 'E','E','E','E', 'I','I','I','I', 'N', 'O','O','O','O','O', 'U','U','U','U', 'Y');
    $string=str_replace( $b, $c, $string);
    return $string;
}


function BuildDuplicateQuery($therecord) {
    $therecord["creationdate"]=date("Y-m-d h:m;s");
    $therecord["createdby"]=$_SESSION["userinfo"]["id"];

    $fiedlsQuery='(';
    $valuesQuery='(';

    foreach ($therecord as $field => $value) {
        $fiedlsQuery.='`'.$field.'`,';
        $valuesQuery.="'".mysql_real_escape_string($value)."',";
        //print_r($fields);
    }
    $fields["keys"]=substr($fiedlsQuery,0,-1).')';
    $fields["values"]=substr($valuesQuery,0,-1).')';

    return $fields;
}

function calculatePromoterComissionTotal ($type,$totalfaturado, $total) {
    /*
    switch($type) {
        case "junior":
            if($totalfaturado<70000) $output["percentage"]=0.05;
            if($totalfaturado>70000) $output["percentage"]=1;
            if($totalfaturado>90000) $output["percentage"]=1.5;
            if($totalfaturado>110000) $output["percentage"]=2;
        break;
        
        case "senior":
            if($totalfaturado<90000) $output["percentage"]=1;
            if($totalfaturado>90000) $output["percentage"]=1.5;
            if($totalfaturado>110000) $output["percentage"]=2;
            if($totalfaturado>120000) $output["percentage"]=3;
            if($totalfaturado>130000) $output["percentage"]=4;
        break;
        
        case "pleno":
        break;
    
    }
    */

    if($totalfaturado<40000) $output["percentage"]=0;
    if($totalfaturado>40000) $output["percentage"]=1;
    if($totalfaturado>55000) $output["percentage"]=2;
    if($totalfaturado>70000) $output["percentage"]=3;
    if($totalfaturado>80000) $output["percentage"]=4;
    //if($total>99000) $output["percentage"]=5;	

    $output["total"]=($total*$output["percentage"])/100;
    return $output;

}
function getProspectstoSet($line=1) {
    global $db;
    if($line==1) {
        $lineQuery=" and date(creationdate)>subdate(now(),INTERVAL 4 DAY)";
    } else {
        $lineQuery=" and date(creationdate)<=subdate(now(),INTERVAL 4 DAY)";
    }

    $sqlstatement=" 
		select 
		* from clients
		where isclient=0 and inactive=0 and isready=1 and (promoterid=0 or promoterid is null) 
		$lineQuery
		order by modifieddate asc
	";
    $sqlquery = $db->query($sqlstatement);
    return $sqlquery;
}

function getNextPromoterID($id,$line=1,$display=false) {
    global $db;

    //get all prospects
    $sqlquery=getProspectstoSet($line);
    $totalProspects=$db->numRows($sqlquery);

    //get last ids
    $sqlstatement=" 
		select 
		value from settings
		where id=".$id."	
	";
    $sqlquery = $db->query($sqlstatement);
    $therecord = $db->fetchArray($sqlquery);
    $lastids=substr($therecord["value"],1,-1);
    $done=explode("::",$lastids);
    //print_r($done);
    if(count($done)>1) {
        foreach ($done as $value) {
            $users[$value]["done"]=1;
        }
    } else if (count($done)==1) {
        $users[$done[0]]["done"]=1;
    }

    $totaldone=count($done);
    if($line==1) {
        $lineQuery=" and isinline1=1";
    } else {
        $lineQuery=" and isinline2=1";
    }
    //get all promoters
    $sqlstatement=" 
		select 
		id,
		firstname,
		TIMESTAMPDIFF(MINUTE,lastlogin,NOW()) as lastlogin,
		if(TIMESTAMPDIFF(MINUTE,lastlogin,NOW())<=".LINEWAITINGTIME.",1,0) as active,
		if(TIMESTAMPDIFF(MINUTE,lastlogin,NOW())<=".LINEWAITINGTIME.",0,1) as off
		from users
		where ispromoter=1 and inactive=0 and isfired=0 
		$lineQuery
	";

    $sqlquery = $db->query($sqlstatement);
    $i=0;

    while($therecord = $db->fetchArray($sqlquery)) {
        $users[$therecord["id"]]["name"]=$therecord["firstname"];
        $users[$therecord["id"]]["lastlogin"]=$therecord["lastlogin"];
        $users[$therecord["id"]]["active"]=$therecord["active"];
        $users[$therecord["id"]]["off"]=$therecord["off"];
        //$users[$therecord["id"]]["done"]=$therecord["firstname"];
        if($therecord["active"]==1 && !isset($users[$therecord["id"]]["done"]) && !isset($nextid)) $nextid=$therecord["id"];
        if(($therecord["off"]==0)||($users[$therecord["id"]]["done"])==1) $i++;
    }
    //print_r($users);
    $saldo=$i-$totaldone;
    //echo $saldo;	
    $output='<ul class="list-group">
	';
    foreach ($users as $key => $value) {
        $class="list-group-item";
        $badge="";
        if(($value["active"]==1) && ($key!=$nextid)) $badge='<span class="label label-warning">em espera</span>';
        if($value["active"]==0) $badge='<span class="label label-warning">em espera</span>';
        if($value["off"]==1) $badge='<span class="label label-danger">ausente</span>';
        if($key==$nextid) $badge.='<span class="label label-info">proximo</span>';
        if($value["done"]==1) $badge='<span class="label label-success">delegado</span>';
        if(strlen($value["name"])>=3) {
            $output.='<li class="'.$class.'" style="padding:0px 0px 6px;">
			<div style="width:80px; text-align:center; float:left;">'.$badge.'</div> '.$value["name"].' 
			</li>
			';
        }
    }
    $output.='</ul>';


    if($saldo==0) { //restart line
        $sqlstatement=" 
			update settings set value='' where id=".$id."
		";
        $sqlquery = $db->query($sqlstatement);
        //$promoterid=getNextPromoterID($id,$display);	
    }
    $return["total"]=$totalProspects;
    $return["saldo"]=$saldo;
    $return["nextid"]=$nextid;
    $return["display"]=$output;

    return $return;
}

function setNextPromoterID($id,$promoterid,$clientid,$clientname) {
    global $db;
    $sqlstatement=" 
		update settings set value=concat(value,':".$promoterid.":' where id=".$id."
	";
    $sqlquery = $db->query($sqlstatement);

    //update salesmanagerid
    $sqlstatement="update clients set promoterid='".$promoterid."' where clients.id=".$clientid."";
    $queryresult = $db->query($sqlstatement);

    $sqlstatement="insert into notes 
	(assignedtoid, assignedtabledefid, attachedid, attachedtabledefid, assignedtodate, subject, typeid, location, createdby, creationdate) 
	values 
	(".$promoterid.",9,".$clientid.",2,now(),'Novo prospecto: ".mysql_real_escape_string($clientname)."',2,'/prospectos/editar/".$clientid."',3,now())
	";
    $queryresult = $db->query($sqlstatement);

}

function addAction($toid,$torelatedtabledefid,$fromid,$fromrelatedtabledefid,$objectid,$typeid,$relatedid='',$relatedtabledefid='',$tonotify=0,$parentid='0',$fromnotify='0',$fromnotifydate='0000-00-00 00:00:00',$tonotifydate='0000-00-00 00:00:00') {
    global $db;
    //check if points to be scored
    $sqlstatement=" 
		select actions_points.relatedid,  sum(actions_points.points) as points, actions_points.expiredate, actions_objects.tabledefid
		from actions_points 
		inner join actions_objects on actions_points.objectid=actions_objects.id
		where 
		(actions_objects.tabledefid=".(int)$relatedtabledefid." and actions_points.relatedid=0)
		or
		(actions_objects.tabledefid=".(int)$relatedtabledefid." and actions_points.relatedid=".(int)$relatedid.")
		";
    $sqlquery = $db->query($sqlstatement);
    if($db->numRows($sqlquery)) {
        $therecord = $db->fetchArray($sqlquery);
        $points=$therecord["points"];
    } else {
        $points=0;
    }



    $sqlstatement=" 
		insert into actions 
		(companyid, toid,parentid,torelatedtabledefid,tonotify,fromid,fromrelatedtabledefid,fromnotify,objectid,typeid,relatedid,relatedtabledefid,points, fromnotifydate, tonotifydate, createdby,creationdate)
		values
		('".$_SESSION["companies"]["active"]["id"]."','".$toid."','".$parentid."','".$torelatedtabledefid."','".$tonotify."','".$fromid."','".$fromrelatedtabledefid."','".$fromnotify."','".$objectid."','".$typeid."','".$relatedid."','".$relatedtabledefid."','".$points."', '".$fromnotifydate."', '".$tonotifydate."', '".$_SESSION["userinfo"]["id"]."',now())
	";
    $sqlquery = $db->query($sqlstatement);
    return $db->insertId();
}

function addMailAction($email,$relatedid='',$relatedtabledefid='',$tonotify,$fromnotify) {
    global $db;

    //START FUNCTION 	
    $objectid="2";
    $typeid="2";
    $relatedtabledefid="28149";

    //get TO and FROM info		
    if( strpos($email["to"],"@slacoaching.org") === false) { //IS FROM CLIENT
        $email["accountemail"]=$email["from"];
        $u="from";
        $c="to";
    } else {
        $email["accountemail"]=$email["to"];
        $u="to";
        $c="from";

    }

    //get USER INFO
    $querystatement="select id, concat(firstname, lastname) as name from users where emaillogin='".$email[$u]."' and inactive=0";
    $queryresult = $db->query($querystatement);
    $therecord=$db->fetchArray($queryresult);
    $email[$u."id"]=$therecord["id"];
    $email[$u."name"]=$therecord["name"];
    $email[$u."tabledefid"]=9;


    //get CLIENT INFO
    $querystatement="select id, name name from clients where email='".$email[$c]."'";
    $queryresult = $db->query($querystatement);
    $therecord=$db->fetchArray($queryresult);
    $email[$c."id"]=$therecord["id"];
    $email[$c."name"]=$therecord["name"];
    $email[$c."tabledefid"]=2;


    $searchQuery="(actions.".$c."id=".$email[$c."id"]." and actions.".$c."relatedtabledefid=2)";

    //get FROM info

    $parentid=0;


    //check email record
    $querystatement="select id from emails_imap where uid='".$email["uid"]."' and accountemail='".$email["accountemail"]."'";

    $queryresult = $db->query($querystatement);
    if($db->numRows($queryresult)==0) { //insert 

        //insert
        $sqlstatement=" 
			insert into emails_imap 
			(uid,accountemail,parentid,subject,toid,torelatedtabledefid,charset,isread,emaildate,createdby,creationdate)
			values
			('".$email["uid"]."','".$email["accountemail"]."',0,'".mysql_real_escape_string($email["subject"])."','".$email["toid"]."','".$email["torelatedtabledefid"]."','".$email["charset"]."','".$email["isread"]."','".$email["emaildate"]."','".@$_SESSION["userinfo"]["id"]."',now())
		";
        $queryresult = $db->query($sqlstatement);
        $email["id"]=$db->insertId();

    } else {
        $therecord=$db->fetchArray($queryresult);
        $email["id"]=$therecord["id"];
    }


    //check action
    $querystatement="select id from actions where 
		relatedid='".$email["id"]."' and relatedtabledefid='".$relatedtabledefid."'
	";
    $queryresult = $db->query($querystatement);
    if($db->numRows($queryresult)==0) { //insert 
        //add action
        $sqlstatement=" 
			insert into actions 
			(toid,torelatedtabledefid,tonotify,fromid,fromrelatedtabledefid,objectid,typeid,relatedid,relatedtabledefid,createdby,creationdate)
			values
			('".$email["toid"]."','".$email["totabledefid"]."','".$tonotify."','".$email["fromid"]."','".$email["fromtabledefid"]."','".$objectid."','".$typeid."','".$email["id"]."','".$relatedtabledefid."','".@$_SESSION["userinfo"]["id"]."','".$email["emaildate"]."')
		";

        $queryresult = $db->query($sqlstatement);
        $actionid=$db->insertId();

    } else {
        $therecord=$db->fetchArray($queryresult);
        $actionid=$therecord["id"];
    }

    return $actionid;
}

function get_web_page( $url )
{
    $options = array(
        CURLOPT_RETURNTRANSFER => true,     // return web page
        CURLOPT_HEADER         => false,    // don't return headers
        CURLOPT_FOLLOWLOCATION => true,     // follow redirects
        CURLOPT_ENCODING       => "",       // handle all encodings
        CURLOPT_USERAGENT      => "spider", // who am i
        CURLOPT_AUTOREFERER    => true,     // set referer on redirect
        CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
        CURLOPT_TIMEOUT        => 120,      // timeout on response
        CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
    );

    $ch      = curl_init( $url );
    curl_setopt_array( $ch, $options );
    $content = curl_exec( $ch );
    $err     = curl_errno( $ch );
    $errmsg  = curl_error( $ch );
    $header  = curl_getinfo( $ch );
    curl_close( $ch );

    $header['errno']   = $err;
    $header['errmsg']  = $errmsg;
    $header['content'] = $content;
    return $header;
}

function displayClientChose($id, $title, $whereclause="", $ajaxurl='', $selectedid="",$templateSelection='', $name='', $option='', $class='form-control', $multiple=false, $preloadtags='',$disabled='') {


    $output["html"]='<select id="'.$id.'" '.($multiple == true ? 'multiple=""':'').' name="'.$name.'" style="width: 100% !important; '.($multiple == true ? 'display:none;':'').'" class="'.$class.'"  '.$disabled.' data-placeholder="'.$title.'" />'.$option.'</select>';
    //$output["js"]=$js;


    if ($preloadtags != '')
    {
        $preload['ini'] = '
                
                initSelection: function(element, callback) {
                        							
                        callback('.$preloadtags.');
                }														

                ';
    }


    $output["js"] = '
	
	$(document).ready(function() {
                (function(){if(jQuery&&jQuery.fn&&jQuery.fn.select2&&jQuery.fn.select2.amd)var e=jQuery.fn.select2.amd;return e.define("select2/i18n/pt-BR",[],function(){return{errorLoading:function(){return"Os resultados não puderam ser carregados."},inputTooLong:function(e){var t=e.input.length-e.maximum,n="Apague "+t+" caracter";return t!=1&&(n+="es"),n},inputTooShort:function(e){var t=e.minimum-e.input.length,n="Digite "+t+" ou mais caracteres";return n},loadingMore:function(){return"Carregando mais resultados…"},maximumSelected:function(e){var t="Você só pode selecionar "+e.maximum+" ite";return e.maximum==1?t+="m":t+="ns",t},noResults:function(){return"Nenhum resultado encontrado"},searching:function(){return"Buscando…"}}}),{define:e.define,require:e.require}})();
    
    
		$("#'.$id.'").select2({
		  allowClear: true,
		  ajax: {
			url: "'.$ajaxurl.'?whereclause='.$whereclause.'",
			dataType: "json",
			delay: 250,
			data: function (params) {
			  return {
				q: params.term, // search term
				page: params.page
			  };
			},
			processResults: function (data, page) {
			  // parse the results into the format expected by Select2.
			  // since we are using custom formatting functions we do not need to
			  // alter the remote JSON data
			  // console.log(data);
                          
			  return {
				 
				results: data.itens
			  };
			},
			cache: true
		  },
		  escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
		  minimumInputLength: 1,
		  templateResult: function (repo) { 
			if (repo.loading) return repo.text; 
			var markup = repo.name;
			return markup;  
		  }, // omitted for brevity, see the source of this page
		  templateSelection: function (repo) { 
                        
                        '.$templateSelection.'
		  },
                  '.@$preload['ini'].'
                  
		})
                
                '.( $preloadtags == 'asdasdas' ? '$("#'.$id.'").select2("val", '.$preloadtags.');':'').'
                
		  
                  
                    console.log($("#tags-box").val());
	  });
	  
	  ';




    return $output;
}


/**
 * Creating date collection between two dates
 *
 * <code>
 * <?php
 * # Example 1
 * date_range("2014-01-01", "2014-01-20", "+1 day", "m/d/Y");
 *
 * # Example 2. you can use even time
 * date_range("01:00:00", "23:00:00", "+1 hour", "H:i:s");
 * </code>
 *
 * @author Ali OYGUR <alioygur@gmail.com>
 * @param string since any date, time or datetime format
 * @param string until any date, time or datetime format
 * @param string step
 * @param string date of output format
 * @return array
 */
function date_range($first, $last, $step = '+1 day', $output_format = 'd/m/Y' ) {

    $dates = array();
    $current = strtotime($first);
    $last = strtotime($last);

    while( $current <= $last ) {

        $dates[] = date($output_format, $current);
        $current = strtotime($step, $current);
    }

    return $dates;
}


function getClientPicture($id) {
    global $db;
    $fcontact=0;

    $picture="/images/user.png";
    //get user email
    $sqlstatement=" 
		select 
		id, picture, email, secondemail, fbookid, fbookpic, fullcontactcheck, type	
		from clients
		where id=".(int)$id."			
	";
    $sqlquery = $db->query($sqlstatement);

    if ($db->numRows($sqlquery) ==0) return $picture;

    $therecord = $db->fetchArray($sqlquery);
    $clientid=$therecord["id"];

    if(strlen(trim($therecord["picture"])) > 0 ) {
        return $therecord['picture'];
    }
    if(strlen(trim($therecord["fbookpic"])) > 0) {
        return $therecord['fbookpic'];
    }


    //if($therecord["picture"])
    if (is_file($_SERVER['DOCUMENT_ROOT'] .'/'.$therecord["picture"])) return $therecord["picture"];



    if ($therecord['type'] == 'juridica') $picture = '/images/company-default.png';

    $querystatement = " 
		SELECT 
		files.name as attname,
		attachments.recordid,
		year(attachments.creationdate) as `year`,
		month(attachments.creationdate) as `month`
		FROM attachments 
		INNER join files on attachments.fileid = files.id
		WHERE tabledefid = 9 AND attachments.recordid ='". $therecord['id']."'
		having recordid>0
		order by attachments.id desc
		limit 0,1
	";

    //$querystatement = "select files.name from attachments inner join files on files.id = attachments.fileid where tabledefid=9 and attachments.recordid = ".$therecord['id']." order by attachments.id desc limit 0, 1";
    $queryresult = $db->query($querystatement);

    $fcontact=$therecord["fullcontactcheck"];
    $fbookid=$therecord["fbookid"];
    $fbookpic=$therecord["fbookpic"];
    $email=$therecord["email"];
    $email2=$therecord["secondemail"];

    if ($db->numRows($queryresult) > 0)
    {
        $attchrecord = $db->fetchArray($queryresult);
        $file=$_SERVER['DOCUMENT_ROOT'] . '/commons/attachments/clients/' . $attchrecord['year'].'/' . $attchrecord['month'].'/' . $attchrecord['attname'];

        if (is_file($file))
        {
            $picture = $file;
        } else if (strlen($fbookpic)>5) {
            $picture=$fbookpic;
        } else if(@get_headers('http://www.slacoaching.com.br/commons/images/clients/' . $attchrecord['year'].'/' . str_pad($attchrecord['month'], 2, "0", STR_PAD_LEFT).'/thumb/' . $attchrecord['attname'])[0] != 'HTTP/1.1 404 Not Found'){
            $picture='http://www.slacoaching.com.br/commons/images/clients/' . $attchrecord['year'].'/' . str_pad($attchrecord['month'], 2, "0", STR_PAD_LEFT).'/thumb/' . $attchrecord['attname'];
            $sqlstatement=" update clients set picture='".$picture."' where id=".$clientid."";
            $sqlquery = $db->query($sqlstatement);
        }


    }else{
        if($fcontact==0) $picture=getFullContact($id,1,$email,$email2);

        if(strlen($fbookpic)>5) $picture=$fbookpic;

    }


    //check if user have uploaded a picure and override FB picture

    return $picture;
}

function getFullContact($id,$show="",$email=0,$email2=0) {
    global $db;
    $key="5b31ecc69356b420";
    $key="6dfc2915d1f99153"; //paid
    //echo "https://api.fullcontact.com/v2/person.json?email=".$email."&apiKey=".$key;
    $data=get_web_page("https://api.fullcontact.com/v2/person.json?email=".$email."&apiKey=".$key);
    $record=json_decode($data["content"]);

    $fbUrl="";
    $fbId="";
    $pic="";

    if($record->status==200) {
        $pic=$record->photos[0]->url;
        $name=$record->contactInfo->fullName;
        $gender=$record->demographics->gender;

        foreach ((array)$record->socialProfiles as $key => $value) {

            switch($value->typeId) {
                default:
                    //print_r($value);
                    break;
                case "facebook":
                    $fbId=$value->id;
                    $fbFollowers=$value->followers;
                    $fbUrl=$value->url;
                    break;
            }
            //echo "Key: $key; Value: $value<br />\n";
        }



    }
    $sqlstatement=" 
		update clients set facebook='".$fbUrl."', fullcontactcheck=1, fbookid='".$fbId."', fbookpic='".$pic."' where id=".$id."			
	";

    $sqlquery = $db->query($sqlstatement);

    if($show==1) {
        return getClientPicture($id);
    }

}
function showTopid($id) {
    $output="";
    if($id>0) $output='<span class="label label-default pull-right">#'.$id.'</span>';
    return $output;
}




//email GMAIL

function getmsg($mbox,$mid) {
    // input $mbox = IMAP stream, $mid = message id
    // output all the following:
    global $charset,$htmlmsg,$plainmsg,$attachments;
    $htmlmsg = $plainmsg = $charset = '';
    $attachments = array();

    // HEADER
    $h = imap_header($mbox,$mid);
    // add code here to get date, from, to, cc, subject...

    // BODY
    $s = imap_fetchstructure($mbox,$mid);
    if (!$s->parts)  // simple
        getpart($mbox,$mid,$s,0);  // pass 0 as part-number
    else {  // multipart: cycle through each part
        foreach ($s->parts as $partno0=>$p)
            getpart($mbox,$mid,$p,$partno0+1);
    }


    switch($charset) {
        case "utf-8":
            $htmlmsg=utf8_decode(utf8_encode($htmlmsg));
            break;
    }
    $output["htmlmsg"]=$htmlmsg;
    $output["charset"]=$charset;
    $output["plainmsg"]=$plainmsg;
    $output["attachments"]=$attachments;

    return $output;
    //echo "$charset ,####, $htmlmsg ,####, $plainmsg ,####, $attachments ,####,";
}

function imapMessage($message,$mbox,$mid) {
    $output=getmsg($mbox,$mid);
    if(isset($_GET["debug"])) {
        print_r($output);
    }
    if(strlen($output["htmlmsg"]) > 2) {
        switch($output["charset"]) {
            default:
                return $output["htmlmsg"];
                break;
            case "iso-8859-1":
                return utf8_encode($output["htmlmsg"]);
                break;
        }
    } else {
        $message = nl2br(imap_qprint(utf8_decode(utf8_encode(imap_fetchbody($mbox, $mid, "1")))));
        return $message;
    }
    exit;
    $structure = imap_fetchstructure($mbox, $mid);

    if(strlen($message)<3) {
        $message = imap_fetchbody($mbox, $mid, "2");
    }
    if(strlen($message)<3) {
        $message = imap_fetchbody($mbox, $mid, "1");
    }

    if(!$structure->parts[1]) {
        $encoding=$structure->parts[0]->encoding;
        //$message=nl2br(imap_fetchbody($mbox, $mid, "1"));
    } else if($structure->parts[0]->parts[1]) {
        $encoding=$structure->parts[0]->parts[1]->encoding;
    } else if(count($structure->parts[0]->parts)>3) {
        $encoding=$structure->parts[0]->parts[1]->encoding;
    } else {
        $encoding=$structure->parts[1]->encoding;
    }

    if($_SESSION["userinfo"]["ghost"]==1) {
        echo $encoding;
        print_r($structure);
    }
    switch($encoding) {
        case "0":
        case "2":
            $message = imap_qprint($message);
            break;
        case "1":
            $message = utf8_encode($message);
            break;
        case "3":
            $message = imap_base64($message);
            break;
        case "4":
            $message = quoted_printable_decode($message);
            break;
    }


    $message = imap_qprint($message);

    return $message;
}

function getpart($mbox,$mid,$p,$partno) {
    // $partno = '1', '2', '2.1', '2.1.3', etc for multipart, 0 if simple
    global $htmlmsg,$plainmsg,$charset,$attachments;

    // DECODE DATA
    $data = ($partno)?
        imap_fetchbody($mbox,$mid,$partno):  // multipart
        imap_body($mbox,$mid);  // simple
    // Any part may be encoded, even plain text messages, so check everything.
    if ($p->encoding==4)
        $data = quoted_printable_decode($data);
    elseif ($p->encoding==3)
        $data = base64_decode($data);

    // PARAMETERS
    // get all parameters, like charset, filenames of attachments, etc.
    $params = array();
    if ($p->parameters)
        foreach ($p->parameters as $x)
            $params[strtolower($x->attribute)] = $x->value;
    if ($p->dparameters)
        foreach ($p->dparameters as $x)
            $params[strtolower($x->attribute)] = $x->value;

    // ATTACHMENT
    // Any part with a filename is an attachment,
    // so an attached text file (type 0) is not mistaken as the message.
    if ($params['filename'] || $params['name']) {
        // filename may be given as 'Filename' or 'Name' or both
        $filename = ($params['filename'])? $params['filename'] : $params['name'];
        // filename may be encoded, so see imap_mime_header_decode()
        $attachments[$filename] = $data;  // this is a problem if two files have same name
    }

    // TEXT
    if ($p->type==0 && $data) {
        // Messages may be split in different parts because of inline attachments,
        // so append parts together with blank row.
        if ( strtolower($p->subtype)=='plain') {
            $plainmsg.= trim($data) . "\n\n";
        } else {
            $htmlmsg.= $data ."<br><br>";
        }
        $charset = $params['charset'];  // assume all parts are same charset
    }

    // EMBEDDED MESSAGE
    // Many bounce notifications embed the original message as type 2,
    // but AOL uses type 1 (multipart), which is not handled here.
    // There are no PHP functions to parse embedded messages,
    // so this just appends the raw source to the main message.
    elseif ($p->type==2 && $data) {
        $plainmsg.= $data."\n\n";
    }

    // SUBPART RECURSION
    if ($p->parts) {
        foreach ($p->parts as $partno0=>$p2)
            getpart($mbox,$mid,$p2,$partno.'.'.($partno0+1));  // 1.2, 1.2.1, etc.
    }
}

function getIcon($type) {
    switch($type) {
        case "gif":
        case "jpg":
        case "jpeg":
        case "png":
        case "bmp":
            $img = "/images/icons/jpg.png";
            break;
        case "zip":
            $img = "/images/icons/zip.png";
            break;
        case "doc":
            $img = "/images/icons/odc.png";
            break;
        case "html":
            $img = "/images/icons/html.png";
            break;
        case "ppt":
            $img = "/images/icons/ppt.png";
            break;
        case "pdf":
            $img = "/images/icons/pdf.png";
            break;
        case "txt":
            $img = "/images/icons/txt.png";
            break;
        case "xls":
            $img = "/images/icons/xls.png";
            break;
        default:
            $img = "/images/icons/others.png";
            break;
    }
    return $img;
}


/**
 * Truncates text.
 *
 * Cuts a string to the length of $length and replaces the last characters
 * with the ending if the text is longer than length.
 *
 * @param string $text String to truncate.
 * @param integer $length Length of returned string, including ellipsis.
 * @param string $ending Ending to be appended to the trimmed string.
 * @param boolean $exact If false, $text will not be cut mid-word
 * @param boolean $considerHtml If true, HTML tags would be handled correctly
 * @return string Trimmed string.
 */
function truncate($text, $length = 100, $ending = '...', $exact = true, $considerHtml = false) {
    if ($considerHtml) {
        // if the plain text is shorter than the maximum length, return the whole text
        if (strlen(preg_replace('/<.*?>/', '', $text)) <= $length) {
            return $text;
        }

        // splits all html-tags to scanable lines
        preg_match_all('/(<.+?>)?([^<>]*)/s', $text, $lines, PREG_SET_ORDER);

        $total_length = strlen($ending);
        $open_tags = array();
        $truncate = '';

        foreach ($lines as $line_matchings) {
            // if there is any html-tag in this line, handle it and add it (uncounted) to the output
            if (!empty($line_matchings[1])) {
                // if it’s an “empty element” with or without xhtml-conform closing slash (f.e.)
                if (preg_match('/^<(\s*.+?\/\s*|\s*(img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param)(\s.+?)?)>$/is', $line_matchings[1])) {
                    // do nothing
                    // if tag is a closing tag (f.e.)
                } else if (preg_match('/^<\s*\/([^\s]+?)\s*>$/s', $line_matchings[1], $tag_matchings)) {
                    // delete tag from $open_tags list
                    $pos = array_search($tag_matchings[1], $open_tags);
                    if ($pos !== false) {
                        unset($open_tags[$pos]);
                    }
                    // if tag is an opening tag (f.e. )
                } else if (preg_match('/^<\s*([^\s>!]+).*?>$/s', $line_matchings[1], $tag_matchings)) {
                    // add tag to the beginning of $open_tags list
                    array_unshift($open_tags, strtolower($tag_matchings[1]));
                }
                // add html-tag to $truncate’d text
                $truncate .= $line_matchings[1];
            }

            // calculate the length of the plain text part of the line; handle entities as one character
            $content_length = strlen(preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i', ' ', $line_matchings[2]));
            if ($total_length+$content_length > $length) {
                // the number of characters which are left
                $left = $length - $total_length;
                $entities_length = 0;
                // search for html entities
                if (preg_match_all('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i', $line_matchings[2], $entities, PREG_OFFSET_CAPTURE)) {
                    // calculate the real length of all entities in the legal range
                    foreach ($entities[0] as $entity) {
                        if ($entity[1]+1-$entities_length <= $left) {
                            $left--;
                            $entities_length += strlen($entity[0]);
                        } else {
                            // no more characters left
                            break;
                        }
                    }
                }
                $truncate .= substr($line_matchings[2], 0, $left+$entities_length);
                // maximum lenght is reached, so get off the loop
                break;
            } else {
                $truncate .= $line_matchings[2];
                $total_length += $content_length;
            }

            // if the maximum length is reached, get off the loop
            if($total_length >= $length) {
                break;
            }
        }
    } else {
        if (strlen($text) <= $length) {
            return $text;
        } else {
            $truncate = substr($text, 0, $length - strlen($ending));
        }
    }

    // if the words shouldn't be cut in the middle...
    if (!$exact) {
        // ...search the last occurance of a space...
        $spacepos = strrpos($truncate, ' ');
        if (isset($spacepos)) {
            // ...and cut the text in this position
            $truncate = substr($truncate, 0, $spacepos);
        }
    }

    // add the defined ending to the text
    $truncate .= $ending;

    if($considerHtml) {
        // close all unclosed html-tags
        foreach ($open_tags as $tag) {
            $truncate .= '';
        }
    }

    return $truncate;

}

function array_sort (&$array, $column=0, $order="ASC") {

    $oper = ($order == "ASC")?">":"<";

    if(!is_array($array)) return;

    usort($array, create_function('$a,$b',"return (\$a['$column'] $oper \$b['$column']);"));

    reset($array);

}

function getTableDefRecord($tabledefid, $relateid) {
    global $db;
    $querystatement = "SELECT maintable FROM `tabledefs` where id=".(int)$tabledefid;
    $queryresult = $db->query($querystatement);
    $therecord = $db->fetchArray($queryresult);
    $table=$therecord["maintable"];
    $tableDisplay=$therecord["displayname"];

    $querystatement = "SELECT * FROM `".$table."` where id=".(int)$relateid;
    $queryresult = $db->query($querystatement);
    $therecord = $db->fetchArray($queryresult);
    $therecord["itemname"]=$tableDisplay;

    return $therecord;

}

function loginByToken($token) {
    global $db;
    $querystatement = "SELECT 
	clients.email, clients.password
	FROM clients
	WHERE 
	md5(concat(clients.id,clients.email,clients.password))='".$token."'
	 AND CHAR_LENGTH( clients.password ) >0
	AND clients.inactive=0";
    $queryresult = $db->query($querystatement);
    $therecord = $db->fetchArray($queryresult);

    if($db->numRows($queryresult)){
        $login=json_decode(verifyLoginClient($therecord["email"],$therecord["password"],"",$db,true));

        if($login->login==1) {
            header("location:".$login->redirect);
        }
    } else {
        echo "<h2>Erro: Credenciais inválidas</h2>";
        exit;
    }
}
function PMT($i, $n, $p) {
    /*
    $i = juros mensais 4, 5, 6% etc
    $n = parcelas em meses: 12, 24 etc
    $p = valor a ser financiado
    */
    $i=$i/100;
    $parcel=$i * -$p * pow((1 + $i), $n) / (1 - pow((1 + $i), $n));
    return $parcel;
}

function companyChooser($groupclass="",$buttonclass="btn-sm btn-default",$breadcrumb=true) {
    global $db;


    $output="";
    if(count($_SESSION["companies"]["childs"])>0){
        if($breadcrumb==true) $output.='<li class="crumb-trail">';
        $output.='
		<div class="btn-group '.$groupclass.'">
			<button aria-expanded="false" type="button" class="btn '.$buttonclass.' dropdown-toggle"  data-toggle="dropdown">Empresa: '.$_SESSION["companies"]["active"]["name"].' <span class="caret"></span></button>
			
			<ul class="dropdown-menu" role="menu">
		';
        //display MAIN COMPANY	
        if($_SESSION["companies"]["parent"]["id"]!=$_SESSION["companies"]["active"]["id"]) {
            $output.='
			<li>
			<a href="#" class="companyChooser" data-id="'.$_SESSION["companies"]["parent"]["id"].'"  data-name="'.$_SESSION["companies"]["parent"]["name"].'" data-parentid="'.$_SESSION["companies"]["parent"]["parentid"].'"  data-appname="'.$_SESSION["companies"]["parent"]["name"].'">'.$_SESSION["companies"]["parent"]["name"].'</a>
			</li>
			';
        }
        //display CHILDS	
        foreach($_SESSION["companies"]["childs"] as $key => $value) {
            if($_SESSION["companies"]["active"]["id"]!=$value["id"]) {
                $output.='
				<li>
				<a href="#" class="companyChooser" data-id="'.$value["id"].'"  data-name="'.$value["name"].'" data-parentid="'.$value["parentid"].'"  data-appname="'.$value["appname"].'">'.$value["name"].'</a>
				</li>
				';
            }

        }
        $output.='
			</ul>
		</div>';
        if($breadcrumb==true) $output.='</li>';
    }


    return $output;
}





function checkIfWorkFlow($objectid,$typeid,$relatedid, $actionid) {
    global $db;

    $querystatement = "
		select 
		workflowsactions.workflowid,
		actions.fromid as clientid,
		workflowssteps.id as workflowstepid,
		workflowsactions.id as workflowactionid,
		workflowsactions.stepid as workflowstepid,
		workflowsactions.groupid as groupid,
		workflowssteps.parentid as parentid,
		workflowsactions.condition as `condition`,
		IFNULL(workflowstoclients.id,0) as workflowclientid
		from actions
		inner join workflowsactions on workflowsactions.actionobjectid=".(int)$objectid." and workflowsactions.actiontypeid=".(int)$typeid." and workflowsactions.relatedid=".(int)$relatedid."
		inner join workflowssteps on workflowssteps.workflowid=workflowsactions.workflowid and workflowssteps.id=workflowsactions.stepid
		left join workflowstoclients on workflowstoclients.clientid=actions.fromid and workflowstoclients.workflowid=workflowsactions.workflowid and workflowsactions.stepid=workflowstoclients.workflowstepid
		where workflowsactions.inactive=0 and actions.id=".(int)$actionid."
		order by workflowsactions.groupid, workflowsactions.id
	";


    $queryresult = $db->query($querystatement);
    $therecord = $db->fetchArray($queryresult);


    //check if user is on workflow if NOT the first step in workflow
    if($therecord["parentid"]>0 && $therecord["workflowclientid"]==0) {
        //stop user not in workflow
        $group["workflow"]=0;
        $group["run"]=0;
        return $group;
    }

    if($db->numRows($queryresult)){
        $groupid=$therecord["groupid"];
        $group["clientid"]=$therecord["clientid"];
        $group["condition"]=$therecord["condition"];
        $group["workflow"]=$therecord["workflowid"];
        $group["workflowactionid"]=$therecord["workflowactionid"];
        $group["workflowstepid"]=$therecord["workflowstepid"];
        $group["workflowclientid"]=$therecord["workflowclientid"];
        $group["groupid"]=$therecord["groupid"];
        $group["stepid"]=$therecord["workflowstepid"];
        $group["parentid"]=$therecord["parentid"];

        $workflow=getWorkFlow($group["workflow"], $group["workflowstepid"]);

        $i=0;

        $result["ok"]=1;
        $result["workflow"]=$therecord["workflowid"];
        $result["workflowactionid"]=$therecord["workflowactionid"];
        $result["workflowstepid"]=$therecord["workflowstepid"];
        $result["clientid"]=$therecord["clientid"];
        $result[$i]["groupid"]=$groupid;
        $result[$i]["ok"]=1;
        foreach ($workflow as $key => $action) {
            $checks=checkActionWorkFlow($objectid,$typeid,$relatedid, $actionid, $action["workflowid"], $action["workflowactionid"],$action["workflowstepid"]);
            if($action["condition"]==0 && $checks["ok"]==0) $result[$groupid]["ok"]=0;
            if($action["condition"]==1) $result[$groupid]["ok"]=$checks["ok"];
            if($result[$groupid]["ok"]==0) $result["ok"]=0;
            $action["ok"]=$checks["ok"];
            $result[$groupid][$i]=$action;
            $i++;
        }
        $return=$result;

    } else {
        $return["workflow"]=0;
        $return["run"]=false;
    }


    //if all ok get what is to be executed
    if($return["ok"]==1) {
        $return["workflowtoclient"]["id"]=addToWorkFlow($objectid,$typeid,$relatedid, $actionid);

    }


    return $return;

}



function getWorkFlow($workflowid, $stepid) {
    global $db;

    $querystatement = "
		select 
		workflowsactions.workflowid,
		workflowsactions.id as workflowactionid,
		workflowsactions.stepid as workflowstepid,
		workflowsactions.groupid as groupid,
		workflowsactions.condition as `condition`
		from workflowsactions 
		where workflowsactions.inactive=0
		and workflowsactions.workflowid=".(int)$workflowid."
		and workflowsactions.stepid=".(int)$stepid."
		order by workflowsactions.groupid, workflowsactions.id
	";


    $queryresult = $db->query($querystatement);

    while($therecord = $db->fetchArray($queryresult)) {
        $return[]=$therecord;
    }

    return $return;

}


function checkActionWorkFlow($objectid,$typeid,$relatedid, $actionid, $wid, $wactionid, $wstepid) {
    global $db;


    $querystatement = "
		select 
		workflowsactions.workflowid,
		actions.fromid as clientid,
		workflowssteps.id as workflowstepid,
		workflowsactions.id as worflowactionid,
		workflowsactions.condition as `condition`,
		workflowsactions.groupid as `groupid`,
		IFNULL(workflowstoclients.id,0) as workflowclientid
		from actions
		inner join workflowsactions on workflowsactions.actionobjectid=".(int)$objectid." and workflowsactions.actiontypeid=".(int)$typeid." and workflowsactions.relatedid=".(int)$relatedid." and workflowsactions.stepid=".(int)$wstepid." and workflowsactions.id=".(int)$wactionid." and workflowsactions.workflowid=".(int)$wid."
		inner join workflowssteps on workflowssteps.workflowid=workflowsactions.workflowid and workflowssteps.id=workflowsactions.stepid
		left join workflowstoclients on workflowstoclients.clientid=actions.fromid and workflowstoclients.workflowid=workflowsactions.workflowid and workflowsactions.stepid=workflowstoclients.workflowstepid
		where workflowsactions.inactive=0 and actions.id=".(int)$actionid."
		order by workflowsactions.groupid, workflowsactions.id
	";


    //echo $querystatement;exit;
    $queryresult = $db->query($querystatement);
    $therecord = $db->fetchArray($queryresult);

    //check if user is on workflow
    if($therecord["parentid"]>0) {

    }

    if($db->numRows($queryresult)){
        $groupid=$therecord["groupid"];
        $group["clientid"]=$therecord["clientid"];
        $group["condition"]=$therecord["condition"];
        $group["workflow"]=$therecord["workflowid"];
        $group["worflowactionid"]=$therecord["worflowactionid"];
        $group["groupid"]=$therecord["groupid"];
        $group["stepid"]=$therecord["workflowstepid"];
        $group["parentid"]=$therecord["parentid"];
        $group["ok"]=1;

        $return=$group;


    } else {
        $return["ok"]=0;
    }

    return $return;

}

function addToWorkFlow($objectid,$typeid,$relatedid, $actionid) {
    global $db;
    $querystatement = "
		select 
			actions.fromid as clientid,
			workflowsactions.workflowid,
			workflowsactions.stepid,
			workflowssteps.points,
			workflowssteps.delay,
			adddate(now(),INTERVAL workflowssteps.delay MINUTE) as rundate,
			workflowssteps.crontab,
			IFNULL(workflowstoclients.id,0) as checker
		from actions
		inner join workflowsactions on workflowsactions.actionobjectid=".(int)$objectid." and workflowsactions.actiontypeid=".(int)$typeid." and workflowsactions.relatedid=".(int)$relatedid."
		inner join workflowssteps on workflowssteps.workflowid=workflowsactions.workflowid and workflowssteps.id=workflowsactions.stepid
		left join workflowstoclients on workflowstoclients.clientid=actions.fromid and workflowstoclients.workflowid=workflowsactions.workflowid and workflowsactions.stepid=workflowstoclients.workflowstepid
		where workflowsactions.inactive=0 and actions.id=".(int)$actionid."
	";

    $queryresult = $db->query($querystatement);
    $therecord = $db->fetchArray($queryresult);

    if($db->numRows($queryresult) && $therecord["checker"]==0){
        $clientid=$therecord["clientid"];
        $workflowid=$therecord["workflowid"];
        $workflowstepid=$therecord["stepid"];
        $points=$therecord["points"];
        $statusid="1";

        //move client to this step

        //remove client from parentstep


        //add last sync
        $querystatement = "
			update workflowssteps set totalsppl=totalsppl+1, totalsin=totalsin+1, totalslastsync=now()
			where workflowssteps.id=".$workflowstepid." and workflowid=".$workflowid."";
        $queryresult = $db->query($querystatement);

        //MISSING addcheck crontab to rundate
        $rundate=$therecord["rundate"];

        $querystatement = "insert into workflowstoclients
		(clientid, workflowid, workflowstepid, statusid, isdone, entrydatetime, rundate, creationdate)
		values 
		('".$clientid."','".$workflowid."','".$workflowstepid."','1',0,now(),'".$rundate."',now())";
        $queryresult = $db->query($querystatement);




        //addleadscore
        addLeadScore($clientid,$points);

        return $db->insertId();
    }



}

function addLeadScore($clientid,$in=0,$out=0) {
    global $db;
    $querystatement = "select sum(`in`)-sum(`out`) as total from leadscore where clientid=".(int)$clientid."";
    $queryresult = $db->query($querystatement);
    $therecord = $db->fetchArray($queryresult);

    if($in>0) {
        $total=$total+$in;
    } else {
        $total=$total-$out;
    }

    $querystatement = "
	insert into leadscore (clientid,`in`,`out`,`total`,`creationdate`)
	values
	('".(int)$clientid."',".$in.",".$out.",".$total.",now())
	";
    $queryresult = $db->query($querystatement);
    return $db->insertId();
}

function setColor($type,$id){
    if(isset($_SESSION["colors"][$ype][$id])) return $_SESSION["colors"][$ype][$id];
    $rand = array('#ffc61a', '#ff9305', '#ff530f', '#ff171b', '#ff1192', '#ff0f4e', '#d748b5', '#d7414e', '#d71048', '#4db215', '#4d4160', '#4d47e1', '#4d79f8', '#4d9ee1', '#4d6fcc', '#4d3953');
    $color = $rand[rand(0,count($rand)-1)];
    $_SESSION["colors"][$ype][$id]=$color;
    return $color;
}

function ngc_openssl_encrypt( $string, $action = 'e', $secret='9gSimpleKey' ) {
    // you may change these values to your own
    $secret_key = $secret;
    $secret_iv = $secret;
 
    $output = false;
    $encrypt_method = "AES-256-CBC";
    $key = hash( 'sha256', $secret_key );
    $iv = substr( hash( 'sha256', $secret_iv ), 0, 16 );
 
    if( $action == 'e' ) {
        $output = base64_encode( openssl_encrypt( $string, $encrypt_method, $key, 0, $iv ) );
    }
    else if( $action == 'd' ){
        $output = openssl_decrypt( base64_decode( $string ), $encrypt_method, $key, 0, $iv );
    }
 
    return $output;
}

?>