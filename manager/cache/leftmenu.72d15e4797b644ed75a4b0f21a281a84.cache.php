<?php if(!class_exists('view')){exit;}?><ul class="kt-menu__nav ">
    <li class="kt-menu__item  kt-menu__item--submenu <?php if( $active_menu == 'dashboard' ){ ?>kt-menu__item--here<?php } ?>" data-ktmenu-submenu-toggle="hover">
    <a href="<?php  echo ADMINBASEURL;?>" class="kt-menu__link kt-menu__toggle">
        <span class="kt-menu__link-icon">
            <i class="fa fa-cogs"></i>
        </span>
        <span class="kt-menu__link-text">Kontrol Paneli</span>
    </a>
    </li>
    <li class="kt-menu__item  kt-menu__item--submenu <?php if( $active_menu == 'categories' ){ ?>kt-menu__item--here kt-menu__item--open<?php } ?>" aria-haspopup="true"  data-ktmenu-submenu-toggle="hover">
    <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
        <span class="kt-menu__link-icon">
            <i class="fa fa-project-diagram"></i>
        </span>
        <span class="kt-menu__link-text">Kategoriler</span>
        <i class="kt-menu__ver-arrow la la-angle-right"></i>
    </a>
    <div class="kt-menu__submenu ">
        <span class="kt-menu__arrow"></span>
        <ul class="kt-menu__subnav">
            <li class="kt-menu__item "><a  href="<?php  echo ADMINBASEURL;?>categories/add" data-toggle="ajaxModal" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Yeni Oluştur</span></a></li>
            <li class="kt-menu__item "><a  href="<?php  echo ADMINBASEURL;?>categories/main" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Tümünü Listele</span></a></li>
        </ul>
    </div>
    </li>
    <li class="kt-menu__item  kt-menu__item--submenu <?php if( $active_menu == 'auctions' ){ ?>kt-menu__item--here kt-menu__item--open<?php } ?>" aria-haspopup="true"  data-ktmenu-submenu-toggle="hover">
    <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
        <span class="kt-menu__link-icon">
            <i class="fa fa-certificate"></i>
        </span>
        <span class="kt-menu__link-text">Müzayedeler</span>
        <i class="kt-menu__ver-arrow la la-angle-right"></i>
    </a>
    <div class="kt-menu__submenu ">
        <span class="kt-menu__arrow"></span>
        <ul class="kt-menu__subnav">
            <li class="kt-menu__item "><a  href="<?php  echo ADMINBASEURL;?>auctions/add" data-toggle="ajaxModal" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Yeni Oluştur</span></a></li>
            <li class="kt-menu__item "><a  href="<?php  echo ADMINBASEURL;?>auctions/main" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Tümünü Listele</span></a></li>
            <li class="kt-menu__item "><a  href="<?php  echo ADMINBASEURL;?>auctions/excel" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Toplu Lot Yükle (Excel)</span></a></li>
        </ul>
    </div>
    </li>
    <li class="kt-menu__item  kt-menu__item--submenu <?php if( $active_menu == 'peyler' ){ ?>kt-menu__item--here kt-menu__item--open<?php } ?>" aria-haspopup="true"  data-ktmenu-submenu-toggle="hover">
    <a href="<?php  echo ADMINBASEURL;?>peyler" class="kt-menu__link kt-menu__toggle">
        <span class="kt-menu__link-icon">
            <i class="fa fa-gavel"></i>
        </span>
        <span class="kt-menu__link-text">Peyler</span>
    </a>
    </li>
    <li class="kt-menu__item  kt-menu__item--submenu <?php if( $active_menu == 'products' ){ ?>kt-menu__item--here kt-menu__item--open<?php } ?>" aria-haspopup="true"  data-ktmenu-submenu-toggle="hover">
    <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
        <span class="kt-menu__link-icon">
            <i class="fa fa-layer-group"></i>
        </span>
        <span class="kt-menu__link-text">Ürünler</span>
        <i class="kt-menu__ver-arrow la la-angle-right"></i>
    </a>
    <div class="kt-menu__submenu ">
        <span class="kt-menu__arrow"></span>
        <ul class="kt-menu__subnav">
            <li class="kt-menu__item "><a  href="<?php  echo ADMINBASEURL;?>products/add" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Yeni Oluştur</span></a></li>
            <li class="kt-menu__item "><a  href="<?php  echo ADMINBASEURL;?>products/main" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Tümünü Listele</span></a></li>
            <li class="kt-menu__item "><a  href="<?php  echo ADMINBASEURL;?>products/stocktypes" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Stok Tipleri</span></a></li>
        </ul>
    </div>
    </li>
    <li class="kt-menu__item  kt-menu__item--submenu <?php if( $active_menu == 'brands' ){ ?>kt-menu__item--here kt-menu__item--open<?php } ?>" aria-haspopup="true"  data-ktmenu-submenu-toggle="hover">
    <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
        <span class="kt-menu__link-icon">
            <i class="fa fa-certificate"></i>
        </span>
        <span class="kt-menu__link-text">Ürün Cinsleri</span>
        <i class="kt-menu__ver-arrow la la-angle-right"></i>
    </a>
    <div class="kt-menu__submenu ">
        <span class="kt-menu__arrow"></span>
        <ul class="kt-menu__subnav">
            <li class="kt-menu__item "><a  href="<?php  echo ADMINBASEURL;?>brands/add" data-toggle="ajaxModal" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Yeni Oluştur</span></a></li>
            <li class="kt-menu__item "><a  href="<?php  echo ADMINBASEURL;?>brands/main" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Tümünü Listele</span></a></li>
        </ul>
    </div>
    </li>
    <li class="kt-menu__item  kt-menu__item--submenu <?php if( $active_menu == 'orders' ){ ?>kt-menu__item--here kt-menu__item--open<?php } ?>" aria-haspopup="true"  data-ktmenu-submenu-toggle="hover">
    <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
        <span class="kt-menu__link-icon">
            <i class="fa fa-shopping-cart"></i>
        </span>
        <span class="kt-menu__link-text">Siparişler </span>
        <span class="kt-menu__link-badge"><span class="kt-badge kt-badge--rounded kt-badge--danger"><?php echo $waitOrder;?></span></span>
        <i class="kt-menu__ver-arrow la la-angle-right"></i>
    </a>
    <div class="kt-menu__submenu ">
        <span class="kt-menu__arrow"></span>
        <ul class="kt-menu__subnav">
            <li class="kt-menu__item "><a  href="<?php  echo ADMINBASEURL;?>orders/waitings" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Bekleyenler</span><span class="kt-menu__link-badge"><span class="kt-badge kt-badge--rounded kt-badge--danger"><?php echo $waitOrder;?></span></span></a></li>
            <li class="kt-menu__item "><a  href="<?php  echo ADMINBASEURL;?>orders/notcomplete" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Tamamlanmamış</span></a></li>
            <li class="kt-menu__item "><a  href="<?php  echo ADMINBASEURL;?>orders/main" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Tümünü Listele</span></a></li>
            <li class="kt-menu__item "><a  href="<?php  echo ADMINBASEURL;?>orders/cargo" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Kargo Takip Bilgileri</span></a></li>
        </ul>
    </div>
    </li>
    <li class="kt-menu__item  kt-menu__item--submenu <?php if( $active_menu == 'customers' ){ ?>kt-menu__item--here kt-menu__item--open<?php } ?>" aria-haspopup="true"  data-ktmenu-submenu-toggle="hover">
    <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
        <span class="kt-menu__link-icon">
            <i class="fa fa-users"></i>
        </span>
        <span class="kt-menu__link-text">Üyeler</span>
        <i class="kt-menu__ver-arrow la la-angle-right"></i>
    </a>
    <div class="kt-menu__submenu ">
        <span class="kt-menu__arrow"></span>
        <ul class="kt-menu__subnav">
            <li class="kt-menu__item "><a  href="<?php  echo ADMINBASEURL;?>customers/add" data-toggle="ajaxModal" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Yeni Oluştur</span></a></li>
            <li class="kt-menu__item "><a  href="<?php  echo ADMINBASEURL;?>customers/main" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Tümünü Listele</span></a></li>
            <li class="kt-menu__item "><a  href="<?php  echo ADMINBASEURL;?>customers/export" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Dışarı Aktar</span></a></li>
        </ul>
    </div>
    </li>
    <li class="kt-menu__item  kt-menu__item--submenu <?php if( $active_menu == 'cargo' ){ ?>kt-menu__item--here kt-menu__item--open<?php } ?>" aria-haspopup="true"  data-ktmenu-submenu-toggle="hover">
    <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
        <span class="kt-menu__link-icon">
            <i class="fa fa-truck"></i>
        </span>
        <span class="kt-menu__link-text">Kargo Yönetimi</span>
        <i class="kt-menu__ver-arrow la la-angle-right"></i>
    </a>
    <div class="kt-menu__submenu ">
        <span class="kt-menu__arrow"></span>
        <ul class="kt-menu__subnav">
            <li class="kt-menu__item "><a  href="<?php  echo ADMINBASEURL;?>cargocompanys/add" data-toggle="ajaxModal" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Yeni Firma</span></a></li>
            <li class="kt-menu__item "><a  href="<?php  echo ADMINBASEURL;?>cargocompanys/main" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Tümünü Listele</span></a></li>
            <li class="kt-menu__item "><a  href="<?php  echo ADMINBASEURL;?>cargocompanys/zones" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Desi/KG Yönetimi</span></a></li>
        </ul>
    </div>
    </li>
    <li class="kt-menu__item  kt-menu__item--submenu <?php if( $active_menu == 'pages' ){ ?>kt-menu__item--here kt-menu__item--open<?php } ?>" aria-haspopup="true"  data-ktmenu-submenu-toggle="hover">
    <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
        <span class="kt-menu__link-icon">
            <i class="fa fa-file-alt"></i>
        </span>
        <span class="kt-menu__link-text">Sayfa Yönetimi</span>
        <i class="kt-menu__ver-arrow la la-angle-right"></i>
    </a>
    <div class="kt-menu__submenu ">
        <span class="kt-menu__arrow"></span>
        <ul class="kt-menu__subnav">
            <?php $counter1=-1; if( isset($YER) && is_array($YER) && sizeof($YER) ) foreach( $YER as $key1 => $value1 ){ $counter1++; ?>

            <li class="kt-menu__item "><a  href="<?php  echo ADMINBASEURL;?>pages/main/<?php echo $key1;?>" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text"><?php echo $value1;?></span></a></li>
            <?php } ?>

            <li class="kt-menu__item "><a  href="<?php  echo ADMINBASEURL;?>pages/banners" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Banner Yönetimi</span></a></li>
        </ul>
    </div>
    </li>
    <li class="kt-menu__item  kt-menu__item--submenu <?php if( $active_menu == 'wallets' ){ ?>kt-menu__item--here kt-menu__item--open<?php } ?>" aria-haspopup="true"  data-ktmenu-submenu-toggle="hover">
    <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
        <span class="kt-menu__link-icon">
            <i class="fas fa-wallet"></i>
        </span>
        <span class="kt-menu__link-text">Kasa Yönetimi</span>
        <i class="kt-menu__ver-arrow la la-angle-right"></i>
    </a>
    <div class="kt-menu__submenu ">
        <span class="kt-menu__arrow"></span>
        <ul class="kt-menu__subnav">
            <li class="kt-menu__item "><a  href="<?php  echo ADMINBASEURL;?>wallets/deposits" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Alacaklar</span></a></li>
            <li class="kt-menu__item "><a  href="<?php  echo ADMINBASEURL;?>wallets/withdrawals" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Borçlar</span></a></li>
            <li class="kt-menu__item "><a  href="<?php  echo ADMINBASEURL;?>wallets/payments" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Ödemeler</span></a></li>
            <li class="kt-menu__item "><a  href="<?php  echo ADMINBASEURL;?>wallets/accounts" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Cari Hareketler</span></a></li>
        </ul>
    </div>
    </li>
    <li class="kt-menu__item  kt-menu__item--submenu <?php if( $active_menu == 'payments' ){ ?>kt-menu__item--here kt-menu__item--open<?php } ?>" aria-haspopup="true"  data-ktmenu-submenu-toggle="hover">
    <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
        <span class="kt-menu__link-icon">
            <i class="fa fa-hand-holding-usd"></i>
        </span>
        <span class="kt-menu__link-text">Ödeme Yönetimi</span>
        <i class="kt-menu__ver-arrow la la-angle-right"></i>
    </a>
    <div class="kt-menu__submenu ">
        <span class="kt-menu__arrow"></span>
        <ul class="kt-menu__subnav">
            <li class="kt-menu__item "><a  href="<?php  echo ADMINBASEURL;?>payments/pos" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">SanalPOS Yönetimi</span></a></li>
            <li class="kt-menu__item "><a  href="<?php  echo ADMINBASEURL;?>payments/banks" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Havale/EFT Yönetimi</span></a></li>
        </ul>
    </div>
    </li>
    <li class="kt-menu__item  kt-menu__item--submenu <?php if( $active_menu == 'reports' ){ ?>kt-menu__item--here kt-menu__item--open<?php } ?>" aria-haspopup="true"  data-ktmenu-submenu-toggle="hover">
    <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
        <span class="kt-menu__link-icon">
            <i class="fa fa-chart-line"></i>
        </span>
        <span class="kt-menu__link-text">Raporlar</span>
        <i class="kt-menu__ver-arrow la la-angle-right"></i>
    </a>
    <div class="kt-menu__submenu ">
        <span class="kt-menu__arrow"></span>
        <ul class="kt-menu__subnav">
            <li class="kt-menu__item "><a  href="<?php  echo ADMINBASEURL;?>reports/peys" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Pey Verenler Listesi</span></a></li>
            <li class="kt-menu__item "><a  href="<?php  echo ADMINBASEURL;?>reports/mostpeys" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">En Çok Pey Veren Üyeler</span></a></li>
            <li class="kt-menu__item "><a  href="<?php  echo ADMINBASEURL;?>reports/buyer" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">En Çok Satın Alan Üyeler</span></a></li>
            <li class="kt-menu__item "><a  href="<?php  echo ADMINBASEURL;?>reports/mostbuyer" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">En Yüksek Ödeme Yapan Üyeler</span></a></li>
            <li class="kt-menu__item "><a  href="<?php  echo ADMINBASEURL;?>reports/seller" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">En Çok Satan Üyeler</span></a></li>
            <li class="kt-menu__item "><a  href="<?php  echo ADMINBASEURL;?>reports/aresults" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Müzayede Sonuç Raporu</span></a></li>
        </ul>
    </div>
    </li>

    <li class="kt-menu__item  kt-menu__item--submenu <?php if( $active_menu == 'languages' ){ ?>kt-menu__item--here kt-menu__item--open<?php } ?>" aria-haspopup="true"  data-ktmenu-submenu-toggle="hover">
    <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
        <span class="kt-menu__link-icon">
            <i class="fa fa-language"></i>
        </span>
        <span class="kt-menu__link-text">Dil Yönetimi</span>
        <i class="kt-menu__ver-arrow la la-angle-right"></i>
    </a>
    <div class="kt-menu__submenu ">
        <span class="kt-menu__arrow"></span>
        <ul class="kt-menu__subnav">
            <li class="kt-menu__item "><a  href="<?php  echo ADMINBASEURL;?>languages/add" data-toggle="ajaxModal"  class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Yeni Oluştur</span></a></li>
            <li class="kt-menu__item "><a  href="<?php  echo ADMINBASEURL;?>languages/main" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Tümünü Listele</span></a></li>
        </ul>
    </div>
    </li>
    <li class="kt-menu__item  kt-menu__item--submenu <?php if( $active_menu == 'settings' ){ ?>kt-menu__item--here kt-menu__item--open<?php } ?>" aria-haspopup="true"  data-ktmenu-submenu-toggle="hover">
    <a href="javascript:;" class="kt-menu__link kt-menu__toggle">
        <span class="kt-menu__link-icon">
            <i class="fa fa-user-cog"></i>
        </span>
        <span class="kt-menu__link-text">Genel Ayarlar</span>
        <i class="kt-menu__ver-arrow la la-angle-right"></i>
    </a>
    <div class="kt-menu__submenu ">
        <span class="kt-menu__arrow"></span>
        <ul class="kt-menu__subnav">
            <li class="kt-menu__item " aria-haspopup="true" ><a  href="<?php  echo ADMINBASEURL;?>settings/main" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Site Ayarları</span></a></li>
            <li class="kt-menu__item " aria-haspopup="true" ><a  href="<?php  echo ADMINBASEURL;?>settings/admins" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Yöneticiler</span></a></li>
            <li class="kt-menu__item " aria-haspopup="true" ><a  href="<?php  echo ADMINBASEURL;?>settings/currencys" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Para Birimleri</span></a></li>
            <li class="kt-menu__item " aria-haspopup="true" ><a  href="<?php  echo ADMINBASEURL;?>settings/popup" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Popup Yönetimi</span></a></li>
            <li class="kt-menu__item " aria-haspopup="true" ><a  href="<?php  echo ADMINBASEURL;?>settings/coupons" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text">Kupon Yönetimi</span></a></li>
        </ul>
    </div>
    </li>
    <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
        <a href="<?php  echo ADMINBASEURL;?>logout" class="kt-menu__link kt-menu__toggle">
            <span class="kt-menu__link-icon">
                <i class="fa fa-sign-out-alt"></i>
            </span>
            <span class="kt-menu__link-text">Güvenli Çıkış</span>
        </a>
    </li>
</ul>