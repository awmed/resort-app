<?php
//start session
session_start();

if(!isset($_SESSION['user'])) {
    header('location: sign_in.php');
    exit();    
}

if(!isset($_GET['username'])) {
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
	echo '<title> EDIT USER </title>'
?>

<?php require 'inc/_global/views/head_end.php'; ?>
<?php require 'inc/_global/views/page_start.php'; ?>

<!-- Page Content -->
<div class="content">
    <nav class="breadcrumb bg-white push">
        <a class="breadcrumb-item" href="dashboard.php">Dashboard</a>
        <span class="breadcrumb-item active">EDIT USER </span>
    </nav>
    <div class="block">
        <div class="block-header block-header-default">
            <h2 class="block-title" style="font-size:25px"><strong>EDIT USER </strong></h2>
        </div>
        <div class="block-content">
            <div class="row justify-content-center py-20">
                <div class="col-xl-5">
                    <form action="inc\backend\database\user_edit.php" method="post" enctype="multipart/form-data">
                        <!-- row -->
                        <div class="form-group">
                            <div class="col-md-12 text-center">
                                <?php                                    
                                $username = $_GET['username'];
                                $status = $_GET['status'];                                   

                                if($status!=1) {
                                    echo '<img class="" src="assets/uploads/staff-image/default.jpg" alt="" style="border-radius:80px;">';
                                }
                                if($status==1) {
                                    $imagename = "assets/uploads/user-image/username-".$username."*"; //select the staff image with any image extension
                                    $imageinfo = glob($imagename);
                                    $imageExt = explode(".", $imageinfo[0]);
                                    $imageActualExt = $imageExt[1];
                                    echo '<img class="" src="assets/uploads/user-image/username-'.$username.'.'.$imageActualExt.'?'.mt_rand().' " alt="" style="border-radius:120px;width:250px;height:250px;object-fit:cover;">';
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
                            <label for="example-text-input">User Type</label>
                            <select class="form-control" id="" name="type" required="true">                                    
                                <?php                               
                                if(isset($_GET['type'])) {
                                    $type = $_GET['type'];                                                                     
                                    
                                    //echo '<option value="'.$row['s_type'].'" >'.$row['s_type'].'</option>';
                                    echo '
                                    <option'.(($type=='admin')? " selected='selected' " : "").'> admin</option>
                                    <option'.(($type=='staff')? " selected='selected' " : "").'> staff</option>
                                    <option'.(($type=='customer')? " selected='selected' " : "").'> customer</option>
                                    ';                                                                   
                                }                                                               
                                ?>
                            </select>                            
                        </div>
                        <!-- end row -->
                        <!-- row -->
                        <div class="form-group">
                            <label for="example-text-input">Username</label>
                            <?php
                            $username = $_GET['username'];
                            echo '<input type="text" class="form-control" id="" name="username" placeholder="Enter First Name" required="true" value="'.$username.'" readonly>';
                            ?>                            
                        </div>
                        <!-- end row -->
                        <!-- row -->
                        <div class="form-group">
                            <label for="example-text-input">Email</label>
                            <?php
                            $email = $_GET['email'];
                            echo '<input type="email" class="form-control" id="" name="email" placeholder="eg: jhon@example.com" required="true" value="'.$email.'">';
                            ?>                            
                        </div>
                        <!-- end row -->
                        <!-- row -->
                        <!-- row -->
                        <div class="form-group">
                            <label for="example-text-input">User Image (JPG/PNG) [Optional] - Max 2MB</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="" name="image">
                                <label class="custom-file-label" for="example-file-input-custom">Choose Image</label>
                            </div>             
                        </div>
                        <!-- end row -->
                        <br>
                        <!-- row -->
                        <div class="form-group">
                            <button type="submit" id="submit" name="submit" class="btn btn-alt-warning btn-rounded" data-toggle="click-ripple"><i class="fa fa-edit mr-5"></i> Update User </button>                                
                            <button type="submit" id="submit" name="remove" class="btn btn-alt-danger btn-rounded" data-toggle="click-ripple"> 
                            <a style="color:#ff6666"><i class="fa fa-remove mr-5"></i>Remove Image</a> 
                            </button>
                        </div>
                        <!-- end row -->     
                        <br><hr>
                        <!-- row -->
                        <div class="form-group">
                            <label for="example-text-input">Change Password</label>                                                              
                        </div>
                        <!-- end row -->
                        <!-- row -->
                        <div class="form-group">
                            <label for="example-text-input">New Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="" name="password" placeholder="Enter Last Name">                            
                        </div>
                        <!-- end row -->
                        <!-- row -->
                        <div class="form-group">
                            <label for="example-text-input">Confirm New Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="" name="password-confirm" placeholder="Enter Last Name">                            
                        </div>
                        <!-- end row --> 
                        <br>
                        <!-- row -->
                        <div class="form-group">
                            <button type="submit" id="submit" name="password-change" class="btn btn-alt-warning btn-rounded" data-toggle="click-ripple"><i class="fa fa-edit mr-5"></i> Update Password </button>                                
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