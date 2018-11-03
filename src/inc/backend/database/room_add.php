<?php
//session start
session_start();
//session id for specific user input
try {
    if(!isset($_POST['submit'])) {
        header('location: /resort/src/room_add.php');
        exit();
    } else {
        //open db connection
        include_once 'db.php';
        $no = trim(stripslashes(mysqli_real_escape_string($conn, $_POST['no'])));
        $type = trim(stripslashes(mysqli_real_escape_string($conn, $_POST['type'])));
        $available = trim(stripslashes(mysqli_real_escape_string($conn, $_POST['available'])));
        $rate = trim(stripslashes(mysqli_real_escape_string($conn, $_POST['rate'])));

        if(empty($no) || empty($type) || empty($available) || empty($rate)) {
            //close db
            mysqli_close($conn);
            $_SESSION['empty'] = true;
            header('location: /resort/src/room_add.php');
            exit();
        } else { 
            //check if room type already exist
            $sql = "SELECT room_no FROM ROOM WHERE room_no=?;";
            $stmt = mysqli_stmt_init($conn);
            if(!@mysqli_stmt_prepare($stmt,$sql)) {
                mysqli_close($conn);
                throw new Exception(mysqli_error($conn));
                exit();
            } else {
                mysqli_stmt_bind_param($stmt, "i", $no);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                $resultCheck = mysqli_num_rows($result);

                if($resultCheck>0) {
                    mysqli_close($conn);
                    $_SESSION['exist'] = true;
                    header('location: /resort/src/room_add.php');
                    exit();
                } else{
                    //add new room
                    $sql = "INSERT INTO room (room_no, room_type, availability, rate) VALUES (?, ?, ?, ?);";
                    if(!@mysqli_stmt_prepare($stmt,$sql)) {
                        mysqli_close($conn);
                        throw new Exception(mysqli_error($conn));
                        exit();
                    } else {
                        mysqli_stmt_bind_param($stmt, "isss", $no, $type, $available, $rate);
                        mysqli_stmt_execute($stmt);
                        mysqli_close($conn);
                        $_SESSION['success'] = true;
                        header('location: /resort/src/room_add.php');
                        exit();
                    }
                }
            }
 
        }        
    }           
}
catch (Exception $e) {
    $e->getMessage();
}