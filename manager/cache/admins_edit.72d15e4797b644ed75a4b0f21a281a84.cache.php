<?php if(!class_exists('view')){exit;}?><div class="modal-dialog" role="document">
    <div class="modal-content">
        <form method="post" action="<?php  echo ADMINBASEURL;?>settings/admins" enctype="multipart/form-data">
            <?php if( $data["id"] ){ ?><input type="hidden" name="id" value="<?php echo $data["id"];?>" /><?php } ?>

            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Yönetici Ekle / Düzenle</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i data-dismiss="modal" class="fa fa-times"></i></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Kullanıcı Adı :</label>
                    <input type="text" class="form-control" required name="s[username]" value="<?php echo $data["username"];?>">
                </div>
                <div class="form-group">
                    <label>Şifre :</label>
                    <input type="password" class="form-control" name="password">
                    <?php if( $data["id"] ){ ?><div class="help-block">Eğer değiştirmek istemiyorsanız boş bırakınız</div><?php } ?>

                </div>

                <div class="form-group">
                    <label>Adı Soyadı :</label>
                    <input type="text" class="form-control" required name="s[name]" value="<?php echo $data["name"];?>">
                </div>
                <div class="form-group">
                    <label>Email :</label>
                    <input type="text" class="form-control" name="s[email]" value="<?php echo $data["email"];?>">
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