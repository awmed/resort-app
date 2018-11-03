<?php
//start session
session_start();
try {
    if(!isset($_POST['checkin-id'])){ 
        header('location: /resort/src/invoice_view.php');
        exit(); 
    }  
       
    if(isset($_POST['checkin-id'])) {
        //open db
        include_once 'db.php';        
        $id = trim(strip_tags(stripslashes(mysqli_real_escape_string($conn, $_POST['checkin-id']))));
        if($id == "") {
            header('location: /resort/src/invoice_view.php');
            exit();
        }
        //display customer data
        $sql = "SELECT * FROM payment WHERE payment_id=?;";
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
            $room = $row['room_no'];
            $amount = $row['amount'];
            $date = $row['date_payed'];

            //foramt date
            $date = date_create($date);  
            $date = $date->format("m/d/Y");
            
            //customer name
            $sql = "SELECT f_name, l_name FROM customer WHERE customer_id=?;";
            @mysqli_stmt_prepare($stmt, $sql);
            mysqli_stmt_bind_param($stmt, "i", $customer);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $row = mysqli_fetch_array($result);

            $fullname = $row['f_name'].' '.$row['l_name'];

            mysqli_close($conn);
            header('location: /resort/src/invoice_view.php?ID='.$id.'&cus='.$customer.'&room='.$room.'&amount='.$amount.'&date='.$date.'&name='.$fullname);
            exit();            
        }
    }    

}//end try
catch (Exception $e) {
    exit($e->getMessage());
}
