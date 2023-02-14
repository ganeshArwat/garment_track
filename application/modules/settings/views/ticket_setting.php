<div class="tab-pane" id="ticket-tab">
    <div class="form-group row">
        <label for="cust_id" class="col-sm-6 col-form-label">Set Ticket NO Range</label>
        <div class="col-sm-6">
            <div class="col-sm-4" style="display: inline;float: left;">
                <div class="form-group row">
                    <label class="col-sm-6">Prefix</label>
                    <input type="text" class="form-control col-sm-6" name="ticket[ticket_no_prefix]" value="<?php echo $setting['ticket_no_prefix'] ? $setting['ticket_no_prefix'] : ''; ?>" />
                </div>
            </div>
            <div class="col-sm-8" style="display: inline;float: right;">
                <div class="form-group row">
                    <label class="col-sm-4">Range</label>
                    <input type="text" class="form-control col-sm-8" name="ticket[ticket_no_range]" value="<?php echo $setting['ticket_no_range'] ? $setting['ticket_no_range'] : ''; ?>" />
                </div>
            </div>


        </div>
    </div>
    <!-- <label for="cust_id" class="col-sm-6 col-form-label">AUTO ALLOCATE TICKET </label>
        <div class="col-sm-6">
            <div class="checkbox">
                <select name="ticket[auto_allocate_ticket]">
                    <option value="1" <?php echo isset($setting['auto_allocate_ticket']) && $setting['auto_allocate_ticket'] == 1  ? 'selected' : ''; ?>>NONE</option>
                    <option value="2" <?php echo isset($setting['auto_allocate_ticket']) && $setting['auto_allocate_ticket'] == 2 ? 'selected' : ''; ?>>CUSTOMER MASTER</option>
                    <option value="3" <?php echo isset($setting['auto_allocate_ticket']) && $setting['auto_allocate_ticket'] == 3 ? 'selected' : ''; ?>>SERVICE MASTER</option>
                </select>
            </div>
        </div> -->
    <!-- <div class="form-group row">
        <label for="cust_id" class="col-sm-6 col-form-label">AUTO ALLOCATE TICKET for Billing</label>
        <div class="col-sm-6">
            <div class="checkbox">
                <select name="ticket[auto_allocate_ticket_billing]">
                    <option value="2" <?php echo isset($setting['auto_allocate_ticket_billing']) && $setting['auto_allocate_ticket_billing'] == 2 ? 'selected' : ''; ?>>CUSTOMER MASTER</option>
                    <option value="3" <?php echo isset($setting['auto_allocate_ticket_billing']) && $setting['auto_allocate_ticket_billing'] == 3 ? 'selected' : ''; ?>>SERVICE MASTER</option>
                </select>
            </div>
        </div>
    </div> -->
    <div class="form-group row">
        <label for="cust_id" class="col-sm-6 col-form-label">AUTO ALLOCATE TICKET for Customer Service </label>
        <div class="col-sm-6">
            <div class="checkbox">
                <select name="ticket[auto_allocate_ticket_cs]">
                    <option value="2" <?php echo isset($setting['auto_allocate_ticket_cs']) && $setting['auto_allocate_ticket_cs'] == 2 ? 'selected' : ''; ?>>CUSTOMER MASTER</option>
                    <option value="3" <?php echo isset($setting['auto_allocate_ticket_cs']) && $setting['auto_allocate_ticket_cs'] == 3 ? 'selected' : ''; ?>>SERVICE MASTER</option>
                </select>
            </div>
        </div>
    </div>
    <div class="form-group row">
        <label for="cust_id" class="col-sm-6 col-form-label">AUTO ALLCATION WHEN NO AWB ADDED</label>
        <div class="col-sm-6">
            <div class="checkbox">
                <select name="ticket[auto_allocate_ticket_none]">
                    <option value="">NONE</option>
                    <?php
                    if (isset($all_user) && is_array($all_user) && count($all_user) > 0) {
                        foreach ($all_user as $key => $value) { ?>
                            <option value="<?php echo $value['id']; ?>" <?php echo isset($setting['auto_allocate_ticket_none']) && $setting['auto_allocate_ticket_none'] == $value['id']  ? 'selected' : ''; ?>><?php echo $value['name']; ?></option>
                    <?php }
                    }
                    ?>
                </select>
            </div>
        </div>
    </div>




</div>