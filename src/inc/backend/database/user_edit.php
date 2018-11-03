<?php
//start session
session_start();
try {
    // if(!isset($_POST['submit']) || !isset($_POST['remove'])) {
    //     header('location: /resort/src/user_modify.php');
    // }
   
    //update user
    if(isset($_POST['submit'])) { 
        //open db connection
        include_once 'db.php';       
        $username = mysqli_real_escape_string($conn, trim($_POST['username']));
        $type = mysqli_real_escape_string($conn, trim($_POST['type']));
        $email = mysqli_real_escape_string($conn, trim($_POST['email']));
        $imgStatus = mysqli_real_escape_string($conn, trim($_POST['img-status']));

        if(empty($username) || empty($type) || empty($email)) {
            //close db
            mysqli_close($conn);
            $_SESSION['empty'] = true;
            header('location: /resort/src/user_edit.php?username='.$username.'&type='.$type.'&email='.$email.'&status='.$imgStatus);
            exit();
        } else {
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
        
            //no image uploaded
            if($imgStatus==0) {
                //set status to 0 - default profile image
                $status = mysqli_real_escape_string($conn, $s=0);
            }

            //custom image uploaded
            if($imgStatus==1) {
                //set status to 1 - custom profile image
                $status = mysqli_real_escape_string($conn, $s=1);            
            }

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
            }
            
            //update statement
            $sql = "UPDATE users SET user_type=?, email=?, img_status=? WHERE username=?;";

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
                mysqli_stmt_bind_param($stmt, 'ssis', $type, $email, $status, $username); //s-string, i-integer, d-double, b-blob
                //run parameters inside database
                mysqli_stmt_execute($stmt);
                //close db connection
                mysqli_close($conn); 
                //redirect 
                $_SESSION['updated'] = true;
                header('location: /resort/src/user_modify.php');
            }
        }       
    }

    //remove image and update database
    if(isset($_POST['remove'])) {     
        //open db connection
        include_once 'db.php';    
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $status = mysqli_real_escape_string($conn, $s=0);

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
        header('location: /resort/src/user_modify.php');
    }

    //change user password  
    if(isset($_POST['password-change'])) {
        //open db connection
        include_once 'db.php';
        $username = mysqli_real_escape_string($conn, trim($_POST['username']));
        $pwd = mysqli_real_escape_string($conn, $_POST['password']);
        $pwdConfirm = mysqli_real_escape_string($conn, $_POST['password-confirm']);

        if($_POST['password'] != $_POST['password-confirm']) {
            $_SESSION['pass-miss'] = true;
            header('location: /resort/src/user_modify.php');
            //close db connection
            mysqli_close($conn); 
            exit();
        } else {
            //hashing password
            $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);  
                          
            //create template
            $sql = "UPDATE users SET password=? WHERE username=?;";
            $stmt = mysqli_stmt_init($conn);
            if(!@mysqli_stmt_prepare($stmt,$sql)) {
                throw new Exception (mysqli_error($conn));
                //close db connection
                mysqli_close($conn); 
                exit();
            } else {
                mysqli_stmt_bind_param($stmt, "ss", $hashedPwd, $username);
                mysqli_stmt_execute($stmt);
                //close db connection
                mysqli_close($conn); 
                //redirect 
                $_SESSION['updated'] = true;
                header('location: /resort/src/user_modify.php');
            }       
        }
    }

    //delete user from database
    if(isset($_GET['username']) && isset($_GET['status'])) {
        //open db connection
        include_once 'db.php';
        $username = $_GET['username'];    
        $sql = "DELETE FROM users WHERE username=?;";

        //create prepare statement
        $stmt = mysqli_stmt_init($conn);
        
        //prepare prepared statment
        if(!@mysqli_stmt_prepare($stmt, $sql)) {
            throw new Exception (mysqli_error($conn));
            //close db connection
            mysqli_close($conn); 
            exit();
        } else {
            //bind parameters
            mysqli_stmt_bind_param($stmt, "s", $username);
            //run prepared statement
            mysqli_stmt_execute($stmt); 
            //close db connection
            mysqli_close($conn); 
        }
            
        //remove image if uploaded
        if(isset($_GET['status'])==1) {
            $imagename = $_SERVER['DOCUMENT_ROOT']."/resort/src/assets/uploads/user-image/username-".$username."*"; //select the staff image with any image extension

            $imageinfo = glob($imagename);
            $imageExt = explode(".", $imageinfo[0]);
            $imageActualExt = $imageExt[1];

            $image = $_SERVER['DOCUMENT_ROOT'].'/resort/src/assets/uploads/user-image/username-'.$username.'.'.$imageActualExt;
            //remove from directory
            unlink($image);
        }
        //redirect
        $_SESSION['deleted'] = true;
        header('location: /resort/src/user_modify.php');
    }       
}
catch (Exception $e) {
    $e->getMessage();
}
