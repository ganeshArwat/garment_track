<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/login_style.css?123">
<div class="container">

    <div class="login-content">
        <form class="login100-form validate-form" id="login">
            <?php if (isset($company_logo) && $company_logo != '' && file_exists($company_logo)) { ?>
                <img src="<?php echo base_url($company_logo); ?>" class="img-responsive" />
            <?php } else { ?>
                <img src="<?php echo base_url(); ?>images/avatar.svg">
            <?php } ?>
            <h2 class="title"><?php echo ucwords($company_name); ?> USER</h2>

            <?php
            $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
            $url = $_SERVER['REDIRECT_QUERY_STRING'];
            ?>

            <input type="hidden" name="company_url" value="<?php echo isset($url) ? $url  : "" ?>" />
            <input type="hidden" name="company_sef" value="<?php echo isset($company_sef) ? $company_sef  : $this->uri->segment(1); ?>" />
            <input type="hidden" name="company_id" value="<?php echo isset($company_id) ? $company_id : 0; ?>" />
            <div class="input-div one">
                <div class="i">
                    <i class="fas fa-user"></i>
                </div>
                <div class="div">
                    <h5>Email ID</h5>
                    <input class="input" type="text" name="username" tabindex="2">
                </div>
            </div>
            <div class="input-div pass">
                <div class="i">
                    <i class="fas fa-lock"></i>
                </div>
                <div class="div">
                    <h5>Password</h5>
                    <input class="input" type="password" name="password" tabindex="3">
                </div>
            </div>
            <input type="submit" class="btn" value="Login">
            <a href="<?php echo site_url('login/user_login/') . 'show_forgot_form?company_url=' . (isset($company_sef) ? $company_sef  : $this->uri->segment(1)); ?>">FORGOT PASSWORD?</a>
        </form>
    </div>

</div>
<script type="text/javascript" src="<?php echo base_url(); ?>js/login_main.js"></script>
<script src="<?php echo JS_PATH_BACKEND; ?>jquery.validate.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.5.2/bootbox.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $("#login").validate({
            rules: {
                company_code: {
                    required: true,
                    minlength: 5
                },
                username: {
                    required: true,
                },
                password: {
                    required: true,
                    minlength: 7
                }
            },
            messages: {
                company_code: {
                    required: 'Company Code required',
                    minlength: 'Enter Correct code'
                },
                username: {
                    required: "Username Required",
                },
                password: {
                    required: "Password Required",
                    minlength: "Minimum 7 Character Required"
                }
            },
            errorElement: 'p',
            errorPlacement: function(error, element) {
                error.insertAfter(element.parent("div").parent("div"));
            },
            submitHandler: function(form) {
                $.ajax({
                    type: "POST",
                    url: "<?php echo site_url('login/user_login/uvf_login'); ?>",
                    data: $(form).serialize(),
                    success: function(data) {
                        window.location = "<?php echo site_url('adminx/login_redirect'); ?>";
                    },
                    error: function(data) {
                        bootbox.alert('The email and password you entered dont match!');
                    }
                });
                return false;
            }

        });
    });
</script>