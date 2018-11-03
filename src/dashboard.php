<?php
//start session
session_start();

if(!isset($_SESSION['user'])) {
    header('location: sign_in.php');
    exit();    
}

//display alert login sucess
if(isset($_SESSION['success'])) {
echo 
'<script>                     
    setTimeout(function() {
        swal({
            title: "Sign In Success",
            text: "'.strtoupper($_SESSION['user']).' Signed In",
            type: "success"
        })
    }, 100);
</script>';

//unset session
unset($_SESSION['success']);
}
?>
<?php require 'inc/_global/config.php'; ?>
<?php require 'inc/backend/config.php'; ?>
<?php
// Codebase - Page specific configuration
$cb->l_header_fixed = false;
$cb->l_header_style = 'glass-inverse';
$cb->l_sidebar_inverse  = true;
?>

<?php require 'inc/_global/views/head_start.php'; 
	echo '<title> Dashboard </title>'
?>

<?php require 'inc/_global/views/head_end.php'; ?>
<?php require 'inc/_global/views/page_start.php'; ?>

<!-- Header Section -->
<div class="bg-image" style="background-image: url('/resort/src/assets/img/photos/dashboard.jpeg');">
    <div class="bg-primary-dark-op">
        <div class="content content-full content-top">
            <h1 class="py-50 text-white text-center text-uppercase">Welcome <?php echo $_SESSION['user']; ?></h1>
        </div>
    </div>
</div>
<!-- END Header Section -->

<!-- Page Content -->
<div class="content">
    <div class="row invisible" data-toggle="appear">
         <!-- Row #1 -->
         <div class="col-md-6 col-xl-3 col-6">
            <a class="block block-link-shadow" href="in_customer.php">
                <div class="block-content block-content-full">
                    <div class="py-20 text-center">
                        <div class="mb-20">
                            <i class="fa fa-calendar-check-o fa-4x text-info"></i>
                        </div>
                        <div class="font-size-h3 font-w600">CHECK IN</div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-6 col-xl-3 col-6">
            <a class="block block-link-shadow" href="out_customer.php">
                <div class="block-content block-content-full">
                    <div class="py-20 text-center">
                        <div class="mb-20">
                            <i class="fa fa-sign-out fa-4x text-danger"></i>
                        </div>
                        <div class="font-size-h3 font-w600">CHECK OUT</div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-6 col-xl-3 col-6">
            <a class="block block-link-shadow" href="payment_add.php">
                <div class="block-content block-content-full">
                    <div class="py-20 text-center">
                        <div class="mb-20">
                            <i class="fa fa-dollar fa-4x text-success"></i>
                        </div>
                        <div class="font-size-h3 font-w600">ADD PAYMENT</div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-6 col-xl-3 col-6">
            <a class="block block-link-shadow" href="invoice_view.php">
                <div class="block-content block-content-full">
                    <div class="py-20 text-center">
                        <div class="mb-20">
                            <i class="si si-printer fa-4x text-warning"></i>
                        </div>
                        <div class="font-size-h3 font-w600">Print Invoice</div>
                    </div>
                </div>
            </a>
        </div>
        <!-- END Row #1 -->
        <!-- Row #2 -->
        <div class="col-6 col-xl-3">
            <a class="block block-rounded block-bordered block-link-shadow" href="reservation_modify.php">
                <div class="block-content block-content-full clearfix">
                    <div class="float-right mt-15 d-none d-sm-block">
                        <i class="fa fa-bookmark-o fa-2x text-primary-light"></i>
                    </div>
                    <div class="font-size-h3 font-w600 text-primary" data-toggle="countTo" data-speed="10000" data-to="150000">0</div>
                    <div class="font-size-sm font-w600 text-uppercase text-muted">Total Reservations</div>
                </div>
            </a>
        </div>
        <div class="col-6 col-xl-3">
            <a class="block block-rounded block-bordered block-link-shadow" href="staff_modify.php">
                <div class="block-content block-content-full clearfix">
                    <div class="float-right mt-15 d-none d-sm-block">
                        <i class="fa fa-user-o fa-2x text-info"></i>
                    </div>
                    <div class="font-size-h3 font-w600 text-info">$<span data-toggle="countTo" data-speed="1000" data-to="780">0</span></div>
                    <div class="font-size-sm font-w600 text-uppercase text-muted">Available Staffs</div>
                </div>
            </a>
        </div>
        <div class="col-6 col-xl-3">
            <a class="block block-rounded block-bordered block-link-shadow" href="customer_modify.php">
                <div class="block-content block-content-full clearfix">
                    <div class="float-right mt-15 d-none d-sm-block">
                        <i class="fa fa-users fa-2x text-elegance-light"></i>
                    </div>
                    <div class="font-size-h3 font-w600 text-elegance" data-toggle="countTo" data-speed="1000" data-to="15">0</div>
                    <div class="font-size-sm font-w600 text-uppercase text-muted">Registered Customers</div>
                </div>
            </a>
        </div>
        <div class="col-6 col-xl-3">
            <a class="block block-rounded block-bordered block-link-shadow" href="users_checked_in.php">
                <div class="block-content block-content-full clearfix">
                    <div class="float-right mt-15 d-none d-sm-block">
                        <i class="fa fa-calendar-check-o fa-2x text-pulse"></i>
                    </div>
                    <div class="font-size-h3 font-w600 text-pulse" data-toggle="countTo" data-speed="1000" data-to="4252">0</div>
                    <div class="font-size-sm font-w600 text-uppercase text-muted">Checked In Users</div>
                </div>
            </a>
        </div>
        <!-- END Row #2 -->   
         <!-- Row #3 -->
         <div class="col-6 col-xl-3">
            <a class="block block-rounded block-bordered block-link-shadow" href="users_checked_out.php">
                <div class="block-content block-content-full clearfix">
                    <div class="float-right mt-15 d-none d-sm-block">
                        <i class="fa fa-sign-out fa-2x text-primary-light"></i>
                    </div>
                    <div class="font-size-h3 font-w600 text-primary" data-toggle="countTo" data-speed="10000" data-to="150000">0</div>
                    <div class="font-size-sm font-w600 text-uppercase text-muted">Checked Out Users</div>
                </div>
            </a>
        </div>
        <div class="col-6 col-xl-3">
            <a class="block block-rounded block-bordered block-link-shadow" href="javascript:void(0)">
                <div class="block-content block-content-full clearfix">
                    <div class="float-right mt-15 d-none d-sm-block">
                        <i class="fa fa-dollar fa-2x text-info"></i>
                    </div>
                    <div class="font-size-h3 font-w600 text-info">$<span data-toggle="countTo" data-speed="1000" data-to="780">0</span></div>
                    <div class="font-size-sm font-w600 text-uppercase text-muted">Total Payments</div>
                </div>
            </a>
        </div>
        <div class="col-6 col-xl-3">
            <a class="block block-rounded block-bordered block-link-shadow" href="room_modify.php">
                <div class="block-content block-content-full clearfix">
                    <div class="float-right mt-15 d-none d-sm-block">
                        <i class="fa fa-building-o fa-2x text-elegance-light"></i>
                    </div>
                    <div class="font-size-h3 font-w600 text-elegance" data-toggle="countTo" data-speed="1000" data-to="15">0</div>
                    <div class="font-size-sm font-w600 text-uppercase text-muted">Available Room</div>
                </div>
            </a>
        </div>
        <div class="col-6 col-xl-3">
            <a class="block block-rounded block-bordered block-link-shadow" href="javascript:void(0)">
                <div class="block-content block-content-full clearfix">
                    <div class="float-right mt-15 d-none d-sm-block">
                        <i class="si si-calendar fa-2x text-pulse"></i>
                    </div>
                    <div class="font-size-h3 font-w600 text-pulse" data-toggle="countTo" data-speed="1000" data-to="25">0</div>
                    <div class="font-size-sm font-w600 text-uppercase text-muted">Staff Schedules</div>
                </div>
            </a>
        </div>
        <!-- END Row #3 -->     
    </div>
</div>
<!-- END Page Content -->

<?php require 'inc/_global/views/page_end.php'; ?>
<?php require 'inc/_global/views/footer_start.php'; ?>

<!-- Page JS Plugins -->
<?php $cb->get_js('js/plugins/chartjs/Chart.bundle.min.js'); ?>

<?php require 'inc/_global/views/footer_end.php'; ?>