<?php if(!class_exists('view')){exit;}?><div class="kt-subheader  kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title">Üyeler</h3>
        </div>
    </div>
</div>

<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
    <?php echo $info;?>

    <div class="kt-portlet kt-portlet--mobile">
        <form class="kt-form kt-form--label-right" method="post" id="customerexport">
            <div class="kt-portlet__head kt-portlet__head--lg">
                <div class="kt-portlet__head-label">
                    <span class="kt-portlet__head-icon">
                        <i class="kt-font-brand fa fa-user-cog"></i>
                    </span>
                    <h3 class="kt-portlet__head-title">
                        Üyeleri Dışarı Aktar
                    </h3>
                </div>
                <div class="kt-portlet__head-toolbar">

                </div>
            </div>
            <div class="kt-portlet__body">
                <div class="kt-section kt-section--first">
                    <div class="kt-section__body" style="position: relative; z-index: 10;">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label class="col-lg-2 col-form-label">Kayıt Tarih Aralığı:</label>
                                    <div class="col-lg-4">
                                        <div class="dtrange">
                                            <input type="text" class="form-control" name="p[date]">
                                            <div class="help-block">Bütün üyeler için boş bırakınız</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-2 col-form-label">Üye Grubu:</label>
                                    <div class="col-lg-4">
                                        <select class="form-control ug" name="p[group_id][]" multiple="multiple" data-actions-box="true">
                                            <?php $counter1=-1; if( isset($groups) && is_array($groups) && sizeof($groups) ) foreach( $groups as $key1 => $value1 ){ $counter1++; ?>

                                            <option value="<?php echo $value1["id"];?>"><?php echo $value1["name"];?></option>
                                            <?php } ?>

                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-2 col-form-label">Ülke:</label>
                                    <div class="col-lg-4">
                                        <select class="form-control" id="country_id" name="p[country_id]" onchange="get_citys(this.value, '#city_id','all');">
                                            <option value="0">Tümü</option>
                                            <?php $counter1=-1; if( isset($countrys) && is_array($countrys) && sizeof($countrys) ) foreach( $countrys as $key1 => $value1 ){ $counter1++; ?>

                                            <option value="<?php echo $value1["id"];?>"><?php echo $value1["name"];?></option>
                                            <?php } ?>

                                        </select>
                                        <div class="help-block">Seçmek istemiyorsanız boş bırakınız</div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-2 col-form-label">Şehir:</label>
                                    <div class="col-lg-4">
                                        <select class="form-control ug" name="p[city_id][]" required id="city_id" multiple="multiple" data-actions-box="true"></select>
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
                            <button type="button" class="btn btn-info btn-customer-export"><i class="fa fa-download"></i> DIŞARI AKTAR</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $('.dtrange').daterangepicker({
            buttonClasses: ' btn',
            applyClass: 'btn-primary',
            cancelClass: 'btn-secondary'
            }, function(start, end, label) {
                $('.dtrange .form-control').val( start.format('DD.MM.YYYY') + ' / ' + end.format('DD.MM.YYYY'));
        });
        $('.ug').selectpicker({
            deselectAllText : 'Tümünü Kaldır',
            selectAllText : 'Tümünü Seç',
            noneSelectedText : 'Seçiniz',
        });
    });
</script>
<style type="text/css">
    .bs-select-all,
    .bs-deselect-all
    {
        background-color: #282a3c;
        border-color: #282a3c;
        color: #ffffff;
    }
</style>