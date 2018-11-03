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
                text: "Staff Added Successfully !",
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
	echo '<title> ADD STAFF </title>'
?>

<?php require 'inc/_global/views/head_end.php'; ?>
<?php require 'inc/_global/views/page_start.php'; ?>

<!-- Page Content -->
<div class="content">
    <nav class="breadcrumb bg-white push">
        <a class="breadcrumb-item" href="dashboard.php">Dashboard</a>
        <span class="breadcrumb-item active">ADD STAFF</span>
    </nav>
    <div class="block">
        <div class="block-header block-header-default">
            <h2 class="block-title" style="font-size:25px"><strong>ADD STAFF</strong></h2>
        </div>
        <div class="block-content">
        <div class="row justify-content-center py-20">
                <div class="col-xl-5">
                    <form action="inc\backend\database\staff_add.php" method="post" enctype="multipart/form-data">
                        <!-- row -->
                        <div class="form-group">
                            <label for="example-text-input">Staff ID</label>
                            <?php
                            try {
                                //display table
                                include_once 'inc/backend/database/db.php';
                                $sql = "SELECT staff_id FROM staff ORDER BY staff_id ASC;";

                                //create prepared statement
                                $stmt = mysqli_stmt_init($conn);

                                //prepare prepared statement
                                if(!@mysqli_stmt_prepare($stmt, $sql)) {
                                    throw new Exception(mysqli_error($conn));
                                } else {
                                    //run prepared statement
                                    mysqli_stmt_execute($stmt);
                                    //get result
                                    $result = mysqli_stmt_get_result($stmt);

                                    for($i=0; $row=mysqli_fetch_array($result); $i++) {
                                        $i = $row['staff_id'];
                                    }
                                    if($i==0) {
                                        $i=1;
                                    }                                        
                                    echo '<input type="text" class="form-control" id="" name="id" placeholder="" readonly="true" value="'.$i.'">'; 
                                }
                            }
                            catch (Exception $e) {
                                $e->getMessage();
                            }    
                            ?>                           
                        </div>
                        <!-- end row -->
                        <!-- row -->
                        <div class="form-group">
                            <label for="example-text-input">Select Staff Type <span class="text-danger">*</span></label>
                                <select class="form-control" id="" name="type" required="true">
                                    <option value="">Please select</option>
                                    <?php
                                    try{
                                        //display table
                                        //include_once 'inc/backend/database/db.php';
                                        $sql = "SELECT DISTINCT s_type FROM staff_type;"; 

                                        //create prepared statement
                                        //$stmt = mysqli_stmt_init($conn);

                                        //prepare prepared statement
                                        if(!@mysqli_stmt_prepare($stmt, $sql)) {
                                            throw new Exception(mysqli_error($conn));
                                        } else {
                                            //run statement
                                            mysqli_stmt_execute($stmt);

                                            //get result
                                            $result = mysqli_stmt_get_result($stmt);                                        

                                            while($row = mysqli_fetch_array($result)) {
                                                echo '<option value="'.$row['s_type'].'">'.$row['s_type'].'</option>';
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
                            <label for="example-text-input">First Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="" name="f-name" placeholder="Enter First Name" required="true">                            
                        </div>
                        <!-- end row -->
                        <!-- row -->
                        <div class="form-group">
                            <label for="example-text-input">Last Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="" name="l-name" placeholder="Enter Last Name" required="true">                            
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
                            <label  for="example-text-input">Gender <span class="text-danger">*</span></label>
                            <div>
                                <div class="custom-control custom-radio custom-control-inline mb-5">
                                    <input class="custom-control-input" type="radio" name="gender" id="example-inline-radio1" value="Male" checked="">
                                    <label class="custom-control-label" for="example-inline-radio1">Male</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline mb-5">
                                    <input class="custom-control-input" type="radio" name="gender" id="example-inline-radio2" value="Female">
                                    <label class="custom-control-label" for="example-inline-radio2">Female</label>
                                </div>
                            </div>
                        </div>
                        <!-- end row -->
                        <!-- row -->
                        <div class="form-group">
                            <label for="example-text-input">Phone <span class="text-danger">*</span></label>
                            <input type="tel" pattern="[0-9]{3}[0-9]{7}" minLength="10" maxLength="10" class="form-control" id="" name="phone" placeholder="eg: 0771234567" required="true">                            
                        </div>
                        <!-- end row -->
                        <!-- row -->
                        <div class="form-group">
                            <label for="example-text-input">Address <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="" name="address" placeholder="Enter Address" required="true">                            
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
                            <button type="submit" id="submit" name="submit" class="btn btn-alt-primary btn-rounded" data-toggle="click-ripple"><i class="fa fa-plus mr-5"></i>Add Staff</button>
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