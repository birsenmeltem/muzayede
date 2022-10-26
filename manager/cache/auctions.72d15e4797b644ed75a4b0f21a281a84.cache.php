<?php if(!class_exists('view')){exit;}?><div class="kt-subheader  kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title">Müzayedeler</h3>
        </div>
    </div>
</div>

<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
     <?php echo $info;?>

    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon">
                    <i class="kt-font-brand fa fa-certificate"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    Müzayedeleri Listele
                </h3>
            </div>
            <div class="kt-portlet__head-toolbar">
                <div class="kt-portlet__head-wrapper">
                    <a href="<?php  echo ADMINBASEURL;?>auctions/add/" data-toggle="ajaxModal"  class="btn btn-success btn-icon-sm">
                        <i class="fa fa-plus"></i>
                        Müzayede Ekle
                    </a>
                </div>
            </div>
        </div>

        <div class="kt-portlet__body">
            <!--begin: Search Form -->
            <div class="kt-form kt-form--label-right kt-margin-t-20 kt-margin-b-10">

                <div class="row align-items-center">

                    <div class="col-xl-8 order-2 order-xl-1">
                        <form method="get">
                            <div class="row align-items-center">
                                <div class="col-md-4 kt-margin-b-20-tablet-and-mobile">
                                    <div class="kt-input-icon kt-input-icon--left">
                                        <input type="text" class="form-control" placeholder="Arama Kelimesi..." name="f[name]">
                                        <span class="kt-input-icon__icon kt-input-icon__icon--left">
                                            <span><i class="la la-search"></i></span>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-4 kt-margin-b-20-tablet-and-mobile">
                                    <div class="kt-form__group kt-form__group--inline">
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
                                <div class="col-md-4 kt-margin-b-20-tablet-and-mobile">
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
                                    <th>Müzayede Adı</th>
                                    <th>Resim</th>
                                    <th>Bağlı Olduğu Kategori</th>
                                    <th>Başlangıç Tarihi</th>
                                    <th>Bitiş Tarihi</th>
                                    <th>Lotlar Arası Saniye(Canlı)</th>
                                    <th>Durumu</th>
                                    <th width="100">İşlemler</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $counter1=-1; if( isset($data) && is_array($data) && sizeof($data) ) foreach( $data as $key1 => $value1 ){ $counter1++; ?>

                                <tr>
                                    <td><?php echo $value1["id"];?></td>
                                    <td><?php echo $value1["name"];?></td>
                                    <td><?php if( $value1["picture"] ){ ?><img src="../data/uploads/<?php echo $value1["picture"];?>" class="img-thumbnail" style="height:60px" /><?php } ?></td>
                                    <td><?php echo $value1["cat"]["name"];?></td>
                                    <td><?php echo $value1["start_date"];?></td>
                                    <td><?php echo $value1["end_date"];?></td>
                                    <td><?php echo $value1["remaining"];?></td>
                                    <td><?php echo $value1["status_name"];?></td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="<?php  echo ADMINBASEURL;?>auctions/edit/<?php echo $value1["id"];?>" class="btn btn-primary btn-sm" data-toggle="ajaxModal" title="Düzenle"><i class="fa fa-edit"></i></a>
                                            <a href="<?php  echo ADMINBASEURL;?>auctions/remove/<?php echo $value1["id"];?>" class="btn btn-danger btn-sm remove" title="Sil"><i class="fa fa-trash-alt"></i></a>
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