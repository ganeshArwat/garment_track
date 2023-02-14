<?php $session_data = $this->session->userdata('admin_user'); ?>
<link href="<?php echo base_url(); ?>assets/plugins/icons/icons.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>assets/plugins/mscrollbar/jquery.mCustomScrollbar.css" rel="stylesheet" />
<link href="<?php echo base_url(); ?>assets/css/style.css?<?php echo fileatime('assets/css/style.css'); ?>" rel="stylesheet">
<div class="box box-default">

    <div class="box-body">
        <div class="container">

            <!-- <div class="row row-sm">
                <div class="col-xl-12 col-lg-12 col-md-12 col-xm-12 mb-4">
                    <div class="carousel slide carousel-fade" data-bs-ride="carousel" id="carouselExample5">
                        <ol class="carousel-indicators">
                            <?php
                            $slider_cnt = 0;
                            if (isset($slider_data) && is_array($slider_data) && count($slider_data) > 0) {
                                foreach ($slider_data as $skey => $svalue) {
                                    if ($svalue['media_path'] != '' && file_exists($svalue['media_path'])) {

                            ?>
                                        <li class="<?php echo $slider_cnt == 0 ? 'active' : ''; ?>" data-bs-slide-to="<?php echo $slider_cnt; ?>" data-bs-target="#carouselExample5"></li>
                            <?php $slider_cnt++;
                                    }
                                }
                            }
                            ?>

                        </ol>
                        <div class="carousel-inner bg-dark">
                            <?php
                            $slider_cnt = 0;
                            if (isset($slider_data) && is_array($slider_data) && count($slider_data) > 0) {
                                foreach ($slider_data as $skey => $svalue) {
                                    if ($svalue['media_path'] != '' && file_exists($svalue['media_path'])) {

                            ?>
                                        <div class="carousel-item <?php echo $slider_cnt == 0 ? 'active' : ''; ?>">
                                            <img alt="img" style="height: 80px;" class="d-block w-100" src="<?php echo base_url($svalue['media_path']); ?>">
                                        </div>
                            <?php $slider_cnt++;
                                    }
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div> -->

            <div class="row row-sm">
                <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
                    <div class="card overflow-hidden sales-card bg-primary-gradient">
                        <div class="ps-3 pt-3 pe-3 pb-2 pt-0">
                            <div class="">
                                <h6 class="mb-3 tx-12 text-white">Total Docket Booked</h6>
                            </div>
                            <div class="pb-0 mt-0">
                                <div class="d-flex">
                                    <div class="">
                                        <h4 class="tx-20 fw-bold mb-1 text-white"><?php echo isset($total_docket) ? $total_docket :  0; ?></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <span id="compositeline" class="pt-1"><canvas width="289" height="30" style="display: inline-block; width: 289.24px; height: 30px; vertical-align: top;"></canvas></span>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
                    <div class="card overflow-hidden sales-card bg-danger-gradient">
                        <div class="ps-3 pt-3 pe-3 pb-2 pt-0">
                            <div class="">
                                <h6 class="mb-3 tx-12 text-white">Total Docket Delivered</h6>
                            </div>
                            <div class="pb-0 mt-0">
                                <div class="d-flex">
                                    <div class="">
                                        <h4 class="tx-20 fw-bold mb-1 text-white"><?php echo isset($total_delivered) ? $total_delivered :  0; ?></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <span id="compositeline2" class="pt-1"><canvas width="289" height="30" style="display: inline-block; width: 289.24px; height: 30px; vertical-align: top;"></canvas></span>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
                    <div class="card overflow-hidden sales-card bg-success-gradient">
                        <div class="ps-3 pt-3 pe-3 pb-2 pt-0">
                            <div class="">
                                <h6 class="mb-3 tx-12 text-white">TOTAL Sales of Booked Docket</h6>
                            </div>
                            <div class="pb-0 mt-0">
                                <div class="d-flex">
                                    <div class="">
                                        <h4 class="tx-20 fw-bold mb-1 text-white"><?php echo isset($total_sale) ? $total_sale :  0; ?></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <span id="compositeline3" class="pt-1"><canvas width="289" height="30" style="display: inline-block; width: 289.24px; height: 30px; vertical-align: top;"></canvas></span>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
                    <div class="card overflow-hidden sales-card bg-warning-gradient">
                        <div class="ps-3 pt-3 pe-3 pb-2 pt-0">
                            <div class="">
                                <h6 class="mb-3 tx-12 text-white">Total Docket In-Transit</h6>
                            </div>
                            <div class="pb-0 mt-0">
                                <div class="d-flex">
                                    <div class="">
                                        <h4 class="tx-20 fw-bold mb-1 text-white"><?php echo isset($total_intransit) ? $total_intransit :  0; ?></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <span id="compositeline4" class="pt-1"><canvas width="289" height="30" style="display: inline-block; width: 289.24px; height: 30px; vertical-align: top;"></canvas></span>
                    </div>
                </div>
            </div>
            <div class="col-sm-5">
                <div style="display: inline;float:left;border: 3px solid sandybrown;padding: 5px;background: beige;">
                    <h5 style="text-align:center">Track Your Shipment</h5>
                    <div class="input-group" style="margin-bottom: 8px;height: 25px !important;">
                        <input class="string form-control form-control-sm small-fields" type="text" id="docket_tracking_no_2" placeholder="AWB NO. / FORWARDING NO." name="header_tracking_no">
                        <div class="input-group-append">
                            <input type="text" value="SEARCH" class="btn btn-default btn btn-sm btn-warning" onclick="get_awb_no_dashboard()" style="height: 25px !important;padding: 4px 6px !important;">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="tracking_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header" style="background: #284d73; color: white;">
                            <h5 class="modal-title" id="exampleModalLongTitle" style="font-size: 20px;margin: 0;">TRACKING DETAILS</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true" style="color:white;">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body p-0" id="tracking_modal_data">

                        </div>
                    </div>
                </div>
            </div>
            <!-- <div class="row row-sm row-deck">
                <div class="col-md-12 col-lg-6 col-xl-6">
                    <div class="card card-table-two">
                        <div class="d-flex justify-content-between">
                            <h4 class="card-title mb-1">Courier Wise Load (In KG)</h4>
                        </div>
                        <div class="table-responsive country-table">
                            <table class="table table-striped table-bordered mb-0 text-sm-nowrap text-lg-nowrap text-xl-nowrap">
                                <thead>
                                    <tr>
                                        <th class="wd-lg-20p">Courier</th>
                                        <th class="wd-lg-20p tx-right">Today</th>
                                        <th class="wd-lg-20p tx-right">Yesterday</th>
                                        <th class="wd-lg-20p tx-right">7 Days</th>
                                        <th class="wd-lg-20p tx-right">15 Days</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $today_load_total = 0;
                                    $yesterday_load_total = 0;
                                    $day7_load_total = 0;
                                    $day15_load_total = 0;
                                    if (isset($all_vendor) && is_array($all_vendor) && count($all_vendor) > 0) {
                                        foreach ($all_vendor as $key => $value) {
                                            $today_load_cnt = isset($today_load[$value['id']]) ? $today_load[$value['id']] : 0;
                                            $yesterday_load = isset($yes_load[$value['id']]) ? $yes_load[$value['id']] : 0;
                                            $day7_load_cnt = isset($day7_load[$value['id']]) ? $day7_load[$value['id']] : 0;
                                            $day15_load_cnt = isset($day15_load[$value['id']]) ? $day15_load[$value['id']] : 0;
                                            $today_load_total +=  $today_load_cnt;
                                            $yesterday_load_total +=  $yesterday_load;
                                            $day7_load_total +=  $day7_load_cnt;
                                            $day15_load_total +=  $day15_load_cnt;
                                    ?>
                                            <tr>
                                                <td><?php echo ucwords($value['name']) ?></td>
                                                <td class="tx-right tx-medium tx-inverse"><?php echo $today_load_cnt; ?></td>
                                                <td class="tx-right tx-medium tx-inverse"><?php echo $yesterday_load; ?></td>
                                                <td class="tx-right tx-medium tx-inverse"><?php echo $day7_load_cnt; ?></td>
                                                <td class="tx-right tx-medium tx-inverse"><?php echo $day15_load_cnt; ?></td>
                                            </tr>
                                    <?php  }
                                    }
                                    ?>

                                    <tr>
                                        <td>Total</td>
                                        <td class="tx-right tx-medium tx-inverse"><?php echo $today_load_total; ?></td>
                                        <td class="tx-right tx-medium tx-inverse"><?php echo $yesterday_load_total; ?></td>
                                        <td class="tx-right tx-medium tx-inverse"><?php echo $day7_load_total; ?></td>
                                        <td class="tx-right tx-medium tx-inverse"><?php echo $day15_load_total; ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-lg-6 col-xl-6">
                    <div class="card card-table-two">
                        <div class="d-flex justify-content-between">
                            <h4 class="card-title mb-1">Courier Wise Status</h4>
                        </div>
                        <div class="table-responsive country-table">
                            <table class="table table-striped table-bordered mb-0 text-sm-nowrap text-lg-nowrap text-xl-nowrap">
                                <thead>
                                    <tr>
                                        <th class="wd-lg-25p">Courier</th>
                                        <th class="wd-lg-25p tx-right">TOTAL</th>
                                        <th class="wd-lg-25p tx-right">Delivered</th>
                                        <th class="wd-lg-25p tx-right">Intransit</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $service_total_cnt = 0;
                                    $service_delivered_total_cnt = 0;
                                    $service_transit_total_cnt = 0;
                                    if (isset($all_vendor) && is_array($all_vendor) && count($all_vendor) > 0) {
                                        foreach ($all_vendor as $key => $value) {
                                            $service_docket = isset($service_total[$value['id']]) ? $service_total[$value['id']] : 0;
                                            $service_delivered_cnt = isset($service_delivered[$value['id']]) ? $service_delivered[$value['id']] : 0;
                                            $service_transit_cnt = isset($service_intransit[$value['id']]) ? $service_intransit[$value['id']] : 0;
                                            $service_total_cnt +=  $service_docket;
                                            $service_delivered_total_cnt +=  $service_delivered_cnt;
                                            $service_transit_total_cnt +=  $service_transit_cnt;
                                    ?>
                                            <tr>
                                                <td><?php echo ucwords($value['name']) ?></td>
                                                <td class="tx-right tx-medium tx-inverse"><?php echo $service_docket; ?></td>
                                                <td class="tx-right tx-medium tx-inverse"><?php echo $service_delivered_cnt; ?></td>
                                                <td class="tx-right tx-medium tx-inverse"><?php echo $service_transit_cnt; ?></td>
                                            </tr>
                                    <?php  }
                                    }
                                    ?>
                                    <tr>
                                        <td>Total</td>
                                        <td class="tx-right tx-medium tx-inverse"><?php echo $service_total_cnt; ?></td>
                                        <td class="tx-right tx-medium tx-inverse"><?php echo $service_delivered_total_cnt; ?></td>
                                        <td class="tx-right tx-medium tx-inverse"><?php echo $service_transit_total_cnt; ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row row-sm">
                <div class="col-md-12 col-lg-6 col-xl-6">
                    <div class="card">
                        <div class="card-body">
                            <div id="chart_1" class="sales-bar">
                                <div style="position: relative;">
                                    <div dir="ltr" style="position: relative; width: 561px; height: 500px;">
                                        <div aria-label="A chart." style="position: absolute; left: 0px; top: 0px; width: 100%; height: 100%;"><svg width="561" height="500" aria-label="A chart." style="overflow: hidden;">
                                                <defs id="_ABSTRACT_RENDERER_ID_0">
                                                    <clipPath id="_ABSTRACT_RENDERER_ID_1">
                                                        <rect x="107" y="96" width="347" height="309"></rect>
                                                    </clipPath>
                                                    <filter id="_ABSTRACT_RENDERER_ID_4">
                                                        <feGaussianBlur in="SourceAlpha" stdDeviation="2"></feGaussianBlur>
                                                        <feOffset dx="1" dy="1"></feOffset>
                                                        <feComponentTransfer>
                                                            <feFuncA type="linear" slope="0.1"></feFuncA>
                                                        </feComponentTransfer>
                                                        <feMerge>
                                                            <feMergeNode></feMergeNode>
                                                            <feMergeNode in="SourceGraphic"></feMergeNode>
                                                        </feMerge>
                                                    </filter>
                                                </defs>
                                                <rect x="0" y="0" width="561" height="500" stroke="none" stroke-width="0" fill="#ffffff"></rect>
                                                <g><text text-anchor="start" x="107" y="73.05" font-family="Arial" font-size="13" font-weight="bold" stroke="none" stroke-width="0" fill="#000000">Docket Status Month-Wise</text>
                                                    <rect x="107" y="62" width="347" height="13" stroke="none" stroke-width="0" fill-opacity="0" fill="#ffffff"></rect>
                                                </g>
                                                <g>
                                                    <rect x="467" y="96" width="81" height="34" stroke="none" stroke-width="0" fill-opacity="0" fill="#ffffff"></rect>
                                                    <g>
                                                        <rect x="467" y="96" width="81" height="13" stroke="none" stroke-width="0" fill-opacity="0" fill="#ffffff"></rect>
                                                        <g><text text-anchor="start" x="498" y="107.05" font-family="Arial" font-size="13" stroke="none" stroke-width="0" fill="#222222">Delive…</text>
                                                            <rect x="498" y="96" width="50" height="13" stroke="none" stroke-width="0" fill-opacity="0" fill="#ffffff"></rect>
                                                        </g>
                                                        <rect x="467" y="96" width="26" height="13" stroke="none" stroke-width="0" fill="#3366cc"></rect>
                                                    </g>
                                                    <g>
                                                        <rect x="467" y="117" width="81" height="13" stroke="none" stroke-width="0" fill-opacity="0" fill="#ffffff"></rect>
                                                        <g><text text-anchor="start" x="498" y="128.05" font-family="Arial" font-size="13" stroke="none" stroke-width="0" fill="#222222">Undeli…</text>
                                                            <rect x="498" y="117" width="50" height="13" stroke="none" stroke-width="0" fill-opacity="0" fill="#ffffff"></rect>
                                                        </g>
                                                        <rect x="467" y="117" width="26" height="13" stroke="none" stroke-width="0" fill="#dc3912"></rect>
                                                    </g>
                                                </g>
                                                <g>
                                                    <rect x="107" y="96" width="347" height="309" stroke="none" stroke-width="0" fill-opacity="0" fill="#ffffff"></rect>
                                                    <g clip-path="url(file:///C:/Users/Admin/Downloads/acm/acm/index.html#_ABSTRACT_RENDERER_ID_1)">
                                                        <g>
                                                            <rect x="107" y="404" width="347" height="1" stroke="none" stroke-width="0" fill="#cccccc"></rect>
                                                            <rect x="107" y="353" width="347" height="1" stroke="none" stroke-width="0" fill="#cccccc"></rect>
                                                            <rect x="107" y="301" width="347" height="1" stroke="none" stroke-width="0" fill="#cccccc"></rect>
                                                            <rect x="107" y="250" width="347" height="1" stroke="none" stroke-width="0" fill="#cccccc"></rect>
                                                            <rect x="107" y="199" width="347" height="1" stroke="none" stroke-width="0" fill="#cccccc"></rect>
                                                            <rect x="107" y="147" width="347" height="1" stroke="none" stroke-width="0" fill="#cccccc"></rect>
                                                            <rect x="107" y="96" width="347" height="1" stroke="none" stroke-width="0" fill="#cccccc"></rect>
                                                            <rect x="107" y="378" width="347" height="1" stroke="none" stroke-width="0" fill="#ebebeb"></rect>
                                                            <rect x="107" y="327" width="347" height="1" stroke="none" stroke-width="0" fill="#ebebeb"></rect>
                                                            <rect x="107" y="276" width="347" height="1" stroke="none" stroke-width="0" fill="#ebebeb"></rect>
                                                            <rect x="107" y="224" width="347" height="1" stroke="none" stroke-width="0" fill="#ebebeb"></rect>
                                                            <rect x="107" y="173" width="347" height="1" stroke="none" stroke-width="0" fill="#ebebeb"></rect>
                                                            <rect x="107" y="122" width="347" height="1" stroke="none" stroke-width="0" fill="#ebebeb"></rect>
                                                        </g>
                                                        <g>
                                                            <rect x="113" y="302" width="8" height="102" stroke="none" stroke-width="0" fill="#3366cc"></rect>
                                                            <rect x="142" y="277" width="8" height="127" stroke="none" stroke-width="0" fill="#3366cc"></rect>
                                                            <rect x="171" y="328" width="8" height="76" stroke="none" stroke-width="0" fill="#3366cc"></rect>
                                                            <rect x="200" y="251" width="8" height="153" stroke="none" stroke-width="0" fill="#3366cc"></rect>
                                                            <rect x="228" y="200" width="9" height="204" stroke="none" stroke-width="0" fill="#3366cc"></rect>
                                                            <rect x="257" y="220" width="9" height="184" stroke="none" stroke-width="0" fill="#3366cc"></rect>
                                                            <rect x="286" y="159" width="8" height="245" stroke="none" stroke-width="0" fill="#3366cc"></rect>
                                                            <rect x="315" y="174" width="8" height="230" stroke="none" stroke-width="0" fill="#3366cc"></rect>
                                                            <rect x="344" y="200" width="8" height="204" stroke="none" stroke-width="0" fill="#3366cc"></rect>
                                                            <rect x="373" y="97" width="8" height="307" stroke="none" stroke-width="0" fill="#3366cc"></rect>
                                                            <rect x="401" y="189" width="9" height="215" stroke="none" stroke-width="0" fill="#3366cc"></rect>
                                                            <rect x="430" y="313" width="9" height="91" stroke="none" stroke-width="0" fill="#3366cc"></rect>
                                                            <rect x="122" y="379" width="9" height="25" stroke="none" stroke-width="0" fill="#dc3912"></rect>
                                                            <rect x="151" y="364" width="9" height="40" stroke="none" stroke-width="0" fill="#dc3912"></rect>
                                                            <rect x="180" y="354" width="8" height="50" stroke="none" stroke-width="0" fill="#dc3912"></rect>
                                                            <rect x="209" y="328" width="8" height="76" stroke="none" stroke-width="0" fill="#dc3912"></rect>
                                                            <rect x="238" y="343" width="8" height="61" stroke="none" stroke-width="0" fill="#dc3912"></rect>
                                                            <rect x="267" y="384" width="8" height="20" stroke="none" stroke-width="0" fill="#dc3912"></rect>
                                                            <rect x="295" y="313" width="9" height="91" stroke="none" stroke-width="0" fill="#dc3912"></rect>
                                                            <rect x="324" y="374" width="9" height="30" stroke="none" stroke-width="0" fill="#dc3912"></rect>
                                                            <rect x="353" y="343" width="8" height="61" stroke="none" stroke-width="0" fill="#dc3912"></rect>
                                                            <rect x="382" y="292" width="8" height="112" stroke="none" stroke-width="0" fill="#dc3912"></rect>
                                                            <rect x="411" y="338" width="8" height="66" stroke="none" stroke-width="0" fill="#dc3912"></rect>
                                                            <rect x="440" y="384" width="8" height="20" stroke="none" stroke-width="0" fill="#dc3912"></rect>
                                                        </g>
                                                        <g>
                                                            <rect x="107" y="404" width="347" height="1" stroke="none" stroke-width="0" fill="#333333"></rect>
                                                        </g>
                                                    </g>
                                                    <g></g>
                                                    <g>
                                                        <g><text text-anchor="middle" x="121.91666666666667" y="424.05" font-family="Arial" font-size="13" stroke="none" stroke-width="0" fill="#222222">Jan</text></g>
                                                        <g><text text-anchor="middle" x="150.75" y="441.05" font-family="Arial" font-size="13" stroke="none" stroke-width="0" fill="#222222">Feb</text></g>
                                                        <g><text text-anchor="middle" x="179.58333333333331" y="424.05" font-family="Arial" font-size="13" stroke="none" stroke-width="0" fill="#222222">Mar</text></g>
                                                        <g><text text-anchor="middle" x="208.41666666666666" y="441.05" font-family="Arial" font-size="13" stroke="none" stroke-width="0" fill="#222222">Apr</text></g>
                                                        <g><text text-anchor="middle" x="237.25" y="424.05" font-family="Arial" font-size="13" stroke="none" stroke-width="0" fill="#222222">May</text></g>
                                                        <g><text text-anchor="middle" x="266.0833333333333" y="441.05" font-family="Arial" font-size="13" stroke="none" stroke-width="0" fill="#222222">Jun</text></g>
                                                        <g><text text-anchor="middle" x="294.91666666666663" y="424.05" font-family="Arial" font-size="13" stroke="none" stroke-width="0" fill="#222222">Jul</text></g>
                                                        <g><text text-anchor="middle" x="323.75" y="441.05" font-family="Arial" font-size="13" stroke="none" stroke-width="0" fill="#222222">Aug</text></g>
                                                        <g><text text-anchor="middle" x="352.5833333333333" y="424.05" font-family="Arial" font-size="13" stroke="none" stroke-width="0" fill="#222222">Sep</text></g>
                                                        <g><text text-anchor="middle" x="381.41666666666663" y="441.05" font-family="Arial" font-size="13" stroke="none" stroke-width="0" fill="#222222">Oct</text></g>
                                                        <g><text text-anchor="middle" x="410.25" y="424.05" font-family="Arial" font-size="13" stroke="none" stroke-width="0" fill="#222222">Nov</text></g>
                                                        <g><text text-anchor="middle" x="439.0833333333333" y="441.05" font-family="Arial" font-size="13" stroke="none" stroke-width="0" fill="#222222">Dec</text></g>
                                                        <g><text text-anchor="end" x="94" y="409.05" font-family="Arial" font-size="13" stroke="none" stroke-width="0" fill="#444444">0</text></g>
                                                        <g><text text-anchor="end" x="94" y="357.7167" font-family="Arial" font-size="13" stroke="none" stroke-width="0" fill="#444444">100</text></g>
                                                        <g><text text-anchor="end" x="94" y="306.3833" font-family="Arial" font-size="13" stroke="none" stroke-width="0" fill="#444444">200</text></g>
                                                        <g><text text-anchor="end" x="94" y="255.05" font-family="Arial" font-size="13" stroke="none" stroke-width="0" fill="#444444">300</text></g>
                                                        <g><text text-anchor="end" x="94" y="203.7167" font-family="Arial" font-size="13" stroke="none" stroke-width="0" fill="#444444">400</text></g>
                                                        <g><text text-anchor="end" x="94" y="152.38330000000002" font-family="Arial" font-size="13" stroke="none" stroke-width="0" fill="#444444">500</text></g>
                                                        <g><text text-anchor="end" x="94" y="101.05" font-family="Arial" font-size="13" stroke="none" stroke-width="0" fill="#444444">600</text></g>
                                                    </g>
                                                </g>
                                                <g>
                                                    <g><text text-anchor="middle" x="41.05" y="250.5" font-family="Arial" font-size="13" font-style="italic" transform="rotate(-90 41.05 250.5)" stroke="none" stroke-width="0" fill="#222222">Docket Count</text>
                                                        <path d="M29.99999999999999,405L30.000000000000007,96L43.00000000000001,96L42.99999999999999,405Z" stroke="none" stroke-width="0" fill-opacity="0" fill="#ffffff"></path>
                                                    </g>
                                                </g>
                                                <g></g>
                                            </svg>
                                            <div aria-label="A tabular representation of the data in the chart." style="position: absolute; left: -10000px; top: auto; width: 1px; height: 1px; overflow: hidden;">
                                                <table>
                                                    <thead>
                                                        <tr>
                                                            <th>Month</th>
                                                            <th>Delivered</th>
                                                            <th>Undelivered</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>Jan</td>
                                                            <td>200</td>
                                                            <td>50</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Feb</td>
                                                            <td>250</td>
                                                            <td>80</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Mar</td>
                                                            <td>150</td>
                                                            <td>100</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Apr</td>
                                                            <td>300</td>
                                                            <td>150</td>
                                                        </tr>
                                                        <tr>
                                                            <td>May</td>
                                                            <td>400</td>
                                                            <td>120</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Jun</td>
                                                            <td>360</td>
                                                            <td>40</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Jul</td>
                                                            <td>480</td>
                                                            <td>180</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Aug</td>
                                                            <td>450</td>
                                                            <td>60</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Sep</td>
                                                            <td>400</td>
                                                            <td>120</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Oct</td>
                                                            <td>600</td>
                                                            <td>220</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Nov</td>
                                                            <td>420</td>
                                                            <td>130</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Dec</td>
                                                            <td>180</td>
                                                            <td>40</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div aria-hidden="true" style="display: none; position: absolute; top: 510px; left: 571px; white-space: nowrap; font-family: Arial; font-size: 13px; font-weight: bold;">450</div>
                                    <div></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-lg-6 col-xl-6">
                    <div class="card">
                        <div class="card-body">
                            <div id="chart_2" class="sales-bar">
                                <div style="position: relative;">
                                    <div dir="ltr" style="position: relative; width: 561px; height: 500px;">
                                        <div aria-label="A chart." style="position: absolute; left: 0px; top: 0px; width: 100%; height: 100%;"><svg width="561" height="500" aria-label="A chart." style="overflow: hidden;">
                                                <defs id="_ABSTRACT_RENDERER_ID_2">
                                                    <clipPath id="_ABSTRACT_RENDERER_ID_3">
                                                        <rect x="107" y="96" width="347" height="309"></rect>
                                                    </clipPath>
                                                </defs>
                                                <rect x="0" y="0" width="561" height="500" stroke="none" stroke-width="0" fill="#ffffff"></rect>
                                                <g><text text-anchor="start" x="107" y="73.05" font-family="Arial" font-size="13" font-weight="bold" stroke="none" stroke-width="0" fill="#000000">Month-Wise Courier Partner Booking</text>
                                                    <rect x="107" y="62" width="347" height="13" stroke="none" stroke-width="0" fill-opacity="0" fill="#ffffff"></rect>
                                                </g>
                                                <g>
                                                    <rect x="467" y="96" width="81" height="131" stroke="none" stroke-width="0" fill-opacity="0" fill="#ffffff"></rect>
                                                    <g>
                                                        <rect x="467" y="96" width="81" height="13" stroke="none" stroke-width="0" fill-opacity="0" fill="#ffffff"></rect>
                                                        <g><text text-anchor="start" x="498" y="107.05" font-family="Arial" font-size="13" stroke="none" stroke-width="0" fill="#222222">DTDC</text></g>
                                                        <rect x="467" y="96" width="26" height="13" stroke="none" stroke-width="0" fill="#3366cc"></rect>
                                                    </g>
                                                    <g>
                                                        <rect x="467" y="117" width="81" height="13" stroke="none" stroke-width="0" fill-opacity="0" fill="#ffffff"></rect>
                                                        <g><text text-anchor="start" x="498" y="128.05" font-family="Arial" font-size="13" stroke="none" stroke-width="0" fill="#222222">FEDEX</text></g>
                                                        <rect x="467" y="117" width="26" height="13" stroke="none" stroke-width="0" fill="#dc3912"></rect>
                                                    </g>
                                                    <g>
                                                        <rect x="467" y="138" width="81" height="13" stroke="none" stroke-width="0" fill-opacity="0" fill="#ffffff"></rect>
                                                        <g><text text-anchor="start" x="498" y="149.05" font-family="Arial" font-size="13" stroke="none" stroke-width="0" fill="#222222">DHL</text></g>
                                                        <rect x="467" y="138" width="26" height="13" stroke="none" stroke-width="0" fill="#ff9900"></rect>
                                                    </g>
                                                    <g>
                                                        <rect x="467" y="159" width="81" height="30" stroke="none" stroke-width="0" fill-opacity="0" fill="#ffffff"></rect>
                                                        <g><text text-anchor="start" x="498" y="170.05" font-family="Arial" font-size="13" stroke="none" stroke-width="0" fill="#222222">ACM</text><text text-anchor="start" x="498" y="187.05" font-family="Arial" font-size="13" stroke="none" stroke-width="0" fill="#222222">Self</text></g>
                                                        <rect x="467" y="159" width="26" height="13" stroke="none" stroke-width="0" fill="#109618"></rect>
                                                    </g>
                                                    <g>
                                                        <rect x="467" y="197" width="81" height="30" stroke="none" stroke-width="0" fill-opacity="0" fill="#ffffff"></rect>
                                                        <g><text text-anchor="start" x="498" y="208.05" font-family="Arial" font-size="13" stroke="none" stroke-width="0" fill="#222222">Amazon</text><text text-anchor="start" x="498" y="225.05" font-family="Arial" font-size="13" stroke="none" stroke-width="0" fill="#222222">Logistics</text></g>
                                                        <rect x="467" y="197" width="26" height="13" stroke="none" stroke-width="0" fill="#990099"></rect>
                                                    </g>
                                                </g>
                                                <g>
                                                    <rect x="107" y="96" width="347" height="309" stroke="none" stroke-width="0" fill-opacity="0" fill="#ffffff"></rect>
                                                    <g clip-path="url(file:///C:/Users/Admin/Downloads/acm/acm/index.html#_ABSTRACT_RENDERER_ID_3)">
                                                        <g>
                                                            <rect x="107" y="404" width="347" height="1" stroke="none" stroke-width="0" fill="#cccccc"></rect>
                                                            <rect x="107" y="353" width="347" height="1" stroke="none" stroke-width="0" fill="#cccccc"></rect>
                                                            <rect x="107" y="301" width="347" height="1" stroke="none" stroke-width="0" fill="#cccccc"></rect>
                                                            <rect x="107" y="250" width="347" height="1" stroke="none" stroke-width="0" fill="#cccccc"></rect>
                                                            <rect x="107" y="199" width="347" height="1" stroke="none" stroke-width="0" fill="#cccccc"></rect>
                                                            <rect x="107" y="147" width="347" height="1" stroke="none" stroke-width="0" fill="#cccccc"></rect>
                                                            <rect x="107" y="96" width="347" height="1" stroke="none" stroke-width="0" fill="#cccccc"></rect>
                                                            <rect x="107" y="378" width="347" height="1" stroke="none" stroke-width="0" fill="#ebebeb"></rect>
                                                            <rect x="107" y="327" width="347" height="1" stroke="none" stroke-width="0" fill="#ebebeb"></rect>
                                                            <rect x="107" y="276" width="347" height="1" stroke="none" stroke-width="0" fill="#ebebeb"></rect>
                                                            <rect x="107" y="224" width="347" height="1" stroke="none" stroke-width="0" fill="#ebebeb"></rect>
                                                            <rect x="107" y="173" width="347" height="1" stroke="none" stroke-width="0" fill="#ebebeb"></rect>
                                                            <rect x="107" y="122" width="347" height="1" stroke="none" stroke-width="0" fill="#ebebeb"></rect>
                                                        </g>
                                                        <g>
                                                            <rect x="113.5" y="302" width="2" height="102" stroke="none" stroke-width="0" fill="#3366cc"></rect>
                                                            <rect x="141.5" y="277" width="2" height="127" stroke="none" stroke-width="0" fill="#3366cc"></rect>
                                                            <rect x="170.5" y="328" width="2" height="76" stroke="none" stroke-width="0" fill="#3366cc"></rect>
                                                            <rect x="199.5" y="251" width="2" height="153" stroke="none" stroke-width="0" fill="#3366cc"></rect>
                                                            <rect x="228.5" y="200" width="2" height="204" stroke="none" stroke-width="0" fill="#3366cc"></rect>
                                                            <rect x="257.5" y="220" width="2" height="184" stroke="none" stroke-width="0" fill="#3366cc"></rect>
                                                            <rect x="286.5" y="159" width="2" height="245" stroke="none" stroke-width="0" fill="#3366cc"></rect>
                                                            <rect x="314.5" y="174" width="2" height="230" stroke="none" stroke-width="0" fill="#3366cc"></rect>
                                                            <rect x="343.5" y="200" width="2" height="204" stroke="none" stroke-width="0" fill="#3366cc"></rect>
                                                            <rect x="372.5" y="97" width="2" height="307" stroke="none" stroke-width="0" fill="#3366cc"></rect>
                                                            <rect x="401.5" y="189" width="2" height="215" stroke="none" stroke-width="0" fill="#3366cc"></rect>
                                                            <rect x="430.5" y="313" width="2" height="91" stroke="none" stroke-width="0" fill="#3366cc"></rect>
                                                            <rect x="116.5" y="379" width="2" height="25" stroke="none" stroke-width="0" fill="#dc3912"></rect>
                                                            <rect x="144.5" y="364" width="2" height="40" stroke="none" stroke-width="0" fill="#dc3912"></rect>
                                                            <rect x="173.5" y="354" width="2" height="50" stroke="none" stroke-width="0" fill="#dc3912"></rect>
                                                            <rect x="202.5" y="328" width="2" height="76" stroke="none" stroke-width="0" fill="#dc3912"></rect>
                                                            <rect x="231.5" y="343" width="2" height="61" stroke="none" stroke-width="0" fill="#dc3912"></rect>
                                                            <rect x="260.5" y="384" width="2" height="20" stroke="none" stroke-width="0" fill="#dc3912"></rect>
                                                            <rect x="289.5" y="313" width="2" height="91" stroke="none" stroke-width="0" fill="#dc3912"></rect>
                                                            <rect x="317.5" y="374" width="2" height="30" stroke="none" stroke-width="0" fill="#dc3912"></rect>
                                                            <rect x="346.5" y="343" width="2" height="61" stroke="none" stroke-width="0" fill="#dc3912"></rect>
                                                            <rect x="375.5" y="292" width="2" height="112" stroke="none" stroke-width="0" fill="#dc3912"></rect>
                                                            <rect x="404.5" y="338" width="2" height="66" stroke="none" stroke-width="0" fill="#dc3912"></rect>
                                                            <rect x="433.5" y="384" width="2" height="20" stroke="none" stroke-width="0" fill="#dc3912"></rect>
                                                            <rect x="119.5" y="302" width="2" height="102" stroke="none" stroke-width="0" fill="#ff9900"></rect>
                                                            <rect x="147.5" y="277" width="2" height="127" stroke="none" stroke-width="0" fill="#ff9900"></rect>
                                                            <rect x="176.5" y="328" width="2" height="76" stroke="none" stroke-width="0" fill="#ff9900"></rect>
                                                            <rect x="205.5" y="251" width="2" height="153" stroke="none" stroke-width="0" fill="#ff9900"></rect>
                                                            <rect x="234.5" y="200" width="2" height="204" stroke="none" stroke-width="0" fill="#ff9900"></rect>
                                                            <rect x="263.5" y="220" width="2" height="184" stroke="none" stroke-width="0" fill="#ff9900"></rect>
                                                            <rect x="292.5" y="159" width="2" height="245" stroke="none" stroke-width="0" fill="#ff9900"></rect>
                                                            <rect x="320.5" y="174" width="2" height="230" stroke="none" stroke-width="0" fill="#ff9900"></rect>
                                                            <rect x="349.5" y="200" width="2" height="204" stroke="none" stroke-width="0" fill="#ff9900"></rect>
                                                            <rect x="378.5" y="97" width="2" height="307" stroke="none" stroke-width="0" fill="#ff9900"></rect>
                                                            <rect x="407.5" y="189" width="2" height="215" stroke="none" stroke-width="0" fill="#ff9900"></rect>
                                                            <rect x="436.5" y="313" width="2" height="91" stroke="none" stroke-width="0" fill="#ff9900"></rect>
                                                            <rect x="122.5" y="379" width="2" height="25" stroke="none" stroke-width="0" fill="#109618"></rect>
                                                            <rect x="150.5" y="364" width="2" height="40" stroke="none" stroke-width="0" fill="#109618"></rect>
                                                            <rect x="179.5" y="354" width="2" height="50" stroke="none" stroke-width="0" fill="#109618"></rect>
                                                            <rect x="208.5" y="328" width="2" height="76" stroke="none" stroke-width="0" fill="#109618"></rect>
                                                            <rect x="237.5" y="343" width="2" height="61" stroke="none" stroke-width="0" fill="#109618"></rect>
                                                            <rect x="266.5" y="384" width="2" height="20" stroke="none" stroke-width="0" fill="#109618"></rect>
                                                            <rect x="295.5" y="313" width="2" height="91" stroke="none" stroke-width="0" fill="#109618"></rect>
                                                            <rect x="323.5" y="374" width="2" height="30" stroke="none" stroke-width="0" fill="#109618"></rect>
                                                            <rect x="352.5" y="343" width="2" height="61" stroke="none" stroke-width="0" fill="#109618"></rect>
                                                            <rect x="381.5" y="292" width="2" height="112" stroke="none" stroke-width="0" fill="#109618"></rect>
                                                            <rect x="410.5" y="338" width="2" height="66" stroke="none" stroke-width="0" fill="#109618"></rect>
                                                            <rect x="439.5" y="384" width="2" height="20" stroke="none" stroke-width="0" fill="#109618"></rect>
                                                            <rect x="125.5" y="251" width="2" height="153" stroke="none" stroke-width="0" fill="#990099"></rect>
                                                            <rect x="153.5" y="302" width="2" height="102" stroke="none" stroke-width="0" fill="#990099"></rect>
                                                            <rect x="182.5" y="277" width="2" height="127" stroke="none" stroke-width="0" fill="#990099"></rect>
                                                            <rect x="211.5" y="200" width="2" height="204" stroke="none" stroke-width="0" fill="#990099"></rect>
                                                            <rect x="240.5" y="328" width="2" height="76" stroke="none" stroke-width="0" fill="#990099"></rect>
                                                            <rect x="269.5" y="174" width="2" height="230" stroke="none" stroke-width="0" fill="#990099"></rect>
                                                            <rect x="298.5" y="148" width="2" height="256" stroke="none" stroke-width="0" fill="#990099"></rect>
                                                            <rect x="326.5" y="315" width="2" height="89" stroke="none" stroke-width="0" fill="#990099"></rect>
                                                            <rect x="355.5" y="354" width="2" height="50" stroke="none" stroke-width="0" fill="#990099"></rect>
                                                            <rect x="384.5" y="367" width="2" height="37" stroke="none" stroke-width="0" fill="#990099"></rect>
                                                            <rect x="413.5" y="266" width="2" height="138" stroke="none" stroke-width="0" fill="#990099"></rect>
                                                            <rect x="442.5" y="161" width="2" height="243" stroke="none" stroke-width="0" fill="#990099"></rect>
                                                        </g>
                                                        <g>
                                                            <rect x="107" y="404" width="347" height="1" stroke="none" stroke-width="0" fill="#333333"></rect>
                                                        </g>
                                                    </g>
                                                    <g></g>
                                                    <g>
                                                        <g><text text-anchor="middle" x="121.91666666666667" y="424.05" font-family="Arial" font-size="13" stroke="none" stroke-width="0" fill="#222222">Jan</text></g>
                                                        <g><text text-anchor="middle" x="150.75" y="441.05" font-family="Arial" font-size="13" stroke="none" stroke-width="0" fill="#222222">Feb</text></g>
                                                        <g><text text-anchor="middle" x="179.58333333333331" y="424.05" font-family="Arial" font-size="13" stroke="none" stroke-width="0" fill="#222222">Mar</text></g>
                                                        <g><text text-anchor="middle" x="208.41666666666666" y="441.05" font-family="Arial" font-size="13" stroke="none" stroke-width="0" fill="#222222">Apr</text></g>
                                                        <g><text text-anchor="middle" x="237.25" y="424.05" font-family="Arial" font-size="13" stroke="none" stroke-width="0" fill="#222222">May</text></g>
                                                        <g><text text-anchor="middle" x="266.0833333333333" y="441.05" font-family="Arial" font-size="13" stroke="none" stroke-width="0" fill="#222222">Jun</text></g>
                                                        <g><text text-anchor="middle" x="294.91666666666663" y="424.05" font-family="Arial" font-size="13" stroke="none" stroke-width="0" fill="#222222">Jul</text></g>
                                                        <g><text text-anchor="middle" x="323.75" y="441.05" font-family="Arial" font-size="13" stroke="none" stroke-width="0" fill="#222222">Aug</text></g>
                                                        <g><text text-anchor="middle" x="352.5833333333333" y="424.05" font-family="Arial" font-size="13" stroke="none" stroke-width="0" fill="#222222">Sep</text></g>
                                                        <g><text text-anchor="middle" x="381.41666666666663" y="441.05" font-family="Arial" font-size="13" stroke="none" stroke-width="0" fill="#222222">Oct</text></g>
                                                        <g><text text-anchor="middle" x="410.25" y="424.05" font-family="Arial" font-size="13" stroke="none" stroke-width="0" fill="#222222">Nov</text></g>
                                                        <g><text text-anchor="middle" x="439.0833333333333" y="441.05" font-family="Arial" font-size="13" stroke="none" stroke-width="0" fill="#222222">Dec</text></g>
                                                        <g><text text-anchor="end" x="94" y="409.05" font-family="Arial" font-size="13" stroke="none" stroke-width="0" fill="#444444">0</text></g>
                                                        <g><text text-anchor="end" x="94" y="357.7167" font-family="Arial" font-size="13" stroke="none" stroke-width="0" fill="#444444">100</text></g>
                                                        <g><text text-anchor="end" x="94" y="306.3833" font-family="Arial" font-size="13" stroke="none" stroke-width="0" fill="#444444">200</text></g>
                                                        <g><text text-anchor="end" x="94" y="255.05" font-family="Arial" font-size="13" stroke="none" stroke-width="0" fill="#444444">300</text></g>
                                                        <g><text text-anchor="end" x="94" y="203.7167" font-family="Arial" font-size="13" stroke="none" stroke-width="0" fill="#444444">400</text></g>
                                                        <g><text text-anchor="end" x="94" y="152.38330000000002" font-family="Arial" font-size="13" stroke="none" stroke-width="0" fill="#444444">500</text></g>
                                                        <g><text text-anchor="end" x="94" y="101.05" font-family="Arial" font-size="13" stroke="none" stroke-width="0" fill="#444444">600</text></g>
                                                    </g>
                                                </g>
                                                <g>
                                                    <g><text text-anchor="middle" x="41.05" y="250.5" font-family="Arial" font-size="13" font-style="italic" transform="rotate(-90 41.05 250.5)" stroke="none" stroke-width="0" fill="#222222">Docket Count</text>
                                                        <path d="M29.99999999999999,405L30.000000000000007,96L43.00000000000001,96L42.99999999999999,405Z" stroke="none" stroke-width="0" fill-opacity="0" fill="#ffffff"></path>
                                                    </g>
                                                </g>
                                                <g></g>
                                            </svg>
                                            <div aria-label="A tabular representation of the data in the chart." style="position: absolute; left: -10000px; top: auto; width: 1px; height: 1px; overflow: hidden;">
                                                <table>
                                                    <thead>
                                                        <tr>
                                                            <th>Month</th>
                                                            <th>DTDC</th>
                                                            <th>FEDEX</th>
                                                            <th>DHL</th>
                                                            <th>ACM Self</th>
                                                            <th>Amazon Logistics</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>Jan</td>
                                                            <td>200</td>
                                                            <td>50</td>
                                                            <td>200</td>
                                                            <td>50</td>
                                                            <td>300</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Feb</td>
                                                            <td>250</td>
                                                            <td>80</td>
                                                            <td>250</td>
                                                            <td>80</td>
                                                            <td>200</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Mar</td>
                                                            <td>150</td>
                                                            <td>100</td>
                                                            <td>150</td>
                                                            <td>100</td>
                                                            <td>250</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Apr</td>
                                                            <td>300</td>
                                                            <td>150</td>
                                                            <td>300</td>
                                                            <td>150</td>
                                                            <td>400</td>
                                                        </tr>
                                                        <tr>
                                                            <td>May</td>
                                                            <td>400</td>
                                                            <td>120</td>
                                                            <td>400</td>
                                                            <td>120</td>
                                                            <td>150</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Jun</td>
                                                            <td>360</td>
                                                            <td>40</td>
                                                            <td>360</td>
                                                            <td>40</td>
                                                            <td>450</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Jul</td>
                                                            <td>480</td>
                                                            <td>180</td>
                                                            <td>480</td>
                                                            <td>180</td>
                                                            <td>500</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Aug</td>
                                                            <td>450</td>
                                                            <td>60</td>
                                                            <td>450</td>
                                                            <td>60</td>
                                                            <td>175</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Sep</td>
                                                            <td>400</td>
                                                            <td>120</td>
                                                            <td>400</td>
                                                            <td>120</td>
                                                            <td>100</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Oct</td>
                                                            <td>600</td>
                                                            <td>220</td>
                                                            <td>600</td>
                                                            <td>220</td>
                                                            <td>75</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Nov</td>
                                                            <td>420</td>
                                                            <td>130</td>
                                                            <td>420</td>
                                                            <td>130</td>
                                                            <td>270</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Dec</td>
                                                            <td>180</td>
                                                            <td>40</td>
                                                            <td>180</td>
                                                            <td>40</td>
                                                            <td>475</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div aria-hidden="true" style="display: none; position: absolute; top: 510px; left: 571px; white-space: nowrap; font-family: Arial; font-size: 13px;">Logistics</div>
                                    <div></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> -->
        </div>
    </div>
</div>
<script>
    function get_awb_no_dashboard() {
        var awb_no = $('#docket_tracking_no_2').val();
        console.log(awb_no)
        if (awb_no != "") {
            $.ajax({
                type: "POST",
                data: {
                    'awb_no': awb_no
                },
                url: "<?php echo site_url('generic_detail/get_awb_id?awb_no=') ?>" + awb_no,
                success: function(data) {
                    if (data !== "" && data !== undefined) {
                        show_tracking_data(data)
                    } else {
                        alert("No AWB Found")
                    }
                }
            })
        } else {
            alert("Please Enter AWB/FORWARDING NO")
        }
    }
</script>
<?php $this->load->view('common_js_function'); ?>