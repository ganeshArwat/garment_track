<p class="list-group-item bg-heading list-group-item-heading">PICK UP / AWB ENTRY</p>
<div style="overflow-y:scroll;max-height:300px;text-align: center;">
    <table class="table table-striped table-responsive table-bordered">
        <tbody>
            <tr>
                <th class="sticky_heading">Customer</th>

                <?php
                if (isset($all_date) && is_array($all_date) && count($all_date) > 0) {
                    foreach ($all_date as $key => $value) { ?>
                        <th class="sticky_heading" colspan="2"><?php echo date('d M,Y', strtotime($value)) ?></th>
                <?php  }
                }
                ?>
                <th colspan="2" class="sticky_heading">Untill now</th>
            </tr>

            <tr>
                <th></th>

                <?php
                if (isset($all_date) && is_array($all_date) && count($all_date) > 0) {
                    foreach ($all_date as $key => $value) { ?>
                        <th>PickUp</th>
                        <th>AWB</th>
                <?php  }
                }
                ?>
                <th>PickUp</th>
                <th>AWB</th>
            </tr>

            <?php
            if (isset($cust_ids) && is_array($cust_ids) && count($cust_ids) > 0) {
                foreach ($cust_ids as $key => $value) { ?>
                    <tr>
                        <td><?php echo isset($all_customer[$key]) ? $all_customer[$key]['code'] : 'Blank' ?></td>

                        <?php
                        if (isset($all_date) && is_array($all_date) && count($all_date) > 0) {
                            foreach ($all_date as $dkey => $dvalue) { ?>
                                <td>
                                    <?php echo isset($pickup_cnt[$key][$dvalue]) ? $pickup_cnt[$key][$dvalue] : 0 ?>
                                </td>
                                <td>
                                    <?php
                                    if (isset($pickup_cnt[$key][$dvalue]) && isset($docket_cnt[$key][$dvalue])) {
                                        echo isset($pickup_cnt[$key][$dvalue]) ? $pickup_cnt[$key][$dvalue] - $docket_cnt[$key][$dvalue] : 0;
                                    } else {
                                        echo isset($pickup_cnt[$key][$dvalue]) ? $pickup_cnt[$key][$dvalue] : 0;
                                    }
                                    ?>
                                </td>
                        <?php  }
                        }
                        ?>

                        <td>
                            <?php echo isset($pickup_cnt[$key]['untill']) ? $pickup_cnt[$key]['untill'] : 0 ?>
                        </td>
                        <td>
                            <?php
                            if (isset($pickup_cnt[$key]["untill"]) && isset($docket_cnt[$key]["untill"])) {
                                echo isset($pickup_cnt[$key]["untill"]) ? $pickup_cnt[$key]["untill"] - $docket_cnt[$key]["untill"] : 0;
                            } else {
                                echo isset($pickup_cnt[$key]["untill"]) ? $pickup_cnt[$key]["untill"] : 0;
                            }
                            ?>
                        </td>
                    </tr>



                <?php  }
            } else { ?>
                <tr>
                    <td colspan="7">No Record Found.</td>
                </tr>
            <?php }
            ?>

        </tbody>
    </table>
</div>