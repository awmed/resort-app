<?php
//start session
session_start();
try {
    if(!isset($_POST['submit'])) {
        header('location: /resort/src/room_modify.php');
    }

    //update staff type 
    if(isset($_POST['submit'])) {                
        //include database
        include_once 'db.php';
        $no = trim(stripslashes(mysqli_real_escape_string($conn, $_POST['no'])));
        $type = trim(stripslashes(mysqli_real_escape_string($conn, $_POST['type'])));
        $available = trim(stripslashes(mysqli_real_escape_string($conn, $_POST['available'])));
        $rate = trim(stripslashes(mysqli_real_escape_string($conn, $_POST['rate'])));

        //validate fields
        if(empty($no) || empty($type) || empty($available) || empty($rate)) {
            //close db
            mysqli_close($conn);
            $_SESSION['empty'] = true;
            header('location: /resort/src/room_edit.php?no='.$no.'&type='.$type.'&avail='.$available.'&rate='.$rate);
            exit();
        } else {

            $sql = "UPDATE room SET room_type=?, availability=?, rate=? WHERE room_no=?;";

            //create prepared statement
            $stmt = mysqli_stmt_init($conn);
            //prepare prepared statement
            if(!@mysqli_stmt_prepare($stmt, $sql)) {
                throw new Exception (mysqli_error($conn));            
                //close db connection
                mysqli_close($conn); 
                exit();
            } else {
                //bind parameters to placeholders
                mysqli_stmt_bind_param($stmt, 'ssii', $type, $available, $rate, $no); //s-string, i-integer, d-double, b-blob
                //run parameters inside database
                mysqli_stmt_execute($stmt);

                //close db connection
                mysqli_close($conn); 
                $_SESSION['updated'] = true;
                header('location: /resort/src/room_modify.php');
            }            
        }              
    } 

    //delete record from database
    if(isset($_GET['no'])) {
        $no = $_GET['no'];
        //include database
        include_once 'db.php';
        $sql= "DELETE FROM room WHERE room_no=?; ";

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
            mysqli_stmt_bind_param($stmt, "i", $no);
            //execute query
            mysqli_stmt_execute($stmt);
            //close db connection
            mysqli_close($conn);
            //redirect success
            $_SESSION['deleted'] = true;
            header('location: /resort/src/room_modify.php');
        }
    }
}
catch (Exception $e) {
    $e->getMessage();
}