<?php if(!class_exists('view')){exit;}?><div class="kt-subheader  kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <span class="kt-subheader__separator kt-hidden"></span>
            <div class="kt-subheader__breadcrumbs">
                <a href="<?php  echo ADMINBASEURL;?>" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
                <span class="kt-subheader__breadcrumbs-separator"></span>
                <a href="<?php  echo ADMINBASEURL;?>orders" class="kt-subheader__breadcrumbs-link">
                    Siparişler
                </a>
                <span class="kt-subheader__breadcrumbs-separator"></span>
                <span class="kt-subheader__breadcrumbs-link kt-subheader__breadcrumbs-link--active">#<?php echo $data["code"];?> Nolu Sipariş Detayı</span>
            </div>
        </div>
        <div class="kt-subheader__toolbar">

        </div>
    </div>
</div>
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
    <?php echo $info;?>

    <div class="kt-portlet kt-portlet--mobile">
        <form class="kt-form kt-form--label-left" method="post">
            <input type="hidden" name="id" value="<?php echo $data["id"];?>" />
            <div class="kt-portlet__head kt-portlet__head--lg">
                <div class="kt-portlet__head-label">
                    <span class="kt-portlet__head-icon">
                        <i class="kt-font-brand fa fa-edit"></i>
                    </span>
                    <h3 class="kt-portlet__head-title">
                        #<?php echo $data["code"];?> Nolu Sipariş Detayı
                    </h3>
                </div>
                <div class="kt-portlet__head-toolbar">
                    <a href="<?php  echo ADMINBASEURL;?>orders" class="btn btn-clean kt-margin-r-10">
                        <i class="la la-arrow-left"></i>
                        <span class="kt-hidden-mobile">Siparişler</span>
                    </a>
                    <!--<a href="<?php  echo ADMINBASEURL;?>orders/invoice/<?php echo $data["id"];?>" data-text="<?php if( $data["invoice_no"] ){ ?>Daha önce fatura yazılmış. Yeni numara ile yazdırmak istiyor musunuz?<?php }else{ ?>Fatura basılacaktır. Onaylıyor musunuz?<?php } ?>" class="btn btn-dark kt-margin-r-10 btn-fatura">
                    <i class="fas fa-file-invoice"></i>
                    <span class="kt-hidden-mobile">Fatura Yazdır</span>
                    </a>-->
                    <a href="<?php  echo ADMINBASEURL;?>orders/invoice/<?php echo $data["id"];?>" target="_blank" class="btn btn-dark kt-margin-r-10">
                        <i class="fas fa-file-invoice"></i>
                        <span class="kt-hidden-mobile">Fatura Yazdır</span>
                    </a>
                    <?php if( $data["cargo_integration_id"] ){ ?>

                    <?php if( $data["cargo_status"]==0 ){ ?>

                    <a href="<?php  echo ADMINBASEURL;?>orders/sendcargo/<?php echo $data["id"];?>" target="_blank" class="btn btn-brand kt-margin-r-10 btn-sendcargo">
                        <i class="fas fa-paper-plane"></i>
                        <span class="kt-hidden-mobile">Kargoya Gönder</span>
                    </a>
                    <?php }else{ ?>

                    <a href="<?php  echo ADMINBASEURL;?>orders/sendcargo/<?php echo $data["id"];?>" target="_blank" class="btn btn-brand kt-margin-r-10">
                        <i class="fas fa-barcode"></i>
                        <span class="kt-hidden-mobile">Kargo Barkod Yazdır</span>
                    </a>
                    <?php } ?>

                    <?php } ?>

                    <a href="<?php  echo ADMINBASEURL;?>orders/sendsms/<?php echo $data["id"];?>" data-toggle="ajaxModal" class="btn btn-warning kt-margin-r-10">
                        <i class="fas fa-sms"></i>
                        <span class="kt-hidden-mobile">SMS Gönder</span>
                    </a>
                    <div class="btn-group">
                        <button type="submit" class="btn btn-success">
                            <i class="la la-check"></i>
                            <span class="kt-hidden-mobile">KAYDET</span>
                        </button>
                    </div>
                </div>
            </div>

            <div class="kt-portlet__body">
                <div class="kt-section kt-section--first">
                    <div class="kt-section__body" style="position: relative; z-index: 10;">
                        <?php if( $data["cargo_integration_id"] && $data["cargo_status"]>0 ){ ?>

                        <div class="alert alert-secondary alert-outline-brand" role="alert">
                            <div class="alert-icon"><i class="fas fa-bullhorn kt-font-brand"></i></div>
                            <div class="alert-text">
                                Bu sipariş, kargo entegrasyonuna gönderilmiştir. Entegraston durumu : <code><?php echo $data["cargo_status_name"];?></code><?php if( $data["cargo_message"] ){ ?> - Sebep : <code><?php echo $data["cargo_message"];?></code><?php } ?>

                            </div>
                        </div>
                        <?php } ?>

                        <div class="row orderdetail">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label class="col-lg-2 col-form-label">Sipariş Kodu :</label>
                                    <div class="col-lg-3">
                                        <p class="form-control-static kt-mt-10"><?php echo $data["code"];?></p>
                                    </div>
                                    <div class="col-md-1"></div>
                                    <label class="col-lg-2 col-form-label">Sipariş Durumu :</label>
                                    <div class="col-lg-3">
                                        <select class="form-control kt-selectpicker" data-style="btn-brand" name="p[status]" required>
                                            <?php $counter1=-1; if( isset($Status) && is_array($Status) && sizeof($Status) ) foreach( $Status as $key1 => $value1 ){ $counter1++; ?>

                                            <option value="<?php echo $key1;?>" <?php if( $data["status"] == $key1 ){ ?> selected <?php } ?>><?php echo strip_tags( $value1 );?></option>
                                            <?php } ?>

                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-2 col-form-label">Sipariş Tarihi :</label>
                                    <div class="col-lg-3">
                                        <p class="form-control-static kt-mt-10"><?php echo $data["create_date"];?></p>
                                    </div>
                                    <div class="col-md-1"></div>
                                    <label class="col-lg-2 col-form-label">Sipariş IP :</label>
                                    <div class="col-lg-3">
                                        <p class="form-control-static kt-mt-10"><?php echo $data["create_ip"];?></p>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-2 col-form-label">Ödeme Türü :</label>
                                    <div class="col-lg-3">
                                        <p class="form-control-static kt-mt-10"><?php echo $data["payment_type_name"];?></p>
                                    </div>
                                    <div class="col-md-1"></div>
                                    <label class="col-lg-2 col-form-label">Ödeme Bankası :</label>
                                    <div class="col-lg-3">
                                        <p class="form-control-static kt-mt-10"><?php echo $data["payment_bank"]["name"];?></p>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-2 col-form-label">Kargo Firması :</label>
                                    <div class="col-lg-3">
                                        <p class="form-control-static kt-mt-10"><?php echo $data["cargo"]["name"];?></p>
                                    </div>
                                    <div class="col-md-1"></div>
                                    <label class="col-lg-2 col-form-label">Takip No :</label>
                                    <div class="col-lg-3">
                                        <div class="input-group">
                                            <input type="text" value="<?php echo $data["tracking_no"];?>" class="form-control" name="p[tracking_no]"  aria-describedby="basic-addon2"/>
                                            <?php if( $data["tracking_url"] ){ ?><div class="input-group-append"><span class="input-group-text" id="basic-addon2"><a href="<?php echo $data["tracking_url"];?>" target="_blank" class="kt-link"><i class="fas fa-truck"></i> Kargo Nerede?</a></span></div><?php } ?>

                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-2 col-form-label">Fatura No :</label>
                                    <div class="col-lg-3">
                                        <p class="form-control-static kt-mt-10"><input type="text" value="<?php echo $data["invoice_no"];?>" name="p[invoice_no]" class="form-control form-control-sm"  aria-describedby="basic-addon2"/></p>
                                    </div>
                                    <div class="col-md-1"></div>
                                    <label class="col-lg-2 col-form-label">Fatura Tarihi :</label>
                                    <div class="col-lg-3">
                                        <p class="form-control-static kt-mt-10"><?php echo $data["invoice_date"];?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-6">
                                <ul class="nav nav-tabs nav-tabs-line nav-tabs-line-brand" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" data-toggle="tab" role="tab"><i class="fas fa-address-card"></i> Teslimat Bilgileri</a>
                                    </li>
                                </ul>

                                <div class="tab-content">
                                    <div class="tab-pane active" role="tabpanel">
                                        <div class="kt-widget1">
                                            <div class="kt-widget1__item">
                                                <div class="kt-widget1__info">
                                                    <h3 class="kt-widget1__title">Adı Soyadı :</h3>
                                                    <span class="kt-widget1__desc"><?php echo $data["shipping"]["name"];?></span>
                                                </div>
                                            </div>
                                            <div class="kt-widget1__item">
                                                <div class="kt-widget1__info">
                                                    <h3 class="kt-widget1__title">Adres :</h3>
                                                    <span class="kt-widget1__desc"><?php echo $data["shipping"]["address"];?> <?php echo $data["shipping"]["state"];?> / <?php echo $data["shipping"]["city_name"];?> / <?php echo $data["shipping"]["country_name"];?> <?php echo $data["shipping"]["postacode"];?></span>
                                                </div>
                                            </div>
                                            <div class="kt-widget1__item">
                                                <div class="kt-widget1__info">
                                                    <h3 class="kt-widget1__title">Telefon :</h3>
                                                    <span class="kt-widget1__desc"><?php echo $data["shipping"]["phone"];?></span>
                                                </div>
                                            </div>
                                            <div class="kt-widget1__item">
                                                <div class="kt-widget1__info">
                                                    <h3 class="kt-widget1__title"></h3>
                                                    <span class="kt-widget1__desc"><a href="<?php  echo ADMINBASEURL;?>orders/address_edit/<?php echo $data["shipping"]["id"];?>" class="btn btn-sm btn-success" data-toggle="ajaxModal">düzenle</a></span>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <ul class="nav nav-tabs nav-tabs-line nav-tabs-line-brand" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" data-toggle="tab" role="tab"><i class="fas fa-address-card"></i> Fatura Bilgileri</a>
                                    </li>
                                </ul>

                                <div class="tab-content">
                                    <div class="tab-pane active" role="tabpanel">
                                        <div class="kt-widget1">
                                            <div class="kt-widget1__item">
                                                <div class="kt-widget1__info">
                                                    <?php if( $data["billing"]["a_type"] ){ ?>

                                                    <h3 class="kt-widget1__title">Firma :</h3>
                                                    <?php }else{ ?>

                                                    <h3 class="kt-widget1__title">İlgili Kişi :</h3>
                                                    <?php } ?>

                                                    <span class="kt-widget1__desc"><?php echo $data["billing"]["name"];?></span>
                                                </div>
                                            </div>
                                            <div class="kt-widget1__item">
                                                <div class="kt-widget1__info">
                                                    <h3 class="kt-widget1__title">Adres :</h3>
                                                    <span class="kt-widget1__desc"><?php echo $data["billing"]["address"];?> <?php echo $data["billing"]["state"];?> / <?php echo $data["billing"]["city_name"];?> / <?php echo $data["billing"]["country_name"];?> <?php echo $data["billing"]["postacode"];?></span>
                                                </div>
                                            </div>
                                            <div class="kt-widget1__item">
                                                <div class="kt-widget1__info">
                                                    <h3 class="kt-widget1__title">Telefon :</h3>
                                                    <span class="kt-widget1__desc"><?php echo $data["billing"]["phone"];?></span>
                                                </div>
                                            </div>
                                            <?php if( $data["billing"]["a_type"] ){ ?>

                                            <div class="kt-widget1__item">
                                                <div class="kt-widget1__info">
                                                    <h3 class="kt-widget1__title">Vergi Dairesi :</h3>
                                                    <span class="kt-widget1__desc"><?php echo $data["billing"]["tax_name"];?></span>
                                                </div>
                                            </div>
                                            <div class="kt-widget1__item">
                                                <div class="kt-widget1__info">
                                                    <h3 class="kt-widget1__title">Vergi Numarası :</h3>
                                                    <span class="kt-widget1__desc"><?php echo $data["billing"]["tax_no"];?></span>
                                                </div>
                                            </div>
                                            <?php }else{ ?>

                                            <div class="kt-widget1__item">
                                                <div class="kt-widget1__info">
                                                    <h3 class="kt-widget1__title">TC Kimlik :</h3>
                                                    <span class="kt-widget1__desc"><?php echo $data["billing"]["tax_no"];?></span>
                                                </div>
                                            </div>
                                            <?php } ?>

                                            <div class="kt-widget1__item">
                                                <div class="kt-widget1__info">
                                                    <h3 class="kt-widget1__title"></h3>
                                                    <span class="kt-widget1__desc"><a href="<?php  echo ADMINBASEURL;?>orders/address_edit/<?php echo $data["billing"]["id"];?>" class="btn btn-sm btn-success" data-toggle="ajaxModal">düzenle</a></span>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <ul class="nav nav-tabs nav-tabs-line nav-tabs-line-danger" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" data-toggle="tab" role="tab"><i class="fas fa-cubes"></i> Ürün Bilgileri</a>
                                    </li>
                                </ul>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-head-solid" id="varyantTable">
                                        <thead>
                                            <tr>
                                                <th>Satıcı</th>
                                                <th>Resim</th>
                                                <th>Lot No</th>
                                                <th>Ürün Bilgisi</th>
                                                <th class="text-center">Güncel Fiyatı</th>
                                                <th>KDV</th>
                                                <th>Komisyon</th>
                                                <th class="text-center">Toplam Tutar</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $counter1=-1; if( isset($data["products"]) && is_array($data["products"]) && sizeof($data["products"]) ) foreach( $data["products"] as $key1 => $value1 ){ $counter1++; ?>

                                            <tr>
                                                <td><?php echo $value1["seller"]["id"];?> - <?php echo $value1["seller"]["name"];?> <?php echo $value1["seller"]["surname"];?></td>
                                                <td><?php if( $value1["picture"] ){ ?><img src="../data/products/<?php echo $value1["product_id"];?>/<?php echo $value1["picture"];?>" class="img-thumbnail" style="height:60px" /><?php } ?></td>
                                                <td><?php echo $value1["sku"];?></td>
                                                <td><a href="<?php  echo ADMINBASEURL;?>products/edit/<?php echo $value1["product_id"];?>" target="_blank"><?php echo $value1["name"];?></a></td>
                                                <td class="text-right"><?php echo $value1["sale_price"];?> <?php echo $value1["product_currency"];?></td>
                                                <td>%<?php echo $value1["kdv"];?></td>
                                                <td class="text-right"><?php echo $data["currency"]["prefix_symbol"];?><?php echo numbers_dot( $value1["comm"] );?><?php echo $data["currency"]["suffix_symbol"];?></td>
                                                <td class="text-right"><?php echo $data["currency"]["prefix_symbol"];?><?php echo numbers_dot( $value1["total_price"] );?><?php echo $data["currency"]["suffix_symbol"];?></td>
                                            </tr>
                                            <?php } ?>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8">
                                Sipariş Notu :<br>
                                <?php echo $data["note"];?>

                                <?php if( $data["card_holdername"] ){ ?>

                                Kart Üzerindeki İsim : <strong><?php echo $data["card_holdername"];?></strong><br>
                                Kart No : <strong><?php echo $data["card_number"];?></strong><br>
                                <?php if( $data["err_message"] ){ ?>Banka Mesaj : <strong><?php echo $data["err_message"];?></strong><br><?php } ?>

                                <?php } ?>

                            </div>
                            <div class="col-md-4">
                                <table class="table table-bordered">
                                    <tr>
                                        <th>Ara Toplam</th>
                                        <td class="text-right"><?php echo $data["currency"]["prefix_symbol"];?><?php echo numbers_dot( $data["total"] );?><?php echo $data["currency"]["suffix_symbol"];?></td>
                                    </tr>
                                    <?php if( $data["coupon_order_price"] ){ ?>

                                    <tr>
                                        <th>Kupon İndirimi</th>
                                        <td class="text-right">-<?php echo $data["currency"]["prefix_symbol"];?><?php echo numbers_dot( $data["coupon_order_price"] );?><?php echo $data["currency"]["suffix_symbol"];?></td>
                                    </tr>
                                    <?php } ?>

                                    <tr>
                                        <th><?php echo $data["cargo"]["name"];?></th>
                                        <td class="text-right"><?php echo $data["currency"]["prefix_symbol"];?><?php echo numbers_dot( $data["cargo_price"] );?><?php echo $data["currency"]["suffix_symbol"];?></td>
                                    </tr>
                                    <tr>
                                        <th><?php echo $data["payment_type_name"];?> <?php if( $data["payment_type"]==1 ){ ?>(<?php echo $data["installment"];?> Taksit)<?php } ?></th>
                                        <td class="text-right"><?php echo $data["currency"]["prefix_symbol"];?><?php echo numbers_dot( $data["payment_price"] );?><?php echo $data["currency"]["suffix_symbol"];?></td>
                                    </tr>
                                    <tr>
                                        <th>Genel Toplam</th>
                                        <td class="text-right"><?php echo $data["currency"]["prefix_symbol"];?><?php echo numbers_dot( $data["price"] );?><?php echo $data["currency"]["suffix_symbol"];?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="kt-portlet__foot">
                <div class="kt-form__actions">
                    <div class="row">
                        <div class="col-lg-3"></div>
                        <div class="col-lg-6">
                            <a class="btn btn-secondary kt-margin-r-10" href="<?php  echo ADMINBASEURL;?>orders"><i class="fa fa-angle-left"></i> GERİ DÖN</a>
                            <a href="<?php  echo ADMINBASEURL;?>orders/invoice/<?php echo $data["id"];?>" data-text="<?php if( $data["invoice_no"] ){ ?>Daha önce fatura yazılmış. Yeni numara ile yazdırmak istiyor musunuz?<?php }else{ ?>Fatura basılacaktır. Onaylıyor musunuz?<?php } ?>" class="btn btn-dark kt-margin-r-10 btn-fatura">
                            <i class="fas fa-file-invoice"></i>
                            <span class="kt-hidden-mobile">Fatura Yazdır</span>
                            </a>
                            <?php if( $data["cargo_integration_id"] && $data["cargo_status"]==0 ){ ?>

                            <a href="javascript:;" data-id="<?php echo $data["id"];?>" class="btn btn-brand kt-margin-r-10 btn-sendcargo">
                                <i class="fas fa-paper-plane"></i>
                                <span class="kt-hidden-mobile">Kargoya Gönder</span>
                            </a>
                            <?php } ?>

                            <a href="<?php  echo ADMINBASEURL;?>orders/sendsms/<?php echo $data["id"];?>" data-toggle="ajaxModal" class="btn btn-warning kt-margin-r-10">
                                <i class="fas fa-sms"></i>
                                <span class="kt-hidden-mobile">SMS Gönder</span>
                            </a>
                            <button type="submit" class="btn btn-success"><i class="la la-check"></i> KAYDET</button>


                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {

        $('.kt-selectpicker').selectpicker();

        $('.productprice').TouchSpin({
            buttondown_class: 'btn btn-secondary',
            buttonup_class: 'btn btn-secondary',
            min: 0,
            max: 10000000000,
            step: 0.01,
            decimals: 2,
            boostat: 5,
        });

    });

    function deleteFile(id,ths) {

        swal.fire({
            title: "Emin misiniz?",
            text: "Resim silinecektir.",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonClass: 'btn-danger',
            confirmButtonText: "Evet, SİL !",
            cancelButtonText: "İptal Et",
            closeOnConfirm: false
        }).then(function (result) {
            if(result.value) {
                $.post( baseURL + 'products/removeimage', {'id':id}, function() {
                    $(ths).parents('.jFiler-item').remove();
                });
            }
        });


    }
</script>
<style type="text/css">
    .orderdetail .form-group { margin-bottom: 0; }
    .nav-tabs.nav-tabs-line { margin-bottom: 0; }
    .kt-widget1 { padding: 15px;}
    .kt-widget1__item { padding-bottom:0.5rem !important; }
    .kt-widget1 .kt-widget1__item .kt-widget1__info .kt-widget1__title {
        font-size: 1.0rem;
        font-weight: 500;
        color: #595d6e;
        float: left;
        margin-right: 10px;
        width: 110px;
    }
</style>