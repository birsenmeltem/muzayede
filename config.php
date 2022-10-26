<?php
header("Content-Type: text/html; charset=utf-8");
$path = pathinfo(__FILE__);
@ini_set("session.gc_maxlifetime", 3600);

ini_set('display_errors',1);
ini_set('memory_limit','2G');

define('DEBUG',0);

$hostURL = str_replace(["http://","www."], "",$_SERVER["HTTP_HOST"]);

if($hostURL != 'localhost') {

    if($_SERVER["HTTPS"] != "on") @header('Location: https://'.rtrim($hostURL,'/').$_SERVER['REQUEST_URI']);
    if(stripos($_SERVER["HTTP_HOST"],'www')!==FALSE) @header('Location: https://'.rtrim($hostURL,'/').$_SERVER['REQUEST_URI']);

    define('BASEURL','https://'.rtrim($hostURL,'/').'/');

} else define('BASEURL','http://localhost/phebusmuzayede/');

if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $IPADDRESS = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $IPADDRESS = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $IPADDRESS = $_SERVER['REMOTE_ADDR'];
}

define('USER_IP',$IPADDRESS);

define('ADMIN_FOLDER','manager');

define('BASE',rtrim($path['dirname'],'/').'/');
define('ADMINBASE',rtrim($path['dirname'],'/').'/'.rtrim(ADMIN_FOLDER,'/').'/');
define('TEMPLATE','views/default/');
define('ADMIN_TEMPLATE','views/default/');
define('SHOW_LIST_PAGE',30);
define('SHOW_LIST_COMMENT',3);

define('ENCRYPTION_KEY','!?Or*gani*zma?!');
define('isDeveloper',(USER_IP == '176.236.214.4'));

define('ADMINBASEURL',BASEURL.rtrim(ADMIN_FOLDER,'/').'/');
define('LIVEBASEURL','https://'.rtrim($hostURL,'/').':2021/');
define('CDNURL','https://phebusmuzayede.com/');
/*
if(!isDeveloper)  {
http_response_code(403);
die;
}*/

$Months = [
    1 =>"Ocak",
    2 =>"Şubat",
    3 =>"Mart",
    4 =>"Nisan",
    5 =>"Mayıs",
    6 =>"Haziran",
    7 =>"Temmuz",
    8 =>"Ağustos",
    9 =>"Eylül",
    10 =>"Ekim",
    11 =>"Kasım",
    12 =>"Aralık"
];

$OrderStatus= [
    -1 => 'Tamamlanmayan Sipariş',
    0 => '<span class="kt-badge kt-badge--primary kt-badge--md kt-badge--inline">Sipariş başarı ile alındı</span>',
    1 => '<span class="kt-badge kt-badge--info kt-badge--md kt-badge--inline kt-label-bg-color-2">Sipariş kargoya hazırlandı</span>',
    2 => '<span class="kt-badge kt-badge--success kt-badge--md kt-badge--inline">Sipariş kargoya verildi</span>',
    3 => '<span class="kt-badge kt-badge--dark kt-badge--md kt-badge--inline">Sipariş tamamlandı.</span>',
    4 => '<span class="kt-badge kt-badge--danger kt-badge--md kt-badge--inline">Sipariş iptal edildi.</span>',
    5 => '<span class="kt-badge kt-badge--secondary kt-badge--md kt-badge--inline">Ödeme bekleniyor.</span>',
];

$PaymentType = [
    1 => 'KREDİ KARTI',
    2 => 'HAVALE/EFT',
    3 => 'KAPIDA NAKİT',
    4 => 'KAPIDA KREDİ KARTI',
];
$CommentStatus = [
    0 => '<span class="kt-badge kt-badge--warning kt-badge--md kt-badge--inline">Onaysız</span>',
    1 => '<span class="kt-badge kt-badge--success kt-badge--md kt-badge--inline">Onaylı</span>',
];

$Status = [
    0 => '<span class="kt-badge kt-badge--warning kt-badge--md kt-badge--inline">Pasif</span>',
    1 => '<span class="kt-badge kt-badge--success kt-badge--md kt-badge--inline">Aktif</span>',
];
$MuzayedeStatus = [
    0 => '<span class="kt-badge kt-badge--danger kt-badge--md kt-badge--inline">Yayında Değil</span>',
    1 => '<span class="kt-badge kt-badge--success kt-badge--md kt-badge--inline">Yayında</span>',
    2 => '<span class="kt-badge kt-badge--info kt-badge--md kt-badge--inline">Canlı</span>',
    3 => '<span class="kt-badge kt-badge--warning kt-badge--md kt-badge--inline">Canlı Bitti</span>',
];

$ProductStatus = [
    0 => '<span class="kt-badge kt-badge--warning kt-badge--md kt-badge--inline">Yayında Değil</span>',
    1 => '<span class="kt-badge kt-badge--success kt-badge--md kt-badge--inline">Yayında</span>',
    2 => '<span class="kt-badge kt-badge--info kt-badge--md kt-badge--inline">Satıldı</span>',
    3 => '<span class="kt-badge kt-badge--danger kt-badge--md kt-badge--inline">Satılmadı</span>',
    4 => '<span class="kt-badge kt-badge--danger kt-badge--md kt-badge--inline">İptal</span>',
];

$CargoStatus = [
    1 => 'Kargo Takip kodu bekleniyor',
    2 => 'Kargo Takip kodu SMS/Mail ile Gönderildi',
    3 => 'Hatalı işlem gönderilemedi.',
];

$YER = [
    0 => 'Üst Menü',
    1 => 'Alt Menü',
    2 => 'İçerikler',
];

$paytr = array(
    'id' => '144859',
    'key' => 'hAzptqsp259Wbn9w',
    'salt' => 'orYGyo9P2bs7Pjo2'
);

$TransactionType = [
    0 => '<span class="kt-badge kt-badge--warning kt-badge--md kt-badge--inline">Aldı</span>',
    1 => '<span class="kt-badge kt-badge--info kt-badge--md kt-badge--inline">Sattı</span>',
    2 => '<span class="kt-badge kt-badge--success kt-badge--md kt-badge--inline">Ödeme</span>',
];

$PaymentPriceType = [
    1 => '<span class="kt-badge kt-badge--success kt-badge--md kt-badge--inline">Tamamlandı</span>',
    2 => '<span class="kt-badge kt-badge--danger kt-badge--md kt-badge--inline">Tamamlanmadı</span>',
    3 => '<span class="kt-badge kt-badge--danger kt-badge--md kt-badge--inline">İptal</span>',
];