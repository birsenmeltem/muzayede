<?php if(!class_exists('view')){exit;}?><div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <form method="post" action="<?php  echo ADMINBASEURL;?>cargocompanys" enctype="multipart/form-data">
            <?php if( $data["id"] ){ ?><input type="hidden" name="id" value="<?php echo $data["id"];?>" /><?php } ?>

            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Firma Ekle / Düzenle</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i data-dismiss="modal" class="fa fa-times"></i></button>
            </div>
            <div class="modal-body">

                <div class="form-group">
                    <label>Firma Adı:</label>
                    <input type="text" class="form-control" required name="s[name]" value="<?php echo $data["name"];?>">
                </div>
                <div class="form-group">
                    <label>Açıklama:</label>
                    <textarea class="form-control summernote" name="s[detail]" id="detail"><?php echo $data["detail"];?></textarea>
                </div>
                <div class="form-group">
                    <label>Sabit Ücret:</label>
                    <input type="text" class="form-control" name="s[price]" value="<?php echo $data["price"];?>">
                </div>
                <div class="form-group">
                    <label>Ücretsiz Kargo Limiti:</label>
                    <input type="text" class="form-control" name="s[free_cargo_price]" value="<?php echo $data["free_cargo_price"];?>">
                </div>
                <?php if( $data["picture"] ){ ?>

                <div class="form-group">
                    <label>Logo(Resim):</label>
                    <div class="col-md-2">
                        <img src="../data/uploads/<?php echo $data["picture"];?>" height="80" class="img-thumbnail" /><br>
                    </div>

                </div>
                <?php } ?>

                <div class="form-group">
                    <label>Logo(Resim):</label>
                    <input type="file" class="form-control" name="img">
                </div>
                <div class="form-group row">
                    <div class="col-md-8">
                        <label>Bağlı Olduğu Entegrasyon:</label>
                        <select class="form-control" name="s[integration_id]">
                            <option value="0">Yok</option>
                            <?php $counter1=-1; if( isset($integrations) && is_array($integrations) && sizeof($integrations) ) foreach( $integrations as $key1 => $value1 ){ $counter1++; ?>

                            <option value="<?php echo $value1["id"];?>" <?php if( $data["id"] == $value1["id"] ){ ?> selected<?php } ?>><?php echo $value1["name"];?></option>
                            <?php } ?>

                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Durum:</label><br>
                        <span class="kt-switch kt-switch--outline kt-switch--icon kt-switch--success">
                            <label>
                                <input type="checkbox" name="s[status]" <?php if( $data["status"] ){ ?> checked <?php } ?> value="1">
                                <span></span>
                            </label>
                        </span>
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