<div id="main-content">
    <section id="primary" class="content-full-width">

        <div class="fullwidth-section dt-sc-paralax full-pattern3">
            <div class="fullwidth-section page-header">
                <div class="container">
                    <h3 class="border-title aligncenter"> <span> <i class="fa fa-user"></i> Reset Password</span></h3>
                </div>
            </div>
            <div class="container">
                <div class="dt-sc-clear"></div>
                <div class="form-wrapper login">


                    <div class="signup-box">

                        <form class="form-horizontal" id="signup" method="post" action="<?php echo site_url('login/update_new_password'); ?>">
                            <input type="hidden" name="user_information[id]" value="<?php echo $this->input->get('id'); ?>">


                            <div class="form-group row">
                                <div class="col-sm-2 col-md-2 col-xs-12">
                                    <label for="password">Password :<span class="required">*</span></label>
                                </div>
                                <div class="col-sm-10 col-md-10 col-xs-12">
                                    <input required="" type="password" class="form-control" name="user_information[password]" id="password">
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-2 col-md-2 col-xs-12">
                                    <label for="repassword">Confirm Password :<span class="required">*</span></label>
                                </div>
                                <div class="col-sm-10 col-md-10 col-xs-12">
                                    <input required="" type="password" class="form-control" name="user_information[repassword]" id="repassword">
                                </div>
                            </div>




                            <div class="form-group row">
                                <div class="col-sm-2 col-md-2 col-xs-12"></div>
                                <div class="col-sm-10 col-md-10 col-xs-12"><button type="submit" name="submitButton" class="btn btn-primary pull-center">Submit</button></div>
                            </div>


                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>


<script src="<?php echo JS_PATH_FRONTEND; ?>jquery.validate.js"></script>
<script type="text/javascript">
    jQuery(document).ready(function() {
        jQuery("#signup").validate({
            rules: {
                login_email: {
                    required: true,
                    email: true
                },
                login_password: {
                    required: true,
                    minlength: 7
                },
                'user_information[repassword]': {
                    equalTo: '#password'
                }
            },
            messages: {
                login_email: {
                    required: "Email Required",
                    email: "Enter Valid Email ID"
                },
                login_password: {
                    required: "Password Required",
                    minlength: "Minimum 7 Character Required"
                }
            },
            errorElement: 'p',
            errorPlacement: function(error, element) {
                error.insertAfter(element);
            },
            submitHandler: function(form) {

                jQuery.ajax({
                    type: "POST",
                    url: "<?php echo site_url('login/update_new_password'); ?>",
                    data: jQuery(form).serialize(),
                    success: function(data) {
                        bootbox.alert('Your password successfully updated.')
                        window.location = "<?php echo site_url(); ?>";
                    },
                    error: function(data) {
                        bootbox.alert('Something wrong');
                    }
                });
                return false;
            }

        });

    });
</script>