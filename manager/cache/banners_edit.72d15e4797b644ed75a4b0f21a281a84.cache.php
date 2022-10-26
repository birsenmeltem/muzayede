<?php if(!class_exists('view')){exit;}?><div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <form method="post" action="<?php  echo ADMINBASEURL;?>pages/banners" enctype="multipart/form-data">
            <?php if( $data["id"] ){ ?><input type="hidden" name="id" value="<?php echo $data["id"];?>" /><?php } ?>

            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Banner Ekle / Düzenle</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i data-dismiss="modal" class="fa fa-times"></i></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Banner Adı:</label>
                    <input type="text" class="form-control" required name="s[name]" value="<?php echo $data["name"];?>">
                    <div class="help-block">Bu bilgi sadece size gösterilir.</div>
                </div>
                <div class="form-group">
                    <label>Yazı 1: (Tasarımlarda karşılığı olursa kullanılır. (Opsiyonel))</label>
                    <input type="text" class="form-control" name="s[text1]" value="<?php echo $data["text1"];?>">
                </div>
                <div class="form-group">
                    <label>Yazı 2: (Tasarımlarda karşılığı olursa kullanılır. (Opsiyonel))</label>
                    <input type="text" class="form-control" name="s[text2]" value="<?php echo $data["text2"];?>">
                </div>
                <div class="form-group">
                    <label>Yönlenecek URL:</label>
                    <input type="text" class="form-control" name="s[url]" value="<?php echo $data["url"];?>">
                    <div class="help-block">Detay butonu ile yönlendirilmek istenirse kullanılır. (Opsiyonel) </div>
                </div>
                <?php if( $data["picture"] ){ ?>

                <div class="form-group">
                    <label>Resim:</label>
                    <div class="col-md-2">
                        <img src="../data/uploads/<?php echo $data["picture"];?>" height="80" class="img-thumbnail" /><br>
                    </div>

                </div>
                <?php } ?>

                <div class="form-group">
                    <label>Resim:</label>
                    <input type="file" class="form-control" name="img">
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