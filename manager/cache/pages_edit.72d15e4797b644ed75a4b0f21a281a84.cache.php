<?php if(!class_exists('view')){exit;}?><div class="kt-subheader  kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <span class="kt-subheader__separator kt-hidden"></span>
            <div class="kt-subheader__breadcrumbs">
                <a href="<?php  echo ADMINBASEURL;?>" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
                <span class="kt-subheader__breadcrumbs-separator"></span>
                <a href="<?php  echo ADMINBASEURL;?>pages/main/<?php echo $data["yer"];?>" class="kt-subheader__breadcrumbs-link">
                    <?php echo $YER[ $data["yer"]];?>

                </a>
                <span class="kt-subheader__breadcrumbs-separator"></span>
                <span class="kt-subheader__breadcrumbs-link kt-subheader__breadcrumbs-link--active">Sayfa Ekle / Düzenle</span>
            </div>
        </div>
        <div class="kt-subheader__toolbar">

        </div>
    </div>
</div>
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
    <?php echo $info;?>

    <div class="kt-portlet kt-portlet--mobile">
        <form class="kt-form kt-form--label-right" method="post">
            <div class="kt-portlet__head kt-portlet__head--lg">
                <div class="kt-portlet__head-label">
                    <span class="kt-portlet__head-icon">
                        <i class="kt-font-brand fa fa-edit"></i>
                    </span>
                    <h3 class="kt-portlet__head-title">
                        <?php if( $data["id"] ){ ?> <?php echo $data["name"];?> Düzenle <?php }else{ ?> Yeni Ekle <?php } ?>

                    </h3>
                </div>
                <div class="kt-portlet__head-toolbar">
                    <a href="<?php  echo ADMINBASEURL;?>pages/main/<?php echo $data["yer"];?>" class="btn btn-clean kt-margin-r-10">
                        <i class="la la-arrow-left"></i>
                        <span class="kt-hidden-mobile">Geri Dön</span>
                    </a>
                    <div class="btn-group">
                        <button type="submit" class="btn btn-success">
                            <i class="fa fa-save"></i>
                            <span class="kt-hidden-mobile">KAYDET</span>
                        </button>
                    </div>
                </div>
            </div>
            <input type="hidden" name="p[yer]"  value="<?php echo $data["yer"];?>" />
            <?php if( $data["id"] ){ ?><input type="hidden" name="id" value="<?php echo $data["id"];?>" /><?php } ?>

            <div class="kt-portlet__body">
                <div class="kt-section kt-section--first">
                    <div class="kt-section__body" style="position: relative; z-index: 10;">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label class="col-lg-2 col-form-label">Sayfa Adı: </label>
                                    <div class="col-lg-9">
                                        <input type="text" class="form-control" value="<?php echo $data["name"];?>" name="p[name]" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-2 col-form-label">Kısa Açıklama:</label>
                                    <div class="col-lg-9">
                                        <textarea cols="" rows="" class="form-control summernote" name="p[shortdetail]" id="shortdetail"><?php echo $data["shortdetail"];?></textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-2 col-form-label">Uzun Açıklama:</label>
                                    <div class="col-lg-9">
                                        <textarea cols="" rows="" class="form-control summernote" name="p[detail]" id="detail"><?php echo $data["detail"];?></textarea>
                                    </div>
                                </div>
                                <?php if( $data["picture"] ){ ?>

                                <div class="form-group row">
                                    <label class="col-lg-2 col-form-label">Kapak Resim: </label>
                                    <div class="col-md-4">
                                        <img src="../data/uploads/<?php echo $data["picture"];?>" height="80" class="img-thumbnail" /><br>
                                    </div>

                                </div>
                                <?php } ?>

                                <div class="form-group row">
                                    <label class="col-lg-2 col-form-label">Kapak Resim: </label>
                                    <div class="col-lg-4">
                                        <input type="file" class="form-control" name="img">
                                    </div>

                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-2 col-form-label">Yayın Durumu:</label>
                                    <div class="col-lg-2">
                                        <span class="kt-switch kt-switch--outline kt-switch--icon kt-switch--success">
                                            <label>
                                                <input type="checkbox" <?php if( $data["status"] ){ ?>checked="checked"<?php } ?> name="p[status]" value="1">
                                                <span></span>
                                            </label>
                                        </span>
                                    </div>
                                    <label class="col-lg-2 col-form-label">Sıra:</label>
                                    <div class="col-lg-5">
                                        <input type="text" class="form-control" value="<?php echo $data["rows"];?>" name="p[rows]" placeholder="999">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-2 col-form-label">SEO Title: </label>
                                    <div class="col-lg-9">
                                        <input type="text" class="form-control" value="<?php echo $data["seo_title"];?>" name="p[seo_title]">
                                        <div class="help-block">Boş bırakırsanız sayfa adını alır.</div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-2 col-form-label">SEO Keywords:</label>
                                    <div class="col-lg-9">
                                        <input type="text" class="form-control" value="<?php echo $data["seo_keywords"];?>" name="p[seo_keywords]">
                                        <div class="help-block">kelimeleri virgül ile ayırınız.</div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-2 col-form-label">SEO Description:</label>
                                    <div class="col-lg-9">
                                        <textarea cols="" rows="" class="form-control" name="p[seo_desc]" id="seo_desc"><?php echo $data["seo_desc"];?></textarea>
                                        <div class="help-block">Maksimum 255 karakter giriniz.</div>
                                    </div>
                                </div>
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
                            <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> KAYDET</button>
                            <a class="btn btn-secondary" href="<?php  echo ADMINBASEURL;?>pages/main/<?php echo $data["yer"];?>"><i class="fa fa-angle-left"></i> GERİ DÖN</a>

                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {


    });

</script>
