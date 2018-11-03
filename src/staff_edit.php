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
	echo '<title> EDIT STAFF </title>'
?>

<?php require 'inc/_global/views/head_end.php'; ?>
<?php require 'inc/_global/views/page_start.php'; ?>

<!-- Page Content -->
<div class="content">
    <nav class="breadcrumb bg-white push">
        <a class="breadcrumb-item" href="dashboard.php">Dashboard</a>
        <span class="breadcrumb-item active">EDIT STAFF </span>
    </nav>
    <div class="block">
        <div class="block-header block-header-default">
            <h2 class="block-title" style="font-size:25px"><strong>EDIT STAFF </strong></h2>
        </div>
        <div class="block-content">
            <div class="row justify-content-center py-20">
                <div class="col-xl-5">
                    <form action="inc\backend\database\staff_edit.php" method="post" enctype="multipart/form-data">
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
                                    // post image status
                                    echo '<input type="hidden" name="img-status" value="'.$status.'">';
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
                            echo '<input type="text" class="form-control" id="" name="id" placeholder="" required="true" value="'.$ID.'" readonly="readonly">';
                            ?>                            
                        </div>
                        <!-- end row -->                       
                        <!-- row -->
                        <div class="form-group">
                            <label for="example-text-input">Staff Type</label>
                            <select class="form-control" id="" name="type" required="true">                                    
                                <?php
                                try{
                                    $type = $_GET['type'];
                                    //echo '<option value="'.$type.'">'.$type.'</option>';

                                    //include database
                                    include_once 'inc/backend/database/db.php';
                                    $sql = "SELECT DISTINCT s_type FROM staff_type;"; 

                                    //prepare prepared statement
                                    $stmt = mysqli_stmt_init($conn);

                                    //prepare prepared statement
                                    if(!@mysqli_stmt_prepare($stmt, $sql)) {
                                        throw new Exception(mysqli_error($conn));
                                    } else {
                                        //run statement
                                        mysqli_stmt_execute($stmt);

                                        //get result
                                        $result = mysqli_stmt_get_result($stmt);                                        

                                        while($row = mysqli_fetch_array($result)) {
                                            //echo '<option value="'.$row['s_type'].'" >'.$row['s_type'].'</option>';
                                            echo '<option'.(($type==$row['s_type'])? " selected='selected' " : "").'>'.$row['s_type'].' </option>';
                                        }
                                        //close db connection
                                        mysqli_close($conn);  
                                    }                                        
                                }
                                catch (Exception $e) {
                                    $e->getMessage();
                                }    
                                ?>
                            </select>                            
                        </div>
                        <!-- end row -->
                        <!-- row -->
                        <div class="form-group">
                            <label for="example-text-input">First Name</label>
                            <?php
                            $type = $_GET['first'];
                            echo '<input type="text" class="form-control" id="" name="f-name" placeholder="Enter First Name" required="true" value="'.$type.'">';
                            ?>                            
                        </div>
                        <!-- end row -->
                        <!-- row -->
                        <div class="form-group">
                            <label for="example-text-input">Last Name</label>
                            <?php
                            $type = $_GET['last'];
                            echo '<input type="text" class="form-control" id="" name="l-name" placeholder="Enter Last Name" required="true" value="'.$type.'">';
                            ?>                            
                        </div>
                        <!-- end row -->
                        <!-- row -->
                        <div class="form-group">
                            <label for="example-text-input">Email</label>
                            <?php
                            $type = $_GET['email'];
                            echo '<input type="email" class="form-control" id="" name="email" placeholder="eg: jhon@example.com" required="true" value="'.$type.'">';
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
                                        <input class="custom-control-input" type="radio" name="gender" id="example-inline-radio1" value="Male" checked>
                                        <label class="custom-control-label" for="example-inline-radio1">Male</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline mb-5">
                                        <input class="custom-control-input" type="radio" name="gender" id="example-inline-radio2" value="Female">
                                        <label class="custom-control-label" for="example-inline-radio2">Female</label>
                                    </div>
                                    ';
                                }
                                if($gen=='Female') {
                                    echo '
                                    <div class="custom-control custom-radio custom-control-inline mb-5">
                                        <input class="custom-control-input" type="radio" name="gender" id="example-inline-radio1" value="Male" >
                                        <label class="custom-control-label" for="example-inline-radio1">Male</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline mb-5">
                                        <input class="custom-control-input" type="radio" name="gender" id="example-inline-radio2" value="Female" checked>
                                        <label class="custom-control-label" for="example-inline-radio2">Female</label>
                                    </div>
                                    ';
                                }
                            ?>                                             
                            </div>
                        </div>
                        <!-- end row -->
                        <!-- row -->
                        <div class="form-group">
                            <label for="example-text-input">Phone</label>
                            <?php
                            $type = $_GET['phone'];
                            echo '<input type="tel" pattern="[0-9]{3}[0-9]{7}" minLength="10" maxLength="10" class="form-control" id="" name="phone" placeholder="Enter Type" required="true" value="'.$type.'">';
                            ?>                            
                        </div>
                        <!-- end row -->
                        <!-- row -->
                        <div class="form-group">
                            <label for="example-text-input">Address</label>
                            <?php
                            $type = $_GET['address'];
                            echo '<input type="text" class="form-control" id="" name="address" placeholder="Enter Address" required="true" value="'.$type.'">';
                            ?>                            
                        </div>
                        <!-- end row -->
                        <!-- row -->
                        <div class="form-group">
                            <label for="example-text-input">Staff Image (JPG/PNG) [Optional] - Max 2MB</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="" name="image">
                                <label class="custom-file-label" for="example-file-input-custom">Choose Image</label>
                            </div>             
                        </div>
                        <!-- end row -->
                        <br>
                        <!-- row -->
                        <div class="form-group">
                            <button type="submit" id="submit" name="submit" class="btn btn-alt-warning btn-rounded" data-toggle="click-ripple"><i class="fa fa-edit mr-5"></i> Update Staff </button>                                
                            <button type="submit" id="submit" name="remove" class="btn btn-alt-danger btn-rounded" data-toggle="click-ripple"> 
                            <a style="color:#ff6666"><i class="fa fa-remove mr-5"></i>Remove Image</a> 
                            </button>
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