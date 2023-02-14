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
                <th style="border: 1px solid #ddd; text-align: center;">Sr No</th>
                <th style="border: 1px solid #ddd; text-align: center;">Booking Date</th>
                <th style="border: 1px solid #ddd; text-align: center;">C.Note</th>
                <th style="border: 1px solid #ddd; text-align: center;">Destination</th>
                <th style="border: 1px solid #ddd; text-align: center;">Consignee Name</th>
                <th style="border: 1px solid #ddd; text-align: center;">Delivery Date</th>
                <th style="border: 1px solid #ddd; text-align: center;">Delivery Time</th>
                <th style="border: 1px solid #ddd; text-align: center;">Receiever Name</th>
                <th style="border: 1px solid #ddd; text-align: center;">POD</th>
            </tr>
            <?php
            $row_cnt = 1;
            if (isset($docket_data) && is_array($docket_data) && count($docket_data) > 0) {
                foreach ($docket_data as $key => $value) { ?>
                    <tr>
                        <td style="border: 1px solid #ddd;padding: 8px;text-align: center;"><?php echo $row_cnt; ?></td>
                        <td style="border: 1px solid #ddd;padding: 8px;text-align: center;"><?php echo isset($value['booking_date']) ? date('d-M-Y', strtotime($value['booking_date'])) : ''; ?></td>
                        <td style="border: 1px solid #ddd;padding: 8px;text-align: center;"><?php echo isset($value['awb_no']) ? $value['awb_no'] : ''; ?></td>
                        <td style="border: 1px solid #ddd;padding: 8px;text-align: center;"><?php echo isset($all_location[$value['destination_id']]) ? $all_location[$value['destination_id']]['name'] : ''; ?></td>
                        <td style="border: 1px solid #ddd;padding: 8px;text-align: center;"><?php echo isset($value['con_name']) ? $value['con_name'] : ''; ?></td>
                        <td style="border: 1px solid #ddd;padding: 8px;text-align: center;"><?php echo isset($value['delivery_date']) ? date('d-M-Y', strtotime($value['delivery_date'])) : ''; ?></td>
                        <td style="border: 1px solid #ddd;padding: 8px;text-align: center;"><?php echo isset($value['delivery_time']) ? date('h:i a', strtotime($value['delivery_time'])) : ''; ?></td>
                        <td style="border: 1px solid #ddd;padding: 8px;text-align: center;"><?php echo isset($value['receiver_name']) ? $value['receiver_name'] : ''; ?></td>
                        <td style="border: 1px solid #ddd;padding: 8px;text-align: center;">
                            <?php
                            if (isset($pod_image[$value['id']])) { ?>
                                <a href="<?php echo $pod_image[$value['id']]; ?>" style="background: #963b3b;color: white;padding: 4px 6px;border-radius: 10%;margin: 2px;text-decoration:none;">VIEW POD</a>
                            <?php }
                            ?>
                        </td>
                    </tr>
            <?php $row_cnt++;
                }
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