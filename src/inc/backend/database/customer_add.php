<?php
//session start
session_start();
//session id for specific user input
try {
    if(!isset($_POST['submit'])) {
        // header('location: ../../../customer_add.php');
        header('location: /resort/src/customer_add.php');
        exit();
    } else {
        //open db connection
        include_once 'db.php';
        $id = mysqli_real_escape_string($conn, $_POST['id']);
        $first = mysqli_real_escape_string($conn, trim($_POST['f-name']));
        $last = mysqli_real_escape_string($conn, trim($_POST['l-name']));
        $email = mysqli_real_escape_string($conn, trim($_POST['email']));
        $gender = mysqli_real_escape_string($conn, trim($_POST['gender']));
        $nationality = mysqli_real_escape_string($conn, trim($_POST['nationality']));
        $phone = mysqli_real_escape_string($conn, trim($_POST['phone']));
        $address = mysqli_real_escape_string($conn, trim($_POST['address']));

        if(empty($first) || empty($last) || empty($email) || empty($phone) ||empty($address)) {
            //close db
            mysqli_close($conn);
            $_SESSION['empty'] = true;
            header('location: /resort/src/customer_add.php');
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
                            $imageNameNew = "customer_id-".$id.".".$imageActualExt;
                            $imageDestination = $_SERVER['DOCUMENT_ROOT'].'/resort/src/assets/uploads/customer-image/'.$imageNameNew;
                            move_uploaded_file($imageTmpName, $imageDestination);                    
                        } else {
                            echo "Image size is too big";
                        }
                    } else {
                        echo "There was an error uploading the image!";
                    }
                } else {
                    echo "You cannot upload images of this type";
                }
            }        
            //create template
            $sql = "INSERT INTO customer (customer_id, f_name, l_name, email, gender, nationality, phone, address, img_status) VALUES (?,?,?,?,?,?,?,?,?);";

            //create prepared statement
            $stmt = mysqli_stmt_init($conn);

            //prepare prepared statement
            if(!@mysqli_stmt_prepare($stmt, $sql)) {
                throw new Exception (mysqli_error());
                //close db connection
                mysqli_close($conn); 
                exit();
            } else {
                //bind parameters to place holders
                mysqli_stmt_bind_param($stmt, "isssssssi", $id, $first, $last, $email, $gender, $nationality, $phone, $address, $status);
                //run statement inside database
                mysqli_stmt_execute($stmt);
                //close db connection
                mysqli_close($conn); 
                //redirect
                $_SESSION['success'] = true;
                header('location: /resort/src/customer_add.php');
            }
            exit(); 
        }              
    }
}
catch (Exception $e) {
    $e->getMessage();
}