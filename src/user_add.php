<?php
//start session
session_start();

if(!isset($_SESSION['user'])) {
    header('location: sign_in.php');
    exit();    
}

//display alert if data is posted to database
if(isset($_SESSION['success'])) {
    echo 
    '<script>                     
        setTimeout(function() {
            swal({
                title: "Success",
                text: "User Added Successfully !",
                type: "success"
            })
        }, 100);
    </script>';
    //unset session
    unset($_SESSION['success']);
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

//display alert if username already taken
if(isset($_SESSION['taken'])) {
    echo 
    '<script>                     
        setTimeout(function() {
            swal({
                title: "Username Already Taken",
                text: "Please Retry !",
                type: "warning"
            })
        }, 100);
    </script>';
    
    //unset session
    unset($_SESSION['taken']);
    }
    
    //display alert if password missmatch
    if(isset($_SESSION['pass-miss'])) {
    echo 
    '<script>                     
        setTimeout(function() {
            swal({
                title: "Password Doesn\'t Match",
                text: "Please Retry !",
                type: "error"
            })
        }, 100);
    </script>';
    
    //unset session
    unset($_SESSION['pass-miss']);
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
	echo '<title> ADD USER </title>'
?>

<?php require 'inc/_global/views/head_end.php'; ?>
<?php require 'inc/_global/views/page_start.php'; ?>

<!-- Page Content -->
<div class="content">
    <nav class="breadcrumb bg-white push">
        <a class="breadcrumb-item" href="dashboard.php">Dashboard</a>
        <span class="breadcrumb-item active">ADD USER</span>
    </nav>
    <div class="block">
        <div class="block-header block-header-default">
            <h2 class="block-title" style="font-size:25px"><strong>ADD USER</strong></h2>
        </div>
        <div class="block-content">
        <div class="row justify-content-center py-20">
                <div class="col-xl-5">
                    <form action="inc\backend\database\user_add.php" method="post" enctype="multipart/form-data">                       
                        <!-- row -->
                        <div class="form-group">
                            <label for="example-text-input">Select User Type <span class="text-danger">*</span></label>
                            <select class="form-control" id="" name="type" required="true">
                                <option value="">Please select</option>                                    
                                <option value="admin">Admin</option>                                    
                                <option value="staff">Staff</option>                                    
                                <option value="customer">Customer</option>                                                                  
                            </select>                            
                        </div>
                        <!-- end row -->
                        <!-- row -->
                        <div class="form-group">
                            <label for="example-text-input">Username <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="" name="username" placeholder="Enter First Name" required="true">                            
                        </div>
                        <!-- end row -->
                        <!-- row -->
                        <div class="form-group">
                            <label for="example-text-input">Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="" name="password" placeholder="Enter Last Name" required="true">                            
                        </div>
                        <!-- end row -->
                        <!-- row -->
                        <div class="form-group">
                            <label for="example-text-input">Confirm Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="" name="password-confirm" placeholder="Enter Last Name" required="true">                            
                        </div>
                        <!-- end row -->
                        <!-- row -->
                        <div class="form-group">
                            <label for="example-text-input">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="" name="email" placeholder="eg: jhon@example.com" required="true">                           
                        </div>
                        <!-- end row -->
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
                            <button type="submit" id="submit" name="submit" class="btn btn-alt-primary btn-rounded" data-toggle="click-ripple"><i class="fa fa-plus mr-5"></i>Add User</button>
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