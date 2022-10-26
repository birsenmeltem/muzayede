<?php if(!class_exists('view')){exit;}?><div class="kt-subheader  kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title">Havale/EFT Yönetimi</h3>
        </div>
    </div>
</div>

<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
    <?php echo $info;?>

    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon">
                    <i class="kt-font-brand fa fa-hand-holding-usd"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    Banka Hesapları
                </h3>
            </div>
            <div class="kt-portlet__head-toolbar">
                <div class="kt-portlet__head-wrapper">
                    <a href="<?php  echo ADMINBASEURL;?>payments/bank_add/" data-toggle="ajaxModal"  class="btn btn-success btn-icon-sm">
                        <i class="fa fa-plus"></i>
                        Hesap Ekle
                    </a>
                </div>
            </div>
        </div>

        <div class="kt-portlet__body">
            <div class="kt-section__content">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Banka Adı</th>
                                    <th>Banka Logo</th>
                                    <th>Hesap Sahibi Adı</th>
                                    <th>Şube Adı</th>
                                    <th>Şube Kodu</th>
                                    <th>Hesap No</th>
                                    <th>IBAN</th>
                                    <th>Durum</th>
                                    <th width="100">İşlemler</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $counter1=-1; if( isset($data) && is_array($data) && sizeof($data) ) foreach( $data as $key1 => $value1 ){ $counter1++; ?>

                                <tr>
                                    <td><?php echo $value1["id"];?></td>
                                    <td><?php echo $value1["bank_name"];?></td>
                                    <td><?php if( $value1["picture"] ){ ?><img src="../data/uploads/<?php echo $value1["picture"];?>" class="img-thumbnail" style="height:60px" /><?php } ?></td>
                                    <td><?php echo $value1["name"];?></td>
                                    <td><?php echo $value1["sube_name"];?></td>
                                    <td><?php echo $value1["sube_code"];?></td>
                                    <td><?php echo $value1["hesap_no"];?></td>
                                    <td><?php echo $value1["iban"];?></td>
                                    <td><?php echo $Status[ $value1["status"]];?></td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="<?php  echo ADMINBASEURL;?>payments/bank_edit/<?php echo $value1["id"];?>" class="btn btn-primary btn-sm" data-toggle="ajaxModal" title="Düzenle"><i class="fa fa-edit"></i></a>
                                            <a href="<?php  echo ADMINBASEURL;?>payments/bank_remove/<?php echo $value1["id"];?>" class="btn btn-danger btn-sm remove" title="Sil"><i class="fa fa-trash-alt"></i></a>
                                        </div>
                                    </td>
                                </tr>
                                <?php } ?>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $('select').select2();
</script>