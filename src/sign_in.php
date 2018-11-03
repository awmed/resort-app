<?php
//start session
session_start();

//unset session if user visit login or go back
if(isset($_SESSION['user'])) {
    unset($_SESSION['user']);
}

//display alert if signin unsuccess
if(isset($_SESSION['error'])) {
echo 
'<script>                     
    setTimeout(function() {
        swal({
            title: "Error",
            text: "Username or Password Invalid !",
            type: "error"
        })
    }, 100);
</script>';

//unset session
unset($_SESSION['error']);
}

//display alert if fields empty
if(isset($_SESSION['empty'])) {
    echo 
    '<script>                     
        setTimeout(function() {
            swal({
                title: "Fields Cannot Be Empty",
                text: "Retry !",
                type: "warning"
            })
        }, 100);
    </script>';
    
    //unset session
    unset($_SESSION['empty']);
}   


?>
<?php require 'inc/_global/config.php'; ?>

<?php require 'inc/_global/views/head_start.php'; 
	echo '<title> Sign In </title>'
?>

<?php require 'inc/_global/views/head_end.php'; ?>
<?php require 'inc/_global/views/page_start.php'; ?>

<!-- Page Content -->
<div class="bg-image" style="background-image: url('/resort/src/assets/img/photos/dashboard.jpeg');">
    <div class="row mx-0 bg-black-op">
        <div class="hero-static col-md-6 col-xl-8 d-none d-md-flex align-items-md-end">
            <div class="p-30 invisible" data-toggle="appear">
                <!-- <p class="font-size-h3 font-w600 text-white">
                    Get Inspired and Create.
                </p>
                <p class="font-italic text-white-op">
                    Copyright &copy; <span class="js-year-copy">2017</span>
                </p> -->
            </div>
        </div>
        <div class="hero-static col-md-6 col-xl-4 d-flex align-items-center bg-white invisible" data-toggle="appear" data-class="animated fadeInRight">
            <div class="content content-full">
                <!-- Header -->
                <div class="px-30 py-10">
                    <a class="link-effect font-w700" href="#">
                        <!-- <i class="si si-fire"></i> -->
                        <span class="font-size-xl text-primary-dark">grand</span><span class="font-size-xl">resort</span>
                    </a>
                    <h1 class="h3 font-w700 mt-30 mb-10">Admin Dashboard</h1>
                    <h2 class="h5 font-w400 text-muted mb-0">Please sign in</h2>
                </div>
                <!-- END Header -->

                <!-- Sign In Form -->
                <!-- jQuery Validation (.js-validation-signin class is initialized in js/pages/op_auth_signin.js) -->
                <!-- For more examples you can check out https://github.com/jzaefferer/jquery-validation -->
                <form class="js-validation-signin px-30" action="inc\backend\database\sign_in.php" method="post">
                    <div class="form-group row">
                        <div class="col-12">
                            <div class="form-material floating">
                                <input type="text" class="form-control" id="login-username" name="login-username" required="true">
                                <label for="login-username">Username</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-12">
                            <div class="form-material floating">
                                <input type="password" class="form-control" id="login-password" name="login-password" required="true">
                                <label for="login-password">Password</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="remember-me" name="login-remember-me">
                                <label class="custom-control-label" for="login-remember-me">Remember Me</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="submit" name="submit" class="btn btn-sm btn-hero btn-alt-primary">
                            <i class="si si-login mr-10"></i> Sign In
                        </button>
                        <div class="mt-30">
                            <a class="link-effect text-muted mr-10 mb-5 d-inline-block" href="sign_up.php">
                                <i class="fa fa-plus mr-5"></i> Create Account
                            </a>
                            <a class="link-effect text-muted mr-10 mb-5 d-inline-block" href="#">
                                <i class="fa fa-warning mr-5"></i> Forgot Password
                            </a>
                        </div>
                    </div>
                </form>
                <!-- END Sign In Form -->
            </div>
        </div>
    </div>
</div>
<!-- END Page Content -->

<?php require 'inc/_global/views/page_end.php'; ?>
<?php require 'inc/_global/views/footer_start.php'; ?>
<?php require 'inc/_global/views/footer_end.php'; ?>