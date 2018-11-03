<?php
//start session
session_start();

if(!isset($_SESSION['user'])) {
    header('location: sign_in.php');
    exit();    
}

if(!isset($_GET['ID'])) {
    header('location: staff_type_modify.php');
    exit();
}

//display alert if data already exists
if(isset($_SESSION['exist'])) {
    echo 
    '<script>                     
        setTimeout(function() {
            swal({
                title: "Staff Type Already Exist",
                text: "Please Retry !",
                type: "warning"
            })
        }, 100);
    </script>';
    //unset session
    unset($_SESSION['exist']);
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
<?php require 'inc/backend/config.php'; ?>
<?php
// Codebase - Page specific configuration
$cb->l_header_fixed     = false;
$cb->l_header_style = 'inverse';
$cb->l_sidebar_inverse  = true;
?>
<?php require 'inc/_global/views/head_start.php'; 
	echo '<title> EDIT STAFF TYPE </title>'
?>


<?php require 'inc/_global/views/head_end.php'; ?>
<?php require 'inc/_global/views/page_start.php'; ?>

<!-- Page Content -->
<div class="content">
    <nav class="breadcrumb bg-white push">
        <a class="breadcrumb-item" href="dashboard.php">Dashboard</a>
        <span class="breadcrumb-item active">EDIT STAFF TYPE</span>
    </nav>
    <div class="block">
        <div class="block-header block-header-default">
            <h2 class="block-title" style="font-size:25px"><strong>EDIT STAFF TYPE</strong></h2>
        </div>
        <div class="block-content">
            <div class="row justify-content-center py-20">
                <div class="col-xl-5">
                    <form action="inc\backend\database\staff_type_edit.php" method="post">                        
                        <!-- row -->
                        <div class="form-group">
                            <label for="example-text-input">Staff Type ID</label>
                            <?php 
                            $ID = $_GET['ID'];
                            echo '<input type="text" class="form-control" id="" name="id" placeholder="" required="true" value="'.$ID.'" readonly="readonly">'
                            ?>                            
                        </div>
                        <!-- end row -->                       
                        <!-- row -->
                        <div class="form-group">
                            <label for="example-text-input">Staff Type</label>
                            <?php
                            $type = $_GET['type'];
                            echo '<input type="text" class="form-control" id="" name="type" placeholder="Enter Type" required="true" value="'.$type.'">'
                            ?>                            
                        </div>
                        <!-- end row -->
                        <!-- row -->
                        <div class="form-group">
                            <label for="example-email-input">Description</label>
                            <?php
                            $desc = $_GET['desc'];
                            echo '<textarea class="form-control" id="" name="description" rows="5" placeholder="Enter Description" required="true">'.$desc.'</textarea>'                               
                            ?>
                        </div>
                        <!-- end row -->
                        <br>
                        <!-- row -->
                        <div class="form-group">
                            <button type="submit" id="submit" name="submit" class="btn btn-alt-warning btn-rounded" data-toggle="click-ripple"><i class="fa fa-edit mr-5"></i> Update Staff Type </button>
                        </div>
                        <!-- end row -->                       
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