<?php if(!class_exists('view')){exit;}?><div class="modal-dialog" role="document">
    <div class="modal-content">
        <form method="post" action="<?php  echo ADMINBASEURL;?>payments/banks"  enctype="multipart/form-data">
            <?php if( $data["id"] ){ ?><input type="hidden" name="id" value="<?php echo $data["id"];?>" /><?php } ?>

            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Hesap Ekle / Düzenle</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i data-dismiss="modal" class="fa fa-times"></i></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Banka Adı:</label>
                    <input type="text" class="form-control" required name="s[bank_name]" value="<?php echo $data["bank_name"];?>">
                </div>

                <?php if( $data["picture"] ){ ?>

                <div class="form-group">
                    <label>Banka Logo(Resim):</label>
                    <div class="col-md-8">
                        <img src="../data/uploads/<?php echo $data["picture"];?>" style="height:80px" class="img-thumbnail" /><br>
                    </div>

                </div>
                <?php } ?>

                <div class="form-group">
                    <label>Banka Logo(Resim):</label>
                    <input type="file" class="form-control" name="img">
                </div>
                <div class="form-group">
                    <label>Şube Adı:</label>
                    <input type="text" class="form-control" name="s[sube_name]" value="<?php echo $data["sube_name"];?>">
                </div>
                <div class="form-group">
                    <label>Şube Kodu:</label>
                    <input type="text" class="form-control" name="s[sube_code]" value="<?php echo $data["sube_code"];?>">
                </div>
                <div class="form-group">
                    <label>Hesap Sahibi Adı:</label>
                    <input type="text" class="form-control" required name="s[name]" value="<?php echo $data["name"];?>">
                </div>
                <div class="form-group">
                    <label>Hesap No:</label>
                    <input type="text" class="form-control" name="s[hesap_no]" value="<?php echo $data["hesap_no"];?>">
                </div>
                <div class="form-group">
                    <label>IBAN:</label>
                    <input type="text" class="form-control" name="s[iban]" value="<?php echo $data["iban"];?>">
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

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Kapat</button>
                <button type="submit" class="btn btn-success">KAYDET</button>
            </div>
        </form>
    </div>
</div>