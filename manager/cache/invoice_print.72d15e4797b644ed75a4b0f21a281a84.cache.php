<?php if(!class_exists('view')){exit;}?><!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><title>
            Sipariş Faturaları
        </title>
        <script src="https://code.jquery.com/jquery-1.11.3.js"></script>


        <style type="text/css" media="print">
            @page {
                size: auto /* auto is the initial value */
                margin: 0mm; /* this affects the margin in the printer settings */
            }

            html {
                background-color: #FFFFFF;
                margin: 0mm; /* this affects the margin on the html before sending to printer */
            }

            body {
                border: 0mm;
                margin: 0mm; /* margin you want for the content */
                font-family: 'Arial' !important;
                font-size: 10px !important;
            }
            @media print{
                @page {
                    size: landscape
                }
            }

        </style>
    </head>

    <body style="margin:0px; padding:0px;">
        <form method="post" id="ctl01">

            <div style="font-size: 10px;">
                <div style='padding:0mm; margin:0mm;width:297mm; height:200mm; overflow:hidden; float:left;'>
                    <div style='width:100%; height:100%; position:relative;'>
                        <div id='unvan1' style='position:absolute; top:38mm; left:3mm;width: 50mm; height: 20mm;' class='ui-widget-content element'><?php echo $data["billing"]["name"];?></div>
                        <div id='unvan2' style='position:absolute; top:38mm; left:77mm;width: 50mm; height: 20mm;' class='ui-widget-content element'><?php echo $data["billing"]["name"];?></div>
                        <div id='unvan3' style='position:absolute; top:39mm; left:153mm;width: 50mm; height: 20mm;' class='ui-widget-content element'><?php echo $data["billing"]["name"];?></div>
                        <div id='unvan4' style='position:absolute; top:38mm; left:226mm;width: 50mm; height: 20mm;' class='ui-widget-content element'><?php echo $data["billing"]["name"];?></div>
                        <div id='adres1' style='position:absolute; top:47mm; left:3mm;width: 36mm; height: 10mm;' class='ui-widget-content element'><?php echo $data["billing"]["address"];?> <?php echo $data["billing"]["state"];?> / <?php echo $data["billing"]["city_name"];?> / <?php echo $data["billing"]["country_name"];?><br /><?php echo $data["billing"]["tax_name"];?> <?php echo $data["billing"]["tax_no"];?></div>
                        <div id='adres2' style='position:absolute; top:47mm; left:77mm;width: 35mm; height: 9mm;' class='ui-widget-content element'><?php echo $data["billing"]["address"];?> <?php echo $data["billing"]["state"];?> / <?php echo $data["billing"]["city_name"];?> / <?php echo $data["billing"]["country_name"];?><br /><?php echo $data["billing"]["tax_name"];?> <?php echo $data["billing"]["tax_no"];?></div>
                        <div id='adres3' style='position:absolute; top:48mm; left:154mm;width: 33mm; height: 10mm;' class='ui-widget-content element'><?php echo $data["billing"]["address"];?> <?php echo $data["billing"]["state"];?> / <?php echo $data["billing"]["city_name"];?> / <?php echo $data["billing"]["country_name"];?><br /><?php echo $data["billing"]["tax_name"];?> <?php echo $data["billing"]["tax_no"];?></div>
                        <div id='adres4' style='position:absolute; top:47mm; left:226mm;width: 35mm; height: 10mm;' class='ui-widget-content element'><?php echo $data["billing"]["address"];?> <?php echo $data["billing"]["state"];?> / <?php echo $data["billing"]["city_name"];?> / <?php echo $data["billing"]["country_name"];?><br /><?php echo $data["billing"]["tax_name"];?> <?php echo $data["billing"]["tax_no"];?></div>
                        <div id='tarih1' style='position:absolute; top:49mm; left:47mm;width: 20mm; height: 5mm;' class='ui-widget-content element'><?php echo $data["create_date"];?></div>
                        <div id='tarih2' style='position:absolute; top:48mm; left:123mm;width: 20mm; height: 5mm;' class='ui-widget-content element'><?php echo $data["create_date"];?></div>
                        <div id='tarih3' style='position:absolute; top:49mm; left:195mm;width: 20mm; height: 5mm;' class='ui-widget-content element'><?php echo $data["create_date"];?></div>
                        <div id='tarih4' style='position:absolute; top:48mm; left:270mm;width: 20mm; height: 5mm;' class='ui-widget-content element'><?php echo $data["create_date"];?></div>
                        <div id='vd1' style='position:absolute; top:62mm; left:85mm;width: 40mm; height: 5mm;' class='ui-widget-content element'></div>
                        <div id='vd2' style='position:absolute; top:62mm; left:234mm;width: 40mm; height: 5mm;' class='ui-widget-content element'></div>
                        <div id='vd3' style='position:absolute; top:62mm; left:14mm;width: 40mm; height: 5mm;' class='ui-widget-content element'></div>
                        <div id='vd4' style='position:absolute; top:63mm; left:160mm;width: 40mm; height: 5mm;' class='ui-widget-content element'></div>
                        <div id='vno1' style='position:absolute; top:62mm; left:262mm;width: 40mm; height: 5mm;' class='ui-widget-content element'></div>
                        <div id='vno2' style='position:absolute; top:61mm; left:34mm;width: 40mm; height: 5mm;' class='ui-widget-content element'></div>
                        <div id='vno3' style='position:absolute; top:61mm; left:105mm;width: 40mm; height: 5mm;' class='ui-widget-content element'></div>
                        <div id='vno4' style='position:absolute; top:63mm; left:182mm;width: 40mm; height: 5mm;' class='ui-widget-content element'></div>
                        <div id='toplamTutar1' style='position:absolute; top:170mm; left:209mm;width: 13mm; height: 5mm;' class='ui-widget-content element'><?php echo $data["total_kdvsiz"];?></div>
                        <div id='toplamTutar2' style='position:absolute; top:172mm; left:58mm;width: 13mm; height: 5mm;' class='ui-widget-content element'><?php echo $data["total_kdvsiz"];?></div>
                        <div id='toplamTutar3' style='position:absolute; top:169mm; left:136mm;width: 13mm; height: 5mm;' class='ui-widget-content element'><?php echo $data["total_kdvsiz"];?></div>
                        <div id='toplamTutar4' style='position:absolute; top:170mm; left:281mm;width: 13mm; height: 5mm;' class='ui-widget-content element'><?php echo $data["total_kdvsiz"];?></div>
                        <div id='toplamKDV1' style='position:absolute; top:173mm; left:136mm;width: 13mm; height: 5mm;' class='ui-widget-content element'><?php echo $data["total_kdv"];?></div>
                        <div id='toplamKDV2' style='position:absolute; top:175mm; left:58mm;width: 13mm; height: 5mm;' class='ui-widget-content element'><?php echo $data["total_kdv"];?></div>
                        <div id='toplamKDV3' style='position:absolute; top:174mm; left:210mm;width: 13mm; height: 5mm;' class='ui-widget-content element'><?php echo $data["total_kdv"];?></div>
                        <div id='toplamKDV4' style='position:absolute; top:174mm; left:280mm;width: 13mm; height: 5mm;' class='ui-widget-content element'><?php echo $data["total_kdv"];?></div>
                        <div id='genelToplam1' style='position:absolute; top:178mm; left:209mm;width: 13mm; height: 5mm;' class='ui-widget-content element'><?php echo $data["price"];?></div>
                        <div id='genelToplam2' style='position:absolute; top:179mm; left:58mm;width: 13mm; height: 5mm;' class='ui-widget-content element'><?php echo $data["price"];?></div>
                        <div id='genelToplam3' style='position:absolute; top:177mm; left:136mm;width: 13mm; height: 5mm;' class='ui-widget-content element'><?php echo $data["price"];?></div>
                        <div id='genelToplam4' style='position:absolute; top:178mm; left:280mm;width: 13mm; height: 5mm;' class='ui-widget-content element'><?php echo $data["price"];?></div>
                        <div id='genelToplamYazi1' style='position:absolute; top:171mm; left:153mm;width: 42mm; height: 12mm;' class='ui-widget-content element'><?php echo $data["yaziyla"];?></div>
                        <div id='genelToplamYazi2' style='position:absolute; top:170mm; left:80mm;width: 40mm; height: 10mm;' class='ui-widget-content element'><?php echo $data["yaziyla"];?></div>
                        <div id='genelToplamYazi3' style='position:absolute; top:171mm; left:5mm;width: 43mm; height: 10mm;' class='ui-widget-content element'><?php echo $data["yaziyla"];?></div>
                        <div id='genelToplamYazi4' style='position:absolute; top:170mm; left:229mm;width: 43mm; height: 10mm;' class='ui-widget-content element'><?php echo $data["yaziyla"];?></div>
                        <div id='urunAdi1' style='position:absolute; overflow:hidden; top:77mm; left:6mm;width: 50mm; min-height: 1mm;' class='ui-widget-content element'><?php echo $data["pro_names"];?></div>
                        <div id='urunAdi2' style='position:absolute; overflow:hidden; top:76mm; left:154mm;width: 50mm; min-height: 1mm;' class='ui-widget-content element'><?php echo $data["pro_names"];?></div>
                        <div id='urunAdi3' style='position:absolute; overflow:hidden; top:77mm; left:79mm;width: 50mm; min-height: 1mm;' class='ui-widget-content element'><?php echo $data["pro_names"];?></div>
                        <div id='urunAdi4' style='position:absolute; overflow:hidden; top:77mm; left:229mm;width: 50mm; min-height: 1mm;' class='ui-widget-content element'><?php echo $data["pro_names"];?></div>
                        <div id='urunMiktar1' style='position:absolute; top:98mm; left:255mm;width: 20mm; height: 1mm;' class='ui-widget-content element'><?php echo $data["pro_quan"];?></div>
                        <div id='urunMiktar2' style='position:absolute; top:98mm; left:29mm;width: 20mm; height: 1mm;' class='ui-widget-content element'><?php echo $data["pro_quan"];?></div>
                        <div id='urunMiktar3' style='position:absolute; top:99mm; left:106mm;width: 20mm; height: 1mm;' class='ui-widget-content element'><?php echo $data["pro_quan"];?></div>
                        <div id='urunMiktar4' style='position:absolute; top:98mm; left:180mm;width: 20mm; height: 1mm;' class='ui-widget-content element'><?php echo $data["pro_quan"];?></div>
                        <div id='urunBirimFiyati1' style='position:absolute; top:99mm; left:119mm;width: 11mm; height: 1mm;' class='ui-widget-content element'><?php echo $data["pro_price"];?><br /></div>
                        <div id='urunBirimFiyati2' style='position:absolute; top:98mm; left:266mm;width: 11mm; height: 1mm;' class='ui-widget-content element'><?php echo $data["pro_price"];?><br /></div>
                        <div id='urunBirimFiyati3' style='position:absolute; top:99mm; left:42mm;width: 11mm; height: 1mm;' class='ui-widget-content element'><?php echo $data["pro_price"];?><br /></div>
                        <div id='urunBirimFiyati4' style='position:absolute; top:98mm; left:192mm;width: 11mm; height: 1mm;' class='ui-widget-content element'><?php echo $data["pro_price"];?><br /></div>
                        <div id='urunTutari1' style='position:absolute; top:98mm; left:282mm;width: 11mm; height: 1mm;' class='ui-widget-content element'><?php echo $data["pro_tprice"];?><br /></div>
                        <div id='urunTutari2' style='position:absolute; top:99mm; left:58mm;width: 11mm; height: 1mm;' class='ui-widget-content element'><?php echo $data["pro_tprice"];?><br /></div>
                        <div id='urunTutari3' style='position:absolute; top:99mm; left:135mm;width: 11mm; height: 1mm;' class='ui-widget-content element'><?php echo $data["pro_tprice"];?><br /></div>
                        <div id='urunTutari4' style='position:absolute; top:98mm; left:207mm;width: 11mm; height: 1mm;' class='ui-widget-content element'><?php echo $data["pro_tprice"];?><br /></div>
                    </div>
                </div>
            </div>
        </form>
        <script>
            $(document).ready(function () {
                window.print();
            });
        </script>
    </body>
</html>
