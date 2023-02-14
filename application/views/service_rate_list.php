<div class="box-header with-border">
    <h3 class="box-title">
        Services
    </h3>
</div>

<input type="hidden" name="popup_sale_amt" value="" id="popup_sale_amt" />
<input type="hidden" name="popup_freight_amt" value="" id="popup_freight_amt" />
<input type="hidden" name="popup_other_amt" value="" id="popup_other_amt" />
<input type="hidden" name="popup_fsc_amt" value="" id="popup_fsc_amt" />

<table class="table table-striped table-responsive table-bordered">
    <thead>
        <tr>
            <th>Select</th>
            <th>Service</th>
            <th>DEST CODE</th>
            <!-- <th>DEST NAME</th> -->
            <th>DEST ZONE</th>
            <!-- <th>PRODUCT</th> -->
            <th>WEIGHT</th>
            <th>AMOUNT</th>
            <th>OTHER CHARGES</th>
            <th>FSC</th>

            <?php if ($company_tax_type == 2) { ?>
                <th>VAT</th>
            <?php } else { ?>
                <th>IGST</th>
                <th>CGST</th>
                <th>SGST</th>
            <?php  } ?>

            <th>TOTAL AMOUNT</th>
            <th>PER KG AMT.</th>

        </tr>

    </thead>
    <tbody>
        <?php if (isset($result) && is_array($result) && count($result) > 0) {
            $cnt = 1;

            foreach ($result as $key => $value) { ?>
                <tr>
                    <td>

                        <div class="custom-control custom-radio" style="padding-left: 10px;">
                            <input type="radio" required="" id="customRadio<?php echo $cnt; ?>" name="software_service" class="custom-control-input popup_service_id" value="<?php echo $value['id'] . '/' . $value['vendor_id']; ?>" data-sales="<?php echo $value['total_amount']; ?>" data-freight="<?php echo $value['total_freight_amt']; ?>" data-fsc="<?php echo $value['fsc_amount']; ?>" data-charge="<?php echo $value['other_charge_total']; ?>" <?php echo (isset($docket_data['vendor_id']) && $docket_data['vendor_id'] == $value['vendor_id'] ? 'checked' : '') ?>>
                            <label style="padding-right: 0px !important;" class="custom-control-label" for="customRadio<?php echo $cnt; ?>"></label>
                        </div>

                    </td>

                    <td><?php echo isset($all_vendor[$value['vendor_id']]) ? ucwords($all_vendor[$value['vendor_id']]['code']) : ''; ?></td>

                    <td><?php echo isset($all_location[$get_data['destination_id']]) ? $all_location[$get_data['destination_id']]['code'] : ''; ?></td>
                    <td><?php echo isset($all_zone[$value['dest_zone_id']]) ? $all_zone[$value['dest_zone_id']]['code'] : ''; ?></td>
                    <td><?php echo isset($get_data['ch_weight']) ? $get_data['ch_weight'] : ''; ?></td>
                    <td><?php echo isset($value['total_freight_amt']) ? $value['total_freight_amt'] : ''; ?></td>
                    <td><?php echo isset($value['other_charge_total']) ? $value['other_charge_total'] : ''; ?></td>
                    <td><?php echo isset($value['fsc_amount']) ? $value['fsc_amount'] : ''; ?></td>

                    <?php if ($company_tax_type == 2) { ?>
                        <td><?php echo isset($value['igst_amount']) ? $value['igst_amount'] + $value['cgst_amount'] + $value['sgst_amount'] : ''; ?></td>
                    <?php } else { ?>
                        <td><?php echo isset($value['igst_amount']) ? $value['igst_amount'] : ''; ?></td>
                        <td><?php echo isset($value['cgst_amount']) ? $value['cgst_amount'] : ''; ?></td>
                        <td><?php echo isset($value['sgst_amount']) ? $value['sgst_amount'] : ''; ?></td>
                    <?php  } ?>


                    <td><?php echo isset($value['total_amount']) ? $value['total_amount'] : ''; ?></td>
                    <td><?php echo isset($value['total_amount_per_kg']) ? $value['total_amount_per_kg'] : ''; ?></td>

                </tr>
        <?php $cnt++;
            }
        } ?>


        <?php if (isset($nimbus_rate['data']) && is_array($nimbus_rate['data']) && count($nimbus_rate['data']) > 0) {
            foreach ($nimbus_rate['data'] as $key => $value) { ?>
                <tr>
                    <td>

                        <div class="custom-control custom-radio" style="padding-left: 10px;">
                            <input type="radio" required="" id="customRadio<?php echo $cnt; ?>" name="nimbuspost_service" class="custom-control-input popup_service_id" value="<?php echo $value['id'] . '/' . $value['nimbus_vendor_id']; ?>" data-sales="<?php echo $value['total_charges']; ?>" data-freight="<?php echo $value['freight_charges']; ?>" data-fsc="0" data-charge="<?php echo $value['total_charges'] - $value['freight_charges']; ?>" <?php echo (isset($docket_data['vendor_id']) && $docket_data['vendor_id'] == $value['nimbus_vendor_id'] && isset($docket_data['nimbuspost_courier_id']) && $docket_data['nimbuspost_courier_id'] == $value['id'] ? 'checked' : '') ?>>
                            <label style="padding-right: 0px !important;" class="custom-control-label" for="customRadio<?php echo $cnt; ?>"></label>
                        </div>

                    </td>

                    <td><?php echo $value['name'] ?></td>
                    <td><?php echo isset($all_location[$get_data['destination_id']]) ? $all_location[$get_data['destination_id']]['code'] : ''; ?></td>
                    <td></td>
                    <td><?php echo isset($get_data['ch_weight']) ? $get_data['ch_weight'] : ''; ?></td>
                    <td><?php echo isset($value['freight_charges']) ? $value['freight_charges'] : ''; ?></td>
                    <td><?php echo isset($value['total_charges']) ? $value['total_charges'] - $value['freight_charges'] : ''; ?></td>
                    <td></td>

                    <?php if ($company_tax_type == 2) { ?>
                        <td></td>
                    <?php } else { ?>
                        <td></td>
                        <td></td>
                        <td></td>
                    <?php  } ?>

                    <td><?php echo isset($value['total_charges']) ? $value['total_charges'] : ''; ?></td>
                    <td><?php echo isset($value['total_charges']) ? $value['total_charges'] / $value['chargeable_weight'] : ''; ?></td>

                </tr>
        <?php $cnt++;
            }
        } ?>



        <?php if (isset($norsk_rate) && is_array($norsk_rate) && count($norsk_rate) > 0) {
            foreach ($norsk_rate as $key => $value) { ?>
                <tr>
                    <td>

                        <div class="custom-control custom-radio" style="padding-left: 10px;">
                            <input type="radio" required="" id="customRadio<?php echo $cnt; ?>" name="norsk_service" class="custom-control-input popup_service_id" value="<?php echo $value['ServiceCode'] . '/' . $value['norsk_vendor_id']; ?>" data-sales="<?php echo $value['total_amount']; ?>" data-freight="<?php echo $value['total_amount']; ?>" data-fsc="0" data-charge="0" <?php echo (isset($docket_data['vendor_id']) && $docket_data['vendor_id'] == $value['norsk_vendor_id'] && isset($docket_extra_field['norsk_service_name']) && $docket_extra_field['norsk_service_name'] == $value['ServiceCode'] ? 'checked' : '') ?>>
                            <label style="padding-right: 0px !important;" class="custom-control-label" for="customRadio<?php echo $cnt; ?>"></label>
                        </div>

                    </td>

                    <td><?php echo $value['software_service'] ?></td>
                    <td><?php echo isset($all_location[$get_data['destination_id']]) ? $all_location[$get_data['destination_id']]['code'] : ''; ?></td>
                    <td></td>
                    <td><?php echo isset($get_data['ch_weight']) ? $get_data['ch_weight'] : ''; ?></td>
                    <td><?php echo isset($value['total_amount']) ? $value['total_amount'] : ''; ?></td>
                    <td></td>
                    <td></td>

                    <?php if ($company_tax_type == 2) { ?>
                        <td></td>
                    <?php } else { ?>
                        <td></td>
                        <td></td>
                        <td></td>
                    <?php  } ?>
                    <td><?php echo isset($value['total_amount']) ? $value['total_amount'] : ''; ?></td>
                    <td><?php echo isset($value['total_amount']) && $value['ChargeableWeight'] > 0 ? $value['total_amount'] / $value['ChargeableWeight'] : ''; ?></td>

                </tr>
        <?php $cnt++;
            }
        } ?>



        <?php if (isset($dhl_rate) && is_array($dhl_rate) && count($dhl_rate) > 0) {
            foreach ($dhl_rate as $key => $value) { ?>
                <tr>
                    <td>

                        <div class="custom-control custom-radio" style="padding-left: 10px;">
                            <input type="radio" required="" id="customRadio<?php echo $cnt; ?>" name="dhl_service" class="custom-control-input popup_service_id" value="<?php echo $value['id'] . '/' . $value['dhl_vendor_id']; ?>" data-sales="<?php echo $value['total_amount']; ?>" data-freight="<?php echo $value['total_amount']; ?>" data-fsc="0" data-charge="0" <?php echo (isset($docket_data['vendor_id']) && $docket_data['vendor_id'] == $value['dhl_vendor_id'] && isset($docket_extra_field['dhl_service_name']) && $docket_extra_field['dhl_service_name'] == $value['ServiceCode'] ? 'checked' : '') ?>>
                            <label style="padding-right: 0px !important;" class="custom-control-label" for="customRadio<?php echo $cnt; ?>"></label>
                        </div>

                    </td>

                    <td><?php echo $value['software_service'] ?></td>
                    <td><?php echo isset($all_location[$get_data['destination_id']]) ? $all_location[$get_data['destination_id']]['code'] : ''; ?></td>
                    <td></td>
                    <td><?php echo isset($get_data['ch_weight']) ? $get_data['ch_weight'] : ''; ?></td>
                    <td><?php echo isset($value['total_amount']) ? $value['total_amount'] : ''; ?></td>
                    <td></td>
                    <td></td>

                    <?php if ($company_tax_type == 2) { ?>
                        <td></td>
                    <?php } else { ?>
                        <td></td>
                        <td></td>
                        <td></td>
                    <?php  } ?>
                    <td><?php echo isset($value['total_amount']) ? $value['total_amount'] : ''; ?></td>
                    <td><?php echo isset($value['total_amount']) && $value['ChargeableWeight'] > 0 ? $value['total_amount'] / $value['ChargeableWeight'] : ''; ?></td>

                </tr>
        <?php $cnt++;
            }
        } ?>


        <?php if (isset($fedex_rate) && is_array($fedex_rate) && count($fedex_rate) > 0) {
            foreach ($fedex_rate as $key => $value) { ?>
                <tr>
                    <td>

                        <div class="custom-control custom-radio" style="padding-left: 10px;">
                            <input type="radio" required="" id="customRadio<?php echo $cnt; ?>" name="fedex_service" class="custom-control-input popup_service_id" value="<?php echo $value['id'] . '/' . $value['fedex_vendor_id']; ?>" data-sales="<?php echo $value['total_amount']; ?>" data-freight="<?php echo $value['total_amount']; ?>" data-fsc="<?php echo $value['fsc_amount']; ?>" data-charge="0" <?php echo (isset($docket_data['vendor_id']) && $docket_data['vendor_id'] == $value['fedex_vendor_id'] ? 'checked' : '') ?>>
                            <label style="padding-right: 0px !important;" class="custom-control-label" for="customRadio<?php echo $cnt; ?>"></label>
                        </div>

                    </td>

                    <td><?php echo $value['software_service'] ?></td>
                    <td><?php echo isset($all_location[$get_data['destination_id']]) ? $all_location[$get_data['destination_id']]['code'] : ''; ?></td>
                    <td></td>
                    <td><?php echo isset($get_data['ch_weight']) ? $get_data['ch_weight'] : ''; ?></td>
                    <td><?php echo isset($value['total_amount']) ? $value['total_amount'] : ''; ?></td>
                    <td></td>
                    <td><?php echo $value['fsc_amount']; ?></td>



                    <?php if ($company_tax_type == 2) { ?>
                        <td></td>
                    <?php } else { ?>
                        <td></td>
                        <td></td>
                        <td></td>
                    <?php  } ?>
                    <td><?php echo isset($value['total_amount']) ? $value['total_amount'] : ''; ?></td>
                    <td><?php echo isset($value['total_amount']) && $value['ChargeableWeight'] > 0 ? $value['total_amount'] / $value['ChargeableWeight'] : ''; ?></td>

                </tr>
        <?php $cnt++;
            }
        } ?>

    </tbody>
</table>