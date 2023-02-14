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
                <th style="border: 1px solid #ddd; text-align: center;">Tracking No</th>
                <th style="border: 1px solid #ddd; text-align: center;">Forwarding No</th>
                <th style="border: 1px solid #ddd; text-align: center;">Old Weight</th>
                <th style="border: 1px solid #ddd; text-align: center;">New Weight</th>
                <th style="border: 1px solid #ddd; text-align: center;">Updated On</th>
            </tr>
            <tr>
                <td style="border: 1px solid #ddd;padding: 8px;text-align: center;"><?php echo isset($docket_data['awb_no']) ? $docket_data['awb_no'] : ''; ?></td>
                <td style="border: 1px solid #ddd;padding: 8px;text-align: center;"><?php echo isset($docket_data['forwarding_no']) ? $docket_data['forwarding_no'] : ''; ?></td>
                <td style="border: 1px solid #ddd;padding: 8px;text-align: center;"><?php echo isset($old_data['old_wt']) ? $old_data['old_wt'] : ''; ?></td>
                <td style="border: 1px solid #ddd;padding: 8px;text-align: center;"><?php echo isset($docket_data['chargeable_wt']) ? $docket_data['chargeable_wt'] : ''; ?></td>
                <td style="border: 1px solid #ddd;padding: 8px;text-align: center;"><?php echo isset($docket_data['modified_date']) ? date('d-M-Y h:i a', strtotime($docket_data['modified_date'])) : ''; ?></td>
            </tr>
        </table>

    </div>

    <div style="margin-top: 60px;">
        <p>Thanks & Regards </p>
        <p><i>Note: This is auto generated email, please do not reply here.</i></p>
    </div>
</body>

</html>