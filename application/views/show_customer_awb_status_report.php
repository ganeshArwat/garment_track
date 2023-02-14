<p class="list-group-item bg-heading list-group-item-heading">Customer Docket Status</p>
<div style="overflow:auto;max-height:300px;text-align: center;width:15%;display: inline;float: left;">
    <table class="table table-striped table-responsive table-bordered status_table">
        <tbody>
            <tr>
                <th class="sticky_heading" colspan="2">Total Number of Entries</th>
            </tr>
            <?php
            if (isset($customer_status["total"]) && is_array($customer_status["total"]) && count($customer_status["total"]) > 0) {
                foreach ($customer_status["total"] as $key => $value) { ?>
                    <tr>
                        <td style="width:70%"><?php echo isset($all_customer[$key]) ? $all_customer[$key]['code'] : 'Blank' ?></td>
                        <td style="width:30%"><?php echo $value; ?></td>
                    </tr>

            <?php    }
            }
            ?>
        </tbody>
    </table>
</div>

<div style="overflow:auto;max-height:300px;text-align: center;width:15%;display: inline;float: left;">
    <table class="table table-striped table-responsive table-bordered status_table">
        <tbody>
            <tr>
                <th class="sticky_heading" colspan="2">Entry State</th>
            </tr>
            <?php
            if (isset($customer_status["entry"]) && is_array($customer_status["entry"]) && count($customer_status["entry"]) > 0) {
                foreach ($customer_status["entry"] as $key => $value) { ?>
                    <tr>
                        <td style="width:70%;"><?php echo isset($all_customer[$key]) ? $all_customer[$key]['code'] : 'Blank' ?></td>
                        <td style="width:30%;"><?php echo $value; ?></td>
                    </tr>

            <?php    }
            }
            ?>

        </tbody>
    </table>
</div>

<div style="overflow:auto;max-height:300px;text-align: center;width:15%;display: inline;float: left;">
    <table class="table table-striped table-responsive table-bordered status_table">
        <tbody>
            <tr>
                <th class="sticky_heading" colspan="2">In Transit</th>
            </tr>
            <?php
            if (isset($customer_status["in_transit"]) && is_array($customer_status["in_transit"]) && count($customer_status["in_transit"]) > 0) {
                foreach ($customer_status["in_transit"] as $key => $value) { ?>
                    <tr>
                        <td style="width:70%;"><?php echo isset($all_customer[$key]) ? $all_customer[$key]['code'] : 'Blank' ?></td>
                        <td style="width:30%;"><?php echo $value; ?></td>
                    </tr>

            <?php    }
            }
            ?>
        </tbody>
    </table>
</div>

<div style="overflow:auto;max-height:300px;text-align: center;width:15%;display: inline;float: left;">
    <table class="table table-striped table-responsive table-bordered status_table">
        <tbody>
            <tr>
                <th class="sticky_heading" colspan="2">Cross EDD In Transit</th>
            </tr>
            <?php
            if (isset($customer_status["cross_edd_in_transit"]) && is_array($customer_status["cross_edd_in_transit"]) && count($customer_status["cross_edd_in_transit"]) > 0) {
                foreach ($customer_status["cross_edd_in_transit"] as $key => $value) { ?>
                    <tr>
                        <td style="width:70%;"><?php echo isset($all_customer[$key]) ? $all_customer[$key]['code'] : 'Blank' ?></td>
                        <td style="width:30%;"><?php echo $value; ?></td>
                    </tr>

            <?php    }
            }
            ?>
        </tbody>
    </table>
</div>

<div style="overflow:auto;max-height:300px;text-align: center;width:15%;display: inline;float: left;">
    <table class="table table-striped table-responsive table-bordered status_table">
        <tbody>
            <tr>
                <th class="sticky_heading" colspan="2">Delivered</th>
            </tr>
            <?php
            if (isset($customer_status["delivered"]) && is_array($customer_status["delivered"]) && count($customer_status["delivered"]) > 0) {
                foreach ($customer_status["delivered"] as $key => $value) { ?>
                    <tr>
                        <td style="width:70%;"><?php echo isset($all_customer[$key]) ? $all_customer[$key]['code'] : 'Blank' ?></td>
                        <td style="width:30%;"><?php echo $value; ?></td>
                    </tr>

            <?php    }
            }
            ?>
        </tbody>
    </table>
</div>

<div style="overflow:auto;max-height:300px;text-align: center;width:15%;display: inline;float: left;">
    <table class="table table-striped table-responsive table-bordered status_table">
        <tbody>
            <tr>
                <th class="sticky_heading" colspan="2">About to Cross EDD</th>
            </tr>
            <?php
            if (isset($customer_status["about_to_cross"]) && is_array($customer_status["about_to_cross"]) && count($customer_status["about_to_cross"]) > 0) {
                foreach ($customer_status["about_to_cross"] as $key => $value) { ?>
                    <tr>
                        <td style="width:70%;"><?php echo isset($all_customer[$key]) ? $all_customer[$key]['code'] : 'Blank' ?></td>
                        <td style="width:30%;"><?php echo $value; ?></td>
                    </tr>

            <?php    }
            }
            ?>
        </tbody>
    </table>
</div>


<div style="overflow:auto;max-height:300px;text-align: center;width:10%;display: inline;float: left;">
    <table class="table table-striped table-responsive table-bordered status_table">
        <tbody>
            <tr>
                <th class="sticky_heading" colspan="2">RTO</th>
            </tr>
            <?php
            if (isset($customer_status["rto"]) && is_array($customer_status["rto"]) && count($customer_status["rto"]) > 0) {
                foreach ($customer_status["rto"] as $key => $value) { ?>
                    <tr>
                        <td style="width:70%;"><?php echo isset($all_customer[$key]) ? $all_customer[$key]['code'] : 'Blank' ?></td>
                        <td style="width:30%;"><?php echo $value; ?></td>
                    </tr>

            <?php    }
            }
            ?>
        </tbody>
    </table>
</div>