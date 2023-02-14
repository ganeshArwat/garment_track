<script>
    function show_include_data(credit_id, credit_id_type, delete_type) {
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('account/get_include_data') ?>",
            data: {
                'credit_id': credit_id,
                'credit_id_type': credit_id_type,
                'delete_type': delete_type
            },
            success: function(data) {
                try {
                    data = JSON.parse(data);
                    if (data['include_data_cnt'] != undefined && data['include_data_cnt'] == 0) {
                        msg = "Are you sure you want to delete this record?";
                        bootbox.confirm(msg, function(result) {
                            if (result) {
                                $.ajax({
                                    type: "POST",
                                    url: "<?php echo site_url('account/delete_include/') ?>" + credit_id + "/" + credit_id_type + "/1",
                                    data: {},
                                    success: function(data) {

                                        if (credit_id_type == 1) {
                                            window.location = '<?php echo site_url() ?>account/receipt/show_list';
                                        } else if (credit_id_type == 2) {
                                            window.location = '<?php echo site_url() ?>account/customer_credit/show_list';
                                        } else if (credit_id_type == 3) {
                                            window.location = '<?php echo site_url() ?>account/opening_balance/show_list';
                                        }
                                    },
                                    error: function(data) {
                                        bootbox.alert('Failed to delete data.Try Again.');
                                    }
                                });
                            }
                        });
                    } else {
                        $("#include_modal_data").html(data);
                        $("#include_modal").modal('show');
                    }
                } catch (e) {
                    $("#include_modal_data").html(data);
                    $("#include_modal").modal('show');
                }

            }
        });
    }

    function show_tracking_data(docket_id) {
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('docket/get_tracking_data') ?>",
            data: {
                'docket_id': docket_id,
            },
            success: function(data) {
                $("#tracking_modal_data").html(data);
                $("#tracking_modal").modal('show');
            }
        });
    }



    function show_purchase_include_data(credit_id, credit_id_type, delete_type) {
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('purchase_account/get_include_data') ?>",
            data: {
                'credit_id': credit_id,
                'credit_id_type': credit_id_type,
                'delete_type': delete_type
            },
            success: function(data) {
                try {
                    data = JSON.parse(data);
                    if (data['include_data_cnt'] != undefined && data['include_data_cnt'] == 0) {
                        msg = "Are you sure you want to delete this record?";
                        bootbox.confirm(msg, function(result) {
                            if (result) {
                                $.ajax({
                                    type: "POST",
                                    url: "<?php echo site_url('purchase_account/delete_include/') ?>" + credit_id + "/" + credit_id_type + "/1",
                                    data: {},
                                    success: function(data) {

                                        if (credit_id_type == 1) {
                                            window.location = '<?php echo site_url() ?>purchase_account/payment/show_list';
                                        } else if (credit_id_type == 2) {
                                            window.location = '<?php echo site_url() ?>purchase_account/customer_credit/show_list';
                                        } else if (credit_id_type == 3) {
                                            window.location = '<?php echo site_url() ?>purchase_account/opening_balance/show_list';
                                        }
                                    },
                                    error: function(data) {
                                        bootbox.alert('Failed to delete data.Try Again.');
                                    }
                                });
                            }
                        });
                    } else {
                        $("#include_modal_data").html(data);
                        $("#include_modal").modal('show');
                    }
                } catch (e) {
                    $("#include_modal_data").html(data);
                    $("#include_modal").modal('show');
                }

            }
        });
    }


    function show_include_in_data(credit_id, credit_id_type, delete_type) {
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('account/get_include_in_data') ?>",
            data: {
                'credit_id': credit_id,
                'credit_id_type': credit_id_type,
                'delete_type': delete_type
            },
            success: function(data) {
                try {
                    data = JSON.parse(data);
                    if (data['include_data_cnt'] != undefined && data['include_data_cnt'] == 0) {
                        msg = "Are you sure you want to delete this record?";
                        bootbox.confirm(msg, function(result) {
                            if (result) {
                                $.ajax({
                                    type: "POST",
                                    url: "<?php echo site_url('account/delete_include_in/') ?>" + credit_id + "/" + credit_id_type + "/1",
                                    data: {},
                                    success: function(data) {

                                        if (credit_id_type == 1) {
                                            window.location = '<?php echo site_url() ?>account/receipt/show_list';
                                        } else if (credit_id_type == 2) {
                                            window.location = '<?php echo site_url() ?>account/customer_credit/show_list';
                                        } else if (credit_id_type == 3) {
                                            window.location = '<?php echo site_url() ?>account/opening_balance/show_list';
                                        }
                                    },
                                    error: function(data) {
                                        bootbox.alert('Failed to delete data.Try Again.');
                                    }
                                });
                            }
                        });
                    } else {
                        $("#include_in_modal_data").html(data);
                        $("#include_in_modal").modal('show');
                    }
                } catch (e) {
                    $("#include_in_modal_data").html(data);
                    $("#include_in_modal").modal('show');
                }

            }
        });
    }


    function show_purchase_include_in_data(credit_id, credit_id_type, delete_type) {
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('purchase_account/get_include_in_data') ?>",
            data: {
                'credit_id': credit_id,
                'credit_id_type': credit_id_type,
                'delete_type': delete_type
            },
            success: function(data) {
                try {
                    data = JSON.parse(data);
                    if (data['include_data_cnt'] != undefined && data['include_data_cnt'] == 0) {
                        msg = "Are you sure you want to delete this record?";
                        bootbox.confirm(msg, function(result) {
                            if (result) {
                                $.ajax({
                                    type: "POST",
                                    url: "<?php echo site_url('purchase_account/delete_include_in/') ?>" + credit_id + "/" + credit_id_type + "/1",
                                    data: {},
                                    success: function(data) {

                                        if (credit_id_type == 1) {
                                            window.location = '<?php echo site_url() ?>purchase_account/payment/show_list';
                                        } else if (credit_id_type == 2) {
                                            window.location = '<?php echo site_url() ?>purchase_account/customer_credit/show_list';
                                        } else if (credit_id_type == 3) {
                                            window.location = '<?php echo site_url() ?>purchase_account/opening_balance/show_list';
                                        }
                                    },
                                    error: function(data) {
                                        bootbox.alert('Failed to delete data.Try Again.');
                                    }
                                });
                            }
                        });
                    } else {
                        $("#include_in_modal_data").html(data);
                        $("#include_in_modal").modal('show');
                    }
                } catch (e) {
                    $("#include_in_modal_data").html(data);
                    $("#include_in_modal").modal('show');
                }

            }
        });
    }
</script>