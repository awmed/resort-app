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
                text: "Reservation Deleted Successfully !",
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
                text: "Reservation Updated Successfully !",
                type: "success"
            })
        }, 100);
    </script>';
    //unset session
    unset($_SESSION['updated']);
}


//display alert if fields empty
if(isset($_SESSION['dateError'])) {
    echo 
    '<script>                     
        setTimeout(function() {
            swal({
                title: "Invalid Date Range",
                text: "Retry !",
                type: "warning"
            })
        }, 100);
    </script>';
    
    //unset session
    unset($_SESSION['dateError']);
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
	echo '<title> MODIFY RESERVATION </title>'
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
        <span class="breadcrumb-item active"> MODIFY RESERVATION </span>
    </nav>
    <div class="block">
        <div class="block-header block-header-default">
            <h2 class="block-title d-print-none" style="font-size:25px"><strong> MODIFY RESERVATION </strong></h2>
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
                            <th class="text-center" style="width: 50px;">ID</th>
                            <th class="text-center">Customer ID</th>
                            <th class="text-center">Room Type</th>
                            <th class="text-center">Room NO</th>
                            <th class="text-center">Check In Date</th>
                            <th class="text-center">Check Out Date</th>
                            <th class="text-center">Date Reserved</th>
                            <th class="text-center">Amount</th>
                            <th class="text-center">Payment Type</th>
                            <th class="text-center">Status</th>
                            <th class="text-center d-print-none" data-orderable="false">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        try {
                            //display table
                            include_once 'inc/backend/database/db.php';
                            $sql = "SELECT * FROM reservation;";
                            
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
                                        <td class="text-center">'.$row["res_id"].'</td>
                                        <td class="text-center">'.$row["customer_id"].'</td>
                                        <td class="text-center">'.$row["room_type"].'</td>
                                        <td class="text-center">'.$row["room_no"].'</td>
                                        <td class="text-center">'.$row["check_in"].'</td>
                                        <td class="text-center">'.$row["check_out"].'</td>
                                        <td class="text-center">'.$row["date_reserved"].'</td>
                                        <td class="text-center">Rs. '.$row["amount"].'</td>
                                        <td class="text-center">'.$row["payment_type"].'</td>
                                        <td class="text-center">'.$row["status"].'</td>

                                        <td class="text-center d-print-none">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-sm btn-secondary" data-toggle="tooltip" title="Edit">
                                                    <a href="reservation_edit.php?ID='.$row['res_id'].'&cus='.$row['customer_id'].'&rtype='.$row['room_type'].'&no='.$row['room_no'].'&in='.$row['check_in'].'&out='.$row['check_out'].'&res='.$row['date_reserved'].'&amt='.$row['amount'].'&ptype='.$row['payment_type'].'&status='.$row['status'].'&typeold='.$row['room_type'].'&roomold='.$row['room_no'].'"> <i class="fa fa-pencil"></i> Edit</a>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-secondary" data-toggle="tooltip" title="Delete">
                                                    <a href="inc/backend/database/reservation_edit.php?ID='.$row['res_id'].'"> <i class="fa fa-times"></i> Delete</a> 
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