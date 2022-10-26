<?php if(!class_exists('view')){exit;}?><div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <form method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $data["id"];?>" />
            <input type="hidden" name="s[ty]" value="<?php echo $data["ty"];?>" />
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Düzenle</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i data-dismiss="modal" class="fa fa-times"></i></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Tarih:</label>
                    <p class="form-control-static"><?php echo $data["create_date"];?></p>
                </div>
                <?php if( $data["user"]["id"] ){ ?>

                <div class="form-group">
                    <label>Üye Adı:</label>
                    <p class="form-control-static"><?php echo $data["user"]["id"];?> - <?php echo $data["user"]["name"];?> <?php echo $data["user"]["surname"];?> (<?php echo $data["user"]["username"];?>)</p>
                </div>
                <?php }else{ ?>

                <div class="form-group">
                    <label>Kaynak:</label>
                    <p class="form-control-static">Diğer</p>
                </div>
                <?php } ?>

                <div class="form-group">
                    <label>Tutar:</label>
                    <input type="text" class="form-control" name="s[price]" value="<?php echo $data["price"];?>">
                </div>
                <?php if( $data["product_id"] ){ ?>

                <div class="form-group">
                    <label>Durum:</label>
                    <select name="s[status]" class="form-control">
                        <?php $counter1=-1; if( isset($PaymentPriceType) && is_array($PaymentPriceType) && sizeof($PaymentPriceType) ) foreach( $PaymentPriceType as $key1 => $value1 ){ $counter1++; ?>

                        <option value="<?php echo $key1;?>" <?php if( $data["status"] == $key1 ){ ?> selected <?php } ?>><?php echo strip_tags( $value1 );?></option>
                        <?php } ?>

                    </select>
                </div>
                <?php } ?>

                <div class="form-group">
                    <label>Not:</label>
                    <textarea cols="" rows="4" class="form-control" name="s[note]"><?php echo $data["note"];?></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Kapat</button>
                <button type="submit" class="btn btn-success">KAYDET</button>
            </div>
        </form>
    </div>
</div>