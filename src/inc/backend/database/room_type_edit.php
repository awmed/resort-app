<?php
//start session
session_start();
try {
    if(!isset($_POST['submit'])) {
        header('location: /resort/src/room_type_modify.php');
    }

    //update staff type 
    if(isset($_POST['submit'])) {                
        //include database
        include_once 'db.php';
        $id = mysqli_real_escape_string($conn, trim($_POST['id']));
        $type = mysqli_real_escape_string($conn, trim($_POST['type']));
        $desc = mysqli_real_escape_string($conn, trim($_POST['description']));

        if(empty($id) || empty($type) || empty($desc)) {
            //close db
            mysqli_close($conn);
            $_SESSION['empty'] = true;
            header('location: /resort/src/room_type_edit.php?ID='.$id.'&type='.$type.'&desc='.$desc);
            exit();
        } else {

            //rename image directory
            $sql = "SELECT room_type FROM room_type WHERE r_type_id=?;";
            //create prepared statement
            $stmt = mysqli_stmt_init($conn);
            
            @mysqli_stmt_prepare($stmt,$sql);
            mysqli_stmt_bind_param($stmt,"i",$id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $row = mysqli_fetch_array($result);
            
            $oldType = $row['room_type'];
            // $oldType = 'test room';

            //change directory
            $path = $_SERVER['DOCUMENT_ROOT'].'/resort/src/assets/uploads/room-image/';
            chdir($path);
            if(is_dir($oldType)) {
                rename($oldType, $type);
            }

            //update statement
            $sql = "UPDATE room_type SET room_type=?, description=? WHERE r_type_id=?;";
            
            //prepare prepared statement
            if(!@mysqli_stmt_prepare($stmt, $sql)) {
                throw new Exception (mysqli_error($conn));
                //close db connection
                mysqli_close($conn); 
                exit();
            } else {
                //bind parameters to placeholders
                mysqli_stmt_bind_param($stmt, 'ssi', $type, $desc, $id); //s-string, i-integer, d-double, b-blob
                //run parameters inside database
                mysqli_stmt_execute($stmt);

                //duplicate entry conflict
                $error = mysqli_errno($conn);
                if($error==1062) {
                    //close db connection
                    mysqli_close($conn); 
                    $_SESSION['exist'] = true;
                    header('location: /resort/src/room_type_edit.php?ID='.$id.'&type='.$type.'&desc='.$desc);
                    exit();
                } else {
                    //close db connection
                    mysqli_close($conn); 
                    $_SESSION['updated'] = true;
                    header('location: /resort/src/room_type_modify.php');
                }
            }
        }
        
    }

    //delete record from database
    if(isset($_GET['ID'])) {
        $id = $_GET['ID'];
        //include database
        include_once 'db.php';
        $sql= "DELETE FROM room_type WHERE r_type_id=?; ";

        //create prepared statement
        $stmt = mysqli_stmt_init($conn);
        //prepare prepared statement
        if(!@mysqli_stmt_prepare($stmt, $sql)) {
            throw new Exception (mysqli_error($conn));
            //close db connection
            mysqli_close($conn);
            exit();
        } else {
            //bind parameters to place holders
            mysqli_stmt_bind_param($stmt, "i", $id);
            //execute query
            mysqli_stmt_execute($stmt);
            //close db connection
            mysqli_close($conn);

            //directory path
            $type = $_GET['rtype'];
            $dir = '../../../assets/uploads/room-image/'.$type.'/';

            foreach(scandir($dir) as $file) {
                if ('.' === $file || '..' === $file) continue;
                if (is_dir("$dir/$file")) rmdir_recursive("$dir/$file");
                else unlink("$dir/$file");
            }
            rmdir($dir);

            //redirect success
            $_SESSION['deleted'] = true;
            header('location: /resort/src/room_type_modify.php');
        }
    }

    //update room facility
    if(isset($_POST['update'])) {
        //open conncetion
        include_once 'db.php';
        $type = mysqli_real_escape_string($conn, $_POST['type']);

        //delete facilities
        $sql = "DELETE FROM room_facilities WHERE room_type=?;";
        //create pp statement
        $stmt = mysqli_stmt_init($conn);
        //prepare pp statement
        if(!mysqli_stmt_prepare($stmt,$sql)) {
            throw new Exception(mysqli_error($conn));
            //close db
            mysqli_close($conn);
            exit();
        } else {
            //bind parameters to placeholder
            mysqli_stmt_bind_param($stmt, "s", $type);
            //execute stmt
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
            $_SESSION ['updated'] = true;
            header('location: /resort/src/room_type_modify.php');
        }
    }

    //delete room facilities
    if(isset($_GET['type'])) {
        $type = $_GET['type'];
        //open db
        include_once 'db.php';
        $sql = "DELETE FROM room_facilities WHERE room_type=?;";
        //create pp statement
        $stmt = mysqli_stmt_init($conn);
        //prepare pp statement
        if(!mysqli_stmt_prepare($stmt,$sql)) {
            throw new Exception(mysqli_error($conn));
            //close db
            mysqli_close($conn);
            exit();
        } else {
            //bind parameters to placeholder
            mysqli_stmt_bind_param($stmt, "s", $type);
            //execute stmt
            mysqli_stmt_execute($stmt);
            //close db
            mysqli_close($conn);
            $_SESSION['deleted'] = true;
            header('location: /resort/src/room_type_modify.php');
        }
    }

    //remove room type image
    if(isset($_GET['image'])) {
        $id = $_GET['rid']; //rid beacuse conflict with id get ! :(
        $type = $_GET['rtype'];
        $desc = $_GET['desc'];
        $image = $_SERVER['DOCUMENT_ROOT'].'/resort/src/'.$_GET['image'];
        if(file_exists($image)) {
            unlink($image);
            $_SESSION['img-deleted'] = true;
            header('location: /resort/src/room_type_edit.php?ID='.$id.'&type='.$type.'&desc='.$desc);
            exit();
        } else {
            echo 'Image not found';
        }
    }

    //add room type images
    if(isset($_POST['add-image'])) { 
        $id = $_POST['id'];       
        $type = $_POST['type'];
        $desc = $_POST['description'];
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
        $_SESSION['img-added'] = true;
        header('location: /resort/src/room_type_edit.php?ID='.$id.'&type='.$type.'&desc='.$desc);
        exit();
    }
    
}
catch (Exception $e) {
    $e->getMessage();
}