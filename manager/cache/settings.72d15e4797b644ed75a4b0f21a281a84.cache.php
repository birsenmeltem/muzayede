<?php if(!class_exists('view')){exit;}?><div class="kt-subheader  kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title">Genel Ayarlar</h3>
        </div>
    </div>
</div>

<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
    <?php echo $info;?>
    <div class="kt-portlet kt-portlet--mobile">
        <form class="kt-form kt-form--label-right" method="post" enctype="multipart/form-data">
            <div class="kt-portlet__head kt-portlet__head--lg">
                <div class="kt-portlet__head-label">
                    <span class="kt-portlet__head-icon">
                        <i class="kt-font-brand fa fa-user-cog"></i>
                    </span>
                    <h3 class="kt-portlet__head-title">
                        Site Ayarları
                    </h3>
                </div>
                <div class="kt-portlet__head-toolbar">
                    <div class="btn-group">
                        <button type="submit" class="btn btn-success">
                            <i class="fa fa-save"></i>
                            <span class="kt-hidden-mobile">KAYDET</span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="kt-portlet__body">
                <div class="kt-section kt-section--first">
                    <div class="kt-section__body" style="position: relative; z-index: 10;">
                        <ul class="nav nav-tabs nav-tabs-line" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#temelbilgi" role="tab"><i class="fas fa-book"></i> Temel Bilgiler</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#smtp" role="tab"><i class="fas fa-mail-bulk"></i> SMTP Bilgileri</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#sms" role="tab"><i class="fas fa-sms"></i> NetGSM Api Bilgileri</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#sosyalmedya" role="tab"><i class="fas fa-share-alt"></i> Sosyal Medya</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#mail" role="tab"><i class="fas fa-envelope-open-text"></i> Mail Tasarımları</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#mesaj" role="tab"><i class="fas fa-comment-alt"></i> SMS Mesajları</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#soz" role="tab"><i class="fas fa-edit"></i> Sözleşmeler</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="temelbilgi" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group row">
                                            <label class="col-lg-2 col-form-label">Site Başlığı: </label>
                                            <div class="col-lg-9">
                                                <input type="text" class="form-control" value="<?php echo $data["seo_title"];?>" name="p[seo_title]" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-2 col-form-label">SEO Keywords:</label>
                                            <div class="col-lg-9">
                                                <textarea cols="" rows="" class="form-control" name="p[seo_keywords]" id="seo_keywords"><?php echo $data["seo_keywords"];?></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-2 col-form-label">SEO Description:</label>
                                            <div class="col-lg-9">
                                                <textarea cols="" rows="" class="form-control" name="p[seo_desc]" id="seo_desc"><?php echo $data["seo_desc"];?></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-2 col-form-label">Firma Adı: </label>
                                            <div class="col-lg-9">
                                                <input type="text" class="form-control" value="<?php echo $data["company_name"];?>" name="p[company_name]">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-2 col-form-label">Firma Adresi:</label>
                                            <div class="col-lg-9">
                                                <textarea cols="" rows="" class="form-control summernote" name="p[company_address]" id="company_address"><?php echo $data["company_address"];?></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-2 col-form-label">Firma Telefon: </label>
                                            <div class="col-lg-9">
                                                <input type="text" class="form-control" value="<?php echo $data["company_phone"];?>" name="p[company_phone]">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-2 col-form-label">Firma Whatsapp: </label>
                                            <div class="col-lg-9">
                                                <input type="text" class="form-control" value="<?php echo $data["whatsapp"];?>" name="p[whatsapp]">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-2 col-form-label">Firma Email: </label>
                                            <div class="col-lg-9">
                                                <input type="text" class="form-control" value="<?php echo $data["company_email"];?>" name="p[company_email]">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-2 col-form-label">Google Maps:</label>
                                            <div class="col-lg-9">
                                                <textarea cols="" rows="" class="form-control" name="p[company_maps]" id="company_maps"><?php echo $data["company_maps"];?></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-2 col-form-label">Meta Kodları:</label>
                                            <div class="col-lg-9">
                                                <textarea cols="" rows="" class="form-control" name="p[metacode]" id="metacode"><?php echo $data["metacode"];?></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-2 col-form-label">Footer Kodları:</label>
                                            <div class="col-lg-9">
                                                <textarea cols="" rows="" class="form-control" name="p[footercode]" id="footercode"><?php echo $data["footercode"];?></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-2 col-form-label">Sayfada Gözükecek Ürün Sayısı: </label>
                                            <div class="col-lg-9">
                                                <input type="text" class="form-control" value="<?php echo $data["show_product_page"];?>" name="p[show_product_page]">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-2 col-form-label">Canlıda Uyar(Kalan Lot Sayısı): </label>
                                            <div class="col-lg-9">
                                                <input type="text" class="form-control" value="<?php echo $data["wait_live"];?>" name="p[wait_live]">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-2 col-form-label">Alıcı Komisyon (%): </label>
                                            <div class="col-lg-9">
                                                <input type="text" class="form-control" value="<?php echo $data["commission"];?>" name="p[commission]">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-2 col-form-label">Satıcı Komisyon (%): </label>
                                            <div class="col-lg-9">
                                                <input type="text" class="form-control" value="<?php echo $data["seller_commission"];?>" name="p[seller_commission]">
                                            </div>
                                        </div>
                                        <?php if( $data["logo"] ){ ?>
                                        <div class="form-group row">
                                            <label class="col-lg-2 col-form-label">Site Logo: </label>
                                            <div class="col-lg-9">
                                                <img src="../data/uploads/<?php echo $data["logo"];?>" class="img-thumbnail" style="height:80px" >
                                                <div class="help-block"><a href="<?php  echo ADMINBASEURL;?>settings/removelogo" class="btn btn-sm btn-warning remove">sil</a></div>
                                            </div>
                                        </div>
                                        <?php } ?>
                                        <div class="form-group row">
                                            <label class="col-lg-2 col-form-label">Site Logo: </label>
                                            <div class="col-lg-9">
                                                <input type="file" class="form-control" name="img">
                                            </div>
                                        </div>
                                        <?php if( $data["favicon"] ){ ?>
                                        <div class="form-group row">
                                            <label class="col-lg-2 col-form-label">Site Favicon: </label>
                                            <div class="col-lg-9">
                                                <img src="../data/uploads/<?php echo $data["favicon"];?>" class="img-thumbnail" style="height:24px" >
                                                <div class="help-block"><a href="<?php  echo ADMINBASEURL;?>settings/removefavicon" class="btn btn-sm btn-warning remove">sil</a></div>
                                            </div>
                                        </div>
                                        <?php } ?>
                                        <div class="form-group row">
                                            <label class="col-lg-2 col-form-label">Site Favicon: </label>
                                            <div class="col-lg-9">
                                                <input type="file" class="form-control" name="favicon">
                                            </div>
                                        </div>
                                        <?php if( $data["watermark"] ){ ?>
                                        <div class="form-group row">
                                            <label class="col-lg-2 col-form-label">Resim Watermark: </label>
                                            <div class="col-lg-9">
                                                <img src="../data/uploads/<?php echo $data["watermark"];?>" class="img-thumbnail" style="height:80px" >
                                                <div class="help-block"><a href="<?php  echo ADMINBASEURL;?>settings/removewatermark" class="btn btn-sm btn-warning remove">sil</a></div>
                                            </div>
                                        </div>
                                        <?php } ?>
                                        <div class="form-group row">
                                            <label class="col-lg-2 col-form-label">Resim Watermark: </label>
                                            <div class="col-lg-9">
                                                <input type="file" class="form-control" name="watermark">
                                                <div class="help-block">Watermark yüklendikten sonraki resimleri etkilemektedir. Resimlere watermark uygulamak istiyorsanız lütfen önce watermark yükleyiniz. Maksimum 600 x 600px olarak transparan (opacity düşük ) şeklinde yükleyiniz.</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="smtp" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group row">
                                            <label class="col-lg-2 col-form-label">SMTP:</label>
                                            <div class="col-lg-2">
                                                <span class="kt-switch kt-switch--outline kt-switch--icon kt-switch--success">
                                                    <label>
                                                        <input type="checkbox" <?php if( $data["SMTP"] ){ ?>checked="checked"<?php } ?> name="p[SMTP]" value="1">
                                                        <span></span>
                                                    </label>
                                                </span>
                                            </div>
                                            <label class="col-lg-2 col-form-label">SMTP Port:</label>
                                            <div class="col-lg-5">
                                                <input type="text" class="form-control" value="<?php echo $data["SMTPport"];?>" name="p[SMTPport]" placeholder="587">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-2 col-form-label">SMTP Host:</label>
                                            <div class="col-lg-9">
                                                <input type="text" class="form-control" value="<?php echo $data["SMTPhost"];?>" name="p[SMTPhost]">
                                                <div class="help-block">İster IP ister domain yazabilirsiniz.</div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-2 col-form-label">SMTP Kullanıcı Adı:</label>
                                            <div class="col-lg-9">
                                                <input type="text" class="form-control" value="<?php echo $data["SMTPusername"];?>" name="p[SMTPusername]">
                                                <div class="help-block">Mail adresi girilir.</div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-2 col-form-label">SMTP Şifre:</label>
                                            <div class="col-lg-9">
                                                <input type="text" class="form-control" value="<?php echo $data["SMTPpassword"];?>" name="p[SMTPpassword]">
                                                <div class="help-block">Mail adresinin şifresidir.</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="sms" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group row">
                                            <label class="col-lg-2 col-form-label">SMS Başlığı:</label>
                                            <div class="col-lg-9">
                                                <input type="text" class="form-control" value="<?php echo $data["sms_header"];?>" name="p[sms_header]">
                                                <div class="help-block">Özel başlık yok ise size verilen 08 ile başlayan numarayı giriniz.</div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-2 col-form-label">Kullanıcı Adı:</label>
                                            <div class="col-lg-9">
                                                <input type="text" class="form-control" value="<?php echo $data["sms_username"];?>" name="p[sms_username]">
                                                <div class="help-block">NetGSM'e kayıt olunan cep numaranız.</div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-2 col-form-label">Şifre:</label>
                                            <div class="col-lg-9">
                                                <input type="text" class="form-control" value="<?php echo $data["sms_password"];?>" name="p[sms_password]">
                                                <div class="help-block">NetGSM şifresidir.</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="sosyalmedya" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group row">
                                            <label class="col-lg-2 col-form-label">Facebook:</label>
                                            <div class="col-lg-9">
                                                <input type="text" class="form-control" value="<?php echo $data["facebook"];?>" name="p[facebook]">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-2 col-form-label">Twitter:</label>
                                            <div class="col-lg-9">
                                                <input type="text" class="form-control" value="<?php echo $data["twitter"];?>" name="p[twitter]">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-2 col-form-label">Youtube:</label>
                                            <div class="col-lg-9">
                                                <input type="text" class="form-control" value="<?php echo $data["google"];?>" name="p[google]">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-2 col-form-label">Instagram:</label>
                                            <div class="col-lg-9">
                                                <input type="text" class="form-control" value="<?php echo $data["instagram"];?>" name="p[instagram]">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="mail" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group row">
                                            <label class="col-lg-2 control-label">Yeni Üyelik Mail Başlığı</label>
                                            <div class="col-lg-10">
                                                <input type="text" name="p[newuser_header]" class="form-control" value="<?php echo $data["newuser_header"];?>">
                                                <div class="help-block">Kullanılabilen değişkenler : {{NAME}}, {{USERNAME}}</div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-2 control-label">Yeni Üyelik Mail İçeriği</label>
                                            <div class="col-lg-10">
                                                <textarea rows="7" class="form-control" name="p[newuser_html]"><?php echo $data["newuser_html"];?></textarea>
                                                <div class="help-block">Kullanılabilen değişkenler : {{NAME}}, {{USERNAME}}, {{PASSWORD}}, {{LOGIN_URL}}, {{LOGO}}</div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-2 control-label">Şifremi Unutum Mail Başlığı</label>
                                            <div class="col-lg-10">
                                                <input type="text" name="p[forgetpassword_header]" class="form-control" value="<?php echo $data["forgetpassword_header"];?>">
                                                <div class="help-block">Kullanılabilen değişkenler :{{NAME}}, {{USERNAME}}</div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-2 control-label">Şifremi Unutum Mail İçeriği</label>
                                            <div class="col-lg-10">
                                                <textarea rows="7" class="form-control" name="p[forgetpassword_html]"><?php echo $data["forgetpassword_html"];?></textarea>
                                                <div class="help-block">Kullanılabilen değişkenler : {{NAME}}, {{USERNAME}}, {{PASSWORD}}, {{LOGIN_URL}}, {{LOGO}}</div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-2 control-label">Peyiniz Geçildi Mail Başlığı</label>
                                            <div class="col-lg-10">
                                                <input type="text" name="p[peygecildi_header]" class="form-control" value="<?php echo $data["peygecildi_header"];?>">
                                                <div class="help-block">Kullanılabilen değişkenler : {{NAME}}, {{MEZAT}}, {{LOTNO}}, {{PEY}}, {{URUNADI}}, {{FIYAT}}, {{GUNCELFIYAT}}, {{URL}}</div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-2 control-label">Peyiniz Geçildi Mail İçeriği</label>
                                            <div class="col-lg-10">
                                                <textarea rows="7" class="form-control" name="p[peygecildi_html]"><?php echo $data["peygecildi_html"];?></textarea>
                                                <div class="help-block">Kullanılabilen değişkenler : {{NAME}}, {{MEZAT}}, {{LOTNO}}, {{PEY}}, {{URUNADI}}, {{FIYAT}}, {{GUNCELFIYAT}}, {{URL}}, {{LOGO}}</div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-2 control-label">Müzayede Başladı Mail Başlığı</label>
                                            <div class="col-lg-10">
                                                <input type="text" name="p[basladi_header]" class="form-control" value="<?php echo $data["basladi_header"];?>">
                                                <div class="help-block">Kullanılabilen değişkenler : {{DATE}}, {{NAME}}, {{MEZAT}}</div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-2 control-label">Müzayede Başladı Mail İçeriği</label>
                                            <div class="col-lg-10">
                                                <textarea rows="7" class="form-control" name="p[basladi_html]"><?php echo $data["basladi_html"];?></textarea>
                                                <div class="help-block">Kullanılabilen değişkenler : {{DATE}}, {{TIME}}, {{ENDTIME}}, {{NAME}}, {{MEZAT}}, {{CANLITIME}}, {{LOTTIME}}, {{URL}}, {{LOGO}} </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-2 control-label">Alarm Sistemi Mail Başlığı</label>
                                            <div class="col-lg-10">
                                                <input type="text" name="p[alarm_header]" class="form-control" value="<?php echo $data["alarm_header"];?>">
                                                <div class="help-block">Kullanılabilen değişkenler : {{NAME}}, {{KEYWORD}}</div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-2 control-label">Alarm Sistemi Mail İçeriği</label>
                                            <div class="col-lg-10">
                                                <textarea rows="7" class="form-control" name="p[alarm_html]"><?php echo $data["alarm_html"];?></textarea>
                                                <div class="help-block">Kullanılabilen değişkenler : {{NAME}}, {{KEYWORD}}, {{PRODUCTS}}, {{URL}}, {{LOGO}} </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="mesaj" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group row">
                                            <label class="col-lg-2 control-label">Müzayede Başlangıç (Tüm Üyeler)</label>
                                            <div class="col-lg-10">
                                                <textarea rows="7" class="form-control" name="p[sms_all]"><?php echo $data["sms_all"];?></textarea>
                                                <div class="help-block">Kullanılabilen değişkenler : {{DATE}}, {{MEZAT}}, {{URL}}</div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-2 control-label">Müzayede Bitiş (Tüm Üyeler)</label>
                                            <div class="col-lg-10">
                                                <textarea rows="7" class="form-control" name="p[sms_pey]"><?php echo $data["sms_pey"];?></textarea>
                                                <div class="help-block">Kullanılabilen değişkenler : {{NAME}}, {{DATE}}, {{MEZAT}}, {{URL}}</div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-2 control-label">Canlıda Uyar</label>
                                            <div class="col-lg-10">
                                                <textarea rows="7" class="form-control" name="p[sms_wait_live]"><?php echo $data["sms_wait_live"];?></textarea>
                                                <div class="help-block">Kullanılabilen değişkenler : {{NAME}}, {{LOT}}, {{URL}}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="soz" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group row">
                                            <label class="col-lg-2 control-label">KVKK Sözleşmesi</label>
                                            <div class="col-lg-10">
                                                <textarea rows="7" class="form-control summernote" name="p[kvkk]"><?php echo $data["kvkk"];?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
            <div class="kt-portlet__foot">
                <div class="kt-form__actions">
                    <div class="row">
                        <div class="col-lg-3"></div>
                        <div class="col-lg-6">
                            <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> KAYDET</button>

                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
    $('select').select2();
</script>