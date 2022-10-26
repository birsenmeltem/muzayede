<?php if(!class_exists('view')){exit;}?><div class="kt-subheader  kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title">Raporlar</h3>
        </div>
        <div class="kt-subheader__toolbar">

        </div>
    </div>
</div>

<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
    <?php echo $info;?>
    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon">
                    <i class="kt-font-brand fas fa-chart-bar"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    Müzayede Sonuç Raporu
                </h3>
            </div>
            <div class="kt-portlet__head-toolbar">
                <div class="kt-portlet__head-wrapper">

                </div>
            </div>
        </div>

        <div class="kt-portlet__body">
            <!--begin: Search Form -->

            <div class="kt-form kt-form--label-right kt-margin-t-20 kt-margin-b-10">
                <form method="post" class="kt-form">
                    <div class="row align-items-center">
                        <div class="col-xl-12 order-2 order-xl-12">
                            <div class="row align-items-center">
                                <div class="col-md-4 kt-margin-b-20-tablet-and-mobile">
                                    <div class="kt-form__group ">
                                        <div class="kt-form__label">
                                            <label>Müzayede:</label>
                                        </div>
                                        <div class="kt-form__control">
                                            <select class="form-control"  name="f[auction_id]">
                                                <option value="0">Seçiniz</option>
                                                <?php $counter1=-1; if( isset($auctions) && is_array($auctions) && sizeof($auctions) ) foreach( $auctions as $key1 => $value1 ){ $counter1++; ?>
                                                <option value="<?php echo $value1["id"];?>" <?php if( $f["auction_id"] == $value1["id"] ){ ?> selected <?php } ?>><?php echo $value1["id"];?> - <?php echo $value1["name"];?> (<?php echo $value1["start_date"];?> - <?php echo $value1["end_date"];?>)</option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2 kt-margin-t-20">
                                    <div class="kt-form__group kt-form__group--inline">
                                        <div class="kt-form__label">

                                        </div>
                                        <div class="kt-form__control">
                                            <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> ARA</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>        <!--end: Search Form -->
        </div>
    </div>

    <?php if( $data["status"] ){ ?>
    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon">
                    <i class="kt-font-brand fas fa-chart-bar"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    <?php echo $data["name"];?> Rapor Sonucu
                </h3>
            </div>
        </div>

        <div class="kt-portlet__body">
            <div class="row">
                <div class="col-xl-6 col-lg-6 order-lg-2 order-xl-1">
                    <div class="kt-portlet--height-fluid">
                        <div class="kt-widget14">
                            <div class="kt-widget14__header">
                                <h3 class="kt-widget14__title">
                                    Müzayede İstatistikleri
                                </h3>
                            </div>
                            <div class="kt-widget14__content">
                                <div class="kt-widget14__chart">
                                    <div class="kt-widget14__stat"></div>
                                    <canvas id="chart_profit_share" style="height: 140px; width: 240px;"></canvas>
                                </div>
                                <div class="kt-widget14__legends">
                                    <div class="kt-widget14__legend">
                                        <span class="kt-widget14__bullet kt-bg-success"></span>
                                        <span class="kt-widget14__stats">Kaç Farklı Üye Pey Verdi (<?php echo $data["farkUye"];?> Adet)</span>
                                    </div>
                                    <div class="kt-widget14__legend">
                                        <span class="kt-widget14__bullet kt-bg-danger"></span>
                                        <span class="kt-widget14__stats">Kaç Farklı Üye Ürün Kazandı (<?php echo $data["uyeBuy"];?> Adet)</span>
                                    </div>
                                    <div class="kt-widget14__legend">
                                        <span class="kt-widget14__bullet kt-bg-info"></span>
                                        <span class="kt-widget14__stats">Kaç Farklı Üye Ürün Sattı (<?php echo $data["uyeSell"];?> Adet)</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6 order-lg-2 order-xl-1">
                    <div class="kt-portlet--height-fluid">
                        <div class="kt-widget14">
                            <div class="kt-widget14__header">
                                <h3 class="kt-widget14__title">
                                    Müzayede Toplam Ciro
                                </h3>
                            </div>
                            <div class="kt-widget14__content">
                                <div class="kt-widget14__chart">
                                    <div class="kt-widget14__stat"></div>
                                    <div id="chart_revenue_change" style="height: 150px; width: 150px;"></div>
                                </div>
                                <div class="kt-widget14__legends">
                                    <div class="kt-widget14__legend">
                                        <span class="kt-widget14__bullet kt-bg-success"></span>
                                        <span class="kt-widget14__stats">Toplam Ciro : <?php echo numbers( $data["ciro"] );?> TL</span>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <script type="text/javascript">
                var config = {
                    type: 'doughnut',
                    data: {
                        datasets: [{
                            data: [
                                '<?php echo $data["farkUye"];?>','<?php echo $data["uyeBuy"];?>','<?php echo $data["uyeSell"];?>'
                            ],
                            backgroundColor: [
                                '#34bfa3',
                                '#fd3995',
                                '#5d78ff',
                                '#ffb822',
                                '#36a3f7'
                            ]
                        }],
                        labels: [
                            'Kaç Farklı Üye Pey Verdi', 'Kaç Farklı Üye Ürün Aldı', 'Kaç Farklı Üye Ürün Sattı'
                        ]
                    },
                    options: {
                        cutoutPercentage: 75,
                        responsive: true,
                        maintainAspectRatio: false,
                        legend: {
                            display: false,
                            position: 'top',
                        },
                        title: {
                            display: false,
                            text: 'Technology'
                        },
                        animation: {
                            animateScale: true,
                            animateRotate: true
                        },
                        tooltips: {
                            enabled: true,
                            intersect: false,
                            mode: 'nearest',
                            bodySpacing: 5,
                            yPadding: 10,
                            xPadding: 10,
                            caretPadding: 0,
                            displayColors: false,
                            backgroundColor: '#5d78ff',
                            titleFontColor: '#ffffff',
                            cornerRadius: 4,
                            footerSpacing: 0,
                            titleSpacing: 0
                        }
                    }
                };
                var ctx = $('#chart_profit_share')[0].getContext('2d');
                var myDoughnut = new Chart(ctx, config);

                Morris.Donut({
                    element: 'chart_revenue_change',
                    data: [{"label":"Toplam Ciro","value":"<?php echo numbers( $data["ciro"] );?>"}],
                    colors: [
                        '#34bfa3',
                    ],
                });
            </script>
            <hr>
            <div class="row">
                <div class="col-md-6">
                    <h5>Pey Verenler</h5>
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Son Pey Zamanı</th>
                                <th>Üye Kullanıcı Adı</th>
                                <th>Üye Adı Soyadı</th>
                                <th>Telefon</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $counter1=-1; if( isset($data["peys"]) && is_array($data["peys"]) && sizeof($data["peys"]) ) foreach( $data["peys"] as $key1 => $value1 ){ $counter1++; ?>
                            <tr>
                                <td><?php echo $value1["create_date"];?></td>
                                <td><?php echo $value1["username"];?></td>
                                <td><?php echo $value1["name"];?> <?php echo $value1["surname"];?></td>
                                <td><?php echo $value1["phone"];?></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-6">
                    <h5>Ürün Takip Edenler</h5>
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Lot Adeti</th>
                                <th>Üye Kullanıcı Adı</th>
                                <th>Üye Adı Soyadı</th>
                                <th>Telefon</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $counter1=-1; if( isset($data["follows"]) && is_array($data["follows"]) && sizeof($data["follows"]) ) foreach( $data["follows"] as $key1 => $value1 ){ $counter1++; ?>
                            <tr>
                                <td><?php echo $value1["total"];?></td>
                                <td><?php echo $value1["username"];?></td>
                                <td><?php echo $value1["name"];?> <?php echo $value1["surname"];?></td>
                                <td><?php echo $value1["phone"];?></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">

                <div class="col-md-6">
                    <h5>Ürün Kazananlar</h5>
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Lot Adeti</th>
                                <th>ID</th>
                                <th>Üye Adı Soyadı</th>
                                <th>Bakiye</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $counter1=-1; if( isset($data["buyer"]) && is_array($data["buyer"]) && sizeof($data["buyer"]) ) foreach( $data["buyer"] as $key1 => $value1 ){ $counter1++; ?>
                            <tr>
                                <td><?php echo $value1["total"];?></td>
                                <td><?php echo $value1["id"];?></td>
                                <td><?php echo $value1["name"];?> <?php echo $value1["surname"];?></td>
                                <td><?php echo numbers( $value1["total_price"] );?> TL</td>
                                <td>
                                    <a href="<?php  echo BASEURL;?>crons/manuelbuyer/<?php echo $data["id"];?>/<?php echo $value1["id"];?>" target="_blank" class="btn btn-primary btn-sm">PDF</a>
                                    <a href="javascript:;" data-a="<?php echo $data["id"];?>" data-u="<?php echo $value1["id"];?>" data-t="0" data-invoice="true" class="btn btn-warning btn-sm">FATURA</a>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-6">
                    <h5>Ürün Satanlar</h5>
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Lot Adeti</th>
                                <th>ID</th>
                                <th>Üye Adı Soyadı</th>
                                <th>Bakiye</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $counter1=-1; if( isset($data["seller"]) && is_array($data["seller"]) && sizeof($data["seller"]) ) foreach( $data["seller"] as $key1 => $value1 ){ $counter1++; ?>
                            <tr>
                                <td><?php echo $value1["total"];?></td>
                                <td><?php echo $value1["id"];?></td>
                                <td><?php echo $value1["name"];?> <?php echo $value1["surname"];?></td>
                                <td><?php echo numbers( $value1["total_price"] );?> TL</td>
                                <td>
                                    <a href="<?php  echo BASEURL;?>crons/manuelseller/<?php echo $data["id"];?>/<?php echo $value1["id"];?>" target="_blank" class="btn btn-primary btn-sm">PDF</a>
                                    <a href="javascript:;" data-a="<?php echo $data["id"];?>" data-u="<?php echo $value1["id"];?>" data-t="1" data-invoice="true" class="btn btn-warning btn-sm">FATURA</a>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="kt-portlet__foot">
            <div class="kt-form__actions">
                <div class="row">
                    <div class="col-lg-3"></div>
                    <div class="col-lg-9 text-right">
                        <a href="javascript:;" onclick="openWinBuyer('<?php echo $data["id"];?>')" class="btn btn-warning"><i class="la la-print"></i> Ürün Alanlar PDF</a>
                        <a href="javascript:;" onclick="openWinSeller('<?php echo $data["id"];?>')"  class="btn btn-danger"><i class="la la-print"></i> Ürün Satanlar PDF</a>
                        <a href="<?php  echo ADMINBASEURL;?>reports/aresult_buyer?<?php echo $querystring;?>" target="_blank" class="btn btn-success btn-customer-export"><i class="la la-print"></i> Ürün Alanlar Liste</a>
                        <a href="<?php  echo ADMINBASEURL;?>reports/aresult_seller?<?php echo $querystring;?>" target="_blank" class="btn btn-info btn-customer-export"><i class="la la-print"></i> Ürün Satanlar Liste</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>
</div>
<script type="text/javascript">
    $('select').select2();
    $('.dtrange').daterangepicker({
        buttonClasses: ' btn',
        applyClass: 'btn-primary',
        cancelClass: 'btn-secondary'
        }, function(start, end, label) {
            $('.dtrange .form-control').val( start.format('DD.MM.YYYY') + ' / ' + end.format('DD.MM.YYYY'));
    });

</script>
