<?php
//start sesssion
session_start();

if(!isset($_SESSION['user'])) {
    header('location: sign_in.php');
    exit();    
}

//display delete message
if(isset($_SESSION['deleted'])) {
    echo 
    '<script>                     
        setTimeout(function() {
            swal({
                title: "Success",
                text: "User Deleted Successfully !",
                type: "success"
            })
        }, 100);
    </script>';
    //unset session
    unset($_SESSION['deleted']);
}

//display update message
if(isset($_SESSION['updated'])) {
    echo 
    '<script>                     
        setTimeout(function() {
            swal({
                title: "Success",
                text: "User Updated Successfully !",
                type: "success"
            })
        }, 100);
    </script>';
    //unset session
    unset($_SESSION['updated']);
}

//display image removed message
if(isset($_SESSION['removed'])) {
    echo 
    '<script>                     
        setTimeout(function() {
            swal({
                title: "Success",
                text: "User Image Removed !",
                type: "success"
            })
        }, 100);
    </script>';
    //unset session
    unset($_SESSION['removed']);
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
	echo '<title> MODIFY USER </title>'
?>
<style type="text/css" media="print">
    @page 
    {
        size: auto;   /* auto is the initial value */
        margin: 0mm 0mm;   /*this affects the margin in the printer settings */       
    }
    /* body { 
        margin: 5cm; 
        /* margin: 50mm 50mm -0mm 50mm; */
        /* display: block; */
    } */
</style>
<!-- Page JS Plugins CSS -->
<?php $cb->get_css('js/plugins/datatables/dataTables.bootstrap4.min.css'); ?>

<?php require 'inc/_global/views/head_end.php'; ?>
<?php require 'inc/_global/views/page_start.php'; ?>

<!-- Page Content -->
<div class="content">
    <nav class="breadcrumb bg-white push d-print-none">
        <a class="breadcrumb-item" href="dashboard.php">Dashboard</a>
        <span class="breadcrumb-item active"> MODIFY USER </span>
    </nav>
    <div class="block">
        <div class="block-header block-header-default">
            <h2 class="block-title d-print-none" style="font-size:25px"><strong> MODIFY USER </strong></h2>
            <div class="block-options">
                <!-- Print Page functionality is initialized in Codebase() -> uiHelperPrint() -->
                <button type="button" class="btn-block-option" onclick="Codebase.helpers('print-page');">
                    <i class="si si-printer"></i> Print
                </button>
                <button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle"></button>
                <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                    <i class="si si-refresh"></i>
                </button>
            </div>
        </div>
        
        <div class="block-content">
            <div class="table-responsive">
                <!-- <table class="table table-striped table-vcenter table-hover"> -->
                <table class="table table-bordered table-striped table-vcenter js-dataTable-full table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th class="text-center">User Type</th>
                            <th class="text-center d-print-none" style="width: 100px;" data-orderable="false"><i class="si si-user"></i></th>
                            <th class="text-center">Username</th>
                            <th class="text-center">Email</th>
                            <th class="text-center d-print-none" data-orderable="false">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        try {
                            //include database
                            include_once 'inc/backend/database/db.php';
                            $sql = "SELECT * FROM users;";
                            
                            //create prepared statement
                            $stmt = mysqli_stmt_init($conn);

                            //prepare  prepared statement
                            if(!@mysqli_stmt_prepare($stmt, $sql)) {
                                throw new Exception(mysqli_error($conn));
                                //close db connection
                                mysqli_close($conn);
                            } else {
                                 //run the statement
                                mysqli_stmt_execute($stmt);

                                //get result
                                $result = mysqli_stmt_get_result($stmt);

                                while($row=mysqli_fetch_array($result)) {
                                    $username = $row['username'];
                                    $status = $row['img_status'];
                                    echo '
                                    <tr>                                        
                                        <td class="text-center">'.strtoupper($row["user_type"]).'</td>
                                        <td class="text-center d-print-none">';
                                        if($status!=1) {
                                            echo '<img class="" src="assets/uploads/user-image/default.jpg" alt="" style="border-radius:80px;width:50px;height:50px;">';
                                        }
                                        if($status==1) {
                                            $imagename = "assets/uploads/user-image/username-".$username."*"; //select the staff image with any image extension
                                            $imageinfo = glob($imagename);
                                            $imageExt = explode(".", $imageinfo[0]);
                                            $imageActualExt = $imageExt[1];
                                            echo '<img class="" src="assets/uploads/user-image/username-'.$username.'.'.$imageActualExt.'?'.mt_rand().' " alt="" style="border-radius:80px;width:50px;height:50px;object-fit:cover;">';
                                            // echo '<input type="hidden" name="img-status" value="'.$status.'">';
                                        }                                                                           
                                    echo '    
                                        </td>
                                        <td class="text-center">'.$row["username"].'</td>
                                        <td class="text-center">'.$row["email"].'</td>

                                        <td class="text-center d-print-none">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-sm btn-secondary" data-toggle="tooltip" title="View Profile">
                                                    <a href="user_profile.php?username='.$row['username'].'&type='.$row['user_type'].'&email='.$row['email'].'&status='.$row['img_status'].' "> <i class="fa fa-user"></i> </a>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-secondary" data-toggle="tooltip" title="Edit">
                                                    <a href="user_edit.php?username='.$row['username'].'&type='.$row['user_type'].'&email='.$row['email'].'&status='.$row['img_status'].'"> <i class="fa fa-pencil"></i> Edit</a>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-secondary" data-toggle="tooltip" title="Delete">
                                                    <a href="inc/backend/database/user_edit.php?username='.$row['username'].'&status='.$row['img_status'].'"> <i class="fa fa-times"></i> Delete</a>
                                                </button>                                                    
                                            </div>
                                        </td>
                                    </tr>
                                    ';                                                                  
                                }
                            //close db connection
                            mysqli_close($conn);      
                            }                          
                        }
                        catch (Exception $e) {
                            $e->getMessage();
                        }    
                        ?>
                    </tbody>
                </table>
                <!-- end table -->
            </div>
            <!-- end table responsive -->
        </div>
        <!-- end block content -->
    </div>
    <!-- end block -->
</div>
<!-- END Page Content -->

<?php require 'inc/_global/views/page_end.php'; ?>
<?php require 'inc/_global/views/footer_start.php'; ?>

<!-- Page JS Plugins -->
<?php $cb->get_js('js/plugins/datatables/jquery.dataTables.min.js'); ?>
<?php $cb->get_js('js/plugins/datatables/dataTables.bootstrap4.min.js'); ?>

<!-- Page JS Code -->
<script src="assets/js/pages/data_tables.js"></script>

<?php require 'inc/_global/views/footer_end.php'; ?>