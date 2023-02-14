<!doctype html>
<html lang="en">

<head>
    <title>Reset Password</title>
</head>

<body style="background-color: #e9e9e9;">

    <div class="main_block" style="position: relative;font-family: sans-serif;background-color: #fff;width: 400px;padding: 30px;border-radius: 4px;box-shadow: 0px 0px 10px 0px rgb(0 0 0 / 30%);margin: auto;margin-top: 50px;">
        <img src="<?php echo MEDIA_PATH_FRONTEND; ?>lock.gif" alt="" style=" position: absolute;top: -30px;left: 0;right: 0;width: 50px;margin: auto;background-color: #fff;border-radius: 5px;padding: 10px;box-shadow: 0px 0px 10px 0px rgb(0 0 0 / 30%);">
        <h1 style="margin-top: 0;padding-top: 15px;">Forgot Your Password?</h1>
        <p style="font-size: 12px;letter-spacing: 1px;">Hey, we received a request to reset your password.</p>
        <p style="font-size: 12px;letter-spacing: 1px;">Let's get you a new one!</p>
        <a target="_blank" href="<?php echo $forgot_link; ?>"><button type="button" style="background-color: #506bec;border-color: #506bec;color: #fff;padding: 10px 15px;border-radius: 5px;margin: 15px 0;">Reset My Password</button></a>
        <p style="font-size: 12px;letter-spacing: 1px;">Having trouble? Contact Us</p>
        <p style="font-size: 12px;letter-spacing: 1px;">Didn't request a password reset? You can ignore this message.</p>
    </div>
</body>

</html>