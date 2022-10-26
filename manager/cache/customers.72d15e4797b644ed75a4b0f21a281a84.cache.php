<?php if(!class_exists('view')){exit;}?><div class="kt-subheader  kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title">Üyeler</h3>
        </div>
    </div>
</div>

<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon">
                    <i class="kt-font-brand fa fa-users"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    Üyeleri Listele
                </h3>
            </div>
            <div class="kt-portlet__head-toolbar">
                <div class="kt-portlet__head-wrapper">
                    <a href="<?php  echo ADMINBASEURL;?>customers/add/" data-toggle="ajaxModal"  class="btn btn-success btn-icon-sm">
                        <i class="fa fa-plus"></i>
                        Üye Ekle
                    </a>
                </div>
            </div>
        </div>

        <div class="kt-portlet__body">
            <!--begin: Search Form -->
            <?php echo $info;?>

            <div class="kt-form kt-form--label-right kt-margin-t-20 kt-margin-b-10">

                <div class="row align-items-center">

                    <div class="col-xl-12 order-2 order-xl-12">
                        <form method="get">
                            <div class="row align-items-center">
                                <div class="col-md-3 kt-margin-b-20-tablet-and-mobile">
                                    <div class="form-group row" style="margin-bottom: 0;">
                                        <label class="col-lg-4 col-form-label">Üye Grubu:</label>
                                        <div class="col-md-8 kt-form__control">
                                            <select class="form-control"  name="f[group_id]">
                                                <option value="all">Tümü</option>
                                                <?php $counter1=-1; if( isset($groups) && is_array($groups) && sizeof($groups) ) foreach( $groups as $key1 => $value1 ){ $counter1++; ?>

                                                <option value="<?php echo $value1["id"];?>" <?php if( isset($f["group_id"]) && $f["group_id"] == $value1["id"] && $f["group_id"] != 'all' ){ ?> selected <?php } ?>><?php echo $value1["name"];?></option>
                                                <?php } ?>


                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 kt-margin-b-20-tablet-and-mobile">
                                    <div class="kt-input-icon kt-input-icon--left">
                                        <input type="text" class="form-control" placeholder="Arama Kelimesi..." name="f[name]">
                                        <span class="kt-input-icon__icon kt-input-icon__icon--left">
                                            <span><i class="la la-search"></i></span>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-3 kt-margin-b-20-tablet-and-mobile">
                                    <div class="kt-form__group kt-form__group--inline">
                                        <div class="kt-form__label">
                                            <label>Durumu:</label>
                                        </div>
                                        <div class="kt-form__control">
                                            <select class="form-control"  name="f[status]">
                                                <option value="all">Tümü</option>
                                                <?php $counter1=-1; if( isset($Status) && is_array($Status) && sizeof($Status) ) foreach( $Status as $key1 => $value1 ){ $counter1++; ?>

                                                <option value="<?php echo $key1;?>" <?php if( isset($f["status"]) && $f["status"] == $key1 && $f["status"] != 'all' ){ ?> selected <?php } ?>><?php echo strip_tags( $value1 );?></option>
                                                <?php } ?>


                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 kt-margin-b-20-tablet-and-mobile">
                                    <div class="kt-form__group kt-form__group--inline">
                                        <div class="kt-form__label">

                                        </div>
                                        <div class="kt-form__control">
                                            <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> ARA</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>        <!--end: Search Form -->
        </div>
        <div class="kt-portlet__body">
            <div class="kt-section__content">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Kayıt Tarihi</th>
                                    <th>Kayıt IP</th>
                                    <th>Email</th>
                                    <th>Adı Soyadı</th>
                                    <th>Ülke</th>
                                    <th>Şehir</th>
                                    <th>Üye Grubu</th>
                                    <th>Durumu</th>
                                    <th>Bakiye</th>
                                    <th width="100">İşlemler</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $counter1=-1; if( isset($data) && is_array($data) && sizeof($data) ) foreach( $data as $key1 => $value1 ){ $counter1++; ?>

                                <tr>
                                    <td><?php echo $value1["id"];?></td>
                                    <td><?php echo $value1["create_date"];?></td>
                                    <td><?php echo $value1["create_ip"];?></td>
                                    <td><?php echo $value1["username"];?></td>
                                    <td><?php echo $value1["name"];?> <?php echo $value1["surname"];?></td>
                                    <td><?php echo $value1["country_name"];?></td>
                                    <td><?php echo $value1["city_name"];?></td>
                                    <td><?php echo $value1["group"]["name"];?></td>
                                    <td><?php echo $value1["status_name"];?></td>
                                    <td><?php echo numbers( $value1["balance"]["total"] );?> TL</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="<?php  echo ADMINBASEURL;?>customers/go2user/<?php echo $value1["id"];?>" class="btn btn-warning btn-sm" title="Giriş Yap" target="_blank"><i class="fa fa-share"></i></a>
                                            <a href="<?php  echo ADMINBASEURL;?>customers/edit/<?php echo $value1["id"];?>" class="btn btn-primary btn-sm" data-toggle="ajaxModal" title="Düzenle"><i class="fa fa-edit"></i></a>
                                            <a href="<?php  echo ADMINBASEURL;?>wallets/views/<?php echo $value1["id"];?>" class="btn btn-dark btn-sm" title="Cari Hareketler"><i class="fa fa-money-bill-alt"></i></a>
                                            <a href="<?php  echo ADMINBASEURL;?>customers/remove/<?php echo $value1["id"];?>" class="btn btn-danger btn-sm remove" title="Sil"><i class="fa fa-trash-alt"></i></a>
                                        </div>
                                    </td>
                                </tr>
                                <?php } ?>

                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-12">
                        <?php echo $pagination;?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $('select').select2();
</script>