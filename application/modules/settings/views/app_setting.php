<style>
    .nav-tabs .nav-link.serachTab {
        background: #8ebfe6 !important;
    }
</style>
<?php
$session_data = $this->session->userdata('admin_user');
$admin_role_name = array('software_user');
?>

<div class="col-12">
    <?php $this->load->view('flashdata_msg'); ?>

    <div style="background: #1667a8;color: rgb(252 245 245);padding: 5px;font-size: 13px;display:none;" class="success_msg">
        <span><b></b></span>
    </div>

    <div style="background: #ff0000;color: #fff;padding: 5px;font-size: 13px;display:none;" class="error_msg">
        <span><b></b></span>
    </div>

    <div class="modal" tabindex="-1" role="dialog" id="definition_modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content" style="width: 615px;">
                <div class="modal-header">
                    <h5 class="modal-title">APP DEFINITION</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>

                </div>
                <div class="modal-body" id="definition_service">
                    <input type="hidden" id="modal_setting_id" />


                    <div class="col-12">
                        <div class="form-group row">
                            <label for="cust_id" class="col-sm-4 col-form-label">SETTING</label>
                            <div class="col-sm-8">
                                <span id="modal_setting_name"></span>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group row">
                            <label for="cust_id" class="col-sm-4 col-form-label">Setting Definition</label>
                            <div class="col-sm-8">
                                <textarea style="height: 95px;" id="modal_setting_definition" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>


                    <button type="button" class="btn btn-primary" onclick="save_definition();">SAVE DEFINITION</button>
                </div>
            </div>
        </div>
    </div>

    <!-- /.box -->
    <form enctype="multipart/form-data" method="POST" id="setting_form" action="<?php echo  isset($mode) && $mode == 'add_comments' ? site_url('settings/save_comments') : site_url('settings/save_setting'); ?>">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <?php echo $heading; ?>
                </h3>

                <?php

                if ($session_data["email"] == "virag@itdservices.in") { ?>
                    <input type="text" class="form-control" id="setting_search" style="display: inline;width: 55%;margin-left: 30px;" placeholder="SEARCH SETTINGS HERE..." />
                    <?php if (isset($mode) && $mode == "add_comments" && $session_data['email'] == "virag@itdservices.in") { ?>
                        <button type="submit" class="btn btn-primary pull-right">SAVE COMMENTS</button>
                    <?php } else { ?>
                        <button type="submit" class="btn btn-primary pull-right">Save</button>
                    <?php } ?>
                    <?php if ($mode != "add_comments" && $session_data['email'] == "virag@itdservices.in") { ?>
                        <a class="btn btn-secondary pull-right" href="<?php echo site_url('settings/show_form/add_comments'); ?>">Add Comments</a>
                    <?php } ?>
                    <?php if (isset($mode) && $mode != 'update') { ?>
                        <a class="pull-right btn btn-secondary text-white" onclick="history.back()" href="javascript:void(0);">BACK</a>
                <?php }
                } ?>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-5 col-lg-3">
                        <ul class="nav nav-tabs flex-column mb-3">
                            <li class="nav-item">
                                <a class="nav-link active show" data-toggle="tab" href="#general-tab">General</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#main-tab">Main</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link " data-toggle="tab" href="#tab-3">MASTER</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-sm-7 col-lg-9" id="setting_div">
                        <div class="tab-content">
                            <?php $this->load->view('general_setting'); ?>
                            <?php $this->load->view('main_setting'); ?>
                            <?php $this->load->view('master_setting'); ?>

                            <?php if ($session_data["email"] == "virag@itdservices.in") { ?>
                                <div class="col-12 mt-3">
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <?php if (isset($mode) && $mode == "add_comments") { ?>
                                                <button type="submit" class="btn btn-primary pull-right">SAVE COMMENTS</button>
                                            <?php } else { ?>
                                                <button type="submit" class="btn btn-primary pull-right">Save</button>
                                            <?php } ?>
                                            <?php if (isset($mode) && $mode != 'update') { ?>
                                                <a class="pull-right btn btn-secondary text-white" onclick="history.back()" href="javascript:void(0);">BACK</a>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>
<script>
    function searchSetting(ActiveTab) {
        var serachTab = [];
        var value = $("#setting_search").val().toLowerCase();

        if (value == '') {
            $("#setting_div .col-form-label").filter(function() {
                $(this).parent().parent().find("*").show();
                $('.nav-link').removeClass('active');
                $('.tab-pane').removeClass('active');
                $('.nav-link').removeClass('serachTab');
                //FISRT TAB NAME
                var firstTab = "general-tab";
                $('a[href$="#' + firstTab + '"]').addClass('active');
                $("#" + firstTab).addClass('active');
            });
        } else {
            $("#setting_div .col-form-label").filter(function() {
                if ($(this).text().toLowerCase().indexOf(value) > -1) {

                    $(this).parent().parent().find("*").show();
                    //GET TAB ID
                    var TabID = $(this).parent().parent().parent().attr('id');
                    serachTab.push(TabID);
                } else {
                    $(this).parent().parent().find("*").hide();
                }
            });

            $('.nav-link').removeClass('active');
            $('.tab-pane').removeClass('active');
            $('.nav-link').removeClass('serachTab');
            if (serachTab.length > 0) {
                var serachTabUnique = serachTab.filter(function(itm, i, serachTab) {
                    return i == serachTab.indexOf(itm);
                });

                console.log(serachTabUnique);
                for (i = 0; i < serachTabUnique.length; ++i) {
                    var activeTabID = serachTabUnique[i];
                    if (ActiveTab != '') {
                        if (ActiveTab == '#' + activeTabID) {
                            $('a[href$="#' + activeTabID + '"]').addClass('active');
                            $("#" + activeTabID).addClass('active');
                        } else {
                            $('a[href$="#' + activeTabID + '"]').addClass('serachTab');
                        }
                    } else {
                        if (i == 0) {
                            $('a[href$="#' + activeTabID + '"]').addClass('active');
                            $("#" + activeTabID).addClass('active');
                        } else {
                            $('a[href$="#' + activeTabID + '"]').addClass('serachTab');
                        }
                    }


                }
            }
        }

    }

    function show_setting_definition(setting_id) {
        var setting_name = $("div").find("[data-id='" + setting_id + "']").children("label").text();

        $.ajax({
            type: "POST",
            url: '<?php echo site_url('settings/get_settings_definition/') ?>' + setting_id,
            data: '',
            success: function(serviceData) {
                var returnedData = JSON.parse(serviceData);
                if (returnedData['error'] != undefined) {
                    alert(returnedData['error']);
                    $("#definition_modal").modal('hide');
                } else {
                    $("#modal_setting_id").val(returnedData['id']);
                    $("#modal_setting_name").text(setting_name);
                    $("#modal_setting_definition").text(returnedData['setting_definition']);
                    $("#definition_modal").modal('show');
                }


            }
        });
    }

    function save_definition() {
        $("#definition_modal").modal('hide');
        $.ajax({
            type: "POST",
            url: '<?php echo site_url('settings/save_definition/') ?>',
            data: {
                'setting_id': $("#modal_setting_id").val(),
                'definition': $("#modal_setting_definition").val(),
            },
            success: function(serviceData) {
                var returnedData = JSON.parse(serviceData);
                if (returnedData['error'] != undefined) {
                    $(".success_msg").html('').hide();
                    $(".error_msg").html(returnedData['error']).show();
                } else {
                    $(".error_msg").html('').hide();
                    $(".success_msg").html(returnedData['success']).show();
                }

            }
        });

    }
    $(document).ready(function() {
        $("#setting_search").on("keyup", function() {
            searchSetting('');
        });
        <?php if (in_array(strtolower($session_data["email"]), $get_all_email) && $session_data['email'] != "virag@itdservices.in") { ?>
            $("#setting_form :input").prop("disabled", true);
            $("#setting_search").prop("disabled", false);
        <?php } ?>

        $(".nav-link").on("click", function() {
            $(this).removeClass('serachTab');
            var ActiveTab = $(this).attr('href');
            searchSetting(ActiveTab);
        });



        // $('.setting_data').each(function() {
        //     var setting_id = $(this).attr('data-id');
        //     if (setting_id != undefined && setting_id > 0) {
        //         var definition_append = '<div class="col-sm-2">';
        //         definition_append += '<a onclick="show_setting_definition(' + setting_id + ')"><i class="fa fa-info-circle setting_info" title="CLICK HERE TO VIEW SETTING DEFINITION">';
        //         definition_append += '</i></a>';
        //         definition_append += '</div>';
        //         $(this).append(definition_append);
        //     }
        // });



    });
</script>