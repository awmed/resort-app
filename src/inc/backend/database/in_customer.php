<?php
//start session
session_start();
try {
    if(!isset($_POST['customer-id'])){ 
        header('location: /resort/src/in_customer.php');
        exit(); 
    }  
   
    if(isset($_POST['add-checkin'])) {
        include_once 'db.php';
        $id = trim(strip_tags(stripslashes(mysqli_real_escape_string($conn, $_POST['id']))));
        $customer = trim(strip_tags(stripslashes(mysqli_real_escape_string($conn, $_POST['customer-id']))));
        $type = trim(strip_tags(stripslashes(mysqli_real_escape_string($conn, $_POST['room-type']))));
        $room = trim(strip_tags(stripslashes(mysqli_real_escape_string($conn, $_POST['room-no']))));
        $out = trim(strip_tags(stripslashes(mysqli_real_escape_string($conn, $_POST['check-out']))));
        $payment = trim(strip_tags(stripslashes(mysqli_real_escape_string($conn, $_POST['payment']))));
        $status = trim(strip_tags(stripslashes(mysqli_real_escape_string($conn, $_POST['status']))));

        if(empty($id) || empty($customer) || empty($type) || empty($room) || empty($out) || empty($payment) || empty($status)) {
            mysqli_close($conn);
            $_SESSION['empty'] = true;
            header('location: /resort/src/in_customer.php');            
            exit();
        } else {
            $in = date_create(); //today
            $out = date_create($out);
            $diff = date_diff($in, $out);
            $diffrence = $diff->format('%a'); //no of days different %R%a

            $checkIn = $in->format("Y/m/d");
            $checkOut = $out->format("Y/m/d");
            
            if($diffrence == 0) {
                $diffrence = 1;
            }

             //invalid date range
            if($diff->format('%R%a') < 0) {
                mysqli_close($conn);
                $_SESSION['dateError'] = true;
                header('location: /resort/src/in_customer.php');
                exit();
            }

            //get room rate
            $sql = "SELECT rate FROM room WHERE room_no=?;";
            $stmt = mysqli_stmt_init($conn);
            if(!@mysqli_stmt_prepare($stmt, $sql)) {
                mysqli_close($conn);
                throw new Exception(mysqli_error($conn));
                exit();
            } else {
                mysqli_stmt_bind_param($stmt, "i", $room);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                $row = mysqli_fetch_array($result);
                //retreived room rate
                $rate = $row['rate'];

                //room rent  calculation
                $amount = $rate * $diffrence;

                //insert check in record
                $sql = "INSERT INTO check_in (in_id, customer_id, room_type, room_no, check_in, check_out, amount, payment_type, status) VALUES (?,?,?,?,?,?,?,?,?);";
                if(!@mysqli_stmt_prepare($stmt, $sql)) {
                    mysqli_close($conn);
                    throw new Exception(mysqli_error($conn));
                    exit();
                } else {
                    mysqli_stmt_bind_param($stmt, "iisississ", $id, $customer, $type, $room, $checkIn, $checkOut, $amount, $payment, $status);
                    mysqli_execute($stmt);

                    $no = trim(strip_tags(stripslashes(mysqli_real_escape_string($conn, "No"))));

                    //update room status yes to no
                    $sql = "UPDATE room SET availability=? WHERE room_no=?;";
                    if(!@mysqli_stmt_prepare($stmt, $sql)) {
                        mysqli_close($conn);
                        throw new Exception(mysqli_error($conn));
                        exit();
                    } else {
                        mysqli_stmt_bind_param($stmt, "si", $no, $room);
                        mysqli_stmt_execute($stmt);
                    }

                    //verify payment status
                    if($status == "Paid") {
                        $sql = "INSERT INTO payment (customer_id, room_no, amount, date_payed) VALUES (?,?,?,?);";
                        if(!@mysqli_stmt_prepare($stmt, $sql)) {
                            mysqli_close($conn);
                            throw new Exception(mysqli_error($conn));
                            exit();
                        } else {
                            mysqli_stmt_bind_param($stmt, "iiis", $customer, $room, $amount, $checkIn);
                            mysqli_stmt_execute($stmt);
                        }
                    }
                    mysqli_close($conn);
                    $_SESSION['success'] = true;
                    header('location: /resort/src/in_customer.php');
                    exit();
                }
                
            }

            // echo $checkIn.'<br>'.$checkOut.'<br>'.$diffrence.'<br>';
            // echo $amount.'<br>'.$rate.'<br>'.$status;
            // exit(); 
        }

        //update yes to no

        //add payment status or not
    }
    
    if(isset($_POST['customer-id'])) {
        //open db
        include_once 'db.php';        
        $customer = trim(strip_tags(stripslashes(mysqli_real_escape_string($conn, $_POST['customer-id']))));
        $type = trim(strip_tags(stripslashes(mysqli_real_escape_string($conn, $_POST['room-type']))));
        if($customer == "") {
            header('location: /resort/src/in_customer.php');
            exit();
        }
        //display customer data
        $sql = "SELECT * FROM customer WHERE customer_id=?;";
        $stmt = mysqli_stmt_init($conn);
        if(!@mysqli_stmt_prepare($stmt,$sql)) {
            mysqli_close($conn);
            throw new Exception(mysqli_error($conn));
            exit();
        } else {
            mysqli_stmt_bind_param($stmt, "i", $customer);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $row = mysqli_fetch_array($result);

            $fname = $row['f_name'];
            $lname = $row['l_name'];
            $nat = $row['nationality'];
            $add = $row['address'];
            $phone = $row['phone'];

            //mysqli_close($conn);
            //header('location: /resort/src/reservation_add.php?ID='.$customer.'&fname='.$fname.'&lname='.$lname.'&nat='.$nat.'&add='.$add.'&phone='.$phone);
            if($type == "") {
                mysqli_close($conn);
                header('location: /resort/src/in_customer.php?ID='.$customer.'&fname='.$fname.'&lname='.$lname.'&nat='.$nat.'&add='.$add.'&phone='.$phone);
                exit();
            } else {
                $yes = trim(strip_tags(stripslashes(mysqli_real_escape_string($conn, "Yes"))));
                //display room data
                $sql = "SELECT * FROM room WHERE room_type=? AND availability=?;";
                $stmt = mysqli_stmt_init($conn);
                if(!@mysqli_stmt_prepare($stmt,$sql)) {
                    mysqli_close($conn);
                    throw new Exception(mysqli_error($conn));
                    exit();
                } else {
                    mysqli_stmt_bind_param($stmt,"ss",$type, $yes);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);
                    
                    while($row = mysqli_fetch_array($result)) {
                        $rooms[] = $row['room_no'];
                    }
                    
                    //print_r($rooms);
                    //exit();
                    mysqli_close($conn);
                    header('location: /resort/src/in_customer.php?ID='.$customer.'&fname='.$fname.'&lname='.$lname.'&nat='.$nat.'&add='.$add.'&phone='.$phone.'&type='.$type.'&rooms='.urlencode(serialize($rooms)));
                    //http_build_query($rooms)
                }
            }
        }
    }    

}//end try
catch (Exception $e) {
    exit($e->getMessage());
}
