<?php if(!class_exists('view')){exit;}?><div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <form method="post" enctype="multipart/form-data">
            <input type="hidden" name="s[ty]" value="2" />
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Yeni Ödeme</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i data-dismiss="modal" class="fa fa-times"></i></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Tarih:</label>
                    <input type="text" class="form-control datetimepickers" name="s[create_time]" value="">
                </div>
                <div class="form-group row">
                    <label class="col-md-2">Ödeme Yapılan:</label>
                    <label class="col-md-3"><input type="radio" class="tt" name="tip" value="user" checked="checked" /> Üye </label>
                    <label class="col-md-3"><input type="radio" class="tt" name="tip" value="other" /> Diğer </label>
                </div>
                <div class="form-group user">
                    <label>Üye Adı:</label>
                    <select name="s[user_id]" class="form-control">
                        <option value="">--Seçiniz--</option>
                        <?php $counter1=-1; if( isset($userList) && is_array($userList) && sizeof($userList) ) foreach( $userList as $key1 => $value1 ){ $counter1++; ?>

                        <option value="<?php echo $value1["id"];?>" <?php if( $user_id == $value1["id"] ){ ?> selected <?php } ?>><?php echo $value1["id"];?>- <?php echo $value1["name"];?> <?php echo $value1["surname"];?> (<?php echo $value1["username"];?>)</option>
                        <?php } ?>

                    </select>
                </div>

                <div class="form-group">
                    <label>Tutar:</label>
                    <input type="text" class="form-control" name="s[price]" value="<?php echo $data["price"];?>">
                </div>
                <div class="form-group">
                    <label>Açıklama:</label>
                    <textarea cols="" rows="4" class="form-control" name="s[detail]"><?php echo $data["detail"];?></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Kapat</button>
                <button type="submit" class="btn btn-success">KAYDET</button>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
    $('select').select2();
    $('.tt').on('click', function() {
       $('.user').fadeToggle();
    });
</script>