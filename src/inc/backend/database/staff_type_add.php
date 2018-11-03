<?php
//start session
session_start();
try {
    if(!isset($_POST['submit'])){ 
        header('location: /resort/src/staff_type_add.php');
        exit(); 
    } else {   
        //open db connection
        include_once 'db.php';
        $id = mysqli_real_escape_string($conn, $_POST['id']);
        $type = mysqli_real_escape_string($conn, trim($_POST['type']));
        $desc = mysqli_real_escape_string($conn, trim($_POST['description']));
        
        //validate fields
        if(empty($type) || empty($desc)) {
            //close db
            mysqli_close($conn);
            $_SESSION['empty'] = true;
            header('location: /resort/src/staff_type_add.php');
            exit();
        } else {
            //check if staff type already exist
            $sql = "SELECT s_type FROM staff_type WHERE s_type='$type';";
            //create prepared statement
            $stmt = mysqli_stmt_init($conn);
            //prepare prepared statement
            if(!mysqli_stmt_prepare($stmt, $sql)) {
                throw new Exception(mysqli_error($conn));
                //close db connection
                mysqli_close($conn);
                exit();
            } else {
                //bind parameters to placeholders
                mysqli_stmt_bind_param($stmt, "s", $type);
                //execute statement
                mysqli_execute($stmt);
                //get result
                $result = mysqli_stmt_get_result($stmt);
                $resultCheck = mysqli_num_rows($result);

                if($resultCheck>0) {
                    //close db
                    mysqli_close($conn);
                    $_SESSION['exist'] = true;
                    header('location: /resort/src/staff_type_add.php');
                } else {
                    //insert statement
                    $sql = "INSERT INTO staff_type (s_type_id,s_type,description) VALUES (?, ?, ?);";
                    
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
                        mysqli_stmt_bind_param($stmt, "iss", $id, $type, $desc); //s-string, i-integer, d-double, b-blob
                        //run parameters inside database
                        mysqli_stmt_execute($stmt);
                        //close db connection
                        mysqli_close($conn); 

                        //redirect
                        $_SESSION ['success'] = true;
                        header('location: /resort/src/staff_type_add.php');
                    } 
                }
            }  
        }             
    }
}//end try
catch (Exception $e) {
    exit($e->getMessage());
}
