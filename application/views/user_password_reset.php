<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/login_style.css?123">
<div class="container">
    <div class="img">
        <img src="<?php echo base_url(); ?>images/preview_52173.jpg">
    </div>
    <div class="login-content">
        <form class="login100-form validate-form" method="post" id="login" action="<?php echo site_url("login/user_login/update_password_admin") . "?cron_company=" . $company_id; ?>">

            <img src="<?php echo base_url(); ?>images/avatar.svg">
            <h2 class="title">Reset Password</h2>
            <input type="hidden" name="key" value="<?php echo $key; ?>">
            <div class="input-div one">
                <div class="i">
                    <i class="fas fa-user"></i>
                </div>
                <div class="div">
                    <input class="input" type="text" name="username" value="<?php echo $username; ?>" tabindex="2" readonly="true" required>
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
            <div class="input-div pass">
                <div class="i">
                    <i class="fas fa-lock"></i>
                </div>
                <div class="div">
                    <h5>Confirm Password</h5>
                    <input class="input" type="password" name="conf_password" tabindex="3">
                </div>
            </div>
            <input type="submit" class="btn" value="RESET PASSWORD">
        </form>