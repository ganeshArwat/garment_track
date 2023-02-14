<!DOCTYPE html>
<html>

<head>
    <style>
        table {
            border-collapse: collapse;
        }

        #result_table th {
            padding: 5px 5px;
            text-align: left;
            background-color: #14226e;
            color: white;
            text-align: center;
        }
    </style>
</head>

<body style="font-family: Calibri;font-size: 16px;color: #000080;">

    <div>
        <p><?php echo isset($msg_body) ? $msg_body : ''; ?></p>
    </div>

    <div>

        <table cellspacing="0" width="100%" style="border-collapse: collapse; width: 100%;" id="result_table">
            <tr>
                <th style="border: 1px solid #ddd; text-align: center;">Awb</th>
                <th style="border: 1px solid #ddd; text-align: center;">Forwarding No</th>
                <th style="border: 1px solid #ddd; text-align: center;">Reference No</th>
                <th style="border: 1px solid #ddd; text-align: center;">Shipper</th>
                <th style="border: 1px solid #ddd; text-align: center;">Consignee</th>
                <th style="border: 1px solid #ddd; text-align: center;">Destination</th>
                <th style="border: 1px solid #ddd; text-align: center;">Pcs</th>
                <th style="border: 1px solid #ddd; text-align: center;">Actual Weight</th>
                <th style="border: 1px solid #ddd; text-align: center;">Chargeable Weight</th>
                <th style="border: 1px solid #ddd; text-align: center;">Product</th>
            </tr>
            <tr>
                <td style="border: 1px solid #ddd;padding: 8px;text-align: center;"><?php echo isset($docket_data['awb_no']) ? $docket_data['awb_no'] : ''; ?></td>
                <?php
                if (isset($docket_data['vendor_id']) && isset($all_vendor[$docket_data['vendor_id']])) {
                    if ($all_vendor[$docket_data['vendor_id']]['hide_forwarding_no_from_website'] == 1) {
                        $docket_data['forwarding_no'] = '';
                    }
                }
                ?>
                <td style="border: 1px solid #ddd;padding: 8px;text-align: center;"><?php echo isset($docket_data['forwarding_no']) ? $docket_data['forwarding_no'] : ''; ?></td>
                <td style="border: 1px solid #ddd;padding: 8px;text-align: center;"><?php echo isset($docket_data['reference_no']) ? $docket_data['reference_no'] : ''; ?></td>
                <td style="border: 1px solid #ddd;padding: 8px;text-align: center;"><?php echo isset($docket_data['shi_name']) ? $docket_data['shi_name'] : ''; ?></td>
                <td style="border: 1px solid #ddd;padding: 8px;text-align: center;"><?php echo isset($docket_data['con_name']) ? $docket_data['con_name'] : ''; ?></td>
                <td style="border: 1px solid #ddd;padding: 8px;text-align: center;"><?php echo isset($all_location[$docket_data['destination_id']]) ? $all_location[$docket_data['destination_id']]['name'] : ''; ?></td>
                <td style="border: 1px solid #ddd;padding: 8px;text-align: center;"><?php echo isset($docket_data['total_pcs']) ? $docket_data['total_pcs'] : ''; ?></td>
                <td style="border: 1px solid #ddd;padding: 8px;text-align: center;"><?php echo isset($docket_data['actual_wt']) ? $docket_data['actual_wt'] : ''; ?></td>
                <td style="border: 1px solid #ddd;padding: 8px;text-align: center;"><?php echo isset($docket_data['chargeable_wt']) ? $docket_data['chargeable_wt'] : ''; ?></td>
                <td style="border: 1px solid #ddd;padding: 8px;text-align: center;"><?php echo isset($all_product[$docket_data['product_id']]) ? $all_product[$docket_data['product_id']]['name'] : ''; ?></td>
            </tr>
        </table>

    </div>

    <div style="margin-top: 60px;">
        <p>Thanks & Regards </p>
        <p><i>Note: This is auto generated email, please do not reply here.</i></p>
    </div>
</body>

</html>