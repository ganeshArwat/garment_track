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
                <?php
                if (isset($selected_field) && is_array($selected_field) && count($selected_field) > 0) {
                    foreach ($selected_field as $key => $value) { ?>
                        <th style="border: 1px solid #ddd; text-align: center;"><?php echo isset($columns[$value]) ?  strtoupper($columns[$value]['field_name']) : '' ?></th>
                <?php   }
                }
                ?>
            </tr>
            <?php
            if (isset($result) && is_array($result) && count($result) > 0) {
                foreach ($result as $rkey => $rvalue) {

            ?>
                    <tr>
                        <?php

                        if (isset($selected_field) && is_array($selected_field) && count($selected_field) > 0) {
                            foreach ($selected_field as $key => $value) {
                                $label_key = $columns[$value]['field_col_name'];
                                $field_type = $columns[$value]['field_type'];
                                $master_name = $columns[$value]['master_name'];
                                $master_col_name = $columns[$value]['master_col_name'];


                        ?>
                                <td style="border: 1px solid #ddd;padding: 8px;text-align: center;">
                                    <?php
                                    if ($field_type == 'select') {
                                        echo isset($select_data[$master_name][$rvalue[$label_key]]) ? $select_data[$master_name][$rvalue[$label_key]][$master_col_name] : '';
                                    } else {
                                        echo isset($rvalue[$label_key]) ? $rvalue[$label_key] : '';
                                    }
                                    ?>
                                </td>
                        <?php   }
                        }
                        ?>
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