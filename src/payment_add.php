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
                text: "Payment Accepted From Customer Successfully !",
                type: "success"
            })
        }, 100);
    </script>';
    //unset session
    unset($_SESSION['success']);
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
	echo '<title>ADD PAYMENT</title>'
?>

<!-- Page JS Plugins CSS -->
<?php $cb->get_css('js/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css'); ?>

<?php require 'inc/_global/views/head_end.php'; ?>
<?php require 'inc/_global/views/page_start.php'; ?>

<!-- Page Content -->
<div class="content">
    <nav class="breadcrumb bg-white push">
        <a class="breadcrumb-item" href="dashboard.php">Dashboard</a>
        <span class="breadcrumb-item active">ADD PAYMENT</span>
    </nav>
    <div class="block">
        <div class="block-header block-header-default">
            <h2 class="block-title" style="font-size:25px"><strong>ADD PAYMENT</strong></h2>
        </div>
        <div class="block-content">
        <div class="row justify-content-center py-20">
                <div class="col-xl-5">
                    <form action="inc\backend\database\payment_add.php" method="post">                        
                        <!-- row -->
                        <div class="form-group">
                            <label for="example-text-input">Select Check In ID <span class="text-danger">*</span></label>
                            <select class="form-control" id="" name="checkin-id" required="true" onchange="this.form.submit()">
                                <option value="">Please select</option>
                                <?php
                                try{
                                    //display table
                                    include_once 'inc/backend/database/db.php';                                

                                    //create prepared statement
                                    $stmt = mysqli_stmt_init($conn);
                                    $sql = "SELECT in_id FROM check_in WHERE status=?;"; 
                                    $unpaid = "Unpaid";
                                    //prepare prepared statement
                                    if(!@mysqli_stmt_prepare($stmt, $sql)) {
                                        throw new Exception(mysqli_error($conn));
                                    } else {
                                        mysqli_stmt_bind_param($stmt,"s", $unpaid);
                                        //run statement
                                        mysqli_stmt_execute($stmt);

                                        //get result
                                        $result = mysqli_stmt_get_result($stmt);                                        

                                        while($row = mysqli_fetch_array($result)) {
                                            if(isset($_GET['ID'])) {
                                                $id = $_GET['ID'];
                                                echo '<option'.(($id==$row['in_id'])? " selected='selected' " : "").'>'.$row['in_id'].' </option>';                                               
                                            } else {
                                                echo '<option value="'.$row['in_id'].'">'.$row['in_id'].'</option>';
                                            }
                                        }
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
                            <label for="example-text-input">Customer ID </label>
                            <?php                            
                            if(isset($_GET['cus'])) {
                                $cus = $_GET['cus'];
                                echo '<input type="text" class="form-control" id="" name="customer-id" placeholder="" required="true" readonly="true" value="'.$cus.'">';
                            } else {
                                echo '<input type="text" class="form-control" id="" name="customer-id" placeholder="" required="true" readonly="true">';
                            }
                            ?>                            
                        </div>
                        <!-- end row -->                     
                        <!-- row -->
                        <div class="form-group">
                            <label for="example-text-input">Room Type </label>
                            <?php
                            if(isset($_GET['type'])) {
                                $type = $_GET['type'];
                                echo '<input type="tel" class="form-control" id="" name="type" placeholder="" required="true" readonly="true" value="'.$type.'">';    
                            } else {
                                echo '<input type="tel" class="form-control" id="" name="type" placeholder="" required="true" readonly="true">';    
                            }
                            ?>               
                        </div>
                        <!-- end row -->
                        <!-- row -->
                        <div class="form-group">
                            <label for="example-text-input">Room No </label>
                            <?php
                            if(isset($_GET['room'])) {
                                $room = $_GET['room'];
                                echo '<input type="tel" class="form-control" id="" name="room-no" placeholder="" required="true" readonly="true" value="'.$room.'">';    
                            } else {
                                echo '<input type="tel" class="form-control" id="" name="room-no" placeholder="" required="true" readonly="true">';    
                            }
                            ?>                        
                        </div>                       
                        <!-- end row -->
                        <!-- Datepicker JS -->
                        <div class="form-group row">
                            <label class="col-12" for="example-daterange1">Date</label>
                            <div class="col-lg-12">
                                <div class="input-daterange input-group" data-date-format="mm/dd/yyyy" data-week-start="1" data-autoclose="true" data-today-highlight="true">
                                    <?php
                                    if(isset($_GET['in'])) {
                                        $in = $_GET['in'];
                                        echo '<input type="text" class="form-control" id="" name="check-in" placeholder="Check In Date" data-week-start="1" data-autoclose="true" data-today-highlight="true" required="true" value="'.$in.'" disabled>';
                                    } else {
                                        echo '<input type="text" class="form-control" id="" name="check-in" placeholder="Check In Date" data-week-start="1" data-autoclose="true" data-today-highlight="true" required="true" disabled >';
                                    }
                                    ?>
                                    <div class="input-group-prepend input-group-append">
                                        <span class="input-group-text font-w600">to</span>
                                    </div>
                                    <?php
                                    if(isset($_GET['out'])) {
                                        $out = $_GET['out'];
                                        echo '<input type="text" class="form-control" id="" name="check-out" placeholder="Check Out Date" data-week-start="1" data-autoclose="true" data-today-highlight="true" required="true" value="'.$out.'" disabled>';
                                    } else {
                                        echo '<input type="text" class="form-control" id="" name="check-out" placeholder="Check Out Date" data-week-start="1" data-autoclose="true" data-today-highlight="true" required="true" disabled>';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <!-- END Datepicker JS -->
                        <!-- row -->
                        <div class="form-group">
                            <label for="example-text-input">Total Amount (Rs.) </label>
                            <?php
                            if(isset($_GET['amount'])) {
                                $amount = $_GET['amount'];
                                echo '<input type="tel" class="form-control" id="" name="amount" placeholder="" required="true" readonly="true" value="'.$amount.'">';    
                            } else {
                                echo '<input type="tel" class="form-control" id="" name="amount" placeholder="" required="true" readonly="true">';    
                            }
                            ?>                         </div>
                        <!-- end row -->
                        <!-- row -->
                        <div class="form-group">
                            <label for="example-text-input">Payment Type</label>                              
                            <?php
                            if(isset($_GET['payment'])) {
                                $payment = $_GET['payment'];
                                echo '<input type="tel" class="form-control" id="" name="payment" placeholder="" required="true" readonly="true" value="'.$payment.'">';    
                            } else {
                                echo '<input type="tel" class="form-control" id="" name="payment" placeholder="" required="true" readonly="true">';    
                            }
                            ?>                            
                        </div>
                        <!-- end row -->     
                        <!-- row -->
                        <div class="form-group">
                            <label for="example-text-input">Payment Status</label>
                            <div>          
                                <?php
                                if(isset($_GET['status'])) { 
                                    $status = $_GET['status'];
                                    if($status == "Unpaid") {
                                        echo'
                                        <div class="custom-control custom-radio custom-control-inline mb-5">
                                            <input class="custom-control-input" type="radio" name="status" id="" value="Unpaid" checked >
                                            <label class="custom-control-label" for="">Unpaid</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline mb-5">
                                            <input class="custom-control-input" type="radio" name="status" id="" value="Paid" >
                                            <label class="custom-control-label" for="">Paid</label>
                                        </div>';  
                                    }
                                    if($status == "Paid") {
                                        echo'
                                        <div class="custom-control custom-radio custom-control-inline mb-5">
                                            <input class="custom-control-input" type="radio" name="status" id="" value="Unpaid" >
                                            <label class="custom-control-label" for="">Unpaid</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline mb-5">
                                            <input class="custom-control-input" type="radio" name="status" id="" value="Paid" checked >
                                            <label class="custom-control-label" for="">Paid</label>
                                        </div>';  
                                    }
                                    
                                } else {
                                    echo'
                                    <div class="custom-control custom-radio custom-control-inline mb-5">
                                        <input class="custom-control-input" type="radio" name="status" id="example-inline-radio1" value="Unpaid" checked>
                                        <label class="custom-control-label" for="example-inline-radio1">Unpaid</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline mb-5">
                                        <input class="custom-control-input" type="radio" name="status" id="example-inline-radio2" value="Paid">
                                        <label class="custom-control-label" for="example-inline-radio2">Paid</label>
                                    </div>';  
                                }   
                                ?>                                                                   
                            </div>
                        </div>
                        <!-- end row -->                     
                        <br>
                        <!-- row -->
                        <div class="form-group">
                            <button type="submit" name="payment-add" class="btn btn-alt-primary btn-rounded" data-toggle="click-ripple"><i class="fa fa-plus mr-5"></i>Accept Payment</button>
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

<!-- Page JS Plugins -->
<?php $cb->get_js('js/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js'); ?>

<!-- Page JS Code -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment-with-locales.min.js"></script>
<script>
    jQuery(function(){
        // Init page helpers (BS Datepicker + BS Colorpicker + BS Maxlength + Select2 + Masked Input + Range Sliders + Tags Inputs plugins)
        Codebase.helpers(['datepicker']);
    });

    //not secure, display rate for two days
    // var future = moment('05/02/2015');
    // var start = moment('04/23/2015');
    // var d = future.diff(start, 'days'); // 9
    // console.log(d);
    // var future = document.getElementById('check-out');
    // var start = document.getElementById('check-in');
    // var d = future.diff(start, 'days'); // 9
    // console.log(d);

    // function test() {        
    //     // document.getElementById('amount').value='test';

    //     document.getElementById('amount').value= var d;
    // }
    //window.onload = test;

</script>

<?php require 'inc/_global/views/footer_end.php'; ?>