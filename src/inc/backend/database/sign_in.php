<?php
//start session
session_start();
try{
    if(!isset($_POST['submit'])) {
        header('location: /resort/src/sign_in.php'); 
    } else {   
        //open db connection
        include_once 'db.php'; 
        $name = mysqli_real_escape_string($conn, strtolower(trim($_POST['login-username'])));
        $password = mysqli_real_escape_string($conn, trim($_POST['login-password']));    

        //validate fields
        if(empty($name)) {
            //close db
            mysqli_close($conn);
            $_SESSION['empty'] = true;
            header('location: /resort/src/sign_in.php');
            exit();
        } else {
            //create a template
            $sql = "SELECT * FROM users WHERE username=?;";
            //create a prepared statement
            $stmt = mysqli_stmt_init($conn);

            //prepare the prepared statement
            if(!@mysqli_stmt_prepare($stmt, $sql)) {
                throw new Exception(mysqli_error($conn));
                //close db connection
                mysqli_close($conn); 
                exit();
            } else {
                //bind parameters to placeholders
                mysqli_stmt_bind_param($stmt, "s", $name);
                //run the parameters inside database
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt); //equivalent of mysqli_query($conn, $sql)
                //user count
                $resultCheck = mysqli_num_rows($result);
                
                if(!$resultCheck > 0) {
                    $_SESSION['error'] = true;
                    header('location: /resort/src/sign_in.php'); 
                    //close db connection
                    mysqli_close($conn); 
                    exit();
                } else {
                    if($row = mysqli_fetch_array($result)) {
                        //de-hashing the password
                        $hashedPwdCheck = password_verify($password, $row['password']);

                        //close db connection
                        mysqli_close($conn); 

                        if($hashedPwdCheck == false) {
                            $_SESSION['error'] = true;
                            header('location: /resort/src/sign_in.php'); 
                            exit();
                        } else if ($hashedPwdCheck == true) {
                            $_SESSION ['user'] = $name;
                            $_SESSION ['imgStatus'] = $row['img_status'];                            
                            $_SESSION ['success'] = true;
                            header('location: /resort/src/dashboard.php');
                            //echo "<script>console.log( 'Debug Objects: " . $result . "' );</script>";
                            exit();
                        }
                    }
                }
            }
            // if(!$resultCheck == 1) {
            //     $_SESSION['error'] = true;
            //     header('location: ../../../sign_in.php'); 
            //     exit();
            // } else {            
            //     $_SESSION ['user'] = $name;
            //     $_SESSION ['success'] = true;
            //     header('location: ../../../dashboard.php');
            // }
        }
    /*
        if($resultCheck==1){        
            //set cookie
            //if(isset($_POST['login-remember-me'])) {
            //   setcookie('username',$name, time()+60*60*7);            
            //}

            //set sessions
            $_SESSION ['user'] = $name;
            $_SESSION ['success'] = true;

            header('location: ../../../dashboard.php');
            //set user types
            //$row = mysqli_fetch_array($result);
            //echo $row['user-type'] = $_SESSION['user-type'];    
            //set home page for staff and admin  
            
        } else {
            $_SESSION['error'] = true;
            header('location: ../../../sign_in.php'); 
        }
    */     
    }
} 
catch (Exception $e) {
    exit($e->getMessage());
}
