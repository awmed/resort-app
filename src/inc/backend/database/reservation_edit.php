<?php
//start session
session_start();
try {
    // if(!isset($_POST['submit'])) {
    //     header('location: /resort/src/reservation_modify.php');
    //     exit(); 
    // }

    //update reservation
    if(isset($_POST['update-reservation'])) {
        //open db connection
        include_once 'db.php';
        $id = trim(strip_tags(stripslashes(mysqli_real_escape_string($conn, $_POST['id']))));
        $customer = trim(strip_tags(stripslashes(mysqli_real_escape_string($conn, $_POST['customer-id']))));
        $type = trim(strip_tags(stripslashes(mysqli_real_escape_string($conn, $_POST['room-type']))));
        $no = @trim(strip_tags(stripslashes(mysqli_real_escape_string($conn, $_POST['room-no']))));
        $in = trim(strip_tags(stripslashes(mysqli_real_escape_string($conn, $_POST['check-in']))));
        $out = trim(strip_tags(stripslashes(mysqli_real_escape_string($conn, $_POST['check-out']))));
        $res = trim(strip_tags(stripslashes(mysqli_real_escape_string($conn, $_POST['res-date']))));
        $amount = trim(strip_tags(stripslashes(mysqli_real_escape_string($conn, $_POST['amount']))));
        $payment = trim(strip_tags(stripslashes(mysqli_real_escape_string($conn, $_POST['payment']))));
        $status = trim(strip_tags(stripslashes(mysqli_real_escape_string($conn, $_POST['status']))));

        //get old room type and room no if new is empty
        if(empty($type) || empty($no)) {
            $type = trim(strip_tags(stripslashes(mysqli_real_escape_string($conn, $_POST['type-old']))));
            $no = trim(strip_tags(stripslashes(mysqli_real_escape_string($conn, $_POST['room-old']))));    
        }

        //validate feilds
        if(empty($id) || empty($customer) || empty($type) || empty($no) || empty($payment) || empty($status)) {
            mysqli_close($conn);
            $_SESSION['empty'] = true;
            header('location: /resort/src/reservation_edit.php?ID='.$id.'&cus='.$customer.'&rtype='.$type.'&no='.$no.'&in='.$in.'&out='.$out.'&res='.$res.'&amt='.$amount.'&ptype='.$payment.'&status='.$status.'&typeold='.$type.'&roomold='.$no);
            exit();
        } else {
            //create date format to insert into mysql
            $in = date_create($_POST['check-in']);
            $out = date_create($_POST['check-out']);
            $diff = date_diff($in, $out);
            $diffrence = $diff->format('%a'); //no of days different %R%a
            $res = date_create($_POST['res-date']); 

            //invalid date range
            if($diff->format('%R%a') < 0) {
                mysqli_close($conn);
                $_SESSION['dateError'] = true;
                header('location: /resort/src/reservation_modify.php');
                exit();
            }

            $checkIn = $in->format("Y/m/d");
            $checkOut = $out->format("Y/m/d");
            $resDate = $res->format("Y/m/d"); 

            //get room rate
            $sql = "SELECT rate FROM room WHERE room_no=?;";
            $stmt = mysqli_stmt_init($conn);
            if(!@mysqli_stmt_prepare($stmt, $sql)) {
                mysqli_close($conn);
                throw new Exception(mysqli_error($conn));
                exit();
            } else {
                mysqli_stmt_bind_param($stmt, "i", $no);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                $row = mysqli_fetch_array($result);
                $rate = $row['rate'];

                //room rent calculation (over ride)
                $amount = $rate * $diffrence;

                //update statement
                $sql = "UPDATE reservation SET room_type=?, room_no=?, check_in=?, check_out=?, date_reserved=?, amount=?, payment_type=?, status=? WHERE res_id=?;";
                if(!@mysqli_stmt_prepare($stmt,$sql)) {
                    mysqli_close($conn);
                    throw new Exception(mysqli_error($conn));
                    exit();
                } else {
                    mysqli_stmt_bind_param($stmt,"sisssissi", $type, $no, $checkIn, $checkOut, $resDate, $amount, $payment, $status, $id);
                    mysqli_stmt_execute($stmt);
                    mysqli_close($conn);
                    $_SESSION['updated'] = true;
                    header('location: /resort/src/reservation_modify.php');
                    exit();
                }
            }

            // echo $checkIn.'<br>'.$checkOut.'<br>'. $resDate.'<br>';
            // echo $type.'<br>'.$no.'<br>';
            // echo $diffrence.'<br>'.$amount;
            // exit();
        }
    }
    
    //delete reservation record
    if(isset($_GET['ID'])) {
        $id = $_GET['ID'];
        //include database
        include_once 'db.php';
        $sql= "DELETE FROM reservation WHERE res_id=?; ";

        //create prepared statement
        $stmt = mysqli_stmt_init($conn);
        //prepare prepared statement
        if(!@mysqli_stmt_prepare($stmt, $sql)) {
            throw new Exception (mysqli_error($conn));
            //close db connection
            mysqli_close($conn);
            exit();
        } else {
            //bind parameters to place holders
            mysqli_stmt_bind_param($stmt, "i", $id);
            //execute query
            mysqli_stmt_execute($stmt);
            //close db connection
            mysqli_close($conn);
            //redirect success
            $_SESSION['deleted'] = true;
            header('location: /resort/src/reservation_modify.php');
        }
    }

    //display available rooms based on selection, src/reservation_edit.php
    if(isset($_POST['room-type'])){
        include 'db.php';
        $id = $_POST['id'];
        $customer = $_POST['customer-id'];
        //$type =$_POST['room-type'];
        $no =$_POST['room-no'];
        $in = $_POST['check-in'];
        $out = $_POST['check-out'];
        $res=$_POST['res-date'];
        $amount = $_POST['amount'];
        $payment = $_POST['payment'];
        $status = $_POST['status'];
        $oldtype = $_POST['type-old'];
        $oldroom =$_POST['room-old'];
        
        // header('location: /resort/src/reservation_edit.php?ID='.$id.'&cus='.$customer.'&rtype='.$type.'&no='.$no.'&in='.$in.'&out='.$out.'&res='.$res.'&amt='.$amount.'&ptype='.$payment.'&status='.$status);

        $type = trim(strip_tags(stripslashes(mysqli_real_escape_string($conn, $_POST['room-type']))));
        $yes = trim(strip_tags(stripslashes(mysqli_real_escape_string($conn, "Yes"))));
       
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
            
            mysqli_close($conn);
            header('location: /resort/src/reservation_edit.php?ID='.$id.'&cus='.$customer.'&rtype='.$type.'&no='.$no.'&in='.$in.'&out='.$out.'&res='.$res.'&amt='.$amount.'&ptype='.$payment.'&status='.$status.'&typeold='.$oldtype.'&roomold='.$oldroom.'&rooms='.urlencode(serialize($rooms)));

        }
    }

}
catch (Exception $e) {
    $e->getMessage();
}