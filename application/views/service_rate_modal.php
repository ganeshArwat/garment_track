<div class="modal" tabindex="-1" role="dialog" id="service_pop_modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="width: 900px;margin-left: -136px;">

            <div class="modal-header" style="padding: 1px 15px !important;">
                <h5 style="color: #f32121;font-weight: 600;">Services</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div><button type="button" class="btn btn-primary nimbus_modal_btn" onclick="save_sales_billing_popup();">SAVE AWB</button></div>
            <div class="modal-body" id="service_pop_modal_data">
            </div>
        </div>
    </div>
</div>

<script>
    function save_sales_billing_popup() {
        $('button[type="submit"]').attr('disabled', 'disabled');
        saveFormID = 'docket_form';
        buttonClick = 'submitClick';
        popupSubmit = 1;

        var popup_service_id = $(".popup_service_id:checked").val();
        if (popup_service_id != '') {
            var sales_amt = $(".popup_service_id:checked").attr('data-sales');
            var freight_amt = $(".popup_service_id:checked").attr('data-freight');
            var fsc_amt = $(".popup_service_id:checked").attr('data-fsc');
            var other_amt = $(".popup_service_id:checked").attr('data-charge');
        } else {
            var sales_amt = 0;
            var freight_amt = 0;
            var fsc_amt = 0;
            var other_amt = 0;
        }
        $("#popup_sale_amt").val(sales_amt);
        $("#popup_freight_amt").val(freight_amt);
        $("#popup_fsc_amt").val(fsc_amt);
        $("#popup_other_amt").val(other_amt);

        save_sales_billing();
    }
</script>