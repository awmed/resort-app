<?php
//session start
session_start();
//session id for specific user input
try {
    if(!isset($_POST['submit'])) {
        header('location: /resort/src/staff_add.php');
        exit();
    } else {
        //open db connection
        include_once 'db.php';
        $type = trim(strip_tags(stripslashes(mysqli_real_escape_string($conn, $_POST['type']))));
        $username = trim(strip_tags(stripslashes(mysqli_real_escape_string($conn, $_POST['username']))));
        $email = trim(strip_tags(stripslashes(mysqli_real_escape_string($conn, $_POST['email']))));
        $pwd = mysqli_real_escape_string($conn, $_POST['password']);
        $pwdConfirm = mysqli_real_escape_string($conn, $_POST['password-confirm']);

        if(empty($type) || empty($username) || empty($email) || empty($pwd) || empty($pwdConfirm)) {
            //close db
            mysqli_close($conn);
            $_SESSION['empty'] = true;
            header('location: /resort/src/user_add.php');
            exit();
        } else {
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
                header('location: /resort/src/user_add.php');
                //close db connection
                mysqli_close($conn); 
                exit();
            } 
            else {
                if($_POST['password'] != $_POST['password-confirm']) {
                    $_SESSION['pass-miss'] = true;
                    header('location: /resort/src/user_add.php');
                    //close db connection
                    mysqli_close($conn); 
                    exit();
                } else{
                    
                    //insert user image as file input
                    $image = $_FILES['image'];
                    $imageName = $_FILES['image']['name'];
                    $imageTmpName = $_FILES['image']['tmp_name']; //temp location
                    $imageSize = $_FILES['image']['size'];
                    $imageError = $_FILES['image']['error'];
                    $imageType = $_FILES['image']['type'];

                    //image extension
                    $imageExt = explode('.',$imageName);
                    $imageActualExt = strtolower(end($imageExt)); //.jpg

                    //allowed image extension
                    $allowed = array('jpg','jpeg','png'); //pdf etc.
                
                    //no image uploaded
                    if(empty($imageSize)) {
                        //set status to 0 - default profile image
                        $status = mysqli_real_escape_string($conn, $s=0);
                    }

                    //custom image uploaded
                    if(!empty($imageSize)) {
                        //set status to 1 - custom profile image
                        $status = mysqli_real_escape_string($conn, $s=1);            

                        if (in_array($imageActualExt, $allowed)) {
                            //verify no errors in image
                            if($imageError == 0) {
                                if($imageSize < 2000000) { //2mb
                                    $imageNameNew = "username-".$username.".".$imageActualExt;
                                    $imageDestination = $_SERVER['DOCUMENT_ROOT'].'/resort/src/assets/uploads/user-image/'.$imageNameNew;
                                    move_uploaded_file($imageTmpName, $imageDestination);                    
                                } else {
                                    echo "Image size is too big";
                                    exit();
                                }
                            } else {
                                echo "There was an error uploading the image!";
                                exit();
                            }
                        } else {
                            echo "You cannot upload images of this type";
                            exit();
                        }
                    }
                    //hashing password
                    $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);  
                          
                    //create template
                    $sql = "INSERT INTO users (user_type, email, username, password, img_status) VALUES (?,?,?,?,?);";

                    //prepare prepared statement
                    if(!@mysqli_stmt_prepare($stmt, $sql)) {
                        throw new Exception (mysqli_error());
                        //close db connection
                        mysqli_close($conn); 
                        exit();
                    } else {
                        //bind parameters to place holders
                        mysqli_stmt_bind_param($stmt, "ssssi", $type, $email, $username, $hashedPwd, $status);
                        //run statement inside database
                        mysqli_stmt_execute($stmt);
                        //close db connection
                        mysqli_close($conn); 
                        //redirect
                        $_SESSION['success'] = true;
                        header('location: /resort/src/user_add.php');
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