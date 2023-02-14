<p class="list-group-item bg-heading list-group-item-heading">TRACKING FAILED REPORT</p>
<div style="overflow-y:scroll;max-height:300px;text-align: center;">
    <table class="table table-striped table-responsive table-bordered">
        <tbody>


            <tr>
                <th>Vendor</th>
                <?php
                if (isset($all_date) && is_array($all_date) && count($all_date) > 0) {
                    foreach ($all_date as $key => $value) { ?>
                        <th><?php echo date('d M,Y', strtotime($value)) ?></th>
                <?php  }
                }
                ?>
            </tr>

            <?php
            if (isset($docket_cnt) && is_array($docket_cnt) && count($docket_cnt) > 0) {
                foreach ($docket_cnt as $key => $value) { ?>
                    <tr>
                        <td><?php echo isset($all_vendor[$key]) ? $all_vendor[$key]['code'] : 'Blank' ?></td>

                        <?php
                        if (isset($all_date) && is_array($all_date) && count($all_date) > 0) {
                            foreach ($all_date as $dkey => $dvalue) { ?>
                                <td>
                                    <?php echo isset($docket_cnt[$key][$dvalue]) ? $docket_cnt[$key][$dvalue] : 0 ?>
                                </td>
                        <?php  }
                        }
                        ?>


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