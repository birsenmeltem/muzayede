<?php if(!class_exists('view')){exit;}?><!DOCTYPE html>
<html lang="en" >
    <!-- begin::Head -->
    <head>
       <?php $tpl = new View;$tpl->assign( $this->var );$tpl->draw( "header" );?>

       <link href="<?php  echo ADMIN_TEMPLATE;?>assets/css/login.css" rel="stylesheet" type="text/css" />
    </head>
    <!-- end::Head -->
    <!-- begin::Body -->
    <body  class="kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-header--fixed kt-header-mobile--fixed kt-subheader--enabled kt-subheader--fixed kt-subheader--solid kt-aside--enabled kt-aside--fixed kt-page--loading"  >
        <!-- begin:: Page -->
        <div class="kt-grid kt-grid--ver kt-grid--root">
            <div class="kt-grid kt-grid--hor kt-grid--root  kt-login kt-login--v3 kt-login--signin" id="kt_login">
                <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" style="background-image: url(<?php  echo TEMPLATE;?>assets/media/bg/bg-3.jpg);">
                    <div class="kt-grid__item kt-grid__item--fluid kt-login__wrapper">
                        <div class="kt-login__container">
                            <div class="kt-login__logo">

                            </div>
                            <?php echo $info;?>

                            <div class="kt-login__signin">
                                <div class="kt-login__head">
                                    <h3 class="kt-login__title">Yönetici Girişi Yapınız</h3>
                                </div>
                                <form class="kt-form" action="" method="POST">
                                    <div class="input-group">
                                        <input class="form-control" type="text" placeholder="Kullanıcı Adı" name="login[username]" autocomplete="off" required="required">
                                    </div>
                                    <div class="input-group">
                                        <input class="form-control" type="password" placeholder="Şifre" name="login[password]" required="required">
                                    </div>
                                    <div class="row kt-login__extra">
                                        <div class="col">
                                            <label class="kt-checkbox">
                                                <input type="checkbox" name="remember"> Beni Hatırla
                                                <span></span>
                                            </label>
                                        </div>
                                        <div class="col kt-align-right">
                                        </div>
                                    </div>
                                    <div class="kt-login__actions">
                                        <button id="kt_login_signin_submit" class="btn btn-brand btn-elevate kt-login__btn-primary"><i class="fa fa-sign-in" aria-hidden="true"></i> Giriş Yap!</button>&nbsp;&nbsp;
                                    </div>
                                </form>
                            </div>
                            <!--
                            <div class="kt-login__account">
                                <span class="kt-login__account-msg">
                                    Don't have an account yet ?
                                </span>
                                &nbsp;&nbsp;
                                <a href="javascript:;" id="kt_login_signup" class="kt-login__account-link">Sign Up!</a>
                            </div>
                            -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end:: Page -->
        <!-- begin::Global Config(global config for global JS sciprts) -->
        <script>
            var KTAppOptions = {
                "colors": {
                    "state": {
                        "brand": "#5d78ff",
                        "dark": "#282a3c",
                        "light": "#ffffff",
                        "primary": "#5867dd",
                        "success": "#34bfa3",
                        "info": "#36a3f7",
                        "warning": "#ffb822",
                        "danger": "#fd3995"
                    },
                    "base": {
                        "label": [
                            "#c5cbe3",
                            "#a1a8c3",
                            "#3d4465",
                            "#3e4466"
                        ],
                        "shape": [
                            "#f0f3ff",
                            "#d9dffa",
                            "#afb4d4",
                            "#646c9a"
                        ]
                    }
                }
            };
        </script>
        <!-- end::Global Config -->
        <!--begin::Global Theme Bundle(used by all pages) -->
        <?php $tpl = new View;$tpl->assign( $this->var );$tpl->draw( "js" );?>

        <!--end::Page Scripts -->
    </body>
    <!-- end::Body -->
</html>