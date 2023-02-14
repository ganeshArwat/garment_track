<div class="tab-pane" id="dashboard-tab">
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['show_run_performance_report']) ? $setting_comment['show_run_performance_report'] : ''; ?>" class="col-sm-6 col-form-label">Show Run perfomance in dashboard</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="dashboard[show_run_performance_report]" id="" cols="80" rows="1"><?php echo isset($setting['show_run_performance_report']) ? $setting['show_run_performance_report'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="show_run_performance_report" value="1" autocomplete="nope" name="dashboard[show_run_performance_report]" <?php echo isset($setting['show_run_performance_report']) && $setting['show_run_performance_report'] == 1 ? 'checked' : ''; ?>>
                        <label for="show_run_performance_report" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['show_bank_balance_report']) ? $setting_comment['show_bank_balance_report'] : ''; ?>" class="col-sm-6 col-form-label">show Bank Balance in Dashboard</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="dashboard[show_bank_balance_report]" id="" cols="80" rows="1"><?php echo isset($setting['show_bank_balance_report']) ? $setting['show_bank_balance_report'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="show_bank_balance_report" value="1" autocomplete="nope" name="dashboard[show_bank_balance_report]" <?php echo isset($setting['show_bank_balance_report']) && $setting['show_bank_balance_report'] == 1 ? 'checked' : ''; ?>>
                        <label for="show_bank_balance_report" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['show_company_outstanding_report']) ? $setting_comment['show_company_outstanding_report'] : ''; ?>" class="col-sm-6 col-form-label">SHow Company Wise outstanding In dashboard</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="dashboard[show_company_outstanding_report]" id="" cols="80" rows="1"><?php echo isset($setting['show_company_outstanding_report']) ? $setting['show_company_outstanding_report'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="show_company_outstanding_report" value="1" autocomplete="nope" name="dashboard[show_company_outstanding_report]" <?php echo isset($setting['show_company_outstanding_report']) && $setting['show_company_outstanding_report'] == 1 ? 'checked' : ''; ?>>
                        <label for="show_company_outstanding_report" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['show_service_weight_report']) ? $setting_comment['show_service_weight_report'] : ''; ?>" class="col-sm-6 col-form-label">Show weight wise service list</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="dashboard[show_service_weight_report]" id="" cols="80" rows="1"><?php echo isset($setting['show_service_weight_report']) ? $setting['show_service_weight_report'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="show_service_weight_report" value="1" autocomplete="nope" name="dashboard[show_service_weight_report]" <?php echo isset($setting['show_service_weight_report']) && $setting['show_service_weight_report'] == 1 ? 'checked' : ''; ?>>
                        <label for="show_service_weight_report" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['show_customer_outstanding_report']) ? $setting_comment['show_customer_outstanding_report'] : ''; ?>" class="col-sm-6 col-form-label">show total sales wise customers</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="dashboard[show_customer_outstanding_report]" id="" cols="80" rows="1"><?php echo isset($setting['show_customer_outstanding_report']) ? $setting['show_customer_outstanding_report'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="show_customer_outstanding_report" value="1" autocomplete="nope" name="dashboard[show_customer_outstanding_report]" <?php echo isset($setting['show_customer_outstanding_report']) && $setting['show_customer_outstanding_report'] == 1 ? 'checked' : ''; ?>>
                        <label for="show_customer_outstanding_report" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['show_last_3_month_booking_report']) ? $setting_comment['show_last_3_month_booking_report'] : ''; ?>" class="col-sm-6 col-form-label">Show Last 3 months booking Details</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="dashboard[show_last_3_month_booking_report]" id="" cols="80" rows="1"><?php echo isset($setting['show_last_3_month_booking_report']) ? $setting['show_last_3_month_booking_report'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="show_last_3_month_booking_report" value="1" autocomplete="nope" name="dashboard[show_last_3_month_booking_report]" <?php echo isset($setting['show_last_3_month_booking_report']) && $setting['show_last_3_month_booking_report'] == 1 ? 'checked' : ''; ?>>
                        <label for="show_last_3_month_booking_report" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>


    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['show_week_undelivered_report']) ? $setting_comment['show_week_undelivered_report'] : ''; ?>" class="col-sm-6 col-form-label">Show Undelivered Report</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="dashboard[show_week_undelivered_report]" id="" cols="80" rows="1"><?php echo isset($setting['show_week_undelivered_report']) ? $setting['show_week_undelivered_report'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="show_week_undelivered_report" value="1" autocomplete="nope" name="dashboard[show_week_undelivered_report]" <?php echo isset($setting['show_week_undelivered_report']) && $setting['show_week_undelivered_report'] == 1 ? 'checked' : ''; ?>>
                        <label for="show_week_undelivered_report" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>


    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['show_week_pod_report']) ? $setting_comment['show_week_pod_report'] : ''; ?>" class="col-sm-6 col-form-label">Show POD Report</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="dashboard[show_week_pod_report]" id="" cols="80" rows="1"><?php echo isset($setting['show_week_pod_report']) ? $setting['show_week_pod_report'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="show_week_pod_report" value="1" autocomplete="nope" name="dashboard[show_week_pod_report]" <?php echo isset($setting['show_week_pod_report']) && $setting['show_week_pod_report'] == 1 ? 'checked' : ''; ?>>
                        <label for="show_week_pod_report" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['show_week_tracking_report']) ? $setting_comment['show_week_tracking_report'] : ''; ?>" class="col-sm-6 col-form-label">Show Tracking Failed Report</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="dashboard[show_week_tracking_report]" id="" cols="80" rows="1"><?php echo isset($setting['show_week_tracking_report']) ? $setting['show_week_tracking_report'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="show_week_tracking_report" value="1" autocomplete="nope" name="dashboard[show_week_tracking_report]" <?php echo isset($setting['show_week_tracking_report']) && $setting['show_week_tracking_report'] == 1 ? 'checked' : ''; ?>>
                        <label for="show_week_tracking_report" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['show_pickup_awb_report']) ? $setting_comment['show_pickup_awb_report'] : ''; ?>" class="col-sm-6 col-form-label">Show PICK UP / AWB ENTRY Report</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="dashboard[show_pickup_awb_report]" id="" cols="80" rows="1"><?php echo isset($setting['show_pickup_awb_report']) ? $setting['show_pickup_awb_report'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="show_pickup_awb_report" value="1" autocomplete="nope" name="dashboard[show_pickup_awb_report]" <?php echo isset($setting['show_pickup_awb_report']) && $setting['show_pickup_awb_report'] == 1 ? 'checked' : ''; ?>>
                        <label for="show_pickup_awb_report" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['show_customer_status_report']) ? $setting_comment['show_customer_status_report'] : ''; ?>" class="col-sm-6 col-form-label">Show Customer Docket Status Report</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="dashboard[show_customer_status_report]" id="" cols="80" rows="1"><?php echo isset($setting['show_customer_status_report']) ? $setting['show_customer_status_report'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="show_customer_status_report" value="1" autocomplete="nope" name="dashboard[show_customer_status_report]" <?php echo isset($setting['show_customer_status_report']) && $setting['show_customer_status_report'] == 1 ? 'checked' : ''; ?>>
                        <label for="show_customer_status_report" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['show_vendor_status_report']) ? $setting_comment['show_vendor_status_report'] : ''; ?>" class="col-sm-6 col-form-label">Show Vendor Docket Status Report</label>
            <div class="col-sm-6">
                <div class="checkbox">
                    <?php if (isset($mode) && $mode == "add_comments") { ?>
                        <textarea name="dashboard[show_vendor_status_report]" id="" cols="80" rows="1"><?php echo isset($setting['show_vendor_status_report']) ? $setting['show_vendor_status_report'] : ''; ?></textarea>
                    <?php } else { ?>
                        <input type="checkbox" id="show_vendor_status_report" value="1" autocomplete="nope" name="dashboard[show_vendor_status_report]" <?php echo isset($setting['show_vendor_status_report']) && $setting['show_vendor_status_report'] == 1 ? 'checked' : ''; ?>>
                        <label for="show_vendor_status_report" style="height: 10px !important;"> </label>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['fetch_edd_customer']) ? $setting_comment['fetch_edd_customer'] : ''; ?>" class="col-sm-6 col-form-label">Calculate EDD for customer By</label>
            <div class="col-sm-6">
                <?php if (isset($mode) && $mode == "add_comments") { ?>
                    <textarea name="docket[fetch_edd_customer]" id="" cols="80" rows="1"><?php echo isset($setting['fetch_edd_customer']) ? $setting['fetch_edd_customer'] : ''; ?></textarea>
                <?php } else { ?>
                    <select name="docket[fetch_edd_customer]">
                        <option value="1" <?php echo isset($setting['fetch_edd_customer']) && $setting['fetch_edd_customer'] == 1 ? 'selected' : ''; ?>>Booking Date</option>
                        <option value="2" <?php echo isset($setting['fetch_edd_customer']) && $setting['fetch_edd_customer'] == 2 ? 'selected' : ''; ?>>Dispatch date</option>
                        <option value="3" <?php echo isset($setting['fetch_edd_customer']) && $setting['fetch_edd_customer'] == 3 ? 'selected' : ''; ?>>Co courier Dispatch date</option>
                        <option value="4" <?php echo isset($setting['fetch_edd_customer']) && $setting['fetch_edd_customer'] == 4 ? 'selected' : ''; ?>>Connection date</option>
                    </select>
                <?php } ?>

            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label for="cust_id" title="<?php echo isset($setting_comment['fetch_edd_vendor']) ? $setting_comment['fetch_edd_vendor'] : ''; ?>" class="col-sm-6 col-form-label">Calculate EDD for Vendor By</label>
            <div class="col-sm-6">
                <?php if (isset($mode) && $mode == "add_comments") { ?>
                    <textarea name="docket[fetch_edd_vendor]" id="" cols="80" rows="1"><?php echo isset($setting['fetch_edd_vendor']) ? $setting['fetch_edd_vendor'] : ''; ?></textarea>
                <?php } else { ?>
                    <select name="docket[fetch_edd_vendor]">
                        <option value="1" <?php echo isset($setting['fetch_edd_vendor']) && $setting['fetch_edd_vendor'] == 1 ? 'selected' : ''; ?>>Booking Date</option>
                        <option value="2" <?php echo isset($setting['fetch_edd_vendor']) && $setting['fetch_edd_vendor'] == 2 ? 'selected' : ''; ?>>Dispatch date</option>
                        <option value="3" <?php echo isset($setting['fetch_edd_vendor']) && $setting['fetch_edd_vendor'] == 3 ? 'selected' : ''; ?>>Co courier Dispatch date</option>
                        <option value="4" <?php echo isset($setting['fetch_edd_vendor']) && $setting['fetch_edd_vendor'] == 4 ? 'selected' : ''; ?>>Connection date</option>
                    </select>
                <?php } ?>

            </div>
        </div>
    </div>

</div>