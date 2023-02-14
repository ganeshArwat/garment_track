<!-- JS file -->
<!-- <script src="http://easyautocomplete.com/dist/jquery.easy-autocomplete.min.js"></script>


<link rel="stylesheet" href="http://easyautocomplete.com/dist/easy-autocomplete.min.css">
<script>
    function autosuggest_input(input_id, module, id_hidden_field) {

        var options = {
            url: function(phrase) {
                return "<?php echo site_url('generic_detail/get_autosuggest_data'); ?>" + "/" + module;
            },

            getValue: function(element) {
                return element.name;
            },

            ajaxSettings: {
                dataType: "json",
                method: "POST",
                data: {
                    dataType: "json"
                }
            },

            preparePostData: function(data) {
                data.phrase = $("#" + input_id).val();
                return data;
            },

            list: {
                onClickEvent: function() {
                    var sele_id = $("#" + input_id).getSelectedItemData().id;
                    $("#" + id_hidden_field).val(sele_id);
                    console.log('Click');
                },
                onChooseEvent: function() {
                    var sele_id = $("#" + input_id).getSelectedItemData().id;
                    $("#" + id_hidden_field).val(sele_id);
                    console.log('onChooseEvent');
                },
                maxNumberOfElements: 500,
            },
            requestDelay: 400
        };

        $("#" + input_id).easyAutocomplete(options);
    }



    function check_input_selection(input_label, id_label) {
        var user = $('#' + input_label).getSelectedItemData();
        console.log(user);
        if (user == -1) {
            // $('#' + input_label).val('');
            // $('#' + id_label).val('');
        }
    }
</script> -->
<link href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" rel="stylesheet">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
    autoCompleteResponse = '';

    var segment1 = '<?php echo $this->uri->segment(1); ?>';
    var segment2 = '<?php echo $this->uri->segment(2); ?>';


    function onBlur_call(input_id) {
        //console.log('called blur');
        if (input_id == 'autosuggest_customer') {
            get_contract_id('customer_id');
        } else if (input_id == 'autosuggest_shipper_city') {
            get_contract_id();
        } else if (input_id == 'autosuggest_consignee_city') {
            get_contract_id();
        } else if (input_id == 'autosuggest_customer') {
            get_contract_id('customer_id');
        } else if (input_id == 'autosuggest_origin') {
            get_contract_id('origin_id');
        } else if (input_id == 'autosuggest_destination') {
            get_contract_id('destination_id');
        } else if (input_id == 'autosuggest_product') {
            get_contract_id('product_id');
        } else if (input_id == 'autosuggest_vendor') {
            get_contract_id('vendor_id');
        } else if (input_id == 'autosuggest_co_vendor') {
            get_contract_id('co_vendor_id');
        } else if (input_id == 'pur_autosuggest_product') {
            get_purchase_cft_contract();
        } else if (input_id == 'pur_autosuggest_vendor') {
            get_purchase_cft_contract();
        } else if (input_id == 'pur_autosuggest_co_vendor') {
            get_purchase_cft_contract();
        } else if (input_id == 'autosuggest_shipper_country') {
            getShipperDialCode();
        } else if (input_id == 'autosuggest_consignee_country') {
            getConsigneeDialCode();
        }
    }

    function autosuggest_input(input_id, module, id_hidden_field, strict_check = 1, column_filter = '') {
        console.log(input_id);
        console.log(strict_check);
        //console.log($('#destination_id').val());
        var segment1 = '<?php echo $this->uri->segment(1); ?>';
        var segment2 = '<?php echo $this->uri->segment(2); ?>';
        var segment3 = '<?php echo $this->uri->segment(3); ?>';

        if (segment1 == 'docket' && segment2 == 'edit') {
            module_id = segment3;
        } else {
            module_id = 0;
        }
        $("#" + input_id).autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "<?php echo site_url('generic_detail/get_autosuggest_data'); ?>" + "/" + module + "/" + segment1 + "/" + segment2,
                    dataType: "json",
                    data: {
                        dataType: "json",
                        phrase: request.term,
                        column_filter: column_filter,
                        destination_id: $('#destination_id').length ? $('#destination_id').val() : 0,
                        customer_id: $('#customer_id').length ? $('#customer_id').val() : 0,
                        mode: '<?php echo isset($mode) ? $mode : ''; ?>',
                        module_id: module_id,
                    },
                    type: "POST",
                    success: function(data) {
                        autoCompleteResponse = data;
                        response($.map(data, function(item) {
                            return {
                                label: item.name,
                                value: item.name,
                                id: item.id
                            };
                        }))
                    },
                });
            },
            select: function(e, i) {
                var itemNameData = i.item.value;
                if (column_filter == '') {
                    var text_input_name = itemNameData;
                    var nameArr = itemNameData.split(" - ");
                    var text_input_name = nameArr[0];
                } else if (column_filter == 'name') {
                    var nameArr = itemNameData.split(" - ");
                    var text_input_name = nameArr[1];
                } else if (column_filter == 'code') {
                    var nameArr = itemNameData.split(" - ");
                    var text_input_name = nameArr[0];
                }

                if ((segment1 == 'docket' || segment1 == 'pick_up_requests') && (module == 'city' || module == 'state')) {
                    var nameArr = autoCompleteResponse[0]['name'].split(" - ");
                    var text_input_name = nameArr[0];
                }
                if ((segment1 == 'customer_masters') && (module == 'city' || module == 'state' || module == 'country')) {
                    var nameArr = autoCompleteResponse[0]['name'].split(" - ");
                    var text_input_name = nameArr[0];
                }

                console.log(nameArr);
                console.log('text_input_name' + text_input_name);
                $("#" + input_id).val(text_input_name);
                $("#" + id_hidden_field).val(i.item.id);

                autoCompleteResponse = '';
            },
            change: function(e, ui) {
                console.log('change call234');
                console.log(input_id);
                console.log(strict_check);
                // console.log(autoCompleteResponse[0]['name']);
                // console.log(ui.item);

                var keyword_val = $("#" + input_id).val();

                console.log('key=' + keyword_val);
                var nameArr = keyword_val.split(" - ");
                $("#" + input_id).val(nameArr[0]);
                if ((input_id == "autosuggest_shipper_name" || input_id == "autosuggest_consignee_name")) {
                    $("#" + input_id).val(nameArr[1]);
                }
                $("#" + input_id + "_name").val(nameArr[1]);
                var keyword_val = $("#" + input_id).val();

                if (keyword_val == '') {
                    $("#" + input_id).val('');
                    $("#" + id_hidden_field).val('');
                    if (segment1 == 'docket' && (segment2 == 'add' || segment2 == 'edit' || segment2 == 'clone_docket')) {
                        onBlur_call(input_id);
                    } else if ((segment1 == 'manifest' || segment1 == 'transfer_manifests') && (segment2 == 'add' || segment2 == 'edit')) {
                        if (id_hidden_field != 'pur_vendor_id' && id_hidden_field != 'pur_co_vendor_id' && id_hidden_field != 'pur_product_id') {
                            get_filtered_docket();
                        }
                    } else if (((segment1 == 'shipper_masters' || segment1 == 'customer_masters') && input_id == 'autosuggest_country_shipperm') ||
                        (segment1 == 'consignee_master' && input_id == 'autosuggest_country_consigneem')) {
                        getDialCode();
                    } else if (segment1 == 'inventories' && input_id == 'autosuggest_customer') {
                        getCustomerProjectBrand();
                    } else if (segment1 == 'pick_up_requests' && input_id.indexOf("pickup_country") != -1) {
                        getPickupDialCode();
                    } else if (segment1 == 'pick_up_requests' && input_id.indexOf("consignee_country_") != -1) {
                        getConsigneeDialCode();
                    } else if (segment1 == 'account' && segment2 == 'receipt' && input_id == 'autosuggest_customer') {
                        getReceiptInclude();
                    } else if (segment1 == 'account' && segment2 == 'customer_credit' && input_id == 'autosuggest_customer') {
                        getCustomerData();
                    } else if (segment1 == 'account' && segment2 == 'opening_balance' && input_id == 'autosuggest_customer') {
                        getCustomerData();
                    }
                } else {
                    if (strict_check == 1) {
                        if (!(ui.item)) {
                            //send ajax call to get id of keyword


                            $.ajax({
                                type: "POST",
                                url: "<?php echo site_url('generic_detail/get_autosuggest_data'); ?>" + "/" + module + "/" + segment1 + "/" + segment2,
                                dataType: "json",
                                data: {
                                    'dataType': "json",
                                    'phrase': $("#" + input_id).val(),
                                    'searchBy': 'start',
                                    'destination_id': $('#destination_id').length ? $('#destination_id').val() : 0,
                                    customer_id: $('#customer_id').length ? $('#customer_id').val() : 0,
                                    mode: '<?php echo isset($mode) ? $mode : ''; ?>',
                                    module_id: module_id,
                                },
                                success: function(returnedData) {
                                    // console.log("2nd ajax" + segment1 + 'segment2' + segment2);


                                    if (returnedData[0] != undefined) {

                                        //console.log(returnedData);
                                        if (column_filter == '') {
                                            var nameArr = returnedData[0]['name'].split(" - ");
                                            e.target.value = nameArr[0];
                                        } else if (column_filter == 'name') {
                                            var nameArr = returnedData[0]['name'].split(" - ");
                                            e.target.value = nameArr[1];
                                        } else if (column_filter == 'code') {
                                            var nameArr = returnedData[0]['name'].split(" - ");
                                            e.target.value = nameArr[0];
                                        }

                                        $("#" + id_hidden_field).val(returnedData[0]['id']);
                                        $("#" + input_id + "_name").val(nameArr[1]);

                                        if (input_id == 'autosuggest_shipper_docket') {
                                            check_selection('autosuggest_shipper_docket', 'shipper_id');
                                        } else if (input_id == 'autosuggest_consignee_docket') {
                                            check_selection('autosuggest_consignee_docket', 'consignee_id');
                                        } else if (input_id == 'autosuggest_pickup_docket') {
                                            check_selection('autosuggest_pickup_docket', 'pickup_address_id');
                                        }


                                        if (input_id == 'autosuggest_consignee_pickup') {
                                            get_pickup_data('autosuggest_consignee_pickup', 'consignee_id');
                                        } else if (input_id == 'autosuggest_pickup_address_pickup') {
                                            get_pickup_data('autosuggest_pickup_address_pickup', 'pickup_id');
                                        }

                                        if (segment1 == 'docket' && (segment2 == 'add' || segment2 == 'edit' || segment2 == 'clone_docket')) {
                                            onBlur_call(input_id);
                                        } else if ((segment1 == 'manifest' || segment1 == 'transfer_manifests') && (segment2 == 'add' || segment2 == 'edit')) {
                                            if (id_hidden_field != 'pur_vendor_id' && id_hidden_field != 'pur_co_vendor_id' && id_hidden_field != 'pur_product_id') {
                                                get_filtered_docket();
                                            }
                                        } else if (((segment1 == 'shipper_masters' || segment1 == 'customer_masters') && input_id == 'autosuggest_country_shipperm') ||
                                            (segment1 == 'consignee_master' && input_id == 'autosuggest_country_consigneem')) {
                                            getDialCode();
                                        } else if (segment1 == 'inventories' && input_id == 'autosuggest_customer') {
                                            getCustomerProjectBrand();
                                        } else if (segment1 == 'pick_up_requests' && input_id.indexOf("pickup_autosuggest_country") != -1) {
                                            getPickupDialCode();
                                        } else if (segment1 == 'pick_up_requests' && input_id.indexOf("autosuggest_country") != -1) {
                                            getConsigneeDialCode();
                                        } else if (segment1 == 'account' && segment2 == 'receipt' && input_id == 'autosuggest_customer') {
                                            getReceiptInclude();
                                        } else if (segment1 == 'account' && segment2 == 'customer_credit' && input_id == 'autosuggest_customer') {
                                            getCustomerData();
                                        } else if (segment1 == 'account' && segment2 == 'opening_balance' && input_id == 'autosuggest_customer') {
                                            getCustomerData();
                                        }

                                    } else {
                                        e.target.value = "";
                                        $("#" + id_hidden_field).val('');
                                        if (segment1 == 'docket' && (segment2 == 'add' || segment2 == 'edit' || segment2 == 'clone_docket')) {
                                            onBlur_call(input_id);
                                        } else if ((segment1 == 'manifest' || segment1 == 'transfer_manifests') && (segment2 == 'add' || segment2 == 'edit')) {
                                            if (id_hidden_field != 'pur_vendor_id' && id_hidden_field != 'pur_co_vendor_id' && id_hidden_field != 'pur_product_id') {
                                                get_filtered_docket();
                                            }
                                        } else if (((segment1 == 'shipper_masters' || segment1 == 'customer_masters') && input_id == 'autosuggest_country_shipperm') ||
                                            (segment1 == 'consignee_master' && input_id == 'autosuggest_country_consigneem')) {
                                            getDialCode();
                                        } else if (segment1 == 'inventories' && input_id == 'autosuggest_customer') {
                                            getCustomerProjectBrand();
                                        } else if (segment1 == 'pick_up_requests' && input_id.indexOf("pickup_country") != -1) {
                                            getPickupDialCode();
                                        } else if (segment1 == 'pick_up_requests' && input_id.indexOf("consignee_country_") != -1) {
                                            getConsigneeDialCode();
                                        } else if (segment1 == 'account' && segment2 == 'receipt' && input_id == 'autosuggest_customer') {
                                            getReceiptInclude();
                                        } else if (segment1 == 'account' && segment2 == 'customer_credit' && input_id == 'autosuggest_customer') {
                                            getCustomerData();
                                        } else if (segment1 == 'account' && segment2 == 'opening_balance' && input_id == 'autosuggest_customer') {
                                            getCustomerData();
                                        }
                                    }
                                },
                                error: function(data) {
                                    e.target.value = "";
                                    $("#" + id_hidden_field).val('');
                                    if (segment1 == 'docket' && (segment2 == 'add' || segment2 == 'edit' || segment2 == 'clone_docket')) {
                                        onBlur_call(input_id);
                                    } else if ((segment1 == 'manifest' || segment1 == 'transfer_manifests') && (segment2 == 'add' || segment2 == 'edit')) {
                                        if (id_hidden_field != 'pur_vendor_id' && id_hidden_field != 'pur_co_vendor_id' && id_hidden_field != 'pur_product_id') {
                                            get_filtered_docket();
                                        }
                                    } else if (((segment1 == 'shipper_masters' || segment1 == 'customer_masters') && input_id == 'autosuggest_country_shipperm') ||
                                        (segment1 == 'consignee_master' && input_id == 'autosuggest_country_consigneem')) {
                                        getDialCode();
                                    } else if (segment1 == 'inventories' && input_id == 'autosuggest_customer') {
                                        getCustomerProjectBrand();
                                    } else if (segment1 == 'pick_up_requests' && input_id.indexOf("pickup_country") != -1) {
                                        getPickupDialCode();
                                    } else if (segment1 == 'pick_up_requests' && input_id.indexOf("consignee_country_") != -1) {
                                        getConsigneeDialCode();
                                    } else if (segment1 == 'account' && segment2 == 'receipt' && input_id == 'autosuggest_customer') {
                                        getReceiptInclude();
                                    } else if (segment1 == 'account' && segment2 == 'customer_credit' && input_id == 'autosuggest_customer') {
                                        getCustomerData();
                                    } else if (segment1 == 'account' && segment2 == 'opening_balance' && input_id == 'autosuggest_customer') {
                                        getCustomerData();
                                    }
                                }
                            });
                        } else {
                            if (input_id == 'autosuggest_shipper_docket') {
                                check_selection('autosuggest_shipper_docket', 'shipper_id');
                            } else if (input_id == 'autosuggest_consignee_docket') {
                                check_selection('autosuggest_consignee_docket', 'consignee_id');
                            }else if((input_id == 'autosuggest_customer') && (segment1 == 'docket' && (segment2 == 'add' || segment2 == 'edit' || segment2 == 'clone_docket'))){
                               <?php if (isset($setting['docket_shipper_by_customer']) && $setting['docket_shipper_by_customer'] == 1) { ?>
                                    check_selection('autosuggest_shipper_docket', 'customer_id');
                              <?php  } ?>
                            }

                            if (input_id == 'autosuggest_pickup_docket') {
                                check_selection('autosuggest_pickup_docket', 'pickup_address_id');
                            }

                            if (input_id == 'autosuggest_consignee_pickup') {
                                get_pickup_data('autosuggest_consignee_pickup', 'consignee_id');
                            } else if (input_id == 'autosuggest_pickup_address_pickup') {
                                get_pickup_data('autosuggest_pickup_address_pickup', 'pickup_id');
                            }

                            if (segment1 == 'docket' && (segment2 == 'add' || segment2 == 'edit' || segment2 == 'clone_docket')) {
                                onBlur_call(input_id);
                            } else if ((segment1 == 'manifest' || segment1 == 'transfer_manifests') && (segment2 == 'add' || segment2 == 'edit')) {
                                if (id_hidden_field != 'pur_vendor_id' && id_hidden_field != 'pur_co_vendor_id' && id_hidden_field != 'pur_product_id') {
                                    get_filtered_docket();
                                }
                            } else if (((segment1 == 'shipper_masters' || segment1 == 'customer_masters') && input_id == 'autosuggest_country_shipperm') ||
                                (segment1 == 'consignee_master' && input_id == 'autosuggest_country_consigneem')) {
                                getDialCode();
                            } else if (segment1 == 'inventories' && input_id == 'autosuggest_customer') {
                                getCustomerProjectBrand();
                            } else if (segment1 == 'pick_up_requests' && input_id.indexOf("pickup_country") != -1) {
                                getPickupDialCode();
                            } else if (segment1 == 'pick_up_requests' && input_id.indexOf("consignee_country_") != -1) {
                                getConsigneeDialCode();
                            } else if (segment1 == 'account' && segment2 == 'receipt' && input_id == 'autosuggest_customer') {
                                getReceiptInclude();
                            } else if (segment1 == 'account' && segment2 == 'customer_credit' && input_id == 'autosuggest_customer') {
                                getCustomerData();
                            } else if (segment1 == 'account' && segment2 == 'opening_balance' && input_id == 'autosuggest_customer') {
                                getCustomerData();
                            }
                        }
                    } else {
                        if (!(ui.item)) {
                            console.log('not gere');
                            console.log(autoCompleteResponse);
                            //DONT CALL THIS FOR DOCKET - CITY STATE
                            if (segment1 == 'docket' && (module == 'city' || module == 'state')) {

                            } else {
                                if (autoCompleteResponse[0] != undefined) {
                                    if (column_filter == '') {
                                        e.target.value = autoCompleteResponse[0]['name'];
                                    } else if (column_filter == 'name') {
                                        var nameArr = autoCompleteResponse[0]['name'].split(" - ");
                                        e.target.value = nameArr[1];
                                    } else if (column_filter == 'code') {
                                        var nameArr = autoCompleteResponse[0]['name'].split(" - ");
                                        e.target.value = nameArr[0];
                                    }
                                    if ((segment1 == 'docket' || segment1 == 'pick_up_requests' || segment1 == 'customer_masters') && (module == 'city' || module == 'state')) {
                                        var nameArr = autoCompleteResponse[0]['name'].split(" - ");
                                        e.target.value = nameArr[0];
                                    }

                                    if ((segment1 == 'customer_masters') && (module == 'city' || module == 'state' || module == 'country')) {
                                        var nameArr = autoCompleteResponse[0]['name'].split(" - ");
                                        e.target.value = nameArr[0];
                                    }

                                    $("#" + id_hidden_field).val(autoCompleteResponse[0]['id']);
                                    if (input_id == 'autosuggest_consignee_pickup') {
                                        get_pickup_data('autosuggest_consignee_pickup', 'consignee_id');
                                    } else if (input_id == 'autosuggest_pickup_address_pickup') {
                                        get_pickup_data('autosuggest_pickup_address_pickup', 'pickup_id');
                                    }
                                } else {
                                    $("#" + id_hidden_field).val('');
                                    if (input_id == 'autosuggest_consignee_pickup') {
                                        get_pickup_data('autosuggest_consignee_pickup', 'consignee_id');
                                    } else if (input_id == 'autosuggest_pickup_address_pickup') {
                                        get_pickup_data('autosuggest_pickup_address_pickup', 'pickup_id');
                                    }
                                }
                            }
                        }
                        if (input_id == 'autosuggest_consignee_pickup') {
                            get_pickup_data('autosuggest_consignee_pickup', 'consignee_id');
                        } else if (input_id == 'autosuggest_pickup_address_pickup') {
                            get_pickup_data('autosuggest_pickup_address_pickup', 'pickup_id');
                        }

                    }
                }
                autoCompleteResponse = '';

            },
            open: function(event, ui) {
                $(this).autocomplete("widget").css({
                    //"width": $(this).innerWidth(),
                    "width": "max-content",
                    // "margin-top": -10,
                });
            }
        }).data("ui-autocomplete")._renderItem = function(ul, item) {
            let txt = String(item.value).replace(new RegExp(this.term, "gi"), "<b>$&</b>");
            return $("<li></li>")
                .data("ui-autocomplete-item", item)
                .append("<a>" + txt + "</a>")
                .appendTo(ul);
        };


    }
</script>