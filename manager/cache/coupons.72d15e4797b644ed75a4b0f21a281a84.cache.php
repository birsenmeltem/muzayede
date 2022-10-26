<?php if(!class_exists('view')){exit;}?><div class="kt-subheader  kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title">Kupon Yönetimi</h3>
        </div>
    </div>
</div>

<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
     <?php echo $info;?>

    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon">
                    <i class="kt-font-brand fa fa-user-cog"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    İndirim Kuponlarını Listele
                </h3>
            </div>
            <div class="kt-portlet__head-toolbar">
                <div class="kt-portlet__head-wrapper">
                    <a href="<?php  echo ADMINBASEURL;?>settings/coupon_add/" data-toggle="ajaxModal"  class="btn btn-success btn-icon-sm">
                        <i class="fa fa-plus"></i>
                        Kupon Oluştur
                    </a>
                </div>
            </div>
        </div>

        <div class="kt-portlet__body">
            <!--begin: Search Form -->
            <div class="kt-form kt-form--label-right kt-margin-t-20 kt-margin-b-10">

                <div class="row align-items-center">

                    <div class="col-xl-12 order-2 order-xl-1">
                        <form method="get">
                            <div class="row align-items-center">
                                <div class="col-md-3 kt-margin-b-20-tablet-and-mobile">
                                    <div class="kt-input-icon kt-input-icon--left">
                                        <input type="text" class="form-control" placeholder="Kupon Kodu" name="f[code]">
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
                                            <select class="form-control"  name="f[used]">
                                                <option value="all">Tümü</option>
                                                <option value="0" <?php if( isset($f["used"]) && $f["used"] == 0 && $f["used"] != 'all' ){ ?> selected <?php } ?>>Kullanılmadı</option>
                                                <option value="1" <?php if( isset($f["used"]) && $f["used"] == 1 && $f["used"] != 'all' ){ ?> selected <?php } ?>>Kullanıldı</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 kt-margin-b-20-tablet-and-mobile">
                                    <div class="kt-form__group kt-form__group--inline">
                                        <div class="kt-form__label">
                                            <label>Çoklu Kullanım:</label>
                                        </div>
                                        <div class="kt-form__control">
                                            <select class="form-control"  name="f[multi]">
                                                <option value="all">Tümü</option>
                                                <option value="0" <?php if( isset($f["multi"]) && $f["multi"] == 0 && $f["multi"] != 'all' ){ ?> selected <?php } ?>>Tek Kullanım</option>
                                                <option value="1" <?php if( isset($f["multi"]) && $f["multi"] == 1 && $f["multi"] != 'all' ){ ?> selected <?php } ?>>Çoklu Kullanım</option>
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
                                    <th>Kupon Kodu</th>
                                    <th>Kullanan Üye</th>
                                    <th>İndirim Türü</th>
                                    <th>İndirim Miktarı</th>
                                    <th>Başlangıç Tarihi</th>
                                    <th>Bitiş Tarihi</th>
                                    <th>Sepet Limiti</th>
                                    <th>Kupon Türü</th>
                                    <th>Durumu</th>
                                    <th width="100">İşlemler</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $counter1=-1; if( isset($data) && is_array($data) && sizeof($data) ) foreach( $data as $key1 => $value1 ){ $counter1++; ?>

                                <tr>
                                    <td><?php echo $value1["id"];?></td>
                                    <td><?php echo $value1["code"];?></td>
                                    <td><?php if( $value1["user_id"] ){ ?><?php echo $value1["user"]["name"];?> (<?php echo $value1["user"]["username"];?>)<?php }else{ ?>-<?php } ?></td>
                                    <td><?php if( $value1["d_type"] ){ ?>Net<?php }else{ ?>Yüzde<?php } ?></td>
                                    <td><?php echo $value1["discount"];?></td>
                                    <td><?php echo $value1["start_date"];?></td>
                                    <td><?php if( $value1["end_time"] ){ ?><?php echo $value1["end_date"];?><?php }else{ ?>SÜRESİZ<?php } ?></td>
                                    <td><?php echo $value1["cart_limit"];?></td>
                                    <td><?php echo $value1["multi_name"];?></td>
                                    <td><?php echo $value1["used_name"];?></td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="<?php  echo ADMINBASEURL;?>settings/coupon_remove/<?php echo $value1["id"];?>" class="btn btn-danger btn-sm remove" title="Sil"><i class="fa fa-trash-alt"></i></a>
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