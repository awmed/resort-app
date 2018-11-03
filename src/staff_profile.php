<?php
//start session
session_start();

if(!isset($_SESSION['user'])) {
    header('location: sign_in.php');
    exit();    
}

if(!isset($_GET['ID'])) {
    header('location: staff_modify.php');
    exit();
}

?>
<?php require 'inc/_global/config.php'; ?>
<?php require 'inc/backend/config.php'; ?>
<?php
// Codebase - Page specific configuration
$cb->l_header_fixed     = false;
$cb->l_header_style = 'inverse';
$cb->l_sidebar_inverse  = true;
?>

<?php require 'inc/_global/views/head_start.php'; 
	echo '<title> STAFF PROFILE </title>'
?>

<style type="text/css" media="print">
    @page 
    {
        size: auto;   /* auto is the initial value */
        margin: 0mm 50mm;  /* this affects the margin in the printer settings */       
    }
    /* body { 
        margin: 5cm; 
        /* margin: 50mm 50mm -0mm 50mm; */
        /* display: block; */
    } */
</style>

<?php require 'inc/_global/views/head_end.php'; ?>
<?php require 'inc/_global/views/page_start.php'; ?>

<!-- Page Content -->
<div class="content">
    <nav class="breadcrumb bg-white push d-print-none">
        <a class="breadcrumb-item" href="dashboard.php">Dashboard</a>
        <span class="breadcrumb-item active"> STAFF PROFILE </span>
    </nav>
    <div class="block">
        <div class="block-header block-header-default d-print-none">
        <h2 class="block-title" style="font-size:25px"><strong> STAFF PROFILE </strong></h2>
        </div>
        <div class="block-content">
            <div class="row justify-content-center py-20">
                <div class="col-xl-5">
                    <form action="" method="post">     
                        <!-- row -->
                        <div class="form-group">
                            <div class="col-md-12 text-center">
                                <?php
                                $ID = $_GET['ID'];
                                $status = $_GET['status'];                                    

                                if($status!=1) {
                                    echo '<img class="" src="assets/uploads/staff-image/default.jpg" alt="" style="border-radius:80px;">';
                                }
                                if($status==1) {
                                    $imagename = "assets/uploads/staff-image/staff_id-".$ID."*"; //select the staff image with any image extension
                                    $imageinfo = glob($imagename);
                                    $imageExt = explode(".", $imageinfo[0]);
                                    $imageActualExt = $imageExt[1];
                                    echo '<img class="" src="assets/uploads/staff-image/staff_id-'.$ID.'.'.$imageActualExt.'?'.mt_rand().' " alt="" style="border-radius:120px;width:250px;height:250px;object-fit:cover;">';
                                }
                                ?>
                            </div>
                        </div>
                        <!-- end row --> 
                        <br>                    
                        <!-- row -->
                        <div class="form-group">
                            <label for="example-text-input">Staff ID</label>
                            <?php 
                            $ID = $_GET['ID'];
                            echo '<input type="" class="form-control" id="" name="id" placeholder="" required="true" value="'.$ID.'" disabled="true">';
                            ?>                            
                        </div>
                        <!-- end row -->                       
                        <!-- row -->
                        <div class="form-group">
                            <label for="example-text-input">Staff Type</label>
                            <?php
                            $type = $_GET['type'];
                            //echo '<option value="'.$type.'">'.$type.'</option>';
                            echo '<input type="" class="form-control" id="" name="id" placeholder="" required="true" value="'.$type.'" disabled="true">';                                 
                            ?>                        
                        </div>
                        <!-- end row -->
                        <!-- row -->
                        <div class="form-group">
                            <label for="example-text-input">First Name</label>
                            <?php
                            $first = $_GET['first'];
                            echo '<input type="" class="form-control" id="" name="f-name" placeholder="Enter First Name" required="true" value="'.$first.'" disabled="true">';
                            ?>                            
                        </div>
                        <!-- end row -->
                        <!-- row -->
                        <div class="form-group">
                            <label for="example-text-input">Last Name</label>
                            <?php
                            $last = $_GET['last'];
                            echo '<input type="" class="form-control" id="" name="l-name" placeholder="Enter Last Name" required="true" value="'.$last.'" disabled="true">';
                            ?>                            
                        </div>
                        <!-- end row -->
                        <!-- row -->
                        <div class="form-group">
                            <label for="example-text-input">Email</label>
                            <?php
                            $email = $_GET['email'];
                            echo '<input type="" class="form-control" id="" name="email" placeholder="eg: jhon@example.com" required="true" value="'.$email.'" disabled="true">';
                            ?>                            
                        </div>
                        <!-- end row -->
                        <!-- row -->
                        <div class="form-group">
                            <label for="example-text-input">Gender</label>
                            <div>                                
                                <?php
                                $gen = $_GET['gender'];
                                if($gen=='Male') {
                                    echo '
                                    <div class="custom-control custom-radio custom-control-inline mb-5">
                                        <input class="custom-control-input" type="radio" name="gender" id="" value="Male" checked disabled>
                                        <label class="custom-control-label" for="example-inline-radio1">Male</label>
                                    </div>                                    
                                    ';
                                }
                                if($gen=='Female') {
                                    echo '
                                    <div class="custom-control custom-radio custom-control-inline mb-5">
                                        <input class="custom-control-input" type="radio" name="gender" id="" value="Female" checked disabled>
                                        <label class="custom-control-label" for="example-inline-radio2">Female</label>
                                    </div>
                                    ';
                                }
                                ?>                    
                            </div>
                        </div>
                        <!-- end row -->
                        <!-- row -->
                        <div class="form-group ">
                            <label  for="example-text-input">Phone</label>
                            <?php
                            $phone = $_GET['phone'];
                            echo '<input type="tel" pattern="[0-9]{3}[0-9]{7}" minLength="10" maxLength="10" class="form-control" id="" name="phone" placeholder="Enter Type" required="true" value="'.$phone.'" disabled="true">';
                            ?>                            
                        </div>
                        <!-- end row -->
                        <!-- row -->
                        <div class="form-group">
                            <label for="example-text-input">Address</label>
                            <?php
                            $add = $_GET['address'];
                            echo '<input type="text" class="form-control" id="" name="address" placeholder="Enter Type" required="true" value="'.$add.'" disabled="true">';
                            ?>                            
                        </div>
                        <!-- end row --> 
                        <br>
                        <!-- row -->
                        <div class="form-group d-print-none">
                            <div class="col-md-12 text-center">
                                <button type="submit" id="" name="" class="btn btn-alt-primary btn-rounded" data-toggle="click-ripple" onclick="Codebase.helpers('print-page');" ><i class="fa fa-print mr-5"></i>Print</button>   
                            </div>
                        </div>
                        <!-- end row -->   
                        <br>                    
                    </form>
                    <!-- end form -->
                </div>
                <!-- end col xl-6 -->
            </div>
            <!-- end row justify -->
        </div>
        <!-- end block content -->
    </div>
    <!-- end block -->
</div>
<!-- END Page Content -->

<?php require 'inc/_global/views/page_end.php'; ?>
<?php require 'inc/_global/views/footer_start.php'; ?>
<?php require 'inc/_global/views/footer_end.php'; ?>