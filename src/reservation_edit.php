<?php
//start session
session_start();

if(!isset($_SESSION['user'])) {
    header('location: sign_in.php');
    exit();    
}

if(!isset($_GET['ID'])) {
    header('location: reservation_modify.php');
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
	echo '<title> EDIT RESERVATION </title>'
?>

<!-- Page JS Plugins CSS -->
<?php $cb->get_css('js/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css'); ?>

<?php require 'inc/_global/views/head_end.php'; ?>
<?php require 'inc/_global/views/page_start.php'; ?>

<!-- Page Content -->
<div class="content">
    <nav class="breadcrumb bg-white push">
        <a class="breadcrumb-item" href="dashboard.php">Dashboard</a>
        <span class="breadcrumb-item active">EDIT RESERVATION</span>
    </nav>
    <div class="block">
        <div class="block-header block-header-default">
            <h2 class="block-title" style="font-size:25px"><strong>EDIT RESERVATION</strong></h2>
        </div>
        <div class="block-content">
        <div class="row justify-content-center py-20">
                <div class="col-xl-5">
                    <form action="inc\backend\database\reservation_edit.php" method="post"id="res-form">
                        <!-- row -->
                        <div class="form-group">
                            <label for="example-text-input">Reservation ID </label>
                            <?php 
                                $id = $_GET['ID'];                                                                                               
                                echo '<input type="text" class="form-control" id="" name="id" placeholder="" readonly="true" value="'.$id.'">';                                                                                      
                            ?>                           
                        </div>
                        <!-- end row -->
                        <!-- row -->
                        <div class="form-group">
                            <label for="example-text-input"> Customer ID <span class="text-danger">*</span></label>                            
                            <?php 
                                $cus = $_GET['cus'];    
                                $oldt = $_GET['typeold'];  
                                $oldr=  $_GET['roomold'];                                                                                         
                                echo '<input type="text" class="form-control" id="" name="customer-id" placeholder="" readonly="true" value="'.$cus.'">';   
                                echo '<input type="hidden" name="type-old" value="'.$oldt.'">';   
                                echo '<input type="hidden" name="room-old" value="'.$oldr.'">';                                                                                   
                            ?>                            
                        </div>
                        <!-- end row -->                        
                        <!-- row -->
                        <div class="form-group">
                            <label for="example-text-input">Current Room Type <span class="text-danger">[<?php echo $_GET['typeold']; ?>]</span></label>
                            <select class="form-control" id="" name="room-type" required="true" onchange="this.form.submit()">
                            <!-- <option value="">Please select</option> -->
                            <?php
                            try{
                                $type = $_GET['rtype'];
                                include_once 'inc/backend/database/db.php';
                                $stmt = mysqli_stmt_init($conn);
                                $sql = "SELECT DISTINCT room_type FROM room_type;";                                 
                                //prepare prepared statement
                                if(!@mysqli_stmt_prepare($stmt, $sql)) {
                                    throw new Exception(mysqli_error($conn));
                                } else {
                                    //run statement
                                    mysqli_stmt_execute($stmt);

                                    //get result
                                    $result = mysqli_stmt_get_result($stmt);                                        

                                    while($row = mysqli_fetch_array($result)) {
                                       echo '<option'.(($type==$row['room_type'])? " selected='selected' " : "").'>'.$row['room_type'].' </option>';
                                       //echo '<option value="'.$row['room_type'].'">'.$row['room_type'].'</option>';                                                                                  
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
                            <label for="example-text-input">Current Room No <span class="text-danger">[<?php echo $_GET['roomold']; ?>]</span></label>
                            <select class="form-control" id="" name="room-no" required="true" readonly="true" >                            
                            <?php 
                            if(isset($_GET['rooms'])) {
                                $rooms=unserialize($_GET['rooms']);
                                for($i=0; $i<count($rooms); $i++) {
                                    echo '<option value="'.$rooms[$i].'">Room '.$rooms[$i].'</option>';
                                }
                            }
                            ?>                                                     
                            </select>                         
                        </div>                       
                        <!-- end row -->
                        <!-- Datepicker JS -->
                        <div class="form-group row">
                            <label class="col-12" for="example-daterange1">Select Date</label>
                            <div class="col-lg-12">
                                <div class="input-daterange input-group" data-date-format="mm/dd/yyyy" data-week-start="1" data-autoclose="true" data-today-highlight="true">
                                    <?php
                                    $in = date_create($_GET['in']);  
                                    $checkIn = $in->format("m/d/Y");  
                                    echo '<input type="text" class="form-control" id="check-in" name="check-in" placeholder="Check In Date" data-week-start="1" data-autoclose="true" data-today-highlight="true" required="true" value="'.$checkIn.'">';
                                    ?>
                                    <div class="input-group-prepend input-group-append">
                                        <span class="input-group-text font-w600">to</span>
                                    </div>
                                    <?php
                                    $out = date_create($_GET['out']);
                                    $checkOut = $out->format("m/d/Y");
                                    echo '<input type="text" class="form-control" id="check-out" name="check-out" placeholder="Check Out Date" data-week-start="1" data-autoclose="true" data-today-highlight="true" required="true" value="'.$checkOut.'">';
                                    ?>
                                </div>
                            </div>
                        </div>
                        <!-- END Datepicker JS -->
                        <div class="form-group row">
                            <label class="col-12" for="example-daterange1">Reserved Date</label>
                            <div class="col-lg-12">
                                <div class="input-daterange input-group" data-date-format="mm/dd/yyyy" data-week-start="1" data-autoclose="true" data-today-highlight="true">
                                    <?php
                                    $res = date_create($_GET['res']);  
                                    $resDate = $res->format("m/d/Y");  
                                    echo '<input type="text" class="form-control" id="check-in" name="res-date" placeholder="Check In Date" data-week-start="1" data-autoclose="true" data-today-highlight="true" required="true" value="'.$resDate.'" readonly="true">';
                                    ?>
                                    
                                </div>
                            </div>
                        </div>
                        <!-- end row -->
                        <!-- row -->
                        <div class="form-group">
                            <label for="example-text-input">Total Amount (Rs.) </label>
                            <?php
                            $amount  = $_GET['amt'];
                            echo '<input type="text" class="form-control" id="amount" name="amount" placeholder="" required="true" readonly="true" value="'.$amount.'">'; 
                            ?>                          
                        </div>
                        <!-- end row -->
                         <!-- row -->
                         <div class="form-group">
                            <label for="example-text-input">Payment Type <span class="text-danger">*</span></label>
                                <select class="form-control" id="" name="payment" required="true">
                                <!-- <option value="">Please select</option> -->
                                <?php
                                $payment = $_GET['ptype'];
                                echo '
                                <option value="Credit Card">Credit Card</option>  
                                <option value="Cash">Cash</option>                              
                                <option value="Online">Online</option>                              
                                <option value="Bank Transfer">Bank Transfer</option>                              
                                <option value="Other">Other</option> 
                                ';
                                ?>                             
                            </select>                            
                        </div>
                        <!-- end row -->     
                        <!-- row -->
                        <div class="form-group">
                            <label for="example-text-input">Payment Status</label>
                            <div>
                            <?php
                                $status = $_GET['status'];
                                if($status=='Unpaid') {
                                    echo '
                                    <div class="custom-control custom-radio custom-control-inline mb-5">
                                        <input class="custom-control-input" type="radio" name="status" id="example-inline-radio1" value="Unpaid" checked>
                                        <label class="custom-control-label" for="example-inline-radio1">Unpaid</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline mb-5">
                                        <input class="custom-control-input" type="radio" name="status" id="example-inline-radio2" value="Paid">
                                        <label class="custom-control-label" for="example-inline-radio2">Paid</label>
                                    </div>
                                    ';
                                }
                                if($status=='Paid') {
                                    echo '
                                    <div class="custom-control custom-radio custom-control-inline mb-5">
                                        <input class="custom-control-input" type="radio" name="status" id="example-inline-radio1" value="Unpaid" >
                                        <label class="custom-control-label" for="example-inline-radio1">Unpaid</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline mb-5">
                                        <input class="custom-control-input" type="radio" name="status" id="example-inline-radio2" value="Paid" checked>
                                        <label class="custom-control-label" for="example-inline-radio2">Paid</label>
                                    </div>
                                    ';
                                }
                            ?>                                             
                            </div>
                        </div>
                        <!-- end row -->                 
                        <br>
                        <!-- row -->
                        <div class="form-group">
                            <button type="submit" name="update-reservation" class="btn btn-alt-warning btn-rounded" data-toggle="click-ripple"><i class="fa fa-edit mr-5"></i>Update Reservation</button>
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

    //window.onload  = document.getElementById('res-form').submit()
</script>

<?php require 'inc/_global/views/footer_end.php'; ?>