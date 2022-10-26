<?php if(!class_exists('view')){exit;}?><div class="modal-dialog" role="document">
    <div class="modal-content">
        <form method="post" action="<?php  echo ADMINBASEURL;?>languages" enctype="multipart/form-data">
            <?php if( $data["id"] ){ ?><input type="hidden" name="id" value="<?php echo $data["id"];?>" /><?php } ?>

            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Dil Ekle / Düzenle</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i data-dismiss="modal" class="fa fa-times"></i></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Dil Kodu:</label>
                    <input type="text" class="form-control" required name="s[flag]" value="<?php echo $data["flag"];?>" <?php if( $data["id"] ){ ?> disabled <?php } ?>>
                    <div class="help-block">Türkçe karakter kullanmayınız ! Örnek Türkçe için "tr"</div>
                </div>
                <div class="form-group">
                    <label>Dil Adı:</label>
                    <input type="text" class="form-control" required name="s[name]" value="<?php echo $data["name"];?>">
                </div>
                <?php if( $data["picture"] ){ ?>

                <div class="form-group">
                    <label>Bayrak:</label>
                    <div class="col-md-4">
                        <img src="../data/langs/<?php echo $data["picture"];?>" style="height:24px" class="img-thumbnail" /><br>
                    </div>

                </div>
                <?php } ?>

                <div class="form-group">
                    <label>Bayrak:</label>
                    <input type="file" class="form-control" name="img">
                </div>
                <div class="form-group">
                    <label>Para Birimi:</label>
                    <select class="form-control" name="s[currency_id]" required>
                        <option value="0">Seçiniz</option>
                        <?php $counter1=-1; if( isset($currencys) && is_array($currencys) && sizeof($currencys) ) foreach( $currencys as $key1 => $value1 ){ $counter1++; ?>

                            <option value="<?php echo $value1["id"];?>" <?php if( $data["currency_id"] == $value1["id"] ){ ?> selected<?php } ?>><?php echo $value1["name"];?></option>
                        <?php } ?>

                    </select>
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