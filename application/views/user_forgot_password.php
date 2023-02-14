<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/login_style.css?123">
<div class="container">
    <div class="img">
        <img src="<?php echo base_url(); ?>images/preview_52173.jpg">
    </div>
    <div class="login-content">
        <form class="login100-form validate-form" method="post" id="login" action="<?php echo site_url("login/user_login/send_password") . "?cron_company=" . $company_id; ?>">

            <img src="<?php echo base_url(); ?>images/avatar.svg">
            <h2 class="title">Forgot Password</h2>

            <div class="input-div one">
                <div class="i">
                    <i class="fas fa-user"></i>
                </div>
                <div class="div">
                    <h5>Email ID</h5>
                    <input class="input" type="text" name="username" tabindex="2" required>
                </div>
            </div>
            <input type="submit" class="btn" value="RESET PASSWORD">
            <p>Note: You will receive reset password link if email id is registered.</p>
        </form>