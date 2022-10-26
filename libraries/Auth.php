<?php
class Auth
{
    public static function handle()
    {
        $user = Session::fetch('user');
        if(!$user){
            $_SERVER['REQUEST_URI'] = str_replace(['/shopper/','shopper/'],'',$_SERVER['REQUEST_URI']);
            self::redirect('user/login?returnUrl='.rtrim(BASEURL,'/') . '/'.ltrim($_SERVER['REQUEST_URI'],'/'));
        }
    }

    public static function redirect($url='')
    {
        @header("Location: ".BASEURL."$url");
        exit;
    }
}
?>
