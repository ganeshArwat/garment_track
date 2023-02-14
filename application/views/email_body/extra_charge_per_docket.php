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
                <th style="border: 1px solid #ddd; text-align: center;">Date</th>
                <th style="border: 1px solid #ddd; text-align: center;">AWB NO.</th>
                <th style="border: 1px solid #ddd; text-align: center;">Charge</th>
                <th style="border: 1px solid #ddd; text-align: center;">Amount</th>
            </tr>

            <?php
            if (isset($docket_charge) && is_array($docket_charge) && count($docket_charge) > 0) {
                foreach ($docket_charge as $key => $value) {
                    if (isset($all_charge[$value['charge_id']])) {
            ?>
                        <tr>
                            <td style="border: 1px solid #ddd;padding: 8px;text-align: center;"><?php echo isset($value['booking_date']) ?  get_format_date(DATE_FORMAT, ($value['booking_date'])) : ''; ?></td>
                            <td style="border: 1px solid #ddd;padding: 8px;text-align: center;"><?php echo isset($value['awb_no']) ? $value['awb_no'] : ''; ?></td>
                            <td style="border: 1px solid #ddd;padding: 8px;text-align: center;"><?php echo isset($all_charge[$value['charge_id']]) ? $all_charge[$value['charge_id']]['name'] : ''; ?></td>
                            <td style="border: 1px solid #ddd;padding: 8px;text-align: center;"><?php echo isset($value['charge_amount']) ? $value['charge_amount'] : ''; ?></td>
                        </tr>
            <?php
                    }
                }
            }   ?>

        </table>

    </div>

    <div style="margin-top: 60px;">
        <p>Thanks & Regards </p>
        <p><i>Note: This is auto generated email, please do not reply here.</i></p>
    </div>
</body>

</html>