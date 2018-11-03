<?php
//start session
session_start();
try {
    if(!isset($_POST['submit'])){ 
        header('location: /resort/src/room_type_add.php');
        exit(); 
    } else {   
        //open db connection
        include_once 'db.php';
        $id = mysqli_real_escape_string($conn, trim($_POST['id']));
        $type = mysqli_real_escape_string($conn, trim($_POST['type']));
        $desc = mysqli_real_escape_string($conn, trim($_POST['description']));
        
        if(empty($type) || empty($desc)) {
            //close db
            mysqli_close($conn);
            $_SESSION['empty'] = true;
            header('location: /resort/src/room_type_add.php');
            exit();
        } else {
            //chcek if room type already exist
            $sql = "SELECT room_type FROM room_type WHERE room_type=?;";

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
                    header('location: /resort/src/room_type_add.php');
                } else {   
                    //multiple image array
                    for($i=0; $i<count($_FILES['image']['name']); $i++) {
                        //upload image (requried)
                        $image = $_FILES['image'][$i];
                        $imageName = $_FILES['image']['name'][$i];
                        $imageTmpName = $_FILES['image']['tmp_name'][$i]; //temp location
                        $imageSize = $_FILES['image']['size'][$i];
                        $imageError = $_FILES['image']['error'][$i];
                        $imageType = $_FILES['image']['type'][$i];

                        //image extension
                        $imageExt = explode('.',$imageName);
                        $imageActualExt = strtolower(end($imageExt)); //.jpg

                        //allowed image extension
                        $allowed = array('jpg','jpeg','png'); //pdf etc.
                                    
                        //custom image uploaded
                        if(!empty($imageSize)) {
                            if (in_array($imageActualExt, $allowed)) {
                                //verify no errors in image
                                if($imageError == 0) {
                                    if($imageSize < 2000000) { //2mb
                                        //check wether the directory exist and create new directory
                                        if(!file_exists($_SERVER['DOCUMENT_ROOT'].'/resort/src/assets/uploads/room-image/'.$type)) {
                                           mkdir($_SERVER['DOCUMENT_ROOT'].'/resort/src/assets/uploads/room-image/'.$type, 0777, true);
                                        }
                                        $imageNameNew = $type."-".mt_rand(00000,99999).".".$imageActualExt;
                                        $imageDestination = $_SERVER['DOCUMENT_ROOT'].'/resort/src/assets/uploads/room-image/'.$type.'/'.$imageNameNew;
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
                    }//end for

                    //insert room type statement
                    $sql = "INSERT INTO room_type (r_type_id, room_type, description) VALUES (?, ?, ?);";                            

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
                        //insert room facilities
                        if(isset($_POST['facility'])) {
                            $facility = array_map(array($conn, 'real_escape_string'), $_POST['facility']); //facility[] array
                            //loop through the dynamic check box
                            for($i=0; $i<count($facility); $i++) {
                                //post only valid inputs
                                if(!empty($facility)) {
                                    $sql = "INSERT INTO room_facilities(room_type,facility) VALUES (?,?);";
                                    //prepare prepared statement
                                    mysqli_stmt_prepare($stmt, $sql);
                                    //bind parameter to place holder
                                    mysqli_stmt_bind_param($stmt, "ss", $type, $facility[$i]);
                                    //execute statement
                                    mysqli_stmt_execute($stmt);                                    
                                }
                            }
                        }
                        //close db connection
                        mysqli_close($conn); 
                        //redirect
                        $_SESSION ['success'] = true;
                        header('location: /resort/src/room_type_add.php');
                    } 
                }
            }    
        }            
    }
}//end try
catch (Exception $e) {
    exit($e->getMessage());
}
