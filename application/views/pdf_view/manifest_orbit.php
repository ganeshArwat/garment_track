<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title></title>
    <style type="text/css">
        * {
            margin: 0 !important;
            padding: 0 !important;
        }

        body {
            margin: auto !important;
        }

        table {
            position: relative;
            width: 100%;

            /* height: 297mm;
            min-height: 297mm;
            max-height: 297mm; */

            border-collapse: collapse;
            font-size: 12px;
            font-weight: normal;
            font-family: sans-serif;
        }

        table td {
            border: 1px solid #ccc;
            padding: 4px !important;
            color: #333;
        }

        @page {
            size: A4;
            margin: 0 !important;
        }

        @media print {

            html,
            body {
                width: 210mm;
                height: 297mm;
            }
        }
    </style>
</head>

<body>
    <table cellspacing="0" cellpadding="0">
        <tr>
            <td colspan="12" style="text-align: center;"><img src="<?php echo base_url($company_data['logo_file']); ?>" style="width: 150px;padding: 10px 0 !important;"></td>
        </tr>
        <tr>
            <td colspan="12" style="text-align: center;"><b style="font-size: 16px;"><?php echo isset($company_data['name']) ? $company_data['name'] : "" ?></b><br><b>PHONE NO :</b> <?php echo isset($company_data['contact_no']) ? $company_data['contact_no'] : "" ?></td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center;"><b>MANIFEST DATE</b></td>
            <td colspan="1" style="text-align: center;"><?php echo date('d/m/Y', strtotime($manifest['manifest_date'])); ?></td>
            <td colspan="4" style="text-align: center;"><b>MANIFEST#<?php echo $manifest['id']; ?></b></td>
            <td colspan="1" style="text-align: center;"><b>VENDOR</b></td>
            <td colspan="4" style="text-align: center;"><?php echo isset($all_co_vendor[$manifest['co_vendor_id']]) ? strtoupper($all_co_vendor[$manifest['co_vendor_id']]['name']) : ''; ?></td>
        </tr>
        <tr style="text-align: center;">
            <td>
                SR NO.
            </td>
            <td>
                AWB NO.
            </td>
            <td>
                CONSIGNEE NAME
            </td>
            <td>
                FORWARDING NO.
            </td>
            <td>
                DESTINATION
            </td>
            <td>
                PCS
            </td>
            <td>
                DOX/NONDOX
            </td>
            <td>
                WEIGHT
            </td>



            <td style="width: 140px !important;">
                DIMENSION
            </td>
            <td>
                SERVICE
            </td>
            <td>
                AMOUNT
            </td>
            <td>
                AMOUNT
            </td>
        </tr>

        <?php if (isset($docket_data) && is_array($docket_data) && count($docket_data) > 0) {
            $srno = 0;
            foreach ($docket_data as $dkey => $dvalue) {
                $srno += 1; ?>
                <tr>
                    <td>
                        <?php echo $srno; ?>
                    </td>
                    <td><?php echo $dvalue['awb_no']; ?></td>
                    <td>
                        <?php echo $dvalue['con_name']; ?>
                    </td>
                    <td><?php echo $dvalue['forwarding_no']; ?></td>

                    <td>
                        <?php echo isset($all_location[$dvalue['destination_id']]) ? strtoupper($all_location[$dvalue['destination_id']]['name']) : ''; ?>
                    </td>
                    <td>
                        <?php echo (int)$dvalue['total_pcs']; ?>
                    </td>
                    <td>
                        <?php echo isset($all_product[$dvalue['product_id']]) ? ($all_product[$dvalue['product_id']]['name']) : ''; ?>
                    </td>
                    <td>
                        <?php echo $dvalue['actual_wt']; ?>
                    </td>



                    <td style="width: 140px !important;">


                        <?php if (isset($dvalue["docket_items"]) && is_array($dvalue["docket_items"]) && count($dvalue["docket_items"]) > 0) {
                            foreach ($dvalue["docket_items"] as $dikey => $divalue) {
                                echo (isset($divalue["item_length"]) && (($divalue["item_length"] - floor($divalue["item_length"])) != 0) ? $divalue["item_length"] : floor($divalue["item_length"])) . "*" . (isset($divalue["item_width"]) && (($divalue["item_width"] - floor($divalue["item_width"])) != 0) ? $divalue["item_width"] : floor($divalue["item_width"])) . "*" . (isset($divalue["item_height"]) && (($divalue["item_height"] - floor($divalue["item_height"])) != 0) ? $divalue["item_height"] : floor($divalue["item_height"])) . " = " . $divalue["volumetric_wt"] . "<br>";
                            }
                        } ?>
                    </td>
                    <td>
                        <?php echo isset($all_vendor[$dvalue['vendor_id']]) ? ($all_vendor[$dvalue['vendor_id']]['name']) : ''; ?>
                    <td>
                        <br>
                    </td>
                    <td>
                        <br>
                    </td>
                </tr>
        <?php }
        } ?>

    </table>
</body>

</html>