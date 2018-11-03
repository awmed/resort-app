<?php
//start session
session_start();
try {
    if(!isset($_POST['checkin-id'])){ 
        header('location: /resort/src/payment_add.php');
        exit(); 
    }  
   
    if(isset($_POST['payment-add'])) {        
        include_once 'db.php';
        $id = trim(strip_tags(stripslashes(mysqli_real_escape_string($conn, $_POST['checkin-id']))));
        $customer = trim(strip_tags(stripslashes(mysqli_real_escape_string($conn, $_POST['customer-id']))));
        $type = trim(strip_tags(stripslashes(mysqli_real_escape_string($conn, $_POST['type']))));
        $room = trim(strip_tags(stripslashes(mysqli_real_escape_string($conn, $_POST['room-no']))));
        $amount = trim(strip_tags(stripslashes(mysqli_real_escape_string($conn, $_POST['amount']))));

        $datePayed = date_create(); //today
        $datePayed = $datePayed->format("Y/m/d");
        
        //insert payment record
        $stmt = mysqli_stmt_init($conn);
        $sql = "INSERT INTO payment (customer_id, room_no, amount, date_payed) VALUES (?,?,?,?);";
        if(!@mysqli_stmt_prepare($stmt, $sql)) {
            mysqli_close($stmt);
            throw new Exception(mysqli_error($conn));
            exit();
        } else {
            mysqli_stmt_bind_param($stmt, "iiis", $customer, $room, $amount, $datePayed);
            mysqli_stmt_execute($stmt);

            //update check in record
            $status = "Paid";
            $sql = "UPDATE check_in SET status=? WHERE in_id=?;";
            if(!@mysqli_stmt_prepare($stmt,$sql)){
                mysqli_close($conn);
                throw new Exception(mysqli_error($conn));
                exit();
            } else {
                mysqli_stmt_bind_param($stmt, "si", $status, $id);
                mysqli_execute($stmt);
                mysqli_close($conn);
                $_SESSION['success'] = true;
                header('location: /resort/src/payment_add.php');
                exit();
            }
        }        
    }
    
    if(isset($_POST['checkin-id'])) {
        //open db
        include_once 'db.php';        
        $id = trim(strip_tags(stripslashes(mysqli_real_escape_string($conn, $_POST['checkin-id']))));
        if($id == "") {
            header('location: /resort/src/payment_add.php');
            exit();
        }
        //display customer data
        $sql = "SELECT * FROM check_in WHERE in_id=?;";
        $stmt = mysqli_stmt_init($conn);
        if(!@mysqli_stmt_prepare($stmt,$sql)) {
            mysqli_close($conn);
            throw new Exception(mysqli_error($conn));
            exit();
        } else {
            mysqli_stmt_bind_param($stmt, "i", $id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $row = mysqli_fetch_array($result);

            $customer = $row['customer_id'];
            $type = $row['room_type'];
            $room = $row['room_no'];
            $in = $row['check_in'];
            $out = $row['check_out'];
            $amount = $row['amount'];
            $payment = $row['payment_type'];
            $status = $row['status'];

            //foramt date
            $in = date_create($in);  
            $checkIn = $in->format("m/d/Y");
            $out = date_create($out);  
            $checkOut = $out->format("m/d/Y");  
            

            mysqli_close($conn);
            header('location: /resort/src/payment_add.php?ID='.$id.'&cus='.$customer.'&type='.$type.'&room='.$room.'&in='.$checkIn.'&out='.$checkOut.'&amount='.$amount.'&payment='.$payment.'&status='.$status);
            exit();            
        }
    }    

}//end try
catch (Exception $e) {
    exit($e->getMessage());
}
