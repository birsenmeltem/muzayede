<?php if(!class_exists('view')){exit;}?><div class="modal-dialog" role="document">
    <div class="modal-content">
        <form method="post" action="<?php  echo ADMINBASEURL;?>brands" enctype="multipart/form-data">
            <?php if( $data["id"] ){ ?><input type="hidden" name="id" value="<?php echo $data["id"];?>" /><?php } ?>

            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Cins Ekle / Düzenle</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i data-dismiss="modal" class="fa fa-times"></i></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Bağlı Olduğu Kategori:</label>
                    <select class="form-control" name="s[category_id]">
                        <option value="0">Seçiniz</option>
                        <?php echo $catlist;?>

                    </select>
                    <div class="help-block">Seçtiğiniz kategorinin altındaki kategorilerdede gözükür.</div>
                </div>
                <div class="form-group">
                    <label>Cins Adı:</label>
                    <input type="text" class="form-control" required name="s[name]" value="<?php echo $data["name"];?>">
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

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Kapat</button>
                <button type="submit" class="btn btn-success">KAYDET</button>
            </div>
        </form>
    </div>
</div>