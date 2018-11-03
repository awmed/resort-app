<?php
//start session
session_start();
try {
    if(!isset($_POST['checkin-id'])){ 
        header('location: /resort/src/out_customer.php');
        exit(); 
    }  
   
    if(isset($_POST['customer-out'])) {        
        include_once 'db.php';
        $id = trim(strip_tags(stripslashes(mysqli_real_escape_string($conn, $_POST['id'])))); //check out id
        $cid = trim(strip_tags(stripslashes(mysqli_real_escape_string($conn, $_POST['checkin-id']))));
        $customer = trim(strip_tags(stripslashes(mysqli_real_escape_string($conn, $_POST['customer-id']))));
        $type = trim(strip_tags(stripslashes(mysqli_real_escape_string($conn, $_POST['type']))));
        $room = trim(strip_tags(stripslashes(mysqli_real_escape_string($conn, $_POST['room-no']))));
        $in = trim(strip_tags(stripslashes(mysqli_real_escape_string($conn, $_POST['check-in']))));
        $out = trim(strip_tags(stripslashes(mysqli_real_escape_string($conn, $_POST['check-out']))));
        $amount = trim(strip_tags(stripslashes(mysqli_real_escape_string($conn, $_POST['amount']))));
        $payment = trim(strip_tags(stripslashes(mysqli_real_escape_string($conn, $_POST['payment']))));
        $status = trim(strip_tags(stripslashes(mysqli_real_escape_string($conn, $_POST['status']))));


        if($status == "Unpaid") {
            mysqli_close($conn);
            $_SESSION['unpaid'] = true;
            header('location: /resort/src/out_customer.php');
            exit();
        } else {
            //format date
            $in = date_create($in);
            $out = date_create($out);
            $checkIn = $in->format("Y/m/d");
            $checkOut = $out->format("Y/m/d");

            //remove customer from checked in
            $stmt = mysqli_stmt_init($conn);
            $sql = "DELETE FROM check_in WHERE in_id=?;";
            if(!@mysqli_stmt_prepare($stmt, $sql)) {
                mysqli_close($conn);
                throw new Exception(mysqli_error($conn));
                exit();
            } else {
                mysqli_stmt_bind_param($stmt, "i", $cid);
                if(mysqli_stmt_execute($stmt)) {
                    //add customer to checked out
                    $sql = "INSERT INTO check_out (out_id, customer_id, room_type, room_no, check_in, check_out, amount, payment_type) VALUES (?,?,?,?,?,?,?,?);";
                    if(!@mysqli_stmt_prepare($stmt, $sql)) {
                        mysqli_close($conn);
                        throw new Exception(mysqli_error($conn));
                        exit();
                    } else {
                        mysqli_stmt_bind_param($stmt, "iisissis", $id, $customer, $type, $room, $checkIn, $checkOut, $amount, $payment);
                        mysqli_stmt_execute($stmt);

                        //set room status to yes
                        $yes = trim(strip_tags(stripslashes(mysqli_real_escape_string($conn, "Yes"))));                        
                        $sql = "UPDATE room SET availability=? WHERE room_no=?;";
                        if(!@mysqli_stmt_prepare($stmt, $sql)) {
                            mysqli_close($conn);
                            throw new Exception(mysqli_error($conn));
                            exit();
                        } else {
                            mysqli_stmt_bind_param($stmt, "si", $yes, $room);
                            mysqli_stmt_execute($stmt);
                            mysqli_close($conn);
                            $_SESSION['success'] = true;
                            header('location: /resort/src/out_customer.php');
                        }
                    }
                }
            }
        }        
    }
    
    if(isset($_POST['checkin-id'])) {
        //open db
        include_once 'db.php';        
        $id = trim(strip_tags(stripslashes(mysqli_real_escape_string($conn, $_POST['checkin-id']))));
        if($id == "") {
            header('location: /resort/src/out_customer.php');
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
            header('location: /resort/src/out_customer.php?ID='.$id.'&cus='.$customer.'&type='.$type.'&room='.$room.'&in='.$checkIn.'&out='.$checkOut.'&amount='.$amount.'&payment='.$payment.'&status='.$status);
            exit();            
        }
    }    

}//end try
catch (Exception $e) {
    exit($e->getMessage());
}
