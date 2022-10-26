<?php if(!class_exists('view')){exit;}?><div class="modal-dialog" role="document">
    <div class="modal-content">
        <form method="post" action="<?php  echo ADMINBASEURL;?>auctions" enctype="multipart/form-data">
            <?php if( $data["id"] ){ ?><input type="hidden" name="id" value="<?php echo $data["id"];?>" /><?php } ?>

            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Müzayede Ekle / Düzenle</h5>
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
                    <label>Müzayede Adı:</label>
                    <input type="text" class="form-control" required name="s[name]" value="<?php echo $data["name"];?>">
                </div>
                <?php if( $data["picture"] ){ ?>

                <div class="form-group">
                    <label>Kapak(Resim):</label>
                    <div class="col-md-4">
                        <img src="../data/uploads/<?php echo $data["picture"];?>" style="height:80px" class="img-thumbnail" /><br>
                    </div>

                </div>
                <?php } ?>

                <div class="form-group">
                    <label>Kapak(Resim):</label>
                    <input type="file" class="form-control" name="img">
                </div>
                <div class="form-group">
                    <label>Başlangıç Tarihi:</label>
                    <input type="text" class="form-control datetimepickers" required name="s[start_time]" value="<?php echo $data["start_date"];?>">
                </div>
                <div class="form-group">
                    <label>Bitiş Tarihi:</label>
                    <input type="text" class="form-control datetimepickers" required name="s[end_time]" value="<?php echo $data["end_date"];?>">
                </div>
                <div class="form-group">
                    <label>Lotlar arası saniye(Canlı):</label>
                    <input type="text" class="form-control" required name="s[remaining]" value="<?php echo $data["remaining"];?>">
                </div>
                <div class="form-group">
                    <label>Durum:</label>
                    <select class="form-control" name="s[status]">
                        <option value="0" <?php if( $data["status"] == 0 ){ ?> selected <?php } ?>>Yayında Değil</option>
                        <option value="1" <?php if( $data["status"] == 1 ){ ?> selected <?php } ?>>Yayında</option>
                        <option value="2" <?php if( $data["status"] == 2 ){ ?> selected <?php } ?>>Canlı</option>
                        <option value="3" <?php if( $data["status"] == 3 ){ ?> selected <?php } ?>>Canlı Bitti</option>
                    </select>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Kapat</button>
                <button type="submit" class="btn btn-success">KAYDET</button>
            </div>
        </form>
    </div>
</div>