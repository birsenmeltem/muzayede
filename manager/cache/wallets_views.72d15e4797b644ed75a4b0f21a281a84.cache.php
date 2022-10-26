<?php if(!class_exists('view')){exit;}?><div class="kt-subheader  kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title">#<?php echo $data["id"];?> - <?php echo $data["name"];?> <?php echo $data["surname"];?> Cari Hareketler</h3>
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
                    Cari  Hareketler
                </h3>
            </div>
            <div class="kt-portlet__head-toolbar">
                <div class="kt-portlet__head-wrapper">
                    <a href="<?php  echo ADMINBASEURL;?>customers" class="btn btn-clean kt-margin-r-10">
                        <i class="la la-arrow-left"></i>
                        <span class="kt-hidden-mobile">Müşteriler</span>
                    </a>
                    <a href="<?php  echo ADMINBASEURL;?>wallets/add/<?php echo $data["id"];?>" data-toggle="ajaxModal" class="btn btn-success btn-icon-sm">
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

                                <div class="col-md-3 kt-margin-b-20-tablet-and-mobile">
                                    <div class="kt-form__label">
                                        <label>İşlem Türü:</label>
                                    </div>
                                    <div class="kt-form__control">
                                        <select name="f[ty]" class="form-controls">
                                            <option value="">-Tümü-</option>
                                            <?php $counter1=-1; if( isset($Type) && is_array($Type) && sizeof($Type) ) foreach( $Type as $key1 => $value1 ){ $counter1++; ?>
                                            <option value="<?php echo $key1;?>"><?php echo strip_tags( $value1 );?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3 kt-margin-b-20-tablet-and-mobile">
                                    <div class="kt-form__label">
                                        <label>Durum:</label>
                                    </div>
                                    <div class="kt-form__control">
                                        <select name="f[status]" class="form-controls">
                                            <option value="">-Tümü-</option>
                                            <?php $counter1=-1; if( isset($PaymentPriceType) && is_array($PaymentPriceType) && sizeof($PaymentPriceType) ) foreach( $PaymentPriceType as $key1 => $value1 ){ $counter1++; ?>
                                            <option value="<?php echo $key1;?>"><?php echo strip_tags( $value1 );?></option>
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
                                            <a class="btn btn-secondary" href="<?php  echo ADMINBASEURL;?>wallets/views/<?php echo $data["id"];?>"><i class="fa fa-trash"></i> TEMİZLE</a>
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
                                        <th>Müzayede</th>
                                        <th>İşlem Zamanı</th>
                                        <th>İşlem Türü</th>
                                        <th>Lot</th>
                                        <th>Açıklama</th>
                                        <th>Tutar</th>
                                        <th>Not</th>
                                        <th>Durum</th>
                                        <th>İşlem Yapan</th>
                                        <th width="100"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $counter1=-1; if( isset($data["tactions"]) && is_array($data["tactions"]) && sizeof($data["tactions"]) ) foreach( $data["tactions"] as $key1 => $value1 ){ $counter1++; ?>
                                    <tr>
                                        <td><?php echo $value1["pro"]["auction_id"];?>-<?php echo $value1["pro"]["auction_name"];?></td>
                                        <td><?php echo $value1["create_date"];?></td>
                                        <td><?php echo $value1["type"];?></td>
                                        <td><?php echo $value1["pro"]["sku"];?></td>
                                        <td><?php echo $value1["pro"]["name"];?></td>
                                        <td><?php echo $value1["price"];?> TL</td>
                                        <td><?php echo $value1["note"];?></td>
                                        <td>
                                            <?php $st=$this->var['st']=$value1["status"];?>
                                            <select class="form-control" onchange="changeWalletStatus(this.value,'<?php echo $value1["id"];?>');">
                                                <option value="">-</option>
                                                <?php $counter2=-1; if( isset($PaymentPriceType) && is_array($PaymentPriceType) && sizeof($PaymentPriceType) ) foreach( $PaymentPriceType as $key2 => $value2 ){ $counter2++; ?>
                                                <option value="<?php echo $key2;?>" <?php if( $st == $key2 ){ ?> selected <?php } ?>><?php echo strip_tags( $value2 );?></option>
                                                <?php } ?>
                                            </select>
                                        </td>
                                        <td><?php echo $value1["create"]["name"];?></td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="<?php  echo ADMINBASEURL;?>wallets/payment_edit/<?php echo $value1["id"];?>" data-toggle="ajaxModal" class="btn btn-primary btn-sm" title="Düzenle"><i class="fa fa-edit"></i></a>
                                                <a href="<?php  echo ADMINBASEURL;?>wallets/remove/<?php echo $value1["id"];?>" class="btn btn-danger btn-sm remove" title="Sil"><i class="fa fa-trash-alt"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                    <?php if( !isset($f) ){ ?>
                                    <tr style="background: red; color:#fff; font-weight: bold;">
                                        <td colspan="4"></td>
                                        <td>Güncel Bakiye</td>
                                        <td><?php echo numbers_dot( $data["balance"] );?> TL</td>
                                        <td></td>
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
    $('select.form-controls').select2();
    $('.dtrange').daterangepicker({
        buttonClasses: ' btn',
        applyClass: 'btn-primary',
        cancelClass: 'btn-secondary'
        }, function(start, end, label) {
            $('.dtrange .form-control').val( start.format('DD.MM.YYYY') + ' / ' + end.format('DD.MM.YYYY'));
    });

    function changeWalletStatus(val,id) {
        $.post(baseURL +'wallets/savestatus', {'id':id, 'val':val});
    }
</script>