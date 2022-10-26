<?php if(!class_exists('view')){exit;}?><div class="kt-subheader  kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title">Genel Ayarlar</h3>
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
                        <i class="kt-font-brand fa fa-user-cog"></i>
                    </span>
                    <h3 class="kt-portlet__head-title">
                        Popup Yönetimi
                    </h3>
                </div>
                <div class="kt-portlet__head-toolbar">
                    <div class="btn-group">
                        <button type="submit" class="btn btn-success">
                            <i class="fa fa-save"></i>
                            <span class="kt-hidden-mobile">KAYDET</span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="kt-portlet__body">
                <div class="kt-section kt-section--first">
                    <div class="kt-section__body" style="position: relative; z-index: 10;">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label class="col-lg-2 col-form-label">Durumu:</label>
                                    <div class="col-lg-2">
                                        <span class="kt-switch kt-switch--outline kt-switch--icon kt-switch--success">
                                            <label>
                                                <input type="checkbox" <?php if( $data["status"] ){ ?>checked="checked"<?php } ?> name="p[status]" value="1">
                                                <span></span>
                                            </label>
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-2 col-form-label">İçerik:</label>
                                    <div class="col-lg-9">
                                        <textarea cols="" rows="" class="form-control summernote" name="p[detail]" id="detail"><?php echo $data["detail"];?></textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-2 col-form-label">Kapanma Süresi(Saniye): </label>
                                    <div class="col-lg-9">
                                        <input type="text" class="form-control" value="<?php echo $data["times"];?>" name="p[times]">
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

                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
    $('select').select2();
</script>