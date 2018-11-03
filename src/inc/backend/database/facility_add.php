<?php
//start session
session_start();
try {
    if(!isset($_POST['submit'])){ 
        header('location: /resort/src/facility_add.php');
        exit(); 
    } else {   
        //open db connection
        include_once 'db.php';
        $id = mysqli_real_escape_string($conn, trim($_POST['id']));
        $facility = mysqli_real_escape_string($conn, trim($_POST['facility']));
        
        if(empty($id) || empty($facility)) {
            //close db
            mysqli_close($conn);
            $_SESSION['empty'] = true;
            header('location: /resort/src/facility_add.php');
        }

        //chcek if room type already exist
        $sql = "SELECT facility FROM facilities WHERE facility='$facility';";

        //create prepared statement
        $stmt = mysqli_stmt_init($conn);
        //prepare prepared statement
        if(!@mysqli_stmt_prepare($stmt, $sql)) {
            throw new Exception(mysqli_error($conn));           
            //close db
            mysqli_close($conn);
            exit();
            
        } else {
            //bind parameters to placeholder
            mysqli_stmt_bind_param($stmt, "s", $type);
            //execute statement
            mysqli_stmt_execute($stmt);
            //get result
            $result = mysqli_stmt_get_result($stmt);
            $resultCheck = mysqli_num_rows($result);

            if($resultCheck>0) {
                //close db
                mysqli_close($conn);
                //redirect
                $_SESSION['exist'] = true;
                header('location: /resort/src/facility_add.php');
            } else {
                //insert statement
                $sql = "INSERT INTO facilities (f_id, facility) VALUES (?, ?);";
                        
                //create prepared statement
                //$stmt = mysqli_stmt_init($conn);
                //prepare the prepare statement
                if(!@mysqli_stmt_prepare($stmt, $sql)) {
                    throw new Exception(mysqli_error($conn));
                    //close db connection
                    mysqli_close($conn); 
                    exit();
                } else {
                    //bind parameters to placeholders
                    mysqli_stmt_bind_param($stmt, "is", $id, $facility); //s-string, i-integer, d-double, b-blob
                    //run parameters inside database
                    mysqli_stmt_execute($stmt);
                    //close db connection
                    mysqli_close($conn); 

                    //redirect
                    $_SESSION ['success'] = true;
                    header('location: /resort/src/facility_add.php');
                } 
            }
        }        
    }
}//end try
catch (Exception $e) {
    exit($e->getMessage());
}
