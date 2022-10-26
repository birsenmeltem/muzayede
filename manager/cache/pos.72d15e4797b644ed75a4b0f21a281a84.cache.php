<?php if(!class_exists('view')){exit;}?><div class="kt-subheader  kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title">SanalPOS Yönetimi</h3>
        </div>
    </div>
</div>

<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon">
                    <i class="kt-font-brand fa fa-hand-holding-usd"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    SanalPOS Bankaları
                </h3>
            </div>
            <div class="kt-portlet__head-toolbar">

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
                                    <th>Banka Adı</th>
                                    <th>Müşteri ID</th>
                                    <th>Merchant Key</th>
                                    <th>Merchant Secret</th>
                                    <th>Durum</th>
                                    <th width="5%">İşlem</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $counter1=-1; if( isset($data) && is_array($data) && sizeof($data) ) foreach( $data as $key1 => $value1 ){ $counter1++; ?>

                                <tr>
                                    <td><?php echo $value1["id"];?></td>
                                    <td><?php echo $value1["name"];?></td>
                                    <td><?php echo $value1["merchant_id"];?></td>
                                    <td><?php echo $value1["merchant_key"];?></td>
                                    <td><?php echo $value1["merchant_secret"];?></td>
                                    <td><?php echo $Status[ $value1["status"]];?></td>
                                    <td><a class="btn btn-sm btn-info" href="<?php  echo ADMINBASEURL;?>payments/installment/<?php echo $value1["id"];?>" title="Taksit Seçenekleri"><i class="far fa-credit-card"></i></a></td>
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