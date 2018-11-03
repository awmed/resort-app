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
                text: "Customer Checked In Successfully !",
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
	echo '<title> CHECK IN CUSTOMER</title>'
?>

<!-- Page JS Plugins CSS -->
<?php $cb->get_css('js/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css'); ?>

<?php require 'inc/_global/views/head_end.php'; ?>
<?php require 'inc/_global/views/page_start.php'; ?>

<!-- Page Content -->
<div class="content">
    <nav class="breadcrumb bg-white push">
        <a class="breadcrumb-item" href="dashboard.php">Dashboard</a>
        <span class="breadcrumb-item active">CHECK IN CUSTOMER</span>
    </nav>
    <div class="block">
        <div class="block-header block-header-default">
            <h2 class="block-title" style="font-size:25px"><strong>CHECK IN CUSTOMER</strong></h2>
        </div>
        <div class="block-content">
        <div class="row justify-content-center py-20">
                <div class="col-xl-5">
                    <form action="inc\backend\database\in_customer.php" method="post">
                        <!-- row -->
                        <div class="form-group">
                            <label for="example-text-input">Check In ID </label>
                            <?php
                            try {
                                //display table
                                include_once 'inc/backend/database/db.php';
                                $sql = "SELECT in_id FROM check_in ORDER BY in_id ASC;";

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
                                        $i = $row['in_id'];
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
                            <label for="example-text-input">Select Customer ID <span class="text-danger">*</span></label>
                            <select class="form-control" id="" name="customer-id" required="true" onchange="this.form.submit()">
                                <option value="">Please select</option>
                                <?php
                                try{
                                    $sql = "SELECT DISTINCT customer_id FROM customer;"; 

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
                                                echo '<option'.(($id==$row['customer_id'])? " selected='selected' " : "").'>'.$row['customer_id'].' </option>';                                               
                                            } else {
                                                echo '<option value="'.$row['customer_id'].'">'.$row['customer_id'].'</option>';
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
                            <label for="example-text-input">Full Name </label>
                            <?php                            
                            if(isset($_GET['fname']) && isset($_GET['lname'])) {
                                $fname = $_GET['fname'];
                                $lname = $_GET['lname'];
                                echo '<input type="text" class="form-control" id="" name="" placeholder="" required="true" readonly="true" value="'.$fname.' '.$lname.'">';
                            } else {
                                echo '<input type="text" class="form-control" id="" name="" placeholder="" required="true" readonly="true">';
                            }
                            ?>                            
                        </div>
                        <!-- end row -->
                        <!-- row -->
                        <div class="form-group">
                            <label for="example-text-input">Nationality </label>
                            <?php
                            if(isset($_GET['nat'])) {
                                $nat =  $_GET['nat'];
                                echo '<input type="text" class="form-control" id="" name="" placeholder="" required="true" readonly="true" value="'.$nat.'">';  
                            } else {
                                echo '<input type="text" class="form-control" id="" name="" placeholder="" required="true" readonly="true">';  
                            }   
                            ?>                         
                        </div>
                        <!-- end row -->
                        <!-- row -->
                        <div class="form-group">
                            <label for="example-text-input">Address </label>
                            <?php
                            if(isset($_GET['add'])){
                                $add = $_GET['add'];
                                echo '<input type="text" class="form-control" id="" name="" placeholder="" required="true" readonly="true" value="'.$add.'">';   
                            } else {
                                echo '<input type="text" class="form-control" id="" name="" placeholder="" required="true" readonly="true">';   
                            } 
                            ?>                       
                        </div>
                        <!-- end row -->
                        <!-- row -->
                        <div class="form-group">
                            <label for="example-text-input">Phone </label>
                            <?php
                            if(isset($_GET['phone'])) {
                                $phn = $_GET['phone'];
                                echo '<input type="tel" class="form-control" id="" name="" placeholder="" required="true" readonly="true" value="'.$phn.'">';    
                            } else {
                                echo '<input type="tel" class="form-control" id="" name="" placeholder="" required="true" readonly="true">';    
                            }
                            ?>                        
                        </div>
                        <!-- end row -->
                        <!-- row -->
                        <div class="form-group">
                            <label for="example-text-input">Select Room Type <span class="text-danger">*</span></label>
                            <select class="form-control" id="" name="room-type" required="true" onchange="this.form.submit()">
                            <option value="">Please select</option>
                            <?php
                            try{
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
                                        if(isset($_GET['type'])) {
                                            $type = $_GET['type'];
                                            echo '<option'.(($type==$row['room_type'])? " selected='selected' " : "").'>'.$row['room_type'].' </option>';                                               
                                        } else {
                                            echo '<option value="'.$row['room_type'].'">'.$row['room_type'].'</option>';
                                        }
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
                            <label for="example-text-input">Available Rooms </label>
                            <select class="form-control" id="" name="room-no" required="true" readonly="true">
                            <?php
                            $rooms=unserialize($_GET['rooms']);
                            if(empty($rooms)) {
                                echo '<option value="" >No available rooms, please select a different room type</option>';
                            } else {
                                for($i=0; $i<count($rooms); $i++) {
                                    echo '<option value="'.$rooms[$i].'">Room '.$rooms[$i].'</option>';
                                }
                                // strcmp($rooms,"N%3B") == 1
                            }
                            
                            ?>                                                     
                            </select>                         
                        </div>                       
                        <!-- end row -->
                        <!-- Datepicker JS -->
                        <!-- <div class="form-group row">
                            <label class="col-12" for="example-daterange1">Select Date</label>
                            <div class="col-lg-12">
                                <div class="input-daterange input-group" data-date-format="mm/dd/yyyy" data-week-start="1" data-autoclose="true" data-today-highlight="true">
                                    <input type="text" class="form-control" id="check-in" name="check-in" placeholder="Check In Date" data-week-start="1" data-autoclose="true" data-today-highlight="true" required="true" >
                                    <div class="input-group-prepend input-group-append">
                                        <span class="input-group-text font-w600">to</span>
                                    </div>
                                    <input type="text" class="form-control" id="check-out" name="check-out" placeholder="Check Out Date" data-week-start="1" data-autoclose="true" data-today-highlight="true" required="true">
                                </div>
                            </div>
                        </div> -->
                        <!-- END Datepicker JS -->
                        <!-- END Datepicker JS -->
                        <div class="form-group row">
                            <label class="col-12" for="example-daterange1">Check Out Date  <span class="text-danger">*</span></label>
                            <div class="col-lg-12">
                                <div class="input-daterange input-group" data-date-format="mm/dd/yyyy" data-week-start="1" data-autoclose="true" data-today-highlight="true">
                                   <input type="text" class="form-control" id="check-out" name="check-out" placeholder="Check Out Date" data-week-start="1" data-autoclose="true" data-today-highlight="true" required="true" >                                    
                                </div>
                            </div>
                        </div>
                        <!-- end row -->
                        <!-- row -->
                        <!-- <div class="form-group">
                            <label for="example-text-input">Total Amount (Rs.) </label>
                            <input type="text" class="form-control" id="amount" name="amount" placeholder="" required="true" readonly="true" >                           
                        </div> -->
                        <!-- end row -->
                        <!-- row -->
                        <div class="form-group">
                            <label for="example-text-input">Payment Type <span class="text-danger">*</span></label>
                                <select class="form-control" id="" name="payment" required="true">
                                <option value="">Please select</option>
                                <option value="Credit Card">Credit Card</option>  
                                <option value="Cash">Cash</option>                              
                                <option value="Online">Online</option>                              
                                <option value="Bank Transfer">Bank Transfer</option>                              
                                <option value="Other">Other</option>                              
                            </select>                            
                        </div>
                        <!-- end row -->     
                        <!-- row -->
                        <div class="form-group">
                            <label for="example-text-input">Payment Status</label>
                            <div>                           
                                <div class="custom-control custom-radio custom-control-inline mb-5">
                                    <input class="custom-control-input" type="radio" name="status" id="example-inline-radio1" value="Unpaid" checked>
                                    <label class="custom-control-label" for="example-inline-radio1">Unpaid</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline mb-5">
                                    <input class="custom-control-input" type="radio" name="status" id="example-inline-radio2" value="Paid">
                                    <label class="custom-control-label" for="example-inline-radio2">Paid</label>
                                </div>                                                                        
                            </div>
                        </div>
                        <!-- end row -->                     
                        <br>
                        <!-- row -->
                        <div class="form-group">
                            <button type="submit" name="add-checkin" class="btn btn-alt-primary btn-rounded" data-toggle="click-ripple"><i class="fa fa-calendar-check-o mr-5"></i>Check In</button>
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