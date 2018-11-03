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
                text: "Room Deleted Successfully !",
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
                text: "Room Updated Successfully !",
                type: "success"
            })
        }, 100);
    </script>';
    //unset session
    unset($_SESSION['updated']);
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
	echo '<title> MODIFY ROOM </title>'
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
        <span class="breadcrumb-item active"> MODIFY ROOM </span>
    </nav>
    <div class="block">
        <div class="block-header block-header-default">
            <h2 class="block-title d-print-none" style="font-size:25px"><strong> MODIFY ROOM </strong></h2>
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
                <table class="table table-bordered table-striped table-vcenter js-dataTable-full table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th class="text-center" style="width: 120px;">Room No</th>
                            <th class="text-center">Room Type</th>
                            <th class="text-center">Available</th>
                            <th class="text-center">Rate</th>
                            <th class="text-center d-print-none" style="width: 120px;" data-orderable="false">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        try {
                            //display table
                            include_once 'inc/backend/database/db.php';
                            $sql = "SELECT * FROM room;";
                            
                            //create prepared statement
                            $stmt = mysqli_stmt_init($conn);

                            //prepare  prepared statement
                            if(!@mysqli_stmt_prepare($stmt, $sql)) {
                                throw new Exception(mysqli_error($conn));
                            } else {
                                //run the statement
                                mysqli_stmt_execute($stmt);

                                //get result
                                $result = mysqli_stmt_get_result($stmt);

                                while($row=mysqli_fetch_array($result)) {
                                    echo ('
                                    <tr>
                                        <td class="text-center">'.$row["room_no"].'</td>
                                        <td class="text-center">'.$row["room_type"].'</td>
                                        <td class="text-center">'.$row["availability"].'</td>
                                        <td class="text-center">Rs. '.$row["rate"].'</td>

                                        <td class="text-center d-print-none">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-sm btn-secondary" data-toggle="tooltip" title="Edit">
                                                    <a href="room_edit.php?no='.$row['room_no'].'&type='.$row['room_type'].'&avail='.$row['availability'].'&rate='.$row['rate'].'"> <i class="fa fa-pencil"></i> Edit</a>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-secondary" data-toggle="tooltip" title="Delete">
                                                    <a href="inc/backend/database/room_edit.php?no='.$row['room_no'].'"> <i class="fa fa-times"></i> Delete</a> 
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    ');
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