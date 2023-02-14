<html>

<head>
    <title>Manifest AWB Print</title>
    <style type="text/css">
        table,
        th,
        td {
            border-collapse: collapse;
            padding: 0;
            vertical-align: top;
            line-height: 16px;
            padding: 3px;
            /*white-space: nowrap;*/
            text-align: left;
        }

        .blt {
            border: 0;
            line-height: 0px;
        }

        .blt-p {
            border: 0;
            line-height: 11px;
        }

        table,
        tbody {
            word-break: break-word;
        }

        thead {
            display: table-header-group
        }

        tfoot {
            display: table-row-group
        }

        thead:before,
        thead:after {
            display: none;
        }

        tbody,
        tr {
            page-break-inside: avoid !important;
            break-inside: avoid;
            -webkit-column-break-inside: avoid;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            text-align: center;
            font-size: 11px;
        }

        body {
            font-family: sans-serif;
        }

        th,
        td {
            border: 1px solid gray;
            font-size: 11px;
        }

        .nowrap {
            white-space: normal;
        }
    </style>
</head>

<body>
    <table>

        <tr>
            <td colspan='10' style="text-align: center;" class="blt-p">
                <img src="<?php echo base_url($company_data['logo_file']); ?>" style="height: 100px;width:100px;" />
            </td>
        </tr>


        <tr>
            <td colspan='10' style="text-align: center;" class="blt-p">
                <p>
                    <b>ADDRESS : </b>
                <p style="font-size: 11px;"><?php echo isset($company_data['address']) ? strtoupper($company_data['address']) : ''; ?></p>
                </p>
                <p>
                    <b>PHONE NO : </b>
                <p style="font-size: 11px;"><?php echo isset($company_data['contact_no']) ? strtoupper($company_data['contact_no']) : ''; ?></p>
                </p>
            </td>
        </tr>


        <tr>
            <td colspan="10" class="blt"></td>
        </tr>
        <tr>
            <th colspan="2" style="width: 5%;">MANIFEST NO</th>
            <td colspan="2"><?php echo $manifest['id']; ?></td>

            <td colspan='2' rowspan='5' class="blt" style="text-align: center; border-top: 1px solid grey;width:35%;">
                <h2>MANIFEST</h2>
            </td>
            <th colspan="2">FLIGHT NO</th>
            <td colspan="2"><?php echo $manifest['flight_no']; ?></td>
        </tr>

        <tr>
            <th colspan="2">MANIFEST DATE</th>
            <td colspan="2"><?php echo date('d/m/Y', strtotime($manifest['manifest_date'])); ?></td>

            <th colspan="2">VENDOR NAME</th>
            <td colspan='2' class="nowrap"><?php echo isset($all_co_vendor[$manifest['co_vendor_id']]) ? strtoupper($all_co_vendor[$manifest['co_vendor_id']]['name']) : ''; ?></td>
        </tr>

        <tr>
            <th colspan="2">ORIGIN</th>
            <td colspan="2"><?php echo isset($all_hub[$manifest['ori_hub_id']]) ? strtoupper($all_hub[$manifest['ori_hub_id']]['code']) : ''; ?></td>

            <th colspan="2">MODE</th>
            <td colspan="2"></td>
        </tr>

        <tr>
            <th colspan="2">DESTINATION</th>
            <td colspan="2"><?php echo isset($all_hub[$manifest['dest_hub_id']]) ? strtoupper($all_hub[$manifest['dest_hub_id']]['code']) : ''; ?></td>

            <th colspan="2">VENDOR WEIGHT</th>
            <td colspan='2'><?php echo $manifest['vendor_wt']; ?></td>
        </tr>

        <tr>
            <th colspan="2">DESTINATION HUB NAME</th>
            <td colspan="2"><?php echo isset($all_hub[$manifest['dest_hub_id']]) ? strtoupper($all_hub[$manifest['dest_hub_id']]['name']) : ''; ?></td>

            <th colspan="2">FORWARDER</th>
            <td colspan='2' class="nowrap"><?php echo isset($all_forwarder[$manifest['forwarder_id']]) ? strtoupper($all_forwarder[$manifest['forwarder_id']]['code']) : ''; ?></td>
        </tr>

        <tr>
            <th colspan="2">VENDOR CD NO.</th>
            <td colspan="2" class="nowrap"><?php echo $manifest['vendor_cd_no']; ?></td>

            <td colspan='2'></td>
            <th colspan="2">VEHICLE NUMBER</th>
            <td colspan='2'><?php echo $manifest['vehicle_no']; ?></td>
        </tr>
        <tr rowspan='2' class="blt">
            <td colspan="10" class="blt"></td>
        </tr>
        </br>
        </br>
        <tr>
            <?php
            if (isset($setting['forwarding_in_pdf']) && $setting['forwarding_in_pdf'] == 1) { ?>
                <th colspan="2" style="width: 5%;">FORWORDING NO.</th>
            <?php  } else { ?>
                <th colspan="2" style="width: 5%;">AWB NO.</th>
            <?php } ?>

            <th style="width: 10%;">BOOKING DATE</th>
            <?php if (isset($setting['manifest_pdf_show_customer_name']) && $setting['manifest_pdf_show_customer_name'] == 1) { ?>
                <th style="width: 10%;">CUSTOMER NAME</th>
            <?php  } else { ?>
                <th style="width: 10%;">SHIPPER NAME</th>
            <?php } ?>
            <th style="width: 35%;" colspan="2">CONSIGNEE NAME</th>
            <th style="width: 10%;">DESTINATION</th>
            <th style="width: 10%;">PIN CODE</th>
            <th style="width: 10%;">ACTL. WEIGHT</th>
            <th style="width: 10%;">NO OF PCS</th>
        </tr>

        <?php
        $row_cnt = 0;
        if (isset($docket_data) && is_array($docket_data) && count($docket_data) > 0) {
            foreach ($docket_data as $dkey => $dvalue) { ?>
                <tr>
                    <?php
                    if (isset($setting['forwarding_in_pdf']) && $setting['forwarding_in_pdf'] == 1) { ?>
                        <td colspan="2"><?php echo $dvalue['forwarding_no']; ?></td>
                    <?php  } else { ?>
                        <td colspan="2"><?php echo $dvalue['awb_no']; ?></td>
                    <?php } ?>

                    <td><?php echo date('d/m/Y', strtotime($dvalue['booking_date'])); ?></td>
                    <?php if (isset($setting['manifest_pdf_show_customer_name']) && $setting['manifest_pdf_show_customer_name'] == 1) { ?>
                        <td><?php echo isset($all_customer[$dvalue['customer_id']]) ? ($all_customer[$dvalue['customer_id']]['name']) : ''; ?></td>
                    <?php  } else { ?>
                        <td><?php echo $dvalue['shi_name']; ?></td>
                    <?php } ?>

                    <td colspan="2"><?php echo $dvalue['con_name']; ?></td>
                    <td><?php echo isset($all_location[$dvalue['destination_id']]) ? strtoupper($all_location[$dvalue['destination_id']]['name']) : ''; ?></td>
                    <td><?php echo $dvalue['des_pincode']; ?></td>
                    <td><?php echo $dvalue['chargeable_wt']; ?></td>
                    <td><?php echo $dvalue['total_pcs']; ?></td>

                </tr>
        <?php $row_cnt++;
            }
        }
        ?>
        <tr rowspan='2' class="blt">
            <td colspan="10" class="blt"></td>
        </tr>
        </br>
        </br>
        <tr>
            <th colspan="2">No Of Records</th>
            <td colspan="8"><?php echo $row_cnt; ?></td>
        </tr>

        <tr>
            <th colspan="2">No Of Pcs</th>
            <td colspan='8'><?php echo $manifest['total_bags_count']; ?></td>
        </tr>

        <tr>
            <th colspan="2">Total Weight</th>
            <td colspan='8'><?php echo $manifest['weight_total']; ?></td>
        </tr>
    </table>
</body>

</html>