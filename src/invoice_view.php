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
	echo '<title> VIEW/PRINT INVOICE </title>'
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

<?php require 'inc/_global/views/head_end.php'; ?>
<?php require 'inc/_global/views/page_start.php'; ?>

<!-- Page Content -->
<div class="content">
    <nav class="breadcrumb bg-white push d-print-none">
        <a class="breadcrumb-item" href="dashboard.php">Dashboard</a>
        <span class="breadcrumb-item active">VIEW/PRINT INVOICE</span>
    </nav>
    <div class="block">
        <div class="block-header block-header-default d-print-none">
            <h2 class="block-title" style="font-size:25px"><strong>VIEW/PRINT INVOICE</strong></h2>
        </div>

        <form action="inc\backend\database\invoice_view.php" method="post">
            <div class="block-content">
                <div class="block">
                <!-- row -->
                <div class="form-group col-md-3 d-print-none">
                    <label for="example-text-input">Select Payment ID <span class="text-danger">*</span></label>
                    <select class="form-control" id="" name="checkin-id" required="true" onchange="this.form.submit()">
                        <option value="">Please select</option>
                        <?php
                        try{
                            //display table
                            include_once 'inc/backend/database/db.php';

                            //create prepared statement
                            $stmt = mysqli_stmt_init($conn);
                            $sql = "SELECT payment_id FROM payment;"; 

                            //prepare prepared statement
                            if(!@mysqli_stmt_prepare($stmt, $sql)) {
                                throw new Exception(mysqli_error($conn));
                            } else {
                                //run statement
                                mysqli_stmt_execute($stmt);

                                //get result
                                $result = mysqli_stmt_get_result($stmt);                                        

                                while($row = mysqli_fetch_array($result)) {
                                    if(isset($_GET['ID'])) {
                                        $id = $_GET['ID'];
                                        echo '<option'.(($id==$row['payment_id'])? " selected='selected' " : "").'>'.$row['payment_id'].' </option>';                                               
                                    } else {
                                        echo '<option value="'.$row['payment_id'].'">'.$row['payment_id'].'</option>';
                                    }
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
                <div class="block-header block-header-default">                    
                    <h3 class="block-title">Payment ID #<?php if(isset($_GET['ID'])) {echo $id;} ?></h3>
                    <div class="block-options">
                        <!-- Print Page functionality is initialized in Codebase() -> uiHelperPrint() -->
                        <button type="button" class="btn-block-option" onclick="Codebase.helpers('print-page');">
                            <i class="si si-printer"></i> Print Invoice
                        </button>
                        <button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle"></button>
                        <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                            <i class="si si-refresh"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content">
                    <!-- Invoice Info -->
                    <div class="row my-20">
                        <!-- Company Info -->
                        <div class="col-6">
                            <p class="h3">Company</p>
                            <address>
                                Street Address<br>
                                State, City<br>
                                Region, Postal Code<br>
                                ltd@example.com
                            </address>
                        </div>
                        <!-- END Company Info -->

                        <!-- Client Info -->
                        <div class="col-6 text-right">
                            <p class="h3">Client</p>
                            <address>
                                Street Address<br>
                                State, City<br>
                                Region, Postal Code<br>
                                ctr@example.com
                            </address>
                        </div>
                        <!-- END Client Info -->
                    </div>
                    <!-- END Invoice Info -->

                    <!-- Table -->
                    <div class="table-responsive push">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 120px;">Payment ID</th>
                                    <th style="width: 120px;">Customer ID</th>
                                    <th>Customer Full Name</th>
                                    <th class="text-center" style="width: 90px;">Room No</th>
                                    <th class="text-right" style="width: 120px;">Date Payed</th>
                                    <th class="text-right" style="width: 120px;">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center"><?php if(isset($_GET['ID'])) {echo $id;} ?></td>
                                    <td class="text-center">
                                        <?php 
                                        if(isset($_GET['cus'])){
                                            $cus = $_GET['cus'];
                                            echo '<div class="text-muted">'.$cus.'</div>';
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php 
                                        if(isset($_GET['name'])){
                                            $name = $_GET['name'];
                                            echo '<div class="text-muted">'.$name.'</div>';
                                        }
                                        ?>
                                    </td>
                                    <td class="text-center">
                                        <?php 
                                        if(isset($_GET['room'])){
                                            $room = $_GET['room'];
                                            echo '<span class="badge badge-pill badge-primary">'.$room.'</span>';
                                        }
                                        ?>
                                    </td>
                                    <td class="text-right">
                                        <?php 
                                        if(isset($_GET['date'])){
                                            $date = $_GET['date'];
                                            echo $date;
                                        }
                                        ?>
                                    </td>
                                    <td class="text-right">
                                        <?php 
                                        if(isset($_GET['amount'])){
                                            $amount = $_GET['amount'];
                                            echo '$ '.$amount;
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <tr class="table-warning">
                                    <td colspan="5" class="font-w700 text-uppercase text-right">Total Payed</td>
                                    <td class="font-w700 text-right">
                                        <?php 
                                        if(isset($_GET['amount'])){
                                            $amount = $_GET['amount'];
                                            echo '$ '.$amount;
                                        }
                                        ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- END Table -->

                    <!-- Footer -->
                    <p class="text-muted text-center">Thank you very much for doing business with us. We look forward to working with you again!</p>
                    <!-- END Footer -->
                </div>
            </div>
            <!-- END Invoice -->   
            </div>
            <!-- end block content -->
        </form>
    </div>
    <!-- end block -->
</div>
<!-- END Page Content -->

<?php require 'inc/_global/views/page_end.php'; ?>
<?php require 'inc/_global/views/footer_start.php'; ?>
<?php require 'inc/_global/views/footer_end.php'; ?>