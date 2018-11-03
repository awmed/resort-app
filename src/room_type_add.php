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
            text: "Room Type Added Successfully !",
            type: "success"
        })
    }, 100);
</script>';
//unset session
unset($_SESSION['success']);
}

//display alert if data already exists
if(isset($_SESSION['exist'])) {
    echo 
    '<script>                     
        setTimeout(function() {
            swal({
                title: "Room Type Already Exist",
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
	echo '<title> ADD ROOM TYPE </title>'
?>

<?php require 'inc/_global/views/head_end.php'; ?>
<?php require 'inc/_global/views/page_start.php'; ?>

<!-- Page Content -->
<div class="content">
    <nav class="breadcrumb bg-white push">
        <a class="breadcrumb-item" href="dashboard.php">Dashboard</a>
        <span class="breadcrumb-item active">ADD ROOM TYPE</span>
    </nav>
    <div class="block">
        <div class="block-header block-header-default">
            <h2 class="block-title" style="font-size:25px"><strong>ADD ROOM TYPE</strong></h2>
        </div>
        <div class="block-content">
            <div class="row justify-content-center py-20">
                <div class="col-xl-5">
                    <form action="inc\backend\database\room_type_add.php" method="post" enctype="multipart/form-data">
                        <!-- row -->
                        <div class="form-group ">
                            <label for="example-text-input">Room Type ID</label>
                                <?php
                                try {
                                    include_once 'inc/backend/database/db.php';
                                    $sql = "SELECT r_type_id FROM room_type ORDER BY r_type_id ASC;";
                                
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
                                            $i = $row['r_type_id'];                                    
                                        }
                                        if ($i==0) {
                                            $i = 1;
                                        }                                 
                                        //close db connection
                                        //mysqli_close($conn);  
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
                            <label for="example-text-input">Room Type <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="" name="type" placeholder="Enter Type" required="true">                            
                        </div>
                        <!-- end row -->
                        <!-- row -->
                        <div class="form-group">
                            <label for="example-email-input">Description <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="" name="description" rows="5" placeholder="Enter Description" required="true"></textarea>  
                        </div>
                        <!-- end row -->
                        <hr>    
                        <!-- row -->
                        <div class="form-group row">
                            <label class="col-12">Facilities</label>
                            <div class="col-12">
                            <?php
                            try {
                                $sql = "SELECT DISTINCT facility FROM facilities;";
                                //prepare prepared statement
                                if(!@mysqli_stmt_prepare($stmt, $sql)) {
                                    throw new Exception(mysqli_error($conn));                                        
                                } else {
                                    //run statement
                                    mysqli_stmt_execute($stmt);
                                    //get result
                                    $result = mysqli_stmt_get_result($stmt);
                                    
                                    while($row = mysqli_fetch_array($result)) {
                                        echo '
                                        <div class="custom-control custom-checkbox custom-control-inline mb-5">
                                            <input class="custom-control-input" type="checkbox" name="facility[]" id="'.$row['facility'].'" value="'.$row['facility'].'" >
                                            <label class="custom-control-label" for="'.$row['facility'].'">'.$row['facility'].'</label>
                                        </div>    
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
                            </div>
                        </div>
                        <!-- end row -->
                        <hr><br>
                        <!-- row -->
                        <div class="form-group row">
                            <label class="col-12">Room Images (JPG/PNG) [Multiple] - Max 2MB</label>
                            <div class="col-12">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="example-file-multiple-input-custom" name="image[]" multiple required="true">
                                    <label class="custom-file-label" for="example-file-multiple-input-custom">Choose files</label>
                                </div>
                            </div>
                        </div>
                        <!-- end row -->
                        <br>
                        <!-- row -->
                        <div class="form-group">
                                <button type="submit" id="submit" name="submit" class="btn btn-alt-primary btn-rounded" data-toggle="click-ripple"><i class="fa fa-plus mr-5"></i>Add Room Type</button>
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