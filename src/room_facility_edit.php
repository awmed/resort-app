<?php
//start session
session_start();

if(!isset($_SESSION['user'])) {
    header('location: sign_in.php');
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
	echo '<title> EDIT ROOM FACILITY </title>'
?>

<?php require 'inc/_global/views/head_end.php'; ?>
<?php require 'inc/_global/views/page_start.php'; ?>

<!-- Page Content -->
<div class="content">
    <nav class="breadcrumb bg-white push">
        <a class="breadcrumb-item" href="dashboard.php">Dashboard</a>
        <span class="breadcrumb-item active">EDIT ROOM FACILITY</span>
    </nav>
    <div class="block">
        <div class="block-header block-header-default">
            <h2 class="block-title" style="font-size:25px"><strong>EDIT ROOM FACILITY</strong></h2>
        </div>
        <div class="block-content">
            <div class="row justify-content-center py-20">
                <div class="col-xl-5">
                    <form action="inc\backend\database\room_type_edit.php" method="post">                      
                        <!-- row -->
                        <div class="form-group">
                            <label for="example-text-input">Room Type <span class="text-danger">*</span></label>
                            <?php
                            $type = $_GET['type'];
                                echo '<input type="text" class="form-control" id="" name="type" placeholder="Enter Type" value="'.$type.'" readonly="true">'  
                            ?>
                        </div>
                        <!-- end row -->
                        <hr>    
                        <!-- row -->
                        <div class="form-group row">
                            <label class="col-12">Select New Facilities</label>
                            <div class="col-12">
                            <?php
                            try {
                                include_once 'inc/backend/database/db.php';
                                $sql = "SELECT DISTINCT facility FROM facilities;";
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
                        <div class="form-group">
                                <button type="submit" id="submit" name="update" class="btn btn-alt-warning btn-rounded" data-toggle="click-ripple"><i class="fa fa-edit mr-5"></i>Update Room Facilities</button>
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