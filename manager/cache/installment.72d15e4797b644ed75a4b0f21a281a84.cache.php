<?php if(!class_exists('view')){exit;}?><div class="kt-subheader  kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title">Taksit Seçenekleri</h3>
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
                    <?php echo $data["pos"]["name"];?> | Taksit Seçenekleri
                </h3>
            </div>
            <div class="kt-portlet__head-toolbar">
                <div class="kt-portlet__head-wrapper">
                    <a href="<?php  echo ADMINBASEURL;?>payments/pos" class="btn btn-clean kt-margin-r-10">
                        <i class="la la-arrow-left"></i>
                        <span class="kt-hidden-mobile">SanalPOS Bankaları</span>
                    </a>
                    <a href="<?php  echo ADMINBASEURL;?>payments/installment_add/<?php echo $data["pos"]["id"];?>" data-toggle="ajaxModal"  class="btn btn-success btn-icon-sm">
                        <i class="fa fa-plus"></i>
                        Taksit Ekle
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
                                    <th>Taksit Sayısı</th>
                                    <th>Komisyon Oranı</th>
                                    <th width="100">İşlemler</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $counter1=-1; if( isset($data["record"]) && is_array($data["record"]) && sizeof($data["record"]) ) foreach( $data["record"] as $key1 => $value1 ){ $counter1++; ?>

                                <tr>
                                    <td><?php echo $value1["id"];?></td>
                                    <td><?php echo $data["pos"]["name"];?></td>
                                    <td><?php echo $value1["installment"];?></td>
                                    <td><?php echo $value1["com"];?></td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="<?php  echo ADMINBASEURL;?>payments/installment_edit/<?php echo $value1["id"];?>" class="btn btn-primary btn-sm" data-toggle="ajaxModal" title="Düzenle"><i class="fa fa-edit"></i></a>
                                            <a href="<?php  echo ADMINBASEURL;?>payments/installment_remove/<?php echo $value1["id"];?>" class="btn btn-danger btn-sm remove" title="Sil"><i class="fa fa-trash-alt"></i></a>
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