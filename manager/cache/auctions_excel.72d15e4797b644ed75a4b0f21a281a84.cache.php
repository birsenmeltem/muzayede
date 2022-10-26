<?php if(!class_exists('view')){exit;}?><div class="kt-subheader  kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title">Müzayede Excel Lot Yükleme</h3>
        </div>
    </div>
</div>

<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
    <?php echo $info;?>

    <div class="kt-portlet kt-portlet--mobile">
        <?php if( !$auction_id ){ ?>

        <form class="kt-form kt-form--label-right" method="post" id="customerexport" enctype="multipart/form-data">
            <div class="kt-portlet__head kt-portlet__head--lg">
                <div class="kt-portlet__head-label">
                    <span class="kt-portlet__head-icon">
                        <i class="kt-font-brand fa fa-user-cog"></i>
                    </span>
                    <h3 class="kt-portlet__head-title">
                        Excel Lot Yükleme
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
                                    <label class="col-lg-2 col-form-label">Müzayede:</label>
                                    <div class="col-lg-4">
                                        <select class="form-control" name="auction_id">
                                            <?php $counter1=-1; if( isset($auctions) && is_array($auctions) && sizeof($auctions) ) foreach( $auctions as $key1 => $value1 ){ $counter1++; ?>

                                            <option value="<?php echo $value1["id"];?>"><?php echo $value1["id"];?> - <?php echo $value1["name"];?> (<?php echo $value1["start_date"];?> - <?php echo $value1["end_date"];?>)</option>
                                            <?php } ?>

                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-2 col-form-label">Excel:</label>
                                    <div class="col-lg-4">
                                        <input type="file" class="form-control" name="excel" />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-lg-2"></div>
                                    <div class="col-lg-4">
                                        NOT : Örnek exceli indirmek için <a href="../data/sample.xlsx">burayı tıklayınız</a>
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
                            <button type="submit" class="btn btn-info btn-customer-export"><i class="la la-angle-right"></i> DEVAM ET</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <?php }else{ ?>

        <form class="kt-form kt-form--label-right" method="post">
            <div class="kt-portlet__head kt-portlet__head--lg">
                <div class="kt-portlet__head-label">
                    <span class="kt-portlet__head-icon">
                        <i class="kt-font-brand fa fa-image"></i>
                    </span>
                    <h3 class="kt-portlet__head-title">
                        Toplu Resim Yükleme
                    </h3>
                </div>
                <div class="kt-portlet__head-toolbar">

                </div>
            </div>
            <div class="kt-portlet__body">
                <div class="kt-section kt-section--first">
                    <div class="kt-section__body" style="position: relative; z-index: 10;">

                        <div>
                            <div class="row">
                                <div class="col-md-4">
                                    <input type="file" name="file" id="uploadimage" multiple="multiple">
                                </div>
                                <div class="col-md-8">
                                    <div id="preview" class="jFiler-items">
                                        <ul class="jFiler-items-list jFiler-items-grid">
                                            <?php $counter1=-1; if( isset($data["pictures"]) && is_array($data["pictures"]) && sizeof($data["pictures"]) ) foreach( $data["pictures"] as $key1 => $value1 ){ $counter1++; ?>

                                            <li class="jFiler-item">
                                                <div class="jFiler-item-container">
                                                    <div class="jFiler-item-inner">
                                                        <div class="jFiler-item-thumb">
                                                            <div class="jFiler-item-status"></div>
                                                            <div class="jFiler-item-info">
                                                                <span class="jFiler-item-title"><b title="<?php echo $value1["picture"];?>"><?php echo ( mb_substr( $value1["picture"], 0,25,"UTF-8" ) );?></b></span>
                                                                <span class="jFiler-item-others"></span>
                                                            </div>

                                                            <div class="jFiler-item-thumb-image" style="background:url('../data/products/<?php echo $data["id"];?>/<?php echo $value1["picture"];?>') center center no-repeat; background-size: auto 110px;"></div>

                                                        </div>
                                                        <div class="jFiler-item-assets jFiler-row">
                                                            <ul class="list-inline pull-left">
                                                                <li><i class="fas fa-arrows-alt"></i> <input type="hidden" name="picrows[]" value="<?php echo $value1["id"];?>" /></li>
                                                            </ul>
                                                            <ul class="list-inline pull-right">
                                                                <li><a onclick="deleteFile('<?php echo $value1["id"];?>',this);" class="icon-jfi-trash jFiler-item-trash-action" id="<?php echo $value1["id"];?>"></a></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                            <?php } ?>

                                        </ul>
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
                            <button type="submit" name="finish" value="1" class="btn btn-info btn-customer-export"><i class="la la-angle-right"></i> YÜKLEMEYİ BİTİR</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <?php } ?>

    </div>
</div>

<style type="text/css">
    .bs-select-all,
    .bs-deselect-all
    {
        background-color: #282a3c;
        border-color: #282a3c;
        color: #ffffff;
    }
</style>
<script type="text/javascript">
    $('select').select2();

    <?php if( $auction_id ){ ?>

    $(document).ready(function() {
        $("#uploadimage").filer({
            limit: null,
            appendTo : '#preview',
            maxSize: null,
            extensions: ['jpg', 'jpeg', 'png'],
            changeInput: '<div class="jFiler-input-dragDrop"><div class="jFiler-input-inner"><div class="jFiler-input-icon"><i class="icon-jfi-cloud-up-o"></i></div><div class="jFiler-input-text"><h3>Dosyaları buraya sürükleyin</h3> <span style="display:inline-block; margin: 15px 0">yada</span></div><a class="jFiler-input-choose-btn blue">Gözatın</a></div></div>',
            showThumbs: true,
            theme: "dragdropbox",
            templates: {
                box: '<ul class="jFiler-items-list jFiler-items-grid"></ul>',
                item: '<li class="jFiler-item">\
                <div class="jFiler-item-container">\
                <div class="jFiler-item-inner">\
                <div class="jFiler-item-thumb">\
                <div class="jFiler-item-status"></div>\
                <div class="jFiler-item-info">\
                <span class="jFiler-item-title"><b title="{{fi-name}}">{{fi-name | limitTo: 25}}</b></span>\
                <span class="jFiler-item-others">{{fi-size2}}</span>\
                </div>\
                {{fi-image}}\
                </div>\
                <div class="jFiler-item-assets jFiler-row">\
                <ul class="list-inline pull-left">\
                <li>{{fi-progressBar}}</li>\
                </ul>\
                </div>\
                </div>\
                </div>\
                </li>',
                itemAppend: '<li class="jFiler-item">\
                <div class="jFiler-item-container">\
                <div class="jFiler-item-inner">\
                <div class="jFiler-item-thumb">\
                <div class="jFiler-item-status"></div>\
                <div class="jFiler-item-info">\
                <span class="jFiler-item-title"><b title="{{fi-name}}">{{fi-name | limitTo: 25}}</b></span>\
                <span class="jFiler-item-others">{{fi-size2}}</span>\
                </div>\
                {{fi-image}}\
                </div>\
                <div class="jFiler-item-assets jFiler-row">\
                <ul class="list-inline pull-left">\
                <li><span class="jFiler-item-others">{{fi-icon}}</span></li>\
                </ul>\
                </div>\
                </div>\
                </div>\
                </li>',
                progressBar: '<div class="bar"></div>',
                itemAppendToEnd: true,
                removeConfirmation: true,
                _selectors: {
                    list: '.jFiler-items-list',
                    item: '.jFiler-item',
                    progressBar: '.bar',
                    remove: '.jFiler-item-trash-action'
                }
            },
            dragDrop: {
                dragEnter: null,
                dragLeave: null,
                drop: null,
            },
            uploadFile: {
                url: baseURL + "auctions/uploadimage",
                data: {'id':'<?php echo $auction_id;?>'},
                type: 'POST',
                enctype: 'multipart/form-data',
                beforeSend: function(){},
                success: function(data, el){
                    el.find(".jFiler-item-trash-action").attr('id',data);
                    var parent = el.find(".jFiler-jProgressBar").parent();
                    el.find(".jFiler-jProgressBar").fadeOut("slow", function(){
                        $("<div class=\"jFiler-item-others text-success\"><i class=\"icon-jfi-check-circle\"></i> Yüklendi</div>").hide().appendTo(parent).fadeIn("slow");
                    });
                },
                error: function(el){
                    var parent = el.find(".jFiler-jProgressBar").parent();
                    el.find(".jFiler-jProgressBar").fadeOut("slow", function(){
                        $("<div class=\"jFiler-item-others text-error\" style=\"color:red\"><i class=\"icon-jfi-minus-circle\"></i> Hata ! Resim eşleşemedi.</div>").hide().appendTo(parent).fadeIn("slow");
                    });
                },
                statusCode: null,
                onProgress: null,
                onComplete: null
            },
            files: null,
            addMore: true,
            clipBoardPaste: true,
            excludeName: null,
            beforeRender: null,
            afterRender: null,
            beforeShow: null,
            beforeSelect: null,
            onSelect: null,
            afterShow: null,
            onEmpty: null,
            options: null,
            captions: {
                button: "Dosya Seçiniz",
                feedback: "Choose files To Upload",
                feedback2: "files were chosen",
                drop: "Dosyaları buraya sürükleyin",
                removeConfirmation: "Dosyayı silmek istediğinizden emin misiniz?",
                errors: {
                    filesLimit: "Only {{fi-limit}} files are allowed to be uploaded.",
                    filesType: "JPG, PNG dosyalarına izin verilmektedir."
                }
            }
        });
    });
    <?php } ?>

</script>