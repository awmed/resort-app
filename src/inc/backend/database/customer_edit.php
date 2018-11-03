<?php
//start session
session_start();
try {
    if(!isset($_POST['submit']) || !isset($_POST['remove'])) {
        header('location: /resort/src/customer_modify.php');
    }
    //current version allows uplods of different extension without deleting the previous, for ex. .png replaces .png, however if .jpg insted of replacing it creates a new file
    //fix - create the unlink fucntion within a loop, to remove all possibel files with other extensions during delete. 


    //update staff
    if(isset($_POST['submit'])) { 
        //open db connection
        include_once 'db.php';       
        $id = mysqli_real_escape_string($conn, $_POST['id']);
        $first = mysqli_real_escape_string($conn, trim($_POST['f-name']));
        $last = mysqli_real_escape_string($conn, trim($_POST['l-name']));
        $email = mysqli_real_escape_string($conn, trim($_POST['email']));
        $gen = mysqli_real_escape_string($conn, trim($_POST['gender']));
        $nationality = mysqli_real_escape_string($conn, $_POST['nationality']);
        $phone = mysqli_real_escape_string($conn, trim($_POST['phone']));
        $add = mysqli_real_escape_string($conn, trim($_POST['address']));
        $imgStatus = mysqli_real_escape_string($conn, $_POST['img-status']);

        if(empty($first) || empty($last) || empty($email) || empty($phone) || empty($add))  {
            //close db
            mysqli_close($conn);
            $_SESSION['empty'] = true;
            //header('location: /resort/src/customer_edit.php?ID='.$id.'&first='.$first.'&last='.$last.'&email='.$email.'&gender='.$gen.'&phone='.$phone.'&address='.$add.'&status='.$imgStatus);
            //header('location: /resort/src/customer_edit.php?ID='.$id);
            header('location: /resort/src/customer_edit.php?ID='.$id.'&first='.$first.'&last='.$last.'&email='.$email.'&gender='.$gen.'&nationality='.$nationality.'&phone='.$phone.'&address='.$add.'&status='.$imgStatus);

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
                            $imageNameNew = "customer_id-".$id.".".$imageActualExt;
                            // $imageDestination = '../../../assets/uploads/staff-image/'.$imageNameNew;
                            $imageDestination = $_SERVER['DOCUMENT_ROOT'].'/resort/src/assets/uploads/customer-image/'.$imageNameNew;
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
            $sql = "UPDATE customer SET f_name=?, l_name=?, email=?, gender=?, nationality=?, phone=?, address=?, img_status=? WHERE customer_id=?;";

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
                mysqli_stmt_bind_param($stmt, 'sssssssii', $first, $last, $email, $gen, $nationality, $phone, $add, $status, $id); //s-string, i-integer, d-double, b-blob
                //run parameters inside database
                mysqli_stmt_execute($stmt);
                //close db connection
                mysqli_close($conn); 
                //redirect 
                $_SESSION['updated'] = true;
                header('location: /resort/src/customer_modify.php');
            }
        }     
    }

    //remove image and update database
    if(isset($_POST['remove'])) {     
        //open db connection
        include_once 'db.php';    
        $ID = mysqli_real_escape_string($conn, $_POST['id']);
        $status = mysqli_real_escape_string($conn, $s=0);

        $imagename = $_SERVER['DOCUMENT_ROOT']."/resort/src/assets/uploads/customer-image/customer_id-".$ID."*"; //select the staff image with any image extension

        $imageinfo = glob($imagename);
        $imageExt = explode(".", $imageinfo[0]);
        $imageActualExt = $imageExt[1];

        $image = $_SERVER['DOCUMENT_ROOT'].'/resort/src/assets/uploads/customer-image/customer_id-'.$ID.'.'.$imageActualExt;

        //update statement
        $sql = "UPDATE customer SET img_status=? WHERE customer_id=?;";

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
            mysqli_stmt_bind_param($stmt, "ii", $status, $ID);
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
        header('location: /resort/src/customer_modify.php');
    }

    //delete staff from database
    if(isset($_GET['ID']) && isset($_GET['status'])) {
        //open db connection
        include_once 'db.php';
        $id = $_GET['ID'];    
        $sql = "DELETE FROM customer WHERE customer_id=?;";

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
            mysqli_stmt_bind_param($stmt, "i", $id);
            //run prepared statement
            mysqli_stmt_execute($stmt); 
            //close db connection
            mysqli_close($conn); 
        }
            
        //remove image if uploaded
        if(isset($_GET['status'])==1) {
            $imagename = $_SERVER['DOCUMENT_ROOT']."/resort/src/assets/uploads/customer-image/customer_id-".$id."*"; //select the staff image with any image extension

            $imageinfo = glob($imagename);
            $imageExt = explode(".", $imageinfo[0]);
            $imageActualExt = $imageExt[1];

            $image = $_SERVER['DOCUMENT_ROOT'].'/resort/src/assets/uploads/customer-image/customer_id-'.$id.'.'.$imageActualExt;
            //remove from directory
            unlink($image);
        }
        //redirect
        $_SESSION['deleted'] = true;
        header('location: /resort/src/customer_modify.php');
    }       
}
catch (Exception $e) {
    $e->getMessage();
}
