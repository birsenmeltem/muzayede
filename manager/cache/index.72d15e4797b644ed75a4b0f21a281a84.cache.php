<?php if(!class_exists('view')){exit;}?><!DOCTYPE html>
<html lang="tr" >
    <!-- begin::Head -->
    <head>
        <?php $tpl = new View;$tpl->assign( $this->var );$tpl->draw( "header" );?>

        <?php $tpl = new View;$tpl->assign( $this->var );$tpl->draw( "js" );?>

    </head>
    <!-- end::Head -->

    <!-- begin::Body -->
    <body  class="kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-header--fixed kt-header-mobile--fixed kt-subheader--enabled kt-subheader--fixed kt-subheader--solid kt-aside--enabled kt-aside--fixed kt-page--loading"  >


        <!-- begin:: Page -->
        <!-- begin:: Header Mobile -->
        <div id="kt_header_mobile" class="kt-header-mobile  kt-header-mobile--fixed " >
            <div class="kt-header-mobile__logo">
                <a href="<?php  echo ADMINBASEURL;?>">
                    Admin Panel
                </a>
            </div>
            <div class="kt-header-mobile__toolbar">
                <button class="kt-header-mobile__toggler kt-header-mobile__toggler--left" id="kt_aside_mobile_toggler"><span></span></button>

                <button class="kt-header-mobile__topbar-toggler" id="kt_header_mobile_topbar_toggler"><i class="fa fa-ellipsis-v"></i></button>
            </div>
        </div>
        <!-- end:: Header Mobile -->
        <div class="kt-grid kt-grid--hor kt-grid--root">
            <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--ver kt-page">
                <!-- begin:: Aside -->

                <!-- Uncomment this to display the close button of the panel
                <button class="kt-aside-close " id="kt_aside_close_btn"><i class="la la-close"></i></button>
                -->

                <div class="kt-aside  kt-aside--fixed  kt-grid__item kt-grid kt-grid--desktop kt-grid--hor-desktop" id="kt_aside">
                    <div
                        id="kt_aside_menus"
                        class="kt-aside-menu "
                        data-ktmenu-vertical="1"
                        data-ktmenu-scroll="1" data-ktmenu-dropdown-timeout="500"
                        >

                        <?php $tpl = new View;$tpl->assign( $this->var );$tpl->draw( "leftmenu" );?>

                    </div>

                    <div class="kt-aside  kt-aside--fixed  kt-grid__item kt-grid kt-grid--desktop kt-grid--hor-desktop" id="kt_aside">
                        <!-- begin:: Aside -->
                        <div class="kt-aside__brand kt-grid__item " id="kt_aside_brand" style="text-align: center">
                            <div class="kt-aside__brand-logo" >
                                <h4><a href="<?php  echo ADMINBASEURL;?>"  style="color: #fff;">
                                        Admin Panel
                                    </a></h4>
                            </div>

                            <div class="kt-aside__brand-tools">
                                <button class="kt-aside__brand-aside-toggler" id="kt_aside_toggler">
                                    <span><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <polygon points="0 0 24 0 24 24 0 24"/>
                                                <path d="M5.29288961,6.70710318 C4.90236532,6.31657888 4.90236532,5.68341391 5.29288961,5.29288961 C5.68341391,4.90236532 6.31657888,4.90236532 6.70710318,5.29288961 L12.7071032,11.2928896 C13.0856821,11.6714686 13.0989277,12.281055 12.7371505,12.675721 L7.23715054,18.675721 C6.86395813,19.08284 6.23139076,19.1103429 5.82427177,18.7371505 C5.41715278,18.3639581 5.38964985,17.7313908 5.76284226,17.3242718 L10.6158586,12.0300721 L5.29288961,6.70710318 Z" fill="#000000" fill-rule="nonzero" transform="translate(8.999997, 11.999999) scale(-1, 1) translate(-8.999997, -11.999999) "/>
                                                <path d="M10.7071009,15.7071068 C10.3165766,16.0976311 9.68341162,16.0976311 9.29288733,15.7071068 C8.90236304,15.3165825 8.90236304,14.6834175 9.29288733,14.2928932 L15.2928873,8.29289322 C15.6714663,7.91431428 16.2810527,7.90106866 16.6757187,8.26284586 L22.6757187,13.7628459 C23.0828377,14.1360383 23.1103407,14.7686056 22.7371482,15.1757246 C22.3639558,15.5828436 21.7313885,15.6103465 21.3242695,15.2371541 L16.0300699,10.3841378 L10.7071009,15.7071068 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" transform="translate(15.999997, 11.999999) scale(-1, 1) rotate(-270.000000) translate(-15.999997, -11.999999) "/>
                                            </g>
                                        </svg></span>
                                    <span><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <polygon points="0 0 24 0 24 24 0 24"/>
                                                <path d="M12.2928955,6.70710318 C11.9023712,6.31657888 11.9023712,5.68341391 12.2928955,5.29288961 C12.6834198,4.90236532 13.3165848,4.90236532 13.7071091,5.29288961 L19.7071091,11.2928896 C20.085688,11.6714686 20.0989336,12.281055 19.7371564,12.675721 L14.2371564,18.675721 C13.863964,19.08284 13.2313966,19.1103429 12.8242777,18.7371505 C12.4171587,18.3639581 12.3896557,17.7313908 12.7628481,17.3242718 L17.6158645,12.0300721 L12.2928955,6.70710318 Z" fill="#000000" fill-rule="nonzero"/>
                                                <path d="M3.70710678,15.7071068 C3.31658249,16.0976311 2.68341751,16.0976311 2.29289322,15.7071068 C1.90236893,15.3165825 1.90236893,14.6834175 2.29289322,14.2928932 L8.29289322,8.29289322 C8.67147216,7.91431428 9.28105859,7.90106866 9.67572463,8.26284586 L15.6757246,13.7628459 C16.0828436,14.1360383 16.1103465,14.7686056 15.7371541,15.1757246 C15.3639617,15.5828436 14.7313944,15.6103465 14.3242754,15.2371541 L9.03007575,10.3841378 L3.70710678,15.7071068 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" transform="translate(9.000003, 11.999999) rotate(-270.000000) translate(-9.000003, -11.999999) "/>
                                            </g>
                                        </svg></span>
                                </button>
                                <!--
                                <button class="kt-aside__brand-aside-toggler kt-aside__brand-aside-toggler--left" id="kt_aside_toggler"><span></span></button>
                                -->
                            </div>
                        </div>
                        <!-- end:: Aside -->	<!-- begin:: Aside Menu -->
                        <div class="kt-aside-menu-wrapper kt-grid__item kt-grid__item--fluid" id="kt_aside_menu_wrapper">

                            <div
                                id="kt_aside_menu"
                                class="kt-aside-menu "
                                data-ktmenu-vertical="1"
                                data-ktmenu-scroll="1" data-ktmenu-dropdown-timeout="500"
                                >

                                <?php $tpl = new View;$tpl->assign( $this->var );$tpl->draw( "leftmenu" );?>

                            </div>
                        </div>
                        <!-- end:: Aside Menu -->
                    </div>
                    <!-- end:: Aside -->
                </div>


                <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor kt-wrapper" id="kt_wrapper">
                    <!-- begin:: Header -->
                    <div id="kt_header" class="kt-header kt-grid__item  kt-header--fixed " >

                        <div class="kt-header-menu-wrapper" id="kt_header_menu_wrapper"></div>

                        <div class="kt-header__topbar">

                            <!-- Start Language-->
                            <div class="kt-header__topbar-item kt-header__topbar-item--langs">
                                <div class="kt-header__topbar-wrapper" data-toggle="dropdown" data-offset="10px,0px">
                                    <span class="kt-header__topbar-icon">
                                        <img class="" src="../data/langs/<?php echo $ActiveAdminLang["picture"];?>" alt="" />
                                    </span>
                                </div>
                                <div class="dropdown-menu dropdown-menu-fit dropdown-menu-right dropdown-menu-anim dropdown-menu-top-unround">
                                    <ul class="kt-nav kt-margin-t-10 kt-margin-b-10">
                                        <?php $counter1=-1; if( isset($Langs) && is_array($Langs) && sizeof($Langs) ) foreach( $Langs as $key1 => $value1 ){ $counter1++; ?>

                                        <li class="kt-nav__item">
                                            <a href="<?php  echo ADMINBASEURL;?>changelang/<?php echo $value1["id"];?>" class="kt-nav__link">
                                                <span class="kt-nav__link-icon"><img src="../data/langs/<?php echo $value1["picture"];?>" alt="" /></span>
                                                <span class="kt-nav__link-text"><?php echo $value1["name"];?></span>
                                            </a>
                                        </li>
                                        <?php } ?>

                                    </ul>
                                </div>
                            </div>

                            <!-- Start Language
                            <div class="kt-header__topbar-item kt-header__topbar-item--langs">
                            <div class="kt-header__topbar-wrapper" data-toggle="dropdown" data-offset="10px,0px">
                            <span class="kt-header__topbar-icon">
                            <img class="" src="<?php  echo ADMIN_TEMPLATE;?>assets/media/flags/020-flag.svg" alt="" />
                            </span>
                            </div>
                            <div class="dropdown-menu dropdown-menu-fit dropdown-menu-right dropdown-menu-anim dropdown-menu-top-unround">
                            <ul class="kt-nav kt-margin-t-10 kt-margin-b-10">
                            <li class="kt-nav__item kt-nav__item--active">
                            <a href="#" class="kt-nav__link">
                            <span class="kt-nav__link-icon"><img src="<?php  echo ADMIN_TEMPLATE;?>assets/media/flags/020-flag.svg" alt="" /></span>
                            <span class="kt-nav__link-text">English</span>
                            </a>
                            </li>
                            <li class="kt-nav__item">
                            <a href="#" class="kt-nav__link">
                            <span class="kt-nav__link-icon"><img src="<?php  echo ADMIN_TEMPLATE;?>assets/media/flags/016-spain.svg" alt="" /></span>
                            <span class="kt-nav__link-text">Spanish</span>
                            </a>
                            </li>
                            <li class="kt-nav__item">
                            <a href="#" class="kt-nav__link">
                            <span class="kt-nav__link-icon"><img src="<?php  echo ADMIN_TEMPLATE;?>assets/media/flags/017-germany.svg" alt="" /></span>
                            <span class="kt-nav__link-text">German</span>
                            </a>
                            </li>
                            </ul>
                            </div>
                            </div>

                            Language bar -->
                            <div class="kt-header__topbar-item kt-header__topbar-item--user">
                                <div class="kt-header__topbar-wrapper" data-toggle="dropdown" data-offset="0px,0px">
                                    <div class="kt-header__topbar-user">
                                        <span class="kt-header__topbar-welcome kt-hidden-mobile">Merhaba,</span>
                                        <span class="kt-header__topbar-username kt-hidden-mobile"><?php echo $user["name"];?></span>
                                        <!--use below badge element instead the user avatar to display username's first letter(remove kt-hidden class to display it) -->
                                        <span class="kt-badge kt-badge--username kt-badge--unified-success kt-badge--lg kt-badge--rounded kt-badge--bold"><?php echo $user_harf;?></span>
                                    </div>
                                </div>

                                <div class="dropdown-menu dropdown-menu-fit dropdown-menu-right dropdown-menu-anim dropdown-menu-top-unround dropdown-menu-xl">
                                    <!--begin: Head -->
                                    <div class="kt-user-card kt-user-card--skin-dark kt-notification-item-padding-x" style="background-image: url(<?php  echo TEMPLATE;?>assets/media/misc/bg-1.jpg)">
                                        <div class="kt-user-card__avatar">
                                            <img class="kt-hidden" alt="Pic" src="<?php  echo TEMPLATE;?>assets/media/users/300_25.jpg" />
                                            <!--use below badge element instead the user avatar to display username's first letter(remove kt-hidden class to display it) -->
                                            <span class="kt-badge kt-badge--lg kt-badge--rounded kt-badge--bold kt-font-success"><?php echo $user_harf;?></span>
                                        </div>
                                        <div class="kt-user-card__name">
                                            <?php echo $user["name"];?>

                                        </div>
                                    </div>
                                    <!--end: Head -->

                                    <!--begin: Navigation -->
                                    <div class="kt-notification">
                                        <a href="<?php  echo ADMINBASEURL;?>settings/admin_edit/<?php echo $user["id"];?>" data-toggle="ajaxModal" class="kt-notification__item">
                                            <div class="kt-notification__item-icon">
                                                <i class="flaticon2-calendar-3 kt-font-success"></i>
                                            </div>
                                            <div class="kt-notification__item-details">
                                                <div class="kt-notification__item-title kt-font-bold">
                                                    Bilgilerim
                                                </div>
                                                <div class="kt-notification__item-time">
                                                    Hesabım ve daha fazlası
                                                </div>
                                            </div>
                                        </a>
                                        <div class="kt-notification__custom kt-space-between">
                                            <a href="<?php  echo ADMINBASEURL;?>logout" class="btn btn-label btn-label-brand btn-sm btn-bold">Güvenli Çıkış</a>
                                        </div>
                                    </div>
                                    <!--end: Navigation -->
                                </div>
                            </div>
                            <!--end: User Bar -->
                        </div>
                        <!-- end:: Header Topbar -->
                    </div>
                    <!-- end:: Header -->

                    <div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
                        <?php echo $content;?>

                        <!-- end:: Content -->
                    </div>

                    <!-- begin:: Footer -->
                    <div class="kt-footer  kt-grid__item kt-grid kt-grid--desktop kt-grid--ver-desktop" id="kt_footer">
                        <div class="kt-container  kt-container--fluid ">
                            <div class="kt-footer__copyright">
                                2020&nbsp;&copy;&nbsp;<a href="http://muzayedesistemi.com" class="kt-link">Müzayedesistemi.com</a>
                            </div>

                        </div>
                    </div>
                    <!-- end:: Footer -->
                </div>
            </div>
        </div>

        <!-- end:: Page -->


        <!-- begin::Scrolltop -->
        <div id="kt_scrolltop" class="kt-scrolltop">
            <i class="fa fa-arrow-up"></i>
        </div>
        <!-- end::Scrolltop -->
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

        <!--end::Global Theme Bundle -->


        <!--begin::Page Scripts(used by this page) -->
        <script src="<?php  echo TEMPLATE;?>assets/js/dashboard.js?v<?php echo $version;?>" type="text/javascript"></script>
        <!--end::Page Scripts -->
    </body>
    <!-- end::Body -->
</html>