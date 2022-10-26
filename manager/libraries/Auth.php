<?php
class Auth
{
    public static function handle()
    {
        $user = Session::fetch('admin');
        if(!$user) self::redirect('login');
    }

    public static function redirect($url=NULL)
    {
        @header("Location: ".ADMINBASEURL."$url");
        exit;
    }

    public static function checkPerm()
    {
        $user = Session::fetch('admin');
        if(!$user['perms']) {
            @header("Location: ".ADMINBASEURL);
            exit;
        }
    }
}
?>
