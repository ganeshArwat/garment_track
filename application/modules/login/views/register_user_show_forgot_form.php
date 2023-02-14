<div id="main-content">
    <section id="primary" class="content-full-width">
        <div class="fullwidth-section dt-sc-paralax full-pattern3">
            <div class="fullwidth-section">
                <div class="container"> 
                    <h3 class="border-title aligncenter"> <span> <i class="fa fa-user"></i> Forgot Password</span></h3>
                </div>
            </div> 
            <div class="dt-sc-hr-invisible-small"></div>
            <div class="container">
                <div class="dt-sc-clear"></div>                            
                <div class="form-wrapper signup-box login">
                    <form class="form-horizontal" id="signup" method="post" action="<?php echo site_url('login/send_password'); ?>">
                        <div class="form-group ">
                            <label for="password">Email<span class="required">*</span></label>
                            <p>Note: You will receive reset password link if email id is registered.</p>
                            <input type="email" required class="form-control"  name="email" id="email">
                        </div>
                        <div class="form-group ">
                            <button type="submit" name="submitButton" class="dt-sc-button pull-center">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
