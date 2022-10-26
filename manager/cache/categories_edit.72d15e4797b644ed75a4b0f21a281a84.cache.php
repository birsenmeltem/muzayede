<?php if(!class_exists('view')){exit;}?><div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <form method="post" <?php if( !$data["parent_id"] ){ ?>action="<?php  echo ADMINBASEURL;?>categories"<?php } ?>>
            <?php if( $data["id"] ){ ?><input type="hidden" name="id" value="<?php echo $data["id"];?>" /><?php } ?>

            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Kategori Ekle / Düzenle</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i data-dismiss="modal" class="fa fa-times"></i></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Bağlı Olduğu Kategori:</label>
                    <select class="form-control" name="s[parent_id]">
                        <option value="0">-Ana Seviye-</option>
                        <?php echo $catlist;?>

                    </select>
                </div>
                <div class="form-group">
                    <label>Kategori Adı:</label>
                    <input type="text" class="form-control" required name="s[name]" value="<?php echo $data["name"];?>">
                </div>
                <div class="form-group">
                    <label>İçerik Yazısı:</label>
                    <textarea class="form-control summernote" name="s[texts]"><?php echo $data["texts"];?></textarea>
                </div>

                <div class="form-group row">
                    <div class="col-md-3">
                        <label>Anasayfada Göster:</label><br>
                        <span class="kt-switch kt-switch--outline kt-switch--icon kt-switch--success">
                            <label>
                                <input type="checkbox" name="s[mainpage]" <?php if( $data["mainpage"] ){ ?> checked <?php } ?> value="1">
                                <span></span>
                            </label>
                        </span>
                    </div>
                    <div class="col-md-8">
                        <label>Anasayfa Sırası:</label>
                        <input type="text" class="form-control" name="s[mainpage_rows]" value="<?php echo $data["mainpage_rows"];?>">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-3">
                        <label>Durum:</label><br>
                        <span class="kt-switch kt-switch--outline kt-switch--icon kt-switch--success">
                            <label>
                                <input type="checkbox" name="s[status]" <?php if( $data["status"] ){ ?> checked <?php } ?> value="1">
                                <span></span>
                            </label>
                        </span>
                    </div>

                    <div class="col-md-8">
                        <label>Sıra:</label>
                        <input type="text" class="form-control" name="s[rows]" value="<?php echo $data["rows"];?>">
                    </div>
                </div>
                <div class="form-group">
                    <label>SEO Title:</label>
                    <input type="text" class="form-control" name="s[seo_title]" value="<?php echo $data["seo_title"];?>">
                </div>
                <div class="form-group">
                    <label>SEO Keywords:</label>
                    <input type="text" class="form-control" name="s[seo_keywords]" value="<?php echo $data["seo_keywords"];?>">
                </div>
                <div class="form-group">
                    <label>SEO Description:</label>
                    <input type="text" class="form-control" name="s[seo_desc]" value="<?php echo $data["seo_desc"];?>">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Kapat</button>
                <button type="submit" class="btn btn-success">KAYDET</button>
            </div>
        </form>
    </div>
</div>