<?php if(!class_exists('view')){exit;}?><div class="kt-subheader  kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title">Raporlar</h3>
        </div>
        <div class="kt-subheader__toolbar">

        </div>
    </div>
</div>

<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
    <?php echo $info;?>

    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon">
                    <i class="kt-font-brand fas fa-chart-bar"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    En Çok Pey Veren Üyeler
                </h3>
            </div>
            <div class="kt-portlet__head-toolbar">
                <div class="kt-portlet__head-wrapper">

                </div>
            </div>
        </div>

        <div class="kt-portlet__body">
            <!--begin: Search Form -->

            <div class="kt-form kt-form--label-right kt-margin-t-20 kt-margin-b-10">
                <form method="post" class="kt-form">
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
                                    <div class="kt-form__group ">
                                        <div class="kt-form__label">
                                            <label>Müzayede:</label>
                                        </div>
                                        <div class="kt-form__control">
                                            <select class="form-control"  name="f[auction_id]">
                                                <option value="all">Tümü</option>
                                                <?php $counter1=-1; if( isset($auctions) && is_array($auctions) && sizeof($auctions) ) foreach( $auctions as $key1 => $value1 ){ $counter1++; ?>

                                                <option value="<?php echo $value1["id"];?>" <?php if( $f["auction_id"] == $value1["id"] ){ ?> selected <?php } ?>><?php echo $value1["id"];?> - <?php echo $value1["name"];?> (<?php echo $value1["start_date"];?> - <?php echo $value1["end_date"];?>)</option>
                                                <?php } ?>

                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2 kt-margin-t-20">
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
    </div>

    <?php if( $data["status"] ){ ?>

    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon">
                    <i class="kt-font-brand fas fa-chart-bar"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    Rapor Sonucu
                </h3>
            </div>
        </div>

        <div class="kt-portlet__body">
            <?php if( $data["data"] ){ ?>

            <table class="table">
                <thead>
                    <tr>
                        <th>Pey Adeti</th>
                        <th>Üye ID</th>
                        <th>Üye Kullanıcı Adı</th>
                        <th>Üye Adı Soyadı</th>
                        <th>Telefon</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $counter1=-1; if( isset($data["data"]) && is_array($data["data"]) && sizeof($data["data"]) ) foreach( $data["data"] as $key1 => $value1 ){ $counter1++; ?>

                    <tr>
                        <td><?php echo $value1["total"];?></td>
                        <td><?php echo $value1["user_id"];?></td>
                        <td><?php echo $value1["username"];?></td>
                        <td><?php echo $value1["name"];?> <?php echo $value1["surname"];?></td>
                        <td><?php echo $value1["phone"];?></td>
                    </tr>
                    <?php } ?>

                </tbody>
            </table>
            <div class="kt-portlet__foot">
                <div class="kt-form__actions">
                    <div class="row">
                        <div class="col-lg-3"></div>
                        <div class="col-lg-9 text-right">
                            <a href="<?php  echo ADMINBASEURL;?>reports/mostpeys_print?<?php echo $data["querystring"];?>" target="_blank" class="btn btn-success btn-customer-export"><i class="la la-print"></i> ÇIKTI AL</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php }else{ ?>

                <div class="alert alert-warning">Sonuç bulunamadı !</div>
            <?php } ?>

        </div>
    </div>
    <?php } ?>

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
