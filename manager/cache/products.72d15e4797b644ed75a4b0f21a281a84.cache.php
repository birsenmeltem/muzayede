<?php if(!class_exists('view')){exit;}?><div class="kt-subheader  kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title">Ürünler</h3>
        </div>
    </div>
</div>

<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
    <?php echo $info;?>

    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon">
                    <i class="kt-font-brand fa fa-layer-group"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    Ürünleri Listele
                </h3>
            </div>
            <div class="kt-portlet__head-toolbar">
                <div class="kt-portlet__head-wrapper">
                    <a href="<?php  echo ADMINBASEURL;?>products/add/" class="btn btn-success btn-icon-sm">
                        <i class="fa fa-plus"></i>
                        Ürün Ekle
                    </a>
                </div>
            </div>
        </div>

        <div class="kt-portlet__body">
            <!--begin: Search Form -->
            <div class="kt-form kt-form--label-right kt-margin-t-20 kt-margin-b-10">
                <form method="get" class="kt-form">
                    <div class="row align-items-center">
                        <div class="col-xl-12 order-2 order-xl-12">
                            <div class="row align-items-center">
                                <div class="col-md-3 kt-margin-b-20-tablet-and-mobile">
                                    <div class="kt-form__group">
                                        <div class="kt-form__label">
                                            <label>Kategori:</label>
                                        </div>
                                        <div class="kt-form__control">
                                            <select class="form-control"  name="f[category_id]">
                                                <option value="all">Tümü</option>
                                                <?php echo $Cats;?>

                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 kt-margin-b-20-tablet-and-mobile">
                                    <div class="kt-form__group">
                                        <div class="kt-form__label">
                                            <label>Ürün Cinsi:</label>
                                        </div>
                                        <div class="kt-form__control">
                                            <select class="form-control"  name="f[brand_id]">
                                                <option value="all">Tümü</option>
                                                <?php $counter1=-1; if( isset($Brands) && is_array($Brands) && sizeof($Brands) ) foreach( $Brands as $key1 => $value1 ){ $counter1++; ?>

                                                <option value="<?php echo $value1["id"];?>" <?php if( $f["brand_id"] == $value1["id"] ){ ?> selected <?php } ?>><?php echo $value1["name"];?></option>
                                                <?php } ?>


                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 kt-margin-b-20-tablet-and-mobile">
                                    <div class="kt-form__label">
                                        <label>Arama Kelimesi:</label>
                                    </div>
                                    <div class="kt-form__control">
                                        <div class="kt-input-icon kt-input-icon--left">
                                            <input type="text" class="form-control" name="f[name]">
                                            <span class="kt-input-icon__icon kt-input-icon__icon--left">
                                                <span><i class="la la-search"></i></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2 kt-margin-b-20-tablet-and-mobile">
                                    <div class="kt-form__group">
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
                                <div class="col-md-12 kt-margin-t-20 text-right">
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
                                        <th width="1%"><label class="kt-checkbox kt-checkbox--bold kt-checkbox--brand"><input type="checkbox" class="secAll" /><span></span></label></th>
                                        <th>Lot No</th>
                                        <th>Kategori</th>
                                        <th>Ürün Adı</th>
                                        <th>Güncel Fiyatı</th>
                                        <th>Açılış Fiyatı</th>
                                        <th>Resim</th>
                                        <th>Durum</th>
                                        <th width="100">İşlemler</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $counter1=-1; if( isset($data) && is_array($data) && sizeof($data) ) foreach( $data as $key1 => $value1 ){ $counter1++; ?>

                                    <tr>
                                        <td><label class="kt-checkbox kt-checkbox--bold kt-checkbox--brand"><input type="checkbox" name="pid[]" value="<?php echo $value1["id"];?>" class="sec" /><span></span></label></td>
                                        <td><?php echo $value1["sku"];?></td>
                                        <td><?php echo $value1["cat"]["names"];?></td>
                                        <td><?php echo $value1["name"];?></td>
                                        <td><?php echo $value1["currency"]["prefix_symbol"];?><?php echo $value1["price"];?><?php echo $value1["currency"]["suffix_symbol"];?></td>
                                        <td><?php echo $value1["currency"]["prefix_symbol"];?><?php echo $value1["old_price"];?><?php echo $value1["currency"]["suffix_symbol"];?></td>
                                        <td><?php if( $value1["picture"] ){ ?><img src="../data/products/<?php echo $value1["id"];?>/<?php echo $value1["picture"];?>" class="img-thumbnail" style="max-width: 60px; max-height: 60px;" /><?php } ?></td>
                                        <td><?php echo $value1["status_name"];?></td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="<?php  echo ADMINBASEURL;?>products/edit/<?php echo $value1["id"];?>" class="btn btn-primary btn-sm" title="Düzenle"><i class="fa fa-edit"></i></a>
                                                <a href="<?php  echo ADMINBASEURL;?>products/remove/<?php echo $value1["id"];?>" class="btn btn-danger btn-sm remove" title="Sil"><i class="fa fa-trash-alt"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php } ?>

                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-2">
                            <button name="removeselected" class="btn btn-dark btn-sm"><i class="fa fa-trash"></i> seçilenleri sil</button>
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
</script>