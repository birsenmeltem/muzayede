<?php
    /**
    * @author : Mesut Eyrice (mesut@organizma.web.tr)
    * Şahsıma ait bir frameworktür. Standart Model/View/Controller mantığı ile çalıştığından ilgili klasörlerde dosyaları bulabilirsiniz.
    * Özel olarak yazılmış olup kopyalanması iznim dışında kullanılması yasaktır.
    */

    session_set_cookie_params(['samesite' => 'None', 'secure' => true]);
    @ob_start();
    @session_start();
    ini_set('memory_limit','6G');
    @date_default_timezone_set('Europe/Istanbul');
    @header('Content-type: text/html; charset=utf-8');
    @header("developed-by: Mesut Eyrice");
    error_reporting(E_ALL ^ (E_NOTICE | E_DEPRECATED));
    ini_set("error_log", "error.log");
    require_once 'config.php';
    require_once 'libraries/Cargo.php';
    require_once 'libraries/Input.php';
    require_once 'libraries/Pagination.php';
    require_once 'libraries/Session.php';
    require_once 'libraries/Auth.php';
    require_once 'libraries/Functions.php';
    require_once 'libraries/Database.php';
    require_once 'libraries/View.php';
    require_once 'libraries/Controller.php';
    require_once 'libraries/Model.php';
    require_once 'libraries/Rooter.php';

    $rooter = new Rooter();

?>