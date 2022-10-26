<?php if(!class_exists('view')){exit;}?><div class="kt-subheader  kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title">Desi / KG Yönetimi</h3>
        </div>
    </div>
</div>

<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon">
                    <i class="kt-font-brand fa fa-truck"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    Teslimat Bölgeleri
                </h3>
            </div>
            <div class="kt-portlet__head-toolbar">
                <div class="kt-portlet__head-wrapper">
                    <a href="<?php  echo ADMINBASEURL;?>cargocompanys/zone_add/" data-toggle="ajaxModal"  class="btn btn-success btn-icon-sm">
                        <i class="fa fa-plus"></i>
                        Bölge Ekle
                    </a>
                </div>
            </div>
        </div>

        <div class="kt-portlet__body">
            <!--begin: Search Form -->
            <?php echo $info;?>

            <!--end: Search Form -->
        </div>
        <div class="kt-portlet__body">
            <div class="kt-section__content">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Teslimat Bölge Adı</th>
                                    <th width="100">İşlemler</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $counter1=-1; if( isset($data) && is_array($data) && sizeof($data) ) foreach( $data as $key1 => $value1 ){ $counter1++; ?>

                                <tr>
                                    <td><?php echo $value1["id"];?></td>
                                    <td><?php echo $value1["name"];?></td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="<?php  echo ADMINBASEURL;?>cargocompanys/zone_edit/<?php echo $value1["id"];?>" class="btn btn-primary btn-sm" data-toggle="ajaxModal" title="Düzenle"><i class="fa fa-edit"></i></a>
                                            <a href="<?php  echo ADMINBASEURL;?>cargocompanys/zone_citys/<?php echo $value1["id"];?>" class="btn btn-secondary btn-sm" title="Kapsayan Şehirler"><i class="fa fa-sitemap"></i></a>
                                            <a href="<?php  echo ADMINBASEURL;?>cargocompanys/zone_calc/<?php echo $value1["id"];?>" class="btn btn-dark btn-sm" title="Desi/KG Hesaplamaları"><i class="fa fa-calculator"></i></a>
                                            <a href="<?php  echo ADMINBASEURL;?>cargocompanys/zone_remove/<?php echo $value1["id"];?>" class="btn btn-danger btn-sm remove" title="Sil"><i class="fa fa-trash-alt"></i></a>
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