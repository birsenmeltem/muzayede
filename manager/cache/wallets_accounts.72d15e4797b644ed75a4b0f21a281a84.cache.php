<?php if(!class_exists('view')){exit;}?><div class="kt-subheader  kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title">Cari Hareketler</h3>
        </div>
    </div>
</div>

<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">

    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon">
                    <i class="kt-font-brand fa fa-layer-group"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    Hareketleri Listele
                </h3>
            </div>
            <div class="kt-portlet__head-toolbar">
                <div class="kt-portlet__head-wrapper">
                    <a href="<?php  echo ADMINBASEURL;?>wallets/add" data-toggle="ajaxModal" class="btn btn-success btn-icon-sm">
                        <i class="fa fa-plus"></i>
                        Ödeme Ekle
                    </a>
                </div>
            </div>
        </div>

        <div class="kt-portlet__body">
            <!--begin: Search Form -->
            <?php echo $info;?>

            <div class="kt-form kt-form--label-right kt-margin-t-20 kt-margin-b-10">
                <form method="get" class="kt-form">
                    <div class="row align-items-center">
                        <div class="col-xl-12 order-2 order-xl-12">
                            <div class="row align-items-center">
                                <div class="col-md-3 kt-margin-b-20-tablet-and-mobile">
                                    <div class="kt-form__group">
                                        <div class="kt-form__label">
                                            <label>Tarih Aralığı:</label>
                                        </div>
                                        <div class="kt-form__control dtrange">
                                            <input type="text" class="form-control" name="f[date]" value="<?php echo $f["date"];?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4 kt-margin-b-20-tablet-and-mobile">
                                    <div class="kt-form__label">
                                        <label>Üye Arama:</label>
                                    </div>
                                    <div class="kt-form__control">
                                        <select name="f[user_id]">
                                            <option value="">-Tümü-</option>
                                            <?php $counter1=-1; if( isset($userList) && is_array($userList) && sizeof($userList) ) foreach( $userList as $key1 => $value1 ){ $counter1++; ?>

                                            <option value="<?php echo $value1["id"];?>"><?php echo $value1["id"];?> - <?php echo $value1["name"];?> <?php echo $value1["surname"];?> (<?php echo $value1["username"];?>)</option>
                                            <?php } ?>

                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3 kt-margin-t-20 text-right">
                                    <div class="kt-form__group kt-form__group--inline">
                                        <div class="kt-form__label">

                                        </div>
                                        <div class="kt-form__control">
                                            <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> ARA</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>        <!--end: Search Form -->
        </div>
        <div class="kt-portlet__body">
            <div class="kt-section__content">
                <form method="post">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Üyelik Tarihi</th>
                                        <th>Kullanıcı Adı</th>
                                        <th>Üye Adı</th>
                                        <th>Üye Soyadı</th>
                                        <th>Telefon</th>
                                        <th>Bakiye</th>
                                        <th width="100">İşlemler</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $counter1=-1; if( isset($data) && is_array($data) && sizeof($data) ) foreach( $data as $key1 => $value1 ){ $counter1++; ?>

                                    <tr>
                                        <td><?php echo $value1["id"];?></td>
                                        <td><?php echo $value1["create_date"];?></td>
                                        <td><?php echo $value1["username"];?></td>
                                        <td><?php echo $value1["name"];?></td>
                                        <td><?php echo $value1["surname"];?></td>
                                        <td><?php echo $value1["phone"];?></td>
                                        <td><?php echo numbers_dot( $value1["balance"]["total"] );?> TL</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="<?php  echo ADMINBASEURL;?>wallets/views/<?php echo $value1["id"];?>"class="btn btn-primary btn-sm" title="Cari Hareketler"><i class="fa fa-eye"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php } ?>

                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-2">
                        </div>
                        <div class="col-md-10">
                            <?php echo $pagination;?>

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $('select').select2();
    $('.dtrange').daterangepicker({
        buttonClasses: ' btn',
        applyClass: 'btn-primary',
        cancelClass: 'btn-secondary'
        }, function(start, end, label) {
            $('.dtrange .form-control').val( start.format('DD.MM.YYYY') + ' / ' + end.format('DD.MM.YYYY'));
    });

</script>