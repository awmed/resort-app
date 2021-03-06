<?php
//start session
session_start();
try {
    if(!isset($_POST['submit'])) {
        header('location: /resort/src/facility_modify.php');
    }

    //update staff type 
    if(isset($_POST['submit'])) {                
        //include database
        include_once 'db.php';
        $id = mysqli_real_escape_string($conn, trim($_POST['id']));
        $facility = mysqli_real_escape_string($conn, trim($_POST['facility']));

        if(empty($id) || empty($facility)) {
            //close db
            mysqli_close($conn);
            $_SESSION['empty'] = true;
            header('location: /resort/src/facility_edit.php?ID='.$id.'&facility='.$facility);
            exit();
        } else {
            $sql = "UPDATE facilities SET facility=? WHERE f_id=?;";

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
                mysqli_stmt_bind_param($stmt, 'si', $facility, $id); //s-string, i-integer, d-double, b-blob
                //run parameters inside database
                mysqli_stmt_execute($stmt);

                
                //duplicate entry conflict
                $error = mysqli_errno($conn);
                if($error==1062) {
                    //close db connection
                    mysqli_close($conn); 
                    $_SESSION['exist'] = true;
                    header('location: /resort/src/facility_edit.php?ID='.$id.'&facility='.$facility);
                    exit();
                } else {
                    //close db connection
                    mysqli_close($conn); 
                    $_SESSION['updated'] = true;
                    header('location: /resort/src/facility_modify.php');
                }
            } 
        }                                         
    }

    //delete record from database
    if(isset($_GET['ID'])) {
        $id = $_GET['ID'];
        //include database
        include_once 'db.php';
        $sql= "DELETE FROM facilities WHERE f_id=?; ";

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
            header('location: /resort/src/facility_modify.php');
        }
    }
}
catch (Exception $e) {
    $e->getMessage();
}