<?php
//start session
session_start();

try {
    if(!isset($_POST['submit'])) {
        header('location: /resort/src/sign_up.php');
        exit(); 
    } else {
        //open db connection
        include_once 'db.php';
        $type = mysqli_real_escape_string($conn, "staff");
        $name = mysqli_real_escape_string($conn, $_POST['username']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $pwd = mysqli_real_escape_string($conn, $_POST['password']);
        $pwdConfirm = mysqli_real_escape_string($conn, $_POST['password-confirm']);
        
        //check if user already exist
        $sql = "SELECT username FROM users WHERE username=?;";

        //create prepare statement
        $stmt = mysqli_stmt_init($conn);

        //prepare the prepare statement
        if(!@mysqli_stmt_prepare($stmt, $sql)) {
            throw new Exception(mysqli_error($conn));
            exit();
            //close db connection
            mysqli_close($conn); 
        } else {
            //bind the prepare statment
            mysqli_stmt_bind_param($stmt, "s", $name);

            //run the parameter inside database
            mysqli_stmt_execute($stmt);

            $result = mysqli_stmt_get_result($stmt);
            $resultCheck = mysqli_num_rows($result);
        }        

        if($resultCheck>0) {
            $_SESSION['taken'] = true;
            header('location: /resort/src/sign_up.php');
            //close db connection
            mysqli_close($conn); 
            exit();
        } 
        else {
            if($_POST['password'] != $_POST['password-confirm']) {
                $_SESSION['pass-miss'] = true;
                header('location: /resort/src/sign_up.php');
                //close db connection
                mysqli_close($conn); 
                exit();
            } else{
                //hashing password
                $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);

                //insert the user into database
                $sql = "INSERT INTO users (user_type,email,username,password) values (?,?,?,?);";

                //create prepared statement
                //$stmt = mysqli_stmt_init($conn);
                //prepare the prepared statement
                if(!@mysqli_stmt_prepare($stmt, $sql)) {
                    throw new Exception(mysqli_error($conn));
                } else {
                    //bind parameters to placeholders
                    mysqli_stmt_bind_param($stmt, "ssss", $type, $email, $name, $hashedPwd);
                    //run parameter inside database
                    mysqli_execute($stmt);

                    //close db connection
                    mysqli_close($conn); 
                    
                    //redirect
                    $_SESSION['success'] = true;
                    header('location: /resort/src/sign_up.php');
                } 
            }
        }
    }
}
catch (Exception $e) {
    exit($e->getMessage());
}