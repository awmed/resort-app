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
                text: "Room Added Successfully !",
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

//display alert if data already exists
if(isset($_SESSION['exist'])) {
    echo 
    '<script>                     
        setTimeout(function() {
            swal({
                title: "Room Already Exist",
                text: "Please Retry !",
                type: "warning"
            })
        }, 100);
    </script>';
    //unset session
    unset($_SESSION['exist']);
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
	echo '<title> ADD ROOM </title>'
?>

<?php require 'inc/_global/views/head_end.php'; ?>
<?php require 'inc/_global/views/page_start.php'; ?>

<!-- Page Content -->
<div class="content">
    <nav class="breadcrumb bg-white push">
        <a class="breadcrumb-item" href="dashboard.php">Dashboard</a>
        <span class="breadcrumb-item active">ADD ROOM</span>
    </nav>
    <div class="block">
        <div class="block-header block-header-default">
            <h2 class="block-title" style="font-size:25px"><strong>ADD ROOM</strong></h2>
        </div>
        <div class="block-content">
        <div class="row justify-content-center py-20">
                <div class="col-xl-5">
                    <form action="inc\backend\database\room_add.php" method="post" enctype="multipart/form-data">
                        <!-- row -->
                        <div class="form-group">
                            <label for="example-text-input">Room No <span class="text-danger">*</span></label>                                     
                             <input type="number" class="form-control" id="" name="no" placeholder="Enter Room No" required="true" >                        
                        </div>
                        <!-- end row -->
                        <!-- row -->
                        <div class="form-group">
                            <label for="example-text-input">Select Room Type <span class="text-danger">*</span></label>
                            <select class="form-control" id="" name="type" required="true">
                                <option value="">Please select</option>
                                <?php
                                try{
                                    //display table
                                    include_once 'inc/backend/database/db.php';
                                    $sql = "SELECT DISTINCT room_type FROM room_type;"; 

                                    //create prepared statement
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
                                            echo '<option value="'.$row['room_type'].'">'.$row['room_type'].'</option>';
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
                            <label  for="example-text-input">Avaiability <span class="text-danger">*</span></label>
                            <div>
                                <div class="custom-control custom-radio custom-control-inline mb-5">
                                    <input class="custom-control-input" type="radio" name="available" id="yes" value="Yes" checked="">
                                    <label class="custom-control-label" for="yes">Yes</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline mb-5">
                                    <input class="custom-control-input" type="radio" name="available" id="no" value="No">
                                    <label class="custom-control-label" for="no">No</label>
                                </div>
                            </div>
                        </div>
                        <!-- end row -->
                        <!-- row -->
                        <div class="form-group">
                            <label for="example-text-input">Rate (Rs.) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="" name="rate" placeholder="Enter Rate" required="true">                            
                        </div>
                        <!-- end row -->
                        <br>
                        <!-- row -->
                        <div class="form-group">
                            <button type="submit" id="submit" name="submit" class="btn btn-alt-primary btn-rounded" data-toggle="click-ripple"><i class="fa fa-plus mr-5"></i>Add Room</button>
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