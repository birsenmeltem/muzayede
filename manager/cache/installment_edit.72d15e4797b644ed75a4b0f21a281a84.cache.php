<?php if(!class_exists('view')){exit;}?><div class="modal-dialog" role="document">
    <div class="modal-content">
        <form method="post" action="<?php  echo ADMINBASEURL;?>payments/installment/<?php echo $data["vpos_id"];?>">
            <input type="hidden" name="s[vpos_id]" value="<?php echo $data["vpos_id"];?>" />
            <?php if( $data["id"] ){ ?><input type="hidden" name="id" value="<?php echo $data["id"];?>" /><?php } ?>

            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Taksit Ekle / Düzenle</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i data-dismiss="modal" class="fa fa-times"></i></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Taksit Sayısı:</label>
                    <input type="text" class="form-control" required name="s[installment]" value="<?php echo $data["installment"];?>">
                </div>


                <div class="form-group">
                    <label>Komsiyon Oranı:</label>
                    <input type="text" class="form-control" name="s[com]" value="<?php echo $data["com"];?>">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Kapat</button>
                <button type="submit" class="btn btn-success">KAYDET</button>
            </div>
        </form>
    </div>
</div>