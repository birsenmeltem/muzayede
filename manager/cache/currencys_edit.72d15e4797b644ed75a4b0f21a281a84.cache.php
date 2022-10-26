<?php if(!class_exists('view')){exit;}?><div class="modal-dialog" role="document">
    <div class="modal-content">
        <form method="post" action="<?php  echo ADMINBASEURL;?>settings/currencys" enctype="multipart/form-data">
            <?php if( $data["id"] ){ ?><input type="hidden" name="id" value="<?php echo $data["id"];?>" /><?php } ?>

            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Para Birimi Ekle / Düzenle</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i data-dismiss="modal" class="fa fa-times"></i></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Para Kodu :</label>
                    <input type="text" class="form-control" required name="s[code]" value="<?php echo $data["code"];?>">
                    <div class="help-block">ISO Para birimlerine uygun giriniz !</div>
                </div>
                <div class="form-group">
                    <label>Para Birimi Adı :</label>
                    <input type="text" class="form-control" required name="s[name]" value="<?php echo $data["name"];?>">
                </div>
                <div class="form-group">
                    <label>Ön Ek Sembolü :</label>
                    <input type="text" class="form-control" name="s[prefix_symbol]" value="<?php echo $data["prefix_symbol"];?>">
                </div>
                <div class="form-group">
                    <label>Arka Ek Sembolü :</label>
                    <input type="text" class="form-control" name="s[suffix_symbol]" value="<?php echo $data["suffix_symbol"];?>">
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