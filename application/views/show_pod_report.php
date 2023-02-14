<p class="list-group-item bg-heading list-group-item-heading"> POD REPORT</p>
<div style="overflow-y:scroll;max-height:300px;text-align: center;">
    <table class="table table-striped table-responsive table-bordered">
        <tbody>

            <tr>
                <th>Vendor</th>
                <th>Week 6</th>
                <th>Week 5</th>
                <th>Week 4</th>
                <th>Week 3</th>
                <th>Week 2</th>
                <th>Week 1</th>
            </tr>

            <?php
            if (isset($docket_cnt) && is_array($docket_cnt) && count($docket_cnt) > 0) {
                foreach ($docket_cnt as $key => $value) { ?>
                    <tr>
                        <td><?php echo isset($all_vendor[$key]) ? $all_vendor[$key]['code'] : 'Blank' ?></td>
                        <td>
                            <?php echo isset($pod_cnt[$key][5]) ? $pod_cnt[$key][5] : 0 ?> /
                            <?php echo isset($value[5]) ? $value[5] : 0 ?>
                        </td>
                        <td>
                            <?php echo isset($pod_cnt[$key][4]) ? $pod_cnt[$key][4] : 0 ?> /
                            <?php echo isset($value[4]) ? $value[4] : 0 ?>
                        </td>
                        <td>
                            <?php echo isset($pod_cnt[$key][3]) ? $pod_cnt[$key][3] : 0 ?> /
                            <?php echo isset($value[3]) ? $value[3] : 0 ?>
                        </td>
                        <td>
                            <?php echo isset($pod_cnt[$key][2]) ? $pod_cnt[$key][2] : 0 ?> /
                            <?php echo isset($value[2]) ? $value[2] : 0 ?>
                        </td>
                        <td>
                            <?php echo isset($pod_cnt[$key][1]) ? $pod_cnt[$key][1] : 0 ?> /
                            <?php echo isset($value[1]) ? $value[1] : 0 ?>
                        </td>
                        <td>
                            <?php echo isset($pod_cnt[$key][0]) ? $pod_cnt[$key][0] : 0 ?> /
                            <?php echo isset($value[0]) ? $value[0] : 0 ?>
                        </td>
                    </tr>
            <?php  }
            }
            ?>

        </tbody>
    </table>
</div>