<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/login_style.css?123">
<?php if (isset($sef_url) && $sef_url == "acm") { ?>
    <style>
        body {
            overflow: visible;
        }

        .footer_block {
            background: #000;
            text-align: center;
            padding: 15px;
        }

        .footer_block a {
            color: #fff;
            display: inline-block;
            padding-right: 15px;
        }

        .footer_block a:last-child {
            padding-right: 0px;
        }

        .container {
            height: 87vh !important;
        }
    </style>
<?php } ?>
<div class="container">
    <div class="img">
        <img src="<?php echo base_url(); ?>images/login_page.jpg">
    </div>
    <div class="login-content">
        <form class="login100-form validate-form" id="login">
            <?php if (isset($company_logo) && $company_logo != '' && file_exists($company_logo)) { ?>
                <img src="<?php echo base_url($company_logo); ?>" class="img-responsive" />
            <?php } else { ?>
                <img src="<?php echo base_url(); ?>images/avatar.svg">
            <?php } ?>
            <h2 class="title"><?php echo ucwords($company_name); ?></h2>

            <input type="hidden" name="company_sef" value="<?php echo isset($sef_url) ? $sef_url : ''; ?>" />
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
            <a href="<?php echo site_url('login/admin_login/') . 'show_forgot_form?company_url=' . (isset($sef_url) ? $sef_url : ''); ?>">FORGOT PASSWORD?</a>
        </form>
    </div>
</div>
<?php if (isset($sef_url) && $sef_url == "acm") { ?>
    <div class="footer_block">
        <div style="display: flex;color:#fff">
            <div style="width:40%;">
                Shop Number 21, Sucheta Niwas, 2nd Floor, 285,<br>
                Shahid Bhagat Singh Road,Old Custom House Rd, <br>
                near South Indian Bank, Ballard Estate, Fort,<br>
                Mumbai, Maharashtra 400001</p>
            </div>
            <div style="width:40%; text-align:center;">
                <p>Contact Us</p>
                <p><a style="text-align: center !important;" href="tel:+919820632871">+91 9820632871</a>
                    <a style="text-align: center !important;" href="tel:+918779739690">+91 8779739690</a>
                    <a style="text-align: center !important;" href="mailto:info@acmexpress.in">info@acmexpress.in</a>
                </p>
            </div>
            <div style="width:10%;">
                <p><a href="https://acmexpress.in/terms_service.php" target="_blank" style="text-align: center !important;">Terms of Service</a></p>
                <p><a href="https://acmexpress.in/privacy_policy.php" target="_blank" style="text-align: center !important;">Privacy Policy</a></p>
                <p><a href="https://acmexpress.in/refund_policy.php" target="_blank" style="text-align: center !important;">Refund Policy</a></p>
                <p><a href="https://acmexpress.in/shipping_policy.php" target="_blank" style="text-align: center !important;">Shipping Policy</a></p>
                <p><a href="https://acmexpress.in/about.php" target="_blank" style="text-align: center !important;">About Us</a></p>
            </div>
        </div>


    </div>
<?php } ?>
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
                    url: "<?php echo site_url('login/admin_login/uvf_login'); ?>",
                    data: $(form).serialize(),
                    success: function(data) {
                        // window.location = "<?php echo site_url('adminx'); ?>";
                        $.ajax({
                            type: "POST",
                            url: "<?php echo site_url('login/admin_login/check_validity'); ?>",
                            data: $(form).serialize(),
                            success: function(data) {
                                window.location = "<?php echo site_url('adminx/login_redirect'); ?>";
                            },
                            error: function(data) {
                                bootbox.alert('Your yearly subscription is over.Please renew it and continue enjoying uninterrupted services.');
                            }
                        });
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