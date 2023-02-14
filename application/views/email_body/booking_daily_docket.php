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
                <th style="border: 1px solid #ddd; text-align: center;">Booking Date</th>
                <th style="border: 1px solid #ddd; text-align: center;">AWB</th>
                <th style="border: 1px solid #ddd; text-align: center;">Forwarding No</th>
                <th style="border: 1px solid #ddd; text-align: center;">Reference No</th>
                <th style="border: 1px solid #ddd; text-align: center;">Service Code</th>
                <th style="border: 1px solid #ddd; text-align: center;">Destination Code</th>
                <th style="border: 1px solid #ddd; text-align: center;">Destination Name</th>
                <th style="border: 1px solid #ddd; text-align: center;">Chargeable Weight</th>
                <th style="border: 1px solid #ddd; text-align: center;">Consignee Name</th>
            </tr>
            <?php
            if (isset($docket_data) && is_array($docket_data) && count($docket_data) > 0) {
                foreach ($docket_data as $key => $value) {
                    $forwarding_no = isset($value['forwarding_no']) ? $value['forwarding_no'] : '';
                    if (isset($value['vendor_id']) && isset($all_vendor[$value['vendor_id']])) {
                        if ($all_vendor[$value['vendor_id']]['hide_forwarding_no_from_website'] == 1) {
                            $forwarding_no  = '';
                        }
                    }
            ?>
                    <tr>
                        <td style="border: 1px solid #ddd;padding: 8px;text-align: center;"><?php echo isset($value['booking_date']) ? date('d-M-Y', strtotime($value['booking_date'])) : ''; ?></td>
                        <td style="border: 1px solid #ddd;padding: 8px;text-align: center;"><?php echo isset($value['awb_no']) ? $value['awb_no'] : ''; ?></td>
                        <td style="border: 1px solid #ddd;padding: 8px;text-align: center;"><?php echo isset($forwarding_no) ? $forwarding_no : ''; ?></td>
                        <td style="border: 1px solid #ddd;padding: 8px;text-align: center;"><?php echo isset($value['reference_no']) ? $value['reference_no'] : ''; ?></td>
                        <td style="border: 1px solid #ddd;padding: 8px;text-align: center;"><?php echo isset($all_vendor[$value['vendor_id']]) ? strtoupper($all_vendor[$value['vendor_id']]['name']) : ''; ?></td>
                        <td style="border: 1px solid #ddd;padding: 8px;text-align: center;"><?php echo isset($all_location[$value['destination_id']]) ? strtoupper($all_location[$value['destination_id']]['code']) : ''; ?></td>
                        <td style="border: 1px solid #ddd;padding: 8px;text-align: center;"><?php echo isset($all_location[$value['destination_id']]) ? strtoupper($all_location[$value['destination_id']]['name']) : ''; ?></td>
                        <td style="border: 1px solid #ddd;padding: 8px;text-align: center;"><?php echo isset($value['chargeable_wt']) ? $value['chargeable_wt'] : ''; ?></td>
                        <td style="border: 1px solid #ddd;padding: 8px;text-align: center;"><?php echo isset($value['con_name']) ? strtoupper($value['con_name']) : ''; ?></td>
                    </tr>
            <?php  }
            }
            ?>

        </table>

    </div>

    <div style="margin-top: 60px;">
        <p>Thanks & Regards </p>
        <p><i>Note: This is auto generated email, please do not reply here.</i></p>
    </div>
</body>

</html>