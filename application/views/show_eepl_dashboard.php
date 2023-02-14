<div class="col-12">
    <style>
        .report_heading {
            text-align: center;
            font-size: 16px;
            font-style: italic;
            color: #035969;
            background: #afb9c1;
        }

        .sticky_heading {
            position: sticky;
            top: 0;
            background: #daeff8;
            text-align: center;
        }

        .bg-heading {
            background-color: #272355 !important;
            color: #fff !important;
            text-align: center;
        }
    </style>
    <?php $this->load->view('flashdata_msg'); ?>
    <!-- /.box -->

    <div class="box">
        <div class="box-body no-padding">
            <div class="row">

                <div class="col-12">
                    <div id="undelivered_report" style="text-align: center;border: 1px solid #272355;margin: 10px;">
                        <h3 class="report_heading">UNDELIVERED REPORT</h3>
                        <img src="<?php echo base_url('media') ?>/loading_image.gif" style="height: 3vw;width: 3vw;">
                    </div>
                </div>
                <div class="col-6">
                    <div id="pod_report" style="text-align: center;border: 1px solid #272355;margin: 10px;">
                        <h3 class="report_heading">POD REPORT</h3>
                        <img src="<?php echo base_url('media') ?>/loading_image.gif" style="height: 3vw;width: 3vw;">
                    </div>
                </div>
                <div class="col-6">
                    <div id="tracking_report" style="text-align: center;border: 1px solid #272355;margin: 10px;">
                        <h3 class="report_heading">TRACKING FAILED REPORT</h3>
                        <img src="<?php echo base_url('media') ?>/loading_image.gif" style="height: 3vw;width: 3vw;">
                    </div>
                </div>
                <div class="col-12">
                    <div id="awb_report" style="text-align: center;border: 1px solid #272355;margin: 10px;">
                        <h3 class="report_heading">PICK UP / AWB ENTRY</h3>
                        <img src="<?php echo base_url('media') ?>/loading_image.gif" style="height: 3vw;width: 3vw;">
                    </div>
                </div>

                <div class="col-12">
                    <div id="docket_status_customer" style="text-align: center;border: 1px solid #272355;margin: 10px;">
                        <h3 class="report_heading">Docket Status Customer Wise</h3>
                        <img src="<?php echo base_url('media') ?>/loading_image.gif" style="height: 3vw;width: 3vw;">
                    </div>
                </div>
                <div class="col-12">
                    <div id="docket_status_vendor" style="text-align: center;border: 1px solid #272355;margin: 10px;">
                        <h3 class="report_heading">Docket Status Vendor Wise</h3>
                        <img src="<?php echo base_url('media') ?>/loading_image.gif" style="height: 3vw;width: 3vw;">
                    </div>
                </div>

            </div>

        </div>
    </div>

</div>
<script>
    function get_undelivered_report_data() {

        $.ajax({
            type: "POST",
            url: "<?php echo site_url('dashboard/show_undelivered_report') ?>",
            data: {},
            success: function(data) {
                $("#undelivered_report").html(data);
            },
            error: function(data) {
                $("#undelivered_report").html('');
                console.log("errror");
            }
        });
    }

    function get_pod_report_data() {

        $.ajax({
            type: "POST",
            url: "<?php echo site_url('dashboard/show_pod_report') ?>",
            data: {},
            success: function(data) {
                $("#pod_report").html(data);
            },
            error: function(data) {
                $("#pod_report").html('');
                console.log("errror");
            }
        });
    }

    function get_tracking_report_data() {

        $.ajax({
            type: "POST",
            url: "<?php echo site_url('dashboard/show_tracking_report') ?>",
            data: {},
            success: function(data) {
                $("#tracking_report").html(data);
            },
            error: function(data) {
                $("#tracking_report").html('');
                console.log("errror");
            }
        });
    }

    function get_awb_report_data() {

        $.ajax({
            type: "POST",
            url: "<?php echo site_url('dashboard/show_awb_report') ?>",
            data: {},
            success: function(data) {
                $("#awb_report").html(data);
            },
            error: function(data) {
                $("#awb_report").html('');
                console.log("errror");
            }
        });
    }

    $(document).ready(function() {
        get_undelivered_report_data();
        get_pod_report_data();
        get_tracking_report_data();
        get_awb_report_data();
    });
</script>