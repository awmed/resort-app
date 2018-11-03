<?php
//start session
session_start();
try {
    if(!isset($_POST['password']) || !isset($_FILES['image'])) {
        header('location: /resort/src/generic_profile.php');
        exit();
    }

    if (isset($_POST['change-password'])) {
        //open db connection
        include_once 'db.php';
        $oldPwd = mysqli_real_escape_string($conn, $_POST['password-old']);
        $pwd = mysqli_real_escape_string($conn, $_POST['password']);
        $pwdConfirm = mysqli_real_escape_string($conn, $_POST['password-confirm']);
        $username = mysqli_real_escape_string($conn, $_SESSION['user']);

        if(empty($oldPwd) || empty($pwd) || empty($pwdConfirm)) {
            //close db
            mysqli_close($conn);
            $_SESSION['empty'] = true;
            header('location: /resort/src/generic_profile.php');
            exit();
        } else {
            $sql = "SELECT * FROM users WHERE username=?;";
            $stmt = mysqli_stmt_init($conn);
            if(!@mysqli_stmt_prepare($stmt,$sql)) {
                mysqli_close($conn);
                throw new Exception(mysqli_error($conn));
                exit();
            } else {
                mysqli_stmt_bind_param($stmt, "s", $username);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);

                if($row = mysqli_fetch_array($result)) {
                    //dehasing the password and verifing old password
                    $hashedPwdCheck = password_verify($oldPwd, $row['password']);

                    if($hashedPwdCheck == false) {
                        mysqli_close($conn);
                        $_SESSION['wrong'] = true;
                        header('location: /resort/src/generic_profile.php'); 
                        exit();
                    } else if($hashedPwdCheck == true) {
                       if($pwd != $pwdConfirm) { 
                            mysqli_close($conn);                       
                            $_SESSION['pass-miss'] = true;
                            header('location: /resort/src/generic_profile.php'); 
                            exit();
                       } else {
                            $hasedPwd = password_hash($pwd, PASSWORD_DEFAULT);
                            $sql = "UPDATE users SET password=? WHERE username=?;";
                            if(!@mysqli_stmt_prepare($stmt,$sql)) {
                                mysqli_close($conn);
                                throw new Exception(mysqli_error($conn));
                                exit();
                            } else {
                                mysqli_stmt_bind_param($stmt, "ss", $hasedPwd, $username);
                                mysqli_stmt_execute($stmt);
                                mysqli_close($conn);
                                $_SESSION['success'] = true;
                                header('location: /resort/src/generic_profile.php');
                                exit(); 
                            }
                        }
                    }
                }
            }
        }
    }

    if (isset($_POST['update-image'])) {
        include_once 'db.php'; 
        $username = mysqli_real_escape_string($conn, $_SESSION['user']);
        $imgStatus = mysqli_real_escape_string($conn, $_SESSION['imgStatus']);

        //insert staff image as file input
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
        
        if(!empty($imageSize)) {
            //set status to 1 - custom profile image
            $status = mysqli_real_escape_string($conn, $s=1);            

            if (in_array($imageActualExt, $allowed)) {
                //verify no errors in image
                if($imageError == 0) {
                    if($imageSize < 2000000) { //2mb
                        $imageNameNew = "username-".$username.".".$imageActualExt;
                        // $imageDestination = '../../../assets/uploads/staff-image/'.$imageNameNew;
                        $imageDestination = $_SERVER['DOCUMENT_ROOT'].'/resort/src/assets/uploads/user-image/'.$imageNameNew;
                        move_uploaded_file($imageTmpName, $imageDestination);                   
                    } else {; 
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
        } else {
            header('location: /resort/src/generic_profile.php');
            exit();
        }

        //update statement
        $sql = "UPDATE users SET img_status=? WHERE username=?;";
    
        //create prepared statement
        $stmt = mysqli_stmt_init($conn);

        //prepare prepared statement
        if(!@mysqli_stmt_prepare($stmt, $sql)) {
            throw new Exception(mysqli_error($conn));
            //close db connection
            mysqli_close($conn); 
            exit();
        } else {
            //bind parameters
            mysqli_stmt_bind_param($stmt, "is", $status, $username);
            //execute statement
            mysqli_stmt_execute($stmt);
            //close db connection
            mysqli_close($conn); 
            $_SESSION['img-success'] = true;
            $_SESSION['imgStatus'] = 1;
            header('location: /resort/src/generic_profile.php');
            exit(); 
        }        
        
    }

    //remove user image
    if (isset($_POST['remove-image'])) {
        include_once 'db.php';    
        $username = mysqli_real_escape_string($conn, $_SESSION['user']);
        $status = mysqli_real_escape_string($conn, $s=0);
        $imgStatus = $_SESSION['imgStatus'];   

        if($imgStatus != 1) {
            mysqli_close($conn);
            $_SESSION['no-img'] = true;
            header('location: /resort/src/generic_profile.php');
            exit();
        } else {
            $imagename = $_SERVER['DOCUMENT_ROOT']."/resort/src/assets/uploads/user-image/username-".$username."*"; //select the staff image with any image extension

            $imageinfo = glob($imagename);
            $imageExt = explode(".", $imageinfo[0]);
            $imageActualExt = $imageExt[1];
    
            $image = $_SERVER['DOCUMENT_ROOT'].'/resort/src/assets/uploads/user-image/username-'.$username.'.'.$imageActualExt;
    
            //update statement
            $sql = "UPDATE users SET img_status=? WHERE username=?;";
    
            //create prepared statement
            $stmt = mysqli_stmt_init($conn);
    
            //prepare prepared statement
            if(!@mysqli_stmt_prepare($stmt, $sql)) {
                throw new Exception(mysqli_error($conn));
                //close db connection
                mysqli_close($conn); 
                exit();
            } else {
                //bind parameters
                mysqli_stmt_bind_param($stmt, "is", $status, $username);
                //execute statement
                mysqli_stmt_execute($stmt);
                //close db connection
                mysqli_close($conn); 
            }        
    
            //remove image
            if (file_exists($image)) {            
                unlink($image);
            } else {
                echo 'Image not found';
                echo "File Name -> $imageActualExt<br>";
                echo $image;
            }        
            //redirect
            $_SESSION['removed'] = true;
            $_SESSION['imgStatus'] = 0;
            header('location: /resort/src/generic_profile.php');    
        }
    }
}
catch (Exception $e) {
    $e->getMessage();
}