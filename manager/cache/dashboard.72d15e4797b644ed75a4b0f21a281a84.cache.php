<?php if(!class_exists('view')){exit;}?><!-- begin:: Content Head -->
<div class="kt-subheader  kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title">Kontrol Paneli</h3>
        </div>
        <div class="kt-subheader__toolbar">
            <div class="kt-header__topbar-item kt-header__topbar-item--user">
                <div class="kt-header__topbar-wrapper" data-toggle="dropdown" data-offset="0px,0px">
                    <div class="kt-header__topbar-user btn btn-danger">
                        <span class="kt-header__topbar-welcome">Online Kullanıcı : </span>
                        <strong class="kt-header__topbar-username"><?php echo $data["canli"];?></strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end:: Content Head -->
<!-- begin:: Content -->
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
    <div class="row">
        <div class="col-xl-4 col-lg-4 order-lg-2 order-xl-1">

            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-widget14">
                    <div class="kt-widget14__header">
                        <h3 class="kt-widget14__title">
                            Aktif Müzayedeler
                        </h3>
                        <span class="kt-widget14__desc">
                            anlık peylerin toplamı
                        </span>
                    </div>
                    <div class="kt-widget14__content">
                        <div class="kt-widget14__chart">
                            <div class="kt-widget14__stat"><?php echo $data["aktifmuzTotal"];?></div>
                            <canvas id="chart_profit_share1" style="height: 140px; width: 140px;"></canvas>
                        </div>
                        <div class="kt-widget14__legends">
                            <?php $counter1=-1; if( isset($data["aktifmuz"]) && is_array($data["aktifmuz"]) && sizeof($data["aktifmuz"]) ) foreach( $data["aktifmuz"] as $key1 => $value1 ){ $counter1++; ?>

                            <div class="kt-widget14__legend">
                                <span class="kt-widget14__bullet kt-bg-<?php echo $bgcolor[ $counter1];?>"></span>
                                <span class="kt-widget14__stats"><?php echo $key1;?> (<?php echo $value1;?> TL)</span>
                            </div>
                            <?php } ?>

                        </div>
                    </div>
                </div>
            </div>

            <!--
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-widget14">
                    <div class="kt-widget14__header kt-margin-b-30">
                        <h3 class="kt-widget14__title">
                            Günlük Satışlar (TL)
                        </h3>
                        <span class="kt-widget14__desc">
                            son 15 günün satış grafiği
                        </span>
                    </div>
                    <div class="kt-widget14__chart" style="height:120px;">
                        <canvas id="chart_daily_sales"></canvas>
                    </div>
                </div>
            </div>

            -->
        </div>
        <div class="col-xl-4 col-lg-4 order-lg-2 order-xl-1">

            <!--begin:: Widgets/Profit Share-->
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-widget14">
                    <div class="kt-widget14__header">
                        <h3 class="kt-widget14__title">
                            Bu Ay Aktif Üyeler
                        </h3>
                        <span class="kt-widget14__desc">
                            en aktif pey veren üyeler
                        </span>
                    </div>
                    <div class="kt-widget14__content">
                        <div class="kt-widget14__chart">
                            <div class="kt-widget14__stat"><?php echo $data["aktifTotal"];?></div>
                            <canvas id="chart_profit_share" style="height: 140px; width: 140px;"></canvas>
                        </div>
                        <div class="kt-widget14__legends">
                            <?php $counter1=-1; if( isset($data["aktifweb"]) && is_array($data["aktifweb"]) && sizeof($data["aktifweb"]) ) foreach( $data["aktifweb"] as $key1 => $value1 ){ $counter1++; ?>

                            <div class="kt-widget14__legend">
                                <span class="kt-widget14__bullet kt-bg-<?php echo $bgcolor[ $counter1];?>"></span>
                                <span class="kt-widget14__stats"><?php echo $key1;?> (<?php echo $value1;?> Adet)</span>
                            </div>
                            <?php } ?>

                        </div>
                    </div>
                </div>
            </div>

            <!--end:: Widgets/Profit Share-->
        </div>
        <div class="col-xl-4 col-lg-4 order-lg-2 order-xl-1">

            <!--begin:: Widgets/Revenue Change-->
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-widget14">
                    <div class="kt-widget14__header">
                        <h3 class="kt-widget14__title">
                            Bu Ayki Peyler
                        </h3>
                        <span class="kt-widget14__desc">
                            bu ay en çok pey verilen ürünler
                        </span>
                    </div>
                    <div class="kt-widget14__content">
                        <div class="kt-widget14__chart">
                            <div id="chart_revenue_change" style="height: 150px; width: 150px;"></div>
                        </div>
                        <div class="kt-widget14__legends">
                            <?php $counter1=-1; if( isset($data["aktifsale"]) && is_array($data["aktifsale"]) && sizeof($data["aktifsale"]) ) foreach( $data["aktifsale"] as $key1 => $value1 ){ $counter1++; ?>

                            <div class="kt-widget14__legend">
                                <span class="kt-widget14__bullet kt-bg-<?php echo $color[ $counter1];?>"></span>
                                <span class="kt-widget14__stats"><?php echo $value1["value"];?> <?php echo $value1["c"];?> - <?php echo $value1["label"];?></span>
                            </div>
                            <?php } ?>


                        </div>
                    </div>
                </div>
            </div>

            <!--end:: Widgets/Revenue Change-->
        </div>
        <div class="col-xl-8 col-lg-9 order-lg-6 order-xl-1">

            <!--begin:: Widgets/Tasks -->
            <div class="kt-portlet kt-portlet--tabs kt-portlet--height-fluid">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Siparişler ( Son 10 )
                        </h3>
                    </div>

                </div>
                <div class="kt-portlet__body">
                    <div class="tab-content">
                        <div class="tab-pane active" id="kt_widget2_tab1_content">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Tarih</th>
                                            <th>Sipariş Kodu</th>
                                            <th>Ödeme Türü</th>
                                            <th>Müşteri</th>
                                            <th>Sipariş Tutarı</th>
                                            <th>Durum</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $counter1=-1; if( isset($data["sonSatis"]) && is_array($data["sonSatis"]) && sizeof($data["sonSatis"]) ) foreach( $data["sonSatis"] as $key1 => $value1 ){ $counter1++; ?>

                                        <tr>
                                            <td><?php echo $value1["create_date"];?></td>
                                            <td><?php echo $value1["code"];?></td>
                                            <td><?php echo $value1["payment_type_name"];?></td>
                                            <td><?php echo $value1["name"];?></td>
                                            <td><?php echo $value1["price"];?></td>
                                            <td><?php echo $value1["status_name"];?></td>
                                            <td><a href="<?php  echo ADMINBASEURL;?>orders/edit/<?php echo $value1["id"];?>" class="btn btn-primary btn-sm" title="detay"><i class="fa fa-eye"></i></a></td>
                                        </tr>
                                        <?php } ?>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!--end:: Widgets/Tasks -->
        </div>
        <div class="col-xl-4 col-lg-3 order-lg-6 order-xl-1">

            <!--begin:: Widgets/Notifications-->
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Peyler ( Son 10)
                        </h3>
                    </div>
                    <div class="kt-portlet__head-toolbar">

                    </div>
                </div>
                <div class="kt-portlet__body">
                    <div class="tab-content">
                        <div class="tab-pane active" id="kt_widget6_tab1_content" aria-expanded="true">
                            <div class="kt-notification">
                                <?php $counter1=-1; if( isset($data["sonOdeme"]) && is_array($data["sonOdeme"]) && sizeof($data["sonOdeme"]) ) foreach( $data["sonOdeme"] as $key1 => $value1 ){ $counter1++; ?>

                                <a href="<?php  echo ADMINBASEURL;?>peyler" class="kt-notification__item">
                                    <div class="kt-notification__item-icon">
                                        <i class="fas fa-comments"></i>
                                    </div>
                                    <div class="kt-notification__item-details">
                                        <div class="kt-notification__item-title">
                                            <?php echo $value1["price"];?> TL ( <?php echo $value1["name"];?> )
                                        </div>
                                        <div class="kt-notification__item-time">
                                            <?php echo $value1["product_name"];?> - <?php echo $value1["create_date"];?>

                                        </div>
                                    </div>
                                </a>
                                <?php } ?>


                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!--end:: Widgets/Notifications-->
        </div>

    </div>

</div>
<script type="text/javascript">

    /*
    var chartData = {
        labels: [<?php echo $data["chart15"]["label"];?>],
        datasets: [{
            //label: 'Dataset 1',
            backgroundColor: '#34bfa3',
            data: [
                <?php echo $data["chart15"]["val"];?>

            ]
            }]
    };

    var chart = new Chart($('#chart_daily_sales')[0], {
        type: 'bar',
        data: chartData,
        options: {
            title: {
                display: false,
            },
            tooltips: {
                intersect: false,
                mode: 'nearest',
                xPadding: 10,
                yPadding: 10,
                caretPadding: 10
            },
            legend: {
                display: false
            },
            responsive: true,
            maintainAspectRatio: false,
            barRadius: 4,
            scales: {
                xAxes: [{
                    display: false,
                    gridLines: false,
                    stacked: true
                }],
                yAxes: [{
                    display: false,
                    stacked: true,
                    gridLines: false
                }]
            },
            layout: {
                padding: {
                    left: 0,
                    right: 0,
                    top: 0,
                    bottom: 0
                }
            }
        }
    });
*/
    <?php if( $data["aktifweb_c"]["val"] ){ ?>

    var config = {
        type: 'doughnut',
        data: {
            datasets: [{
                data: [
                    <?php echo $data["aktifweb_c"]["val"];?>

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
                <?php echo $data["aktifweb_c"]["label"];?>

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
    <?php } ?>


    <?php if( $data["aktifmuz_c"]["val"] ){ ?>

    var config = {
        type: 'doughnut',
        data: {
            datasets: [{
                data: [
                    <?php echo $data["aktifmuz_c"]["val"];?>

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
                <?php echo $data["aktifmuz_c"]["label"];?>

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
    var ctx = $('#chart_profit_share1')[0].getContext('2d');
    var myDoughnut = new Chart(ctx, config);
    <?php } ?>


    <?php if( $data["aktifsale_c"] ){ ?>

    Morris.Donut({
        element: 'chart_revenue_change',
        data: <?php echo $data["aktifsale_c"];?>,
        colors: [
            '#34bfa3',
            '#fd3995',
            '#5d78ff',
            '#ffb822',
            '#36a3f7',


        ],
    });
    <?php } ?>

</script>