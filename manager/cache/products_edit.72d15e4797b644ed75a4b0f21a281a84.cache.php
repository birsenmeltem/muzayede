<?php if(!class_exists('view')){exit;}?><div class="kt-subheader  kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <span class="kt-subheader__separator kt-hidden"></span>
            <div class="kt-subheader__breadcrumbs">
                <a href="<?php  echo ADMINBASEURL;?>" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
                <span class="kt-subheader__breadcrumbs-separator"></span>
                <a href="<?php  echo ADMINBASEURL;?>products" class="kt-subheader__breadcrumbs-link">
                    Ürünler
                </a>
                <span class="kt-subheader__breadcrumbs-separator"></span>
                <span class="kt-subheader__breadcrumbs-link kt-subheader__breadcrumbs-link--active">Ürün Ekle / Düzenle</span>
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
                    <a href="<?php  echo ADMINBASEURL;?>products" class="btn btn-clean kt-margin-r-10">
                        <i class="la la-arrow-left"></i>
                        <span class="kt-hidden-mobile">Ürünler</span>
                    </a>
                    <?php if( $data["id"] ){ ?>

                    <a href="<?php echo $data["url"];?>" class="btn btn-dark kt-margin-r-10" target="_blank">
                        <i class="fa fa-eye"></i>
                        <span class="kt-hidden-mobile">Sitede Gör</span>
                    </a>
                    <?php } ?>

                    <div class="btn-group">
                        <button type="submit" class="btn btn-success">
                            <i class="fa fa-save"></i>
                            <span class="kt-hidden-mobile">KAYDET</span>
                        </button>
                    </div>
                </div>
            </div>

            <?php if( $data["id"] ){ ?><input type="hidden" name="id" value="<?php echo $data["id"];?>" /><?php } ?>

            <div class="kt-portlet__body">
                <div class="kt-section kt-section--first">
                    <div class="kt-section__body" style="position: relative; z-index: 10;">
                        <ul class="nav nav-tabs nav-tabs-line" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#temelbilgi" role="tab"><i class="fas fa-book"></i> Temel Bilgiler</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#aciklamalar" role="tab"><i class="fas fa-keyboard"></i> Açıklamalar</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#seo" role="tab"><i class="fab fa-google"></i> SEO Bilgileri</a>
                            </li>
                            <?php if( $data["id"] ){ ?>

                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#resimler" role="tab"><i class="fas fa-images"></i> Resimler</a>
                            </li>
                            <?php } ?>

                        </ul>

                        <div class="tab-content">
                            <div class="tab-pane active" id="temelbilgi" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label">Müzayede: <strong class="kt-font-danger">*</strong></label>
                                            <div class="col-lg-9">
                                                <select class="form-control auction" name="p[auction_id]" required>
                                                    <?php $counter1=-1; if( isset($Auctions) && is_array($Auctions) && sizeof($Auctions) ) foreach( $Auctions as $key1 => $value1 ){ $counter1++; ?>

                                                    <option value="<?php echo $value1["id"];?>" <?php if( $data["auction_id"] == $value1["id"] ){ ?> selected <?php } ?>><?php echo $value1["id"];?> - <?php echo $value1["name"];?> (<?php echo $value1["start_date"];?> - <?php echo $value1["end_date"];?>)</option>
                                                    <?php } ?>

                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label">Kategori: <strong class="kt-font-danger">*</strong></label>
                                            <div class="col-lg-9">
                                                <select class="form-control categorylist" multiple="multiple" name="p[category_id][]" required>
                                                    <?php echo $Cats;?>

                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label">Lot No: <strong class="kt-font-danger">*</strong></label>
                                            <div class="col-lg-9">
                                                <input type="text" class="form-control" value="<?php echo $data["sku"];?>" name="p[sku]" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label">Ürün Adı: <strong class="kt-font-danger">*</strong></label>
                                            <div class="col-lg-9">
                                                <input type="text" class="form-control" value="<?php echo $data["name"];?>" name="p[name]" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label">Fatura Adı:</label>
                                            <div class="col-lg-9">
                                                <input type="text" class="form-control" value="<?php echo $data["invoice_name"];?>" name="p[invoice_name]">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label">Barkod:</label>
                                            <div class="col-lg-9">
                                                <input type="text" class="form-control" value="<?php echo $data["barcode"];?>" name="p[barcode]">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label">Açılış Fiyatı:</label>
                                            <div class="col-lg-3">
                                                <input type="text" class="form-control productprice" value="<?php echo $data["old_price"];?>" name="p[old_price]">
                                            </div>
                                            <label class="col-lg-3 col-form-label">Para Birimi:</label>
                                            <div class="col-lg-3">
                                                <select class="form-control" name="p[currency_id]">
                                                    <?php $counter1=-1; if( isset($Currencys) && is_array($Currencys) && sizeof($Currencys) ) foreach( $Currencys as $key1 => $value1 ){ $counter1++; ?>

                                                    <option value="<?php echo $value1["id"];?>" <?php if( $data["currency_id"] == $value1["id"] ){ ?> selected<?php } ?>><?php echo $value1["code"];?></option>
                                                    <?php } ?>

                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label">Güncel Fiyatı:</label>
                                            <div class="col-lg-3">
                                                <input type="text" class="form-control productprice" value="<?php echo $data["price"];?>" name="p[price]">
                                            </div>
                                            <label class="col-lg-3 col-form-label">KDV Oranı:</label>
                                            <div class="col-lg-3">
                                                <input type="number" min="0" class="form-control" value="<?php echo $data["kdv"];?>" name="p[kdv]" placeholder="8">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label">Desi/Kg:</label>
                                            <div class="col-lg-9">
                                                <input type="text" class="form-control" value="<?php echo $data["desi"];?>" name="p[desi]">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label">Yayın Durumu:</label>
                                            <div class="col-lg-3">
                                                <select class="form-control" name="p[status]">
                                                    <option value="0" <?php if( $data["status"] == 0 ){ ?> selected <?php } ?>>Yayında Değil</option>
                                                    <option value="1" <?php if( $data["status"] == 1 ){ ?> selected <?php } ?>>Yayında</option>
                                                    <option value="2" <?php if( $data["status"] == 2 ){ ?> selected <?php } ?>>Satıldı</option>
                                                    <option value="3" <?php if( $data["status"] == 3 ){ ?> selected <?php } ?>>Satılmadı</option>
                                                    <option value="4" <?php if( $data["status"] == 4 ){ ?> selected <?php } ?>>İptal</option>
                                                </select>
                                            </div>
                                            <label class="col-lg-2 col-form-label">Ürün Sırası:</label>
                                            <div class="col-lg-3">
                                                <input type="text" class="form-control" value="<?php echo $data["rows"];?>" name="p[rows]" placeholder="999">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label">Ürün Cinsi:</label>
                                            <div class="col-lg-9">
                                                <select class="form-control brandlist" name="p[brand_id]">
                                                    <option value="0">-Yok-</option>
                                                    <?php $counter1=-1; if( isset($Brands) && is_array($Brands) && sizeof($Brands) ) foreach( $Brands as $key1 => $value1 ){ $counter1++; ?>

                                                    <optgroup label="<?php echo $key1;?>">
                                                        <?php $counter2=-1; if( isset($value1) && is_array($value1) && sizeof($value1) ) foreach( $value1 as $key2 => $value2 ){ $counter2++; ?>

                                                        <option value="<?php echo $value2["id"];?>" <?php if( $data["brand_id"] == $value2["id"] ){ ?> selected <?php } ?>><?php echo $value2["name"];?></option>
                                                        <?php } ?>

                                                    </optgroup>
                                                    <?php } ?>

                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label">GTIN:</label>
                                            <div class="col-lg-9">
                                                <input type="text" class="form-control" value="<?php echo $data["gtin"];?>" name="p[gtin]">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label">MPN:</label>
                                            <div class="col-lg-9">
                                                <input type="text" class="form-control" value="<?php echo $data["mpn"];?>" name="p[mpn]">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label">Satıcı ID:</label>
                                            <div class="col-lg-9">
                                                <input type="text" class="form-control" value="<?php echo $data["seller"];?>" name="p[seller]">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label">Alıcı ID:</label>
                                            <div class="col-lg-9">
                                                <input type="text" class="form-control" value="<?php echo $data["sale"];?>" name="p[sale]">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label">Google Feed:</label>
                                            <div class="col-lg-1">
                                                <span class="kt-switch kt-switch--icon">
                                                    <label>
                                                        <input type="checkbox" <?php if( $data["google_ads"] ){ ?>checked="checked"<?php } ?> name="p[google_ads]" value="1">
                                                        <span></span>
                                                    </label>
                                                </span>
                                            </div>

                                            <label class="col-lg-3 col-form-label">Facebook Feed:</label>
                                            <div class="col-lg-1">
                                                <span class="kt-switch kt-switch--icon">
                                                    <label>
                                                        <input type="checkbox" <?php if( $data["facebook_ads"] ){ ?>checked="checked"<?php } ?> name="p[facebook_ads]" value="1">
                                                        <span></span>
                                                    </label>
                                                </span>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="aciklamalar" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group row">
                                            <label class="col-lg-2 col-form-label">Youtube Video URL:</label>
                                            <div class="col-lg-9">
                                                <input type="text" class="form-control" value="<?php echo $data["video_link"];?>" name="p[video_link]">
                                                <div class="help-block">Lütfen izleme linkindeki "<strong>watch?v=<code>3sN-DKevBuE</code></strong>" bölümdeki "v" değişkeninin değerini ekleyiniz.</div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-2 col-form-label">Kısa Açıklama:</label>
                                            <div class="col-lg-10">
                                                <textarea cols="" rows="" class="summernote" name="p[shortdetail]" id="shortdetail"><?php echo $data["shortdetail"];?></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-2 col-form-label">Uzun Açıklama:</label>
                                            <div class="col-lg-10">
                                                <textarea cols="" rows="" class="summernote" name="p[detail]" id="detail"><?php echo $data["detail"];?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="seo" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-9">
                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label">SEO Title:</label>
                                            <div class="col-lg-9">
                                                <input type="text" class="form-control" value="<?php echo $data["seo_title"];?>" name="p[seo_title]">
                                                <div class="help-block">Boş bırakırsanız ürün adını alır.</div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label">SEO Keywords:</label>
                                            <div class="col-lg-9">
                                                <input type="text" class="form-control" value="<?php echo $data["seo_keywords"];?>" name="p[seo_keywords]">
                                                <div class="help-block">kelimeleri virgül ile ayırınız.</div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label">SEO Description:</label>
                                            <div class="col-lg-9">
                                                <textarea cols="" rows="" class="form-control" name="p[seo_desc]" id="seo_desc"><?php echo $data["seo_desc"];?></textarea>
                                                <div class="help-block">Maksimum 255 karakter giriniz.</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php if( $data["id"] ){ ?>

                            <div class="tab-pane" id="resimler" role="tabpanel">
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
                            <?php } ?>

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
                            <a class="btn btn-secondary" href="<?php  echo ADMINBASEURL;?>products"><i class="fa fa-angle-left"></i> GERİ DÖN</a>

                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {

        $('.categorylist').select2({
            placeholder: "Lütfen en az 1 kategori seçiniz"
        });
        $('.auction').select2({
            placeholder: "Lütfen müzayede seçiniz"
        });
        $('.brandlist').select2();

        $('.productprice').TouchSpin({
            buttondown_class: 'btn btn-secondary',
            buttonup_class: 'btn btn-secondary',
            min: 0,
            max: 10000000000,
            step: 0.01,
            decimals: 2,
            boostat: 5,
        });

        $("#uploadimage").filer({
            limit: null,
            appendTo : '#preview',
            maxSize: null,
            extensions: ['jpg', 'jpeg', 'png', 'gif'],
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
                <ul class="list-inline pull-right">\
                <li><a class="icon-jfi-trash jFiler-item-trash-action"></a></li>\
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
                <ul class="list-inline pull-right">\
                <li><a class="icon-jfi-trash jFiler-item-trash-action"></a></li>\
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
                url: baseURL + "products/uploadimage",
                data: {'id':'<?php echo $data["id"];?>'},
                type: 'POST',
                enctype: 'multipart/form-data',
                beforeSend: function(){},
                success: function(data, el){
                    el.find(".jFiler-item-trash-action").attr('id',data);
                    var parent = el.find(".jFiler-jProgressBar").parent();
                    el.find(".jFiler-jProgressBar").fadeOut("slow", function(){
                        $("<div class=\"jFiler-item-others text-success\"><i class=\"icon-jfi-check-circle\"></i> Yüklendi</div><input type=\"hidden\" name=\"picrows[]\" value="+data+" />").hide().appendTo(parent).fadeIn("slow");
                    });
                },
                error: function(el){
                    var parent = el.find(".jFiler-jProgressBar").parent();
                    el.find(".jFiler-jProgressBar").fadeOut("slow", function(){
                        $("<div class=\"jFiler-item-others text-error\"><i class=\"icon-jfi-minus-circle\"></i> Hata !</div>").hide().appendTo(parent).fadeIn("slow");
                    });
                },
                statusCode: null,
                onProgress: null,
                onComplete: null
            },
            files: null,
            addMore: false,
            clipBoardPaste: true,
            excludeName: null,
            beforeRender: null,
            afterRender: null,
            beforeShow: null,
            beforeSelect: null,
            onSelect: null,
            afterShow: null,
            onRemove: function(itemEl, file, id, listEl, boxEl, newInputEl, inputEl){
                var id = (itemEl.find('.jFiler-item-trash-action').attr('id'));
                $.post( baseURL + 'products/removeimage', {'id':id});
            },
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
                    filesType: "JPG, PNG, GIF dosyalarına izin verilmektedir."
                }
            }
        });

        $('.jFiler-items-list').sortable();

        $('.jFiler-items-list').sortable().bind('sortupdate', function(e, ui) {
            var form = $('input','.jFiler-items-grid').serialize();
            $.post(baseURL + 'products/picturerows', form);
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
