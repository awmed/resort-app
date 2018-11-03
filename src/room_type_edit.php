<?php
//start session
session_start();

if(!isset($_SESSION['user'])) {
    header('location: sign_in.php');
    exit();    
}

if(!isset($_GET['ID'])) {
    header('location: room_type_modify.php');
    exit();
}

//display alert if data already exists
if(isset($_SESSION['exist'])) {
    echo 
    '<script>                     
        setTimeout(function() {
            swal({
                title: "Room Type Already Exist",
                text: "Please Retry !",
                type: "warning"
            })
        }, 100);
    </script>';
    //unset session
    unset($_SESSION['exist']);
}

//display alert if fields empty
if(isset($_SESSION['empty'])) {
    echo 
    '<script>                     
        setTimeout(function() {
            swal({
                title: "Fields Cannot Be Empty",
                text: "Retry !",
                type: "warning"
            })
        }, 100);
    </script>';
    
    //unset session
    unset($_SESSION['empty']);
} 

//display delete message
if(isset($_SESSION['img-deleted'])) {
    echo 
    '<script>                     
        setTimeout(function() {
            swal({
                title: "Success",
                text: "Image Deleted Successfully !",
                type: "success"
            })
        }, 100);
    </script>';
    //unset session
    unset($_SESSION['img-deleted']);
}

//display delete message
if(isset($_SESSION['img-added'])) {
    echo 
    '<script>                     
        setTimeout(function() {
            swal({
                title: "Success",
                text: "Image Added Successfully !",
                type: "success"
            })
        }, 100);
    </script>';
    //unset session
    unset($_SESSION['img-added']);
}
?>
<?php require 'inc/_global/config.php'; ?>
<?php require 'inc/backend/config.php'; ?>
<?php
// Codebase - Page specific configuration
$cb->l_header_fixed     = false;
$cb->l_header_style = 'inverse';
$cb->l_sidebar_inverse  = true;
?>
<?php require 'inc/_global/views/head_start.php'; 
	echo '<title> EDIT ROOM TYPE </title>'
?>


<?php require 'inc/_global/views/head_end.php'; ?>
<?php require 'inc/_global/views/page_start.php'; ?>

<!-- Page Content -->
<div class="content">
    <nav class="breadcrumb bg-white push">
        <a class="breadcrumb-item" href="dashboard.php">Dashboard</a>
        <span class="breadcrumb-item active">EDIT ROOM TYPE</span>
    </nav>
    <div class="block">
        <div class="block-header block-header-default">
            <h2 class="block-title" style="font-size:25px"><strong>EDIT ROOM TYPE</strong></h2>
        </div>
        <div class="block-content">
            <div class="row justify-content-center py-20">
                <div class="col-xl-5">
                    <form action="inc\backend\database\room_type_edit.php" method="post" enctype="multipart/form-data">                        
                        <!-- row -->
                        <div class="form-group">
                            <label for="example-text-input">Room Type ID</label>
                            <?php 
                            $ID = $_GET['ID'];
                            echo '<input type="text" class="form-control" id="" name="id" placeholder="" required="true" value="'.$ID.'" readonly="readonly">'
                            ?>                            
                        </div>
                        <!-- end row -->                       
                        <!-- row -->
                        <div class="form-group">
                            <label for="example-text-input">Room Type</label>
                            <?php
                            $type = $_GET['type'];
                            echo '<input type="text" class="form-control" id="" name="type" placeholder="Enter Type" required="true" value="'.$type.'">'
                            ?>                            
                        </div>
                        <!-- end row -->
                        <!-- row -->
                        <div class="form-group">
                            <label for="example-email-input">Description</label>
                            <?php
                            $desc = $_GET['desc'];
                            echo '<textarea class="form-control" id="" name="description" rows="5" placeholder="Enter Description" required="true">'.$desc.'</textarea>'                               
                            ?>
                        </div>
                        <!-- end row -->
                        <br>    
                        <!-- row -->
                        <div class="form-group">
                            <button type="submit" id="submit" name="submit" class="btn btn-alt-warning btn-rounded" data-toggle="click-ripple"><i class="fa fa-edit mr-5"></i> Update Room Type </button>
                        </div>
                        <!-- end row -->                       
                    <!-- </form> -->
                    <!-- end form -->
                </div>
                <!-- end col xl-6 -->
            </div>
            <!-- end row justify -->
        </div>
        <!-- end block content -->
        <div class="block-content">
            <div class="row justify-content-center py-20">
                <div class="col-xl-11">
                    <!-- <form action="inc\backend\database\room_type_edit.php" method="post" ec>                             -->
                        <h2 class="block-title"><strong>EDIT ROOM IMAGES</strong></h2> 
                        <hr><br>                 
                        <div class="row items-push js-gallery js-gallery-enabled">
                            <?php 
                            //directory path
                            $dir = $_GET['type'];
                            $dir_path = 'assets/uploads/room-image/'.$dir.'/';
                            $extension_array = array('jpg','png','jpeg');
                            
                            if(is_dir($dir_path)) {
                                $images = scandir($dir_path);

                                for($i=0; $i<count($images); $i++) {
                                    //exclude . and ..
                                    if($images[$i] != '.' && $images[$i] != '..' ) {
                                        //get file extension
                                        $image = pathinfo($images[$i]);
                                        $extension = $image['extension'];

                                        //check file extension
                                        if(in_array($extension, $extension_array)) {                                   
                                            //echo "<img class='img-fluid options-item' src='$dir_path$images[$i]' alt=''>";
                                            echo "
                                            <div class='col-md-4 animated fadeIn'>
                                                <div class='options-container fx-item-zoom-in fx-overlay-slide-left'>
                                                    <img class='img-fluid options-item' src='$dir_path$images[$i]' alt=''>               
                                                    <div class='options-overlay bg-primary-dark-op'>
                                                        <div class='options-overlay-content'>
                                                            <a class='btn btn-sm btn-rounded btn-noborder btn-alt-primary min-width-75 img-lightbox' href='$dir_path$images[$i]'>
                                                                <i class='fa fa-search-plus'></i> View
                                                            </a>
                                                            <button type='submit' id='submit' name='remove' class='btn btn-sm btn-rounded btn-noborder btn-alt-danger min-width-75' data-toggle='click-ripple'> 
                                                                <a style='color:#ff6666' href='inc/backend/database/room_type_edit.php?image=$dir_path$images[$i]&rid=$ID&rtype=$type&desc=$desc'>
                                                                    <i class='fa fa-remove'></i> Delete
                                                                </a>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>    
                                            ";                                                    
                                        }
                                    }
                                }
                            }
                            ?>
                        </div>
                        <!-- end gallery -->
                        <br><br>
                        <!-- row -->
                        <div class="form-group row">
                            <label class="col-12">Room Images (JPG/PNG) [Multiple] - Max 2MB</label>
                            <div class="col-md-5 col-xs-12">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="example-file-multiple-input-custom" name="image[]" multiple >
                                    <label class="custom-file-label" for="example-file-multiple-input-custom">Choose files</label>
                                </div>
                            </div>
                        </div>
                        <!-- end row -->
                        <div class="form-group">
                            <button type="submit" id="submit" name="add-image" class="btn btn-alt-primary btn-rounded" data-toggle="click-ripple"><i class="fa fa-plus mr-5"></i> Add Image </button>
                        </div>
                        <!-- end row -->  
                    </form>
                    <!-- end form -->
                </div>
                <!-- end col xl-6 -->
            </div>
            <!-- end row justify -->
        </div>
        <!-- end block content -->
    </div>
    <!-- end block -->
</div>
<!-- END Page Content -->

<?php require 'inc/_global/views/page_end.php'; ?>
<?php require 'inc/_global/views/footer_start.php'; ?>
<?php require 'inc/_global/views/footer_end.php'; ?>