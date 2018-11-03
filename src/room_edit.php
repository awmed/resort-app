<?php
//start session
session_start();

if(!isset($_SESSION['user'])) {
    header('location: sign_in.php');
    exit();    
}

if(!isset($_GET['no'])) {
    header('location: room_modify.php');
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
	echo '<title> EDIT ROOM </title>'
?>


<?php require 'inc/_global/views/head_end.php'; ?>
<?php require 'inc/_global/views/page_start.php'; ?>

<!-- Page Content -->
<div class="content">
    <nav class="breadcrumb bg-white push">
        <a class="breadcrumb-item" href="dashboard.php">Dashboard</a>
        <span class="breadcrumb-item active">EDIT ROOM</span>
    </nav>
    <div class="block">
        <div class="block-header block-header-default">
            <h2 class="block-title" style="font-size:25px"><strong>EDIT ROOM</strong></h2>
        </div>
        <div class="block-content">
            <div class="row justify-content-center py-20">
                <div class="col-xl-5">
                    <form action="inc\backend\database\room_edit.php" method="post">                        
                        <!-- row -->
                        <div class="form-group">
                            <label for="example-text-input">Room No</label>
                            <?php 
                            $no = $_GET['no'];
                            echo '<input type="text" class="form-control" id="" name="no" placeholder="Enter Room No" required="true" value="'.$no.'" readonly="readonly">'
                            ?>                            
                        </div>
                        <!-- end row -->                       
                        <!-- row -->
                        <div class="form-group">
                            <label for="example-text-input">Room Type <span class="text-danger">*</span></label>
                            <select class="form-control" id="" name="type" required="true">
                            <?php
                            $type = $_GET['type'];
                            try{
                                //open sb
                                include_once 'inc/backend/database/db.php';

                                $sql = "SELECT DISTINCT room_type FROM room_type;";
                                $stmt = mysqli_stmt_init($conn);
                                if(!@mysqli_stmt_prepare($stmt,$sql)) {
                                    mysqli_close($conn);
                                    throw new Exception(mysqli_error($conn));                                    
                                } else {
                                    mysqli_stmt_execute($stmt);
                                    $result = mysqli_stmt_get_result($stmt);

                                    while($row=mysqli_fetch_array($result)) {
                                        //echo '<option value="'.$row['room_type'].'">'.$row['room_type'].'</option>';
                                        echo '<option'.(($type==$row['room_type'])? " selected='selected' " : "").'>'.$row['room_type'].' </option>';
                                    }
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
                            <?php
                            $avail = $_GET['avail'];
                            if($avail == 'Yes') {
                                echo  '
                                <div class="custom-control custom-radio custom-control-inline mb-5">
                                    <input class="custom-control-input" type="radio" name="available" id="yes" value="Yes" checked="">
                                    <label class="custom-control-label" for="yes">Yes</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline mb-5">
                                    <input class="custom-control-input" type="radio" name="available" id="no" value="No">
                                    <label class="custom-control-label" for="no">No</label>
                                </div>
                                ';
                            }
                            if($avail == 'No') {
                                echo '
                                <div class="custom-control custom-radio custom-control-inline mb-5">
                                    <input class="custom-control-input" type="radio" name="available" id="yes" value="Yes">
                                    <label class="custom-control-label" for="yes">Yes</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline mb-5">
                                    <input class="custom-control-input" type="radio" name="available" id="no" value="No" checked="">
                                    <label class="custom-control-label" for="no">No</label>
                                </div>
                                ';
                            }
                            ?>
                            </div>
                        </div>
                        <!-- end row -->
                        <!-- row -->
                        <div class="form-group">
                            <label for="example-email-input">Rate (Rs.) <span class="text-danger">*</span></label>
                            <?php
                            $rate = $_GET['rate'];
                            echo '<input type="text" class="form-control" id="" name="rate" placeholder="Enter Rate" required="true" value="'.$rate.'">'
                            ?>
                        </div>
                        <!-- end row -->
                        <br>
                        <!-- row -->
                        <div class="form-group">
                            <button type="submit" id="submit" name="submit" class="btn btn-alt-warning btn-rounded" data-toggle="click-ripple"><i class="fa fa-edit mr-5"></i> Update Room </button>
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