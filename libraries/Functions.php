<?php
function checkMobile($mobile) {

    $mob = str_replace(' ','',$mobile);
    $kac = strlen($mob);

    if($kac==10) {
        $mobile = '0'.substr($mob,0,3).''.substr($mob,3,3).''.substr($mob,6,2).''.substr($mob,8,2);
    } else if ($kac==11) {
        $mobile = '0'.substr($mob,1,3).''.substr($mob,4,3).''.substr($mob,7,2).''.substr($mob,9,2);
    }
    else
    {
        $mobile = '0'.substr($mob,0,3).') '.substr($mob,3,3).''.substr($mob,6,2).''.substr($mob,8,2);
    }

    return $mobile;
}
function GetPey($price) {
    $NewPrice = ($price+1);
    if($NewPrice < 20) return $NewPrice;
    if($NewPrice >= 20 && $NewPrice < 50) return ($NewPrice % 2 == 0) ? $NewPrice : ++$NewPrice;
    if($NewPrice >= 50 && $NewPrice < 100) return ($NewPrice % 5 == 0) ? $NewPrice : ($NewPrice + (5 - ($NewPrice % 5)));
    if($NewPrice >= 100 && $NewPrice < 200) return ($NewPrice % 10 == 0) ? $NewPrice : ($NewPrice + (10 - ($NewPrice % 10)));
    if($NewPrice >= 200 && $NewPrice < 500) return ($NewPrice % 25 == 0) ? $NewPrice : ($NewPrice + (25 - ($NewPrice % 25)));
    if($NewPrice >= 500 && $NewPrice < 1000) return ($NewPrice % 50 == 0) ? $NewPrice : ($NewPrice + (50 - ($NewPrice % 50)));
    if($NewPrice >= 1000 && $NewPrice < 2000) return ($NewPrice % 100 == 0) ? $NewPrice : ($NewPrice + (100 - ($NewPrice % 100)));
    if($NewPrice >= 2000 && $NewPrice < 5000) return ($NewPrice % 250 == 0) ? $NewPrice : ($NewPrice + (250 - ($NewPrice % 250)));
    if($NewPrice >= 5000 && $NewPrice < 10000) return ($NewPrice % 500 == 0) ? $NewPrice : ($NewPrice + (500 - ($NewPrice % 500)));
    if($NewPrice >= 10000 && $NewPrice < 20000) return ($NewPrice % 1000 == 0) ? $NewPrice : ($NewPrice + (1000 - ($NewPrice % 1000)));
    if($NewPrice >= 20000 && $NewPrice < 50000) return ($NewPrice % 2500 == 0) ? $NewPrice : ($NewPrice + (2500 - ($NewPrice % 2500)));
    if($NewPrice >= 50000 && $NewPrice < 100000) return ($NewPrice % 5000 == 0) ? $NewPrice : ($NewPrice + (5000 - ($NewPrice % 5000)));
    if($NewPrice >= 100000) return ($NewPrice % 10000 == 0) ? $NewPrice : ($NewPrice + (10000 - ($NewPrice % 10000)));
}

function PeyCheck($NewPrice) {
    if($NewPrice < 20) return $NewPrice;
    if($NewPrice >= 20 && $NewPrice < 50) return ($NewPrice % 2 == 0) ? $NewPrice : ++$NewPrice;
    if($NewPrice >= 50 && $NewPrice < 100) return ($NewPrice % 5 == 0) ? $NewPrice : ($NewPrice + (5 - ($NewPrice % 5)));
    if($NewPrice >= 100 && $NewPrice < 200) return ($NewPrice % 10 == 0) ? $NewPrice : ($NewPrice + (10 - ($NewPrice % 10)));
    if($NewPrice >= 200 && $NewPrice < 500) return ($NewPrice % 25 == 0) ? $NewPrice : ($NewPrice + (25 - ($NewPrice % 25)));
    if($NewPrice >= 500 && $NewPrice < 1000) return ($NewPrice % 50 == 0) ? $NewPrice : ($NewPrice + (50 - ($NewPrice % 50)));
    if($NewPrice >= 1000 && $NewPrice < 2000) return ($NewPrice % 100 == 0) ? $NewPrice : ($NewPrice + (100 - ($NewPrice % 100)));
    if($NewPrice >= 2000 && $NewPrice < 5000) return ($NewPrice % 250 == 0) ? $NewPrice : ($NewPrice + (250 - ($NewPrice % 250)));
    if($NewPrice >= 5000 && $NewPrice < 10000) return ($NewPrice % 500 == 0) ? $NewPrice : ($NewPrice + (500 - ($NewPrice % 500)));
    if($NewPrice >= 10000 && $NewPrice < 20000) return ($NewPrice % 1000 == 0) ? $NewPrice : ($NewPrice + (1000 - ($NewPrice % 1000)));
    if($NewPrice >= 20000 && $NewPrice < 50000) return ($NewPrice % 2500 == 0) ? $NewPrice : ($NewPrice + (2500 - ($NewPrice % 2500)));
    if($NewPrice >= 50000 && $NewPrice < 100000) return ($NewPrice % 5000 == 0) ? $NewPrice : ($NewPrice + (5000 - ($NewPrice % 5000)));
    if($NewPrice >= 100000) return ($NewPrice % 10000 == 0) ? $NewPrice : ($NewPrice + (10000 - ($NewPrice % 10000)));
}

function generateRandomString($length = 7) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function GenerateCategoryList($d, $r = 0, $pk = 'parent_id', $k = 'id', $c = 'children'){
    $m = array();
    foreach ($d as $e) {
        isset($m[$e[$pk]]) ?: $m[$e[$pk]] = array();
        isset($m[$e[$k]]) ?: $m[$e[$k]] = array();
        $m[$e[$pk]][] = array_merge($e, array($c => &$m[$e[$k]]));
    }

    return $m[$r];
}

function file_size($size) {
    $filesizename = array(" Byte", " KB", " MB", " GB", " TB", " PB", " EB", " ZB", " YB");
    return $size ? round($size/pow(1024, ($i = floor(log($size, 1024)))), 2) . $filesizename[$i] : '0 Byte';
}
function numbers($value){
    return number_format($value,2,'.','');
}

function numbers_dot($value){
    return number_format($value,2,'.',',');
}

function strUpper($val) {
    $search = array('??','??','??','??','??','??','i');
    $replace = array('??','I','??','??','??','??','??');
    return strtoupper(str_replace($search,$replace,$val));
}
function trUcwords($str) {
    return ltrim(mb_convert_case(str_replace(array(' I',' ??', ' ??', ' i'),
        array(' I',' I',' ??',' ??'),' '.$str),
        MB_CASE_TITLE, "UTF-8"));
}
function convertstring($val,$replaces='-') {
    $search = array('??','??','??','??','??','??','??','??','??','??','??','??','?');
    $replace = array('i','g','u','s','o','c','G','U','s','i','O','c','');
    $result = strtolower(str_replace($search,$replace,$val));
    return preg_replace('#[^a-zA-Z0-9]+#',$replaces,$result);
}
function convertstringsms($val) {
    $search = array('??','??','??','??','??','??','??','??','??','??','??','??','?','&');
    $replace = array('i','g','u','s','o','c','G','U','s','i','O','c','','&amp;');
    return (str_replace($search,$replace,$val));
}
function _GETFilter(){
    $g = explode('&',$_SERVER['QUERY_STRING']);
    foreach($g as $key => $val)
    {
        if($key=='url') continue;
        $tmp[] = $val;
    }
    return implode('&',(array)$tmp);
}
function percent_calc($x,$y){
    if($x>=$y) return 100;
    return @(($x / $y) * 100);
}
function calcDiscount($price1,$price2){
    return @round( (($price2-$price1)/$price2) * 100 );
}
function sendMail($email,$subject,$message,$path='./',$filename = null) {
    if(!defined('SMTPHOST')) return false;
    require_once "{$path}libraries/Phpmailer.php";
    $mail = new PHPMailer();
    $mail->IsSMTP();
    $mail->IsHTML(true);
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = 'ssl';
    $mail->Host = SMTPHOST;
    $mail->SMTPDebug = 0;
    $mail->Port = SMTPPORT;
    $mail->Username = SMTPUSER;
    $mail->Password = SMTPPASS;
    $mail->SetFrom(SMTPUSER,SMTPHEADER);
    $mail->AddAddress($email);
    $mail->CharSet = 'UTF-8';
    $mail->Subject = $subject;
    $mail->MsgHTML($message);
    if($filename) {
        if(is_array($filename)) {
            foreach($filename as $file)  $mail->addAttachment($file);
        } else $mail->addAttachment($filename);
    }
    $mail->SMTPOptions = array (
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true)
    );
    return $mail->Send();
}
function gecenZamanHesapla($seconds = 1, $time = '', $ikili = false){
    if ( ! is_numeric($seconds))
    {
        $seconds = 1;
    }
    if ( ! is_numeric($time))
    {
        $time = time();
    }
    if ($time <= $seconds)
    {
        $seconds = 1;
    }
    else
    {
        $seconds = $time - $seconds;
    }
    $str = '';
    $years = floor($seconds / 31536000);
    if ($years > 0)
    {
        $str .= $years.' Y??l , ';
    }
    $seconds -= $years * 31536000;
    $months = floor($seconds / 2628000);
    if ($years > 0 OR $months > 0)
    {
        if ($months > 0)
        {
            $str .= $months.' Ay , ';
        }
        $seconds -= $months * 2628000;
    }
    $weeks = floor($seconds / 604800);
    if ($years > 0 OR $months > 0 OR $weeks > 0)
    {
        if ($weeks > 0)
        {
            $str .= $weeks.' Hafta , ';
        }
        $seconds -= $weeks * 604800;
    }
    $days = floor($seconds / 86400);
    if ($months > 0 OR $weeks > 0 OR $days > 0)
    {
        if ($days > 0)
        {
            $str .= $days.' G??n , ';
        }
        $seconds -= $days * 86400;
    }
    $hours = floor($seconds / 3600);
    if ($days > 0 OR $hours > 0)
    {
        if ($hours > 0)
        {
            $str .= $hours.' sa, ';
        }
        $seconds -= $hours * 3600;
    }
    $minutes = floor($seconds / 60);
    if ($days > 0 OR $hours > 0 OR $minutes > 0)
    {
        if ($minutes > 0)
        {
            $str .= $minutes.' dk, ';
        }
        $seconds -= $minutes * 60;
    }
    if ($str == '')
    {
        $str .= $seconds.'", ';
    }
    if($ikili){
        $parcala = explode(',', trim($str));
        if(count($parcala) == 3){
            return $parcala[0].", ".$parcala[1];
        }else{
            return substr(trim($str), 0, -1);
        }
    }else{
        return substr(trim($str), 0, -1);
    }
}
function redirect($uri = '', $method = 'auto', $code = NULL){
    if ( ! preg_match('#^(\w+:)?//#i', $uri))
    {
        $uri = rtrim(BASEURL,'/').'/'.ltrim($uri,'/');
    }

    if ($method === 'auto' && isset($_SERVER['SERVER_SOFTWARE']) && strpos($_SERVER['SERVER_SOFTWARE'], 'Microsoft-IIS') !== FALSE)
    {
        $method = 'refresh';
    }
    elseif ($method !== 'refresh' && (empty($code) OR ! is_numeric($code)))
    {
        if (isset($_SERVER['SERVER_PROTOCOL'], $_SERVER['REQUEST_METHOD']) && $_SERVER['SERVER_PROTOCOL'] === 'HTTP/1.1')
        {
            $code = ($_SERVER['REQUEST_METHOD'] !== 'GET')
            ? 303
            : 307;
        }
        else
        {
            $code = 302;
        }
    }

    switch ($method)
    {
        case 'refresh':
            header('Refresh:0;url='.$uri);
            break;
        default:
            header('Location: '.$uri, TRUE, $code);
            break;
    }
    exit;
}
function remove_invisible_characters($str, $url_encoded = TRUE){
    $non_displayables = array();

    if ($url_encoded)
    {
        $non_displayables[] = '/%0[0-8bcef]/i';    // url encoded 00-08, 11, 12, 14, 15
        $non_displayables[] = '/%1[0-9a-f]/i';    // url encoded 16-31
        $non_displayables[] = '/%7f/i';    // url encoded 127
    }

    $non_displayables[] = '/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S';    // 00-08, 11, 12, 14-31, 127

    do
    {
        $str = preg_replace($non_displayables, '', $str, -1, $count);
    }
    while ($count);

    return $str;
}
function dump($str, $die = FALSE){
    if(!isDeveloper) return;
    echo '<pre>';
    print_r($str);
    echo '</pre>';
    if($die) die();
}

function get_static_cache($val,$cachetime = (CACHE_TIME * 60))
{

    $cachefile = BASE . 'cache/html/'.ACTIVELANG.'_'.convertstring($val).'.cache';

    if (file_exists($cachefile)) {
        if(time() - $cachetime < filemtime($cachefile))
        {
            return json_decode(file_get_contents($cachefile),true);
            exit;
        }
        else if (file_exists($cachefile)) @unlink($cachefile);
    }

    return false;
}

function set_static_cache($val,$data)
{
    $cachefile = BASE . 'cache/html/'.ACTIVELANG.'_'.convertstring($val).'.cache';
    file_put_contents($cachefile,json_encode($data));
    return $data;
}

function createFilterIds($ids,$count)
{

    $total = array_count_values($ids);
    foreach($total as $key => $val)
    {
        if($val>=$count)
        {
            $newIDS[] = $key;
        }
    }
    return $newIDS;
}

function shorten($url) {
    return file_get_contents('http://tinyurl.com/api-create.php?url='.$url);
}

function encrypt($string) {
    $output = false;
    $encrypt_method = "AES-256-CBC";
    $key = hash('sha256', ENCRYPTION_KEY);
    $iv = substr(hash('sha256', ENCRYPTION_KEY), 0, 16);
    $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
    $output = base64_encode($output);
    return $output;
}

function decrypt($string) {
    $output = false;
    $encrypt_method = "AES-256-CBC";
    $key = hash('sha256', ENCRYPTION_KEY);
    $iv = substr(hash('sha256', ENCRYPTION_KEY), 0, 16);
    $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    return $output;
}

function curl($url){
    $options = array (
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CONNECTTIMEOUT => 5, // timeout on connect
        CURLOPT_TIMEOUT => 5, // timeout on response
        CURLOPT_MAXREDIRS => 1 ,
        CURLOPT_REFERER => "https://goo.gl/",
        CURLOPT_AUTOREFERER => true,
        CURLOPT_URL => $url,
        CURLOPT_USERAGENT => $_SERVER['HTTP_USER_AGENT'],
    );
    $ch = curl_init();
    curl_setopt_array ( $ch, $options );
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

function money2text($money='0.00') {
    $money = explode('.',$money);
    if(count($money)!=2) return false;
    $money_left = $money['0'];
    $money_right = $money['1'];

    //DOKUZLAR
    if(strlen($money_left)==9){
        $i = (int) floor($money_left/100000000);
        if($i==1) $l9="Y??Z";
        if($i==2) $l9="??K?? Y??Z";
        if($i==3) $l9="???? Y??Z";
        if($i==4) $l9="D??RT Y??Z";
        if($i==5) $l9="BE?? Y??Z";
        if($i==6) $l9="ALTI Y??Z";
        if($i==7) $l9="YED?? Y??Z";
        if($i==8) $l9="SEK??Z Y??Z";
        if($i==9) $l9="DOKUZ Y??Z";
        if($i==0) $l9="";
        $money_left = substr($money_left,1,strlen($money_left)-1);
    }

    //SEK??ZLER
    if(strlen($money_left)==8){
        $i = (int) floor($money_left/10000000);
        if($i==1) $l8="ON";
        if($i==2) $l8="Y??RM??";
        if($i==3) $l8="OTUZ";
        if($i==4) $l8="KIRK";
        if($i==5) $l8="ELL??";
        if($i==6) $l8="ATMI??";
        if($i==7) $l8="YETM????";
        if($i==8) $l8="SEKSEN";
        if($i==9) $l8="DOKSAN";
        if($i==0) $l8="";
        $money_left=substr($money_left,1,strlen($money_left)-1);
    }

    //YED??LER
    if(strlen($money_left)==7){
        $i = (int) floor($money_left/1000000);
        if($i==1){
            if($i!="NULL"){
                $l7 = "B??R M??LYON";
            }else{
                $l7 = "M??LYON";
            }
        }
        if($i==2) $l7="??K?? M??LYON";
        if($i==3) $l7="???? M??LYON";
        if($i==4) $l7="D??RT M??LYON";
        if($i==5) $l7="BE?? M??LYON";
        if($i==6) $l7="ALTI M??LYON";
        if($i==7) $l7="YED?? M??LYON";
        if($i==8) $l7="SEK??Z M??LYON";
        if($i==9) $l7="DOKUZ M??LYON";
        if($i==0){
            if($i!="NULL"){
                $l7="M??LYON";
            }else{
                $l7="";
            }
        }
        $money_left=substr($money_left,1,strlen($money_left)-1);
    }

    //ALTILAR
    if(strlen($money_left)==6){
        $i = (int) floor($money_left/100000);
        if($i==1) $l6="Y??Z";
        if($i==2) $l6="??K?? Y??Z";
        if($i==3) $l6="???? Y??Z";
        if($i==4) $l6="D??RT Y??Z";
        if($i==5) $l6="BE?? Y??Z";
        if($i==6) $l6="ALTI Y??Z";
        if($i==7) $l6="YED?? Y??Z";
        if($i==8) $l6="SEK??Z Y??Z";
        if($i==9) $l6="DOKUZ Y??Z";
        if($i==0) $l6="";
        $money_left = substr($money_left,1,strlen($money_left)-1);
    }

    //BE??LER
    if(strlen($money_left)==5){
        $i = (int) floor($money_left/10000);
        if($i==1) $l5="ON B??N";
        if($i==2) $l5="Y??RM?? B??N";
        if($i==3) $l5="OTUZ B??N";
        if($i==4) $l5="KIRK B??N";
        if($i==5) $l5="ELL?? B??N";
        if($i==6) $l5="ATMI?? B??N";
        if($i==7) $l5="YETM???? B??N";
        if($i==8) $l5="SEKSEN B??N";
        if($i==9) $l5="DOKSAN B??N";
        if($i==0) $l5="";
        $money_left=substr($money_left,1,strlen($money_left)-1);
    }

    //D??RTLER
    if(strlen($money_left)==4){
        $i = (int) floor($money_left/1000);
        if($i==1){
            if($i!=""){
                $l4 = "B??R B??N";
            }else{
                $l4 = "B??N";
            }
        }
        if($i==2) $l4="??K?? B??N";
        if($i==3) $l4="???? B??N";
        if($i==4) $l4="D??RT B??N";
        if($i==5) $l4="BE?? B??N";
        if($i==6) $l4="ALTI B??N";
        if($i==7) $l4="YED?? B??N";
        if($i==8) $l4="SEKZ B??N";
        if($i==9) $l4="DOKUZ B??N";
        if($i==0){
            if($i!=""){
                $l4="B??N";
            }else{
                $l4="";
            }
        }
        $money_left=substr($money_left,1,strlen($money_left)-1);
    }

    //????LER
    if(strlen($money_left)==3){
        $i = (int) floor($money_left/100);
        if($i==1) $l3="Y??Z";
        if($i==2) $l3="??K??Y??Z";
        if($i==3) $l3="????Y??Z";
        if($i==4) $l3="D??RTY??Z";
        if($i==5) $l3="BE??Y??Z";
        if($i==6) $l3="ALTIY??Z";
        if($i==7) $l3="YED??Y??Z";
        if($i==8) $l3="SEK??ZY??Z";
        if($i==9) $l3="DOKUZY??Z";
        if($i==0) $l3="";
        $money_left=substr($money_left,1,strlen($money_left)-1);
    }

    //??K??LER
    if(strlen($money_left)==2){
        $i = (int) floor($money_left/10);
        if($i==1) $l2="ON";
        if($i==2) $l2="Y??RM??";
        if($i==3) $l2="OTUZ";
        if($i==4) $l2="KIRK";
        if($i==5) $l2="ELL??";
        if($i==6) $l2="ATMI??";
        if($i==7) $l2="YETM????";
        if($i==8) $l2="SEKSEN";
        if($i==9) $l2="DOKSAN";
        if($i==0) $l2="";
        $money_left=substr($money_left,1,strlen($money_left)-1);
    }

    //B??RLER
    if(strlen($money_left)==1){
        $i = (int) floor($money_left/1);
        if($i==1) $l1="B??R";
        if($i==2) $l1="??K??";
        if($i==3) $l1="????";
        if($i==4) $l1="D??RT";
        if($i==5) $l1="BE??";
        if($i==6) $l1="ALTI";
        if($i==7) $l1="YED??";
        if($i==8) $l1="SEK??Z";
        if($i==9) $l1="DOKUZ";
        if($i==0) $l1="";
        $money_left=substr($money_left,1,strlen($money_left)-1);
    }

    //SA?? ??K??
    if(strlen($money_right)==2){
        $i = (int) floor($money_right/10);
        if($i==1) $r2="ON";
        if($i==2) $r2="Y??RM??";
        if($i==3) $r2="OTUZ";
        if($i==4) $r2="KIRK";
        if($i==5) $r2="ELL??";
        if($i==6) $r2="ALTMI??";
        if($i==7) $r2="YETM????";
        if($i==8) $r2="SEKSEN";
        if($i==9) $r2="DOKSAN";
        if($i==0) $r2="SIFIR";
        $money_right=substr($money_right,1,strlen($money_right)-1);
    }

    //SA?? B??R
    if(strlen($money_right)==1){
        $i = (int) floor($money_right/1);
        if($i==1) $r1="B??R";
        if($i==2) $r1="??K??";
        if($i==3) $r1="????";
        if($i==4) $r1="D??RT";
        if($i==5) $r1="BE??";
        if($i==6) $r1="ALTI";
        if($i==7) $r1="YED??";
        if($i==8) $r1="SEK??Z";
        if($i==9) $r1="DOKUZ";
        if($i==0) $r1="";
        $money_right=substr($money_right,1,strlen($money_right)-1);
    }

    return "$l9 $l8 $l7 $l6 $l5 $l4 $l3 $l2 $l1 T??RK L??RASI $r2 $r1 KURU??";
}

function sendSMS($number,$text) {
    if(!defined('SMSUSER')) return false;
    if(!trim($number)) return false;

    $xml='<?xml version="1.0" encoding="iso-8859-9"?>
    <mainbody>
    <header>
    <company>NETGSM</company>
    <usercode>'.SMSUSER.'</usercode>
    <password>'.SMSPASSWORD.'</password>
    <startdate></startdate>
    <stopdate></stopdate>
    <type>1:n</type>
    <msgheader>'.SMSHEADER.'</msgheader>
    </header>
    <body>
    <msg><![CDATA['.convertstringsms($text).']]></msg>
    <no>'.$number.'</no>
    </body>
    </mainbody>';

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,'http://api.netgsm.com.tr/xmlbulkhttppost.asp');
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Content-Type: text/xml"));
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
    $result = curl_exec($ch);
    file_put_contents('sms.txt',print_r($result,1),FILE_APPEND);
    return $result;
}
?>
