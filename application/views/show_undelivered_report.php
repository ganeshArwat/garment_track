<p class="list-group-item bg-heading list-group-item-heading">UNDELIVERED REPORT</p>
<div style="overflow-y:scroll;max-height:300px;text-align: center;">
    <table class="table table-striped table-responsive table-bordered">
        <tbody>
            <tr>
                <th class="sticky_heading">Vendor</th>
                <th class="sticky_heading">Week 6
                    <br> <?php echo date('d/m/Y', strtotime($week_data[0]['start'])) . ' - ' . date('d/m/Y', strtotime($week_data[0]['end'])) ?>
                </th>
                <th class="sticky_heading">Week 5
                    <br><?php echo date('d/m/Y', strtotime($week_data[1]['start'])) . ' - ' . date('d/m/Y', strtotime($week_data[1]['end'])) ?>
                </th>
                <th class="sticky_heading">Week 4
                    <br><?php echo date('d/m/Y', strtotime($week_data[2]['start'])) . ' - ' . date('d/m/Y', strtotime($week_data[2]['end'])) ?>
                </th>
                <th class="sticky_heading">Week 3
                    <br> <?php echo date('d/m/Y', strtotime($week_data[3]['start'])) . ' - ' . date('d/m/Y', strtotime($week_data[3]['end'])) ?>
                </th>
                <th class="sticky_heading">Week 2
                    <br><?php echo date('d/m/Y', strtotime($week_data[4]['start'])) . ' - ' . date('d/m/Y', strtotime($week_data[4]['end'])) ?>
                </th>
                <th class="sticky_heading">Week 1
                    <br> <?php echo date('d/m/Y', strtotime($week_data[5]['start'])) . ' - ' . date('d/m/Y', strtotime($week_data[5]['end'])) ?>
                </th>
            </tr>

            <?php
            if (isset($docket_cnt) && is_array($docket_cnt) && count($docket_cnt) > 0) {
                foreach ($docket_cnt as $key => $value) { ?>
                    <tr>
                        <td><?php echo isset($all_vendor[$key]) ? $all_vendor[$key]['code'] : 'Blank' ?></td>
                        <td>
                            <?php echo isset($undeliver_cnt[$key][0]) ? $undeliver_cnt[$key][0] : 0 ?> /
                            <?php echo isset($value[0]) ? $value[0] : 0 ?>
                        </td>
                        <td>
                            <?php echo isset($undeliver_cnt[$key][1]) ? $undeliver_cnt[$key][1] : 0 ?> /
                            <?php echo isset($value[1]) ? $value[1] : 0 ?>
                        </td>
                        <td>
                            <?php echo isset($undeliver_cnt[$key][2]) ? $undeliver_cnt[$key][2] : 0 ?> /
                            <?php echo isset($value[2]) ? $value[2] : 0 ?>
                        </td>
                        <td>
                            <?php echo isset($undeliver_cnt[$key][3]) ? $undeliver_cnt[$key][3] : 0 ?> /
                            <?php echo isset($value[3]) ? $value[3] : 0 ?>
                        </td>
                        <td>
                            <?php echo isset($undeliver_cnt[$key][4]) ? $undeliver_cnt[$key][4] : 0 ?> /
                            <?php echo isset($value[4]) ? $value[4] : 0 ?>
                        </td>
                        <td>
                            <?php echo isset($undeliver_cnt[$key][5]) ? $undeliver_cnt[$key][5] : 0 ?> /
                            <?php echo isset($value[5]) ? $value[5] : 0 ?>
                        </td>
                    </tr>
            <?php  }
            }
            ?>

        </tbody>
    </table>
</div>