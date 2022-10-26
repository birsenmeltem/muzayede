<?php if(!class_exists('view')){exit;}?><div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <form method="post" action="<?php  echo ADMINBASEURL;?>customers" enctype="multipart/form-data">
            <?php if( $data["id"] ){ ?><input type="hidden" name="id" value="<?php echo $data["id"];?>" /><?php } ?>
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Üye Ekle / Düzenle</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i data-dismiss="modal" class="fa fa-times"></i></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Email :</label>
                    <input type="email" class="form-control" required name="u[username]" value="<?php echo $data["username"];?>">
                </div>
                <div class="form-group">
                    <label>Şifre :</label>
                    <input type="text" class="form-control" name="password">
                    <?php if( $data["id"] ){ ?><div class="help-block">Eğer değiştirmek istemiyorsanız boş bırakınız</div><?php } ?>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-6">
                            <label>Adı :</label>
                            <input type="text" class="form-control" required name="u[name]" id="adname" value="<?php echo $data["name"];?>">
                        </div><!-- End .col-sm-6 -->
                        <div class="col-sm-6">
                            <label>Soyadı :</label>
                            <input type="text" class="form-control" required name="u[surname]" id="surname" value="<?php echo $data["surname"];?>">
                        </div><!-- End .col-sm-6 -->
                    </div><!-- End .row -->
                </div>
                <div class="form-group">
                    <label>Adres :</label>
                    <textarea class="form-control" name="u[address]"><?php echo $data["address"];?></textarea>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-6">
                            <label>Ülke :</label>
                            <select  name="u[country_id]" id="country_id" class="form-control" onchange="get_citys(this.value, '#city_id');">
                                <option value="">Seçiniz</option>
                                <?php $counter1=-1; if( isset($countrys) && is_array($countrys) && sizeof($countrys) ) foreach( $countrys as $key1 => $value1 ){ $counter1++; ?>
                                <option value="<?php echo $value1["id"];?>" <?php if( $value1["id"] == $data["country_id"] ){ ?> selected <?php } ?>><?php echo $value1["name"];?></option>
                                <?php } ?>
                            </select>
                        </div><!-- End .col-sm-6 -->
                        <div class="col-sm-6">
                            <label>Şehir :</label>
                            <select name="u[city_id]" class="form-control" id="city_id">
                                <?php $counter1=-1; if( isset($citys) && is_array($citys) && sizeof($citys) ) foreach( $citys as $key1 => $value1 ){ $counter1++; ?>
                                <option value="<?php echo $value1["id"];?>" <?php if( $value1["id"] == $data["city_id"] ){ ?> selected <?php } ?>><?php echo $value1["name"];?></option>
                                <?php } ?>
                            </select>
                        </div><!-- End .col-sm-6 -->
                    </div><!-- End .row -->
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-6">
                            <label>İlçe :</label>
                            <input type="text" class="form-control" name="u[state]" id="state" value="<?php echo $data["state"];?>">
                        </div><!-- End .col-sm-6 -->
                        <div class="col-sm-6">
                            <label>Telefon :</label>
                            <input type="text" class="form-control" name="u[phone]" id="phone" value="<?php echo $data["phone"];?>">
                        </div><!-- End .col-sm-6 -->
                    </div><!-- End .row -->
                </div>
                <div class="form-group row">
                    <div class="col-md-8">
                        <label>Bağlı Olduğu Grup:</label>
                        <select class="form-control" name="u[group_id]">
                            <option value="0">Seçiniz</option>
                            <?php $counter1=-1; if( isset($groups) && is_array($groups) && sizeof($groups) ) foreach( $groups as $key1 => $value1 ){ $counter1++; ?>
                            <option value="<?php echo $value1["id"];?>" <?php if( $data["group_id"] == $value1["id"] ){ ?> selected <?php } ?>><?php echo $value1["name"];?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Durum:</label><br>
                        <span class="kt-switch kt-switch--outline kt-switch--icon kt-switch--success">
                            <label>
                                <input type="checkbox" name="u[status]" <?php if( $data["status"] ){ ?> checked <?php } ?> value="1">
                                <span></span>
                            </label>
                        </span>
                    </div>

                </div>
                <div class="form-group">
                    <label>Paraşüt ID :</label>
                    <input type="text" class="form-control" name="u[parasut_id]" value="<?php echo $data["parasut_id"];?>">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Kapat</button>
                <button type="submit" class="btn btn-success">KAYDET</button>
            </div>
        </form>
    </div>
</div>