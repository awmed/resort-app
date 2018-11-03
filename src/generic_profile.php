<?php
//start sesssion
session_start();

if(!isset($_SESSION['user'])) {
    header('location: sign_in.php');
    exit();    
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

//display alert if current passowrd wrong
if(isset($_SESSION['wrong'])) {
    echo 
    '<script>                     
        setTimeout(function() {
            swal({
                title: "Invalid Password",
                text: "Re enter the current password !",
                type: "error"
            })
        }, 100);
    </script>';
    
    //unset session
    unset($_SESSION['wrong']);
}

//display alert if password missmatch
if(isset($_SESSION['pass-miss'])) {
echo 
'<script>                     
    setTimeout(function() {
        swal({
            title: "New Password Doesn\'t Match",
            text: "Please Retry !",
            type: "error"
        })
    }, 100);
</script>';

//unset session
unset($_SESSION['pass-miss']);
}

//display alert if data is posted to database
if(isset($_SESSION['success'])) {
    echo 
    '<script>                     
        setTimeout(function() {
            swal({
                title: "Success",
                text: "Password Updated Successfully !",
                type: "success"
            })
        }, 100);
    </script>';
    //unset session
    unset($_SESSION['success']);
}

//display alert if password missmatch
if(isset($_SESSION['no-img'])) {
    echo 
    '<script>                     
        setTimeout(function() {
            swal({
                title: "Warning",
                text: "No Image Uploaded By User",
                type: "warning"
            })
        }, 100);
    </script>';
    
    //unset session
    unset($_SESSION['no-img']);
}

//display alert if image removed
if(isset($_SESSION['removed'])) {
    echo 
    '<script>                     
        setTimeout(function() {
            swal({
                title: "Success",
                text: "User Image Removed Successfully !",
                type: "success"
            })
        }, 100);
    </script>';
    //unset session
    unset($_SESSION['removed']);
}

//display alert if image removed
if(isset($_SESSION['img-success'])) {
    echo 
    '<script>                     
        setTimeout(function() {
            swal({
                title: "Success",
                text: "User Image Added Successfully !",
                type: "success"
            })
        }, 100);
    </script>';
    //unset session
    unset($_SESSION['img-success']);
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
<?php require 'inc/_global/views/head_start.php'; ?>
<?php require 'inc/_global/views/head_end.php'; ?>
<?php require 'inc/_global/views/page_start.php'; ?>

<!-- Page Content -->
<!-- User Info -->
<div class="bg-image bg-image-bottom" style="background-image: url('/resort/src/assets/img/photos/profile.jpeg');">
    <div class="bg-primary-dark-op py-30">
        <div class="content content-full text-center">
            <!-- Avatar -->
            <div class="mb-15">
                <a class="img-link img-link-zoom-in">
                    <?php //$cb->get_avatar(15, '', 96, 'img-thumb'); 
                    if($_SESSION ['imgStatus'] != 1) {
                        echo '<img class="" src="assets/uploads/user-image/default.jpg" alt="" style="border-radius:100px;width:200px;height:200px;;">';
                    }
                    if($_SESSION ['imgStatus'] == 1) {
                        $imagename = "assets/uploads/user-image/username-".$_SESSION['user']."*"; //select the customer image with any image extension
                        $imageinfo = glob($imagename);
                        $imageExt = explode(".", $imageinfo[0]);
                        $imageActualExt = $imageExt[1];
                        echo '<img class="" src="assets/uploads/user-image/username-'.$_SESSION['user'].'.'.$imageActualExt.'?'.mt_rand().' " alt="" style="border-radius:100px;width:200px;height:200px;object-fit:cover;">';

                    }
                    ?>
                </a>
            </div>
            <!-- END Avatar -->

            <!-- Personal -->
            <h1 class="h3 text-white font-w700 mb-10 text-uppercase"><?php echo $_SESSION['user']; ?></h1>
            <!-- <h2 class="h5 text-white-op">
                Product Manager <a class="text-primary-light" href="javascript:void(0)">@GraphicXspace</a>
            </h2> -->
            <!-- END Personal -->
        </div>
    </div>
</div>
<!-- END User Info -->

<!-- Main Content -->
<div class="content">
    <!-- Page Content -->
    <div class="content">
        <div class="block">
            <div class="block-header block-header-default">
                <h2 class="block-title d-print-none" style="font-size:25px"><strong> Edit Profile </strong></h2>
            </div>
            
            <div class="block-content">
                <div class="row justify-content-center py-20">
                    <div class="col-xl-5">
                        <form action="inc\backend\database\generic_profile.php" method="post" enctype="multipart/form-data">
                            <!-- row -->
                            <div class="form-group">
                                <label for="example-text-input">Change Password</label><hr>
                            </div>
                            <!-- end row -->
                            <!-- row -->
                            <div class="form-group">
                                <label for="example-text-input">Old Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="" name="password-old" placeholder="Enter Old Password">                            
                            </div>
                            <!-- end row -->
                            <!-- row -->
                            <div class="form-group">
                                <label for="example-text-input">New Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="" name="password" placeholder="Enter New Password">                            
                            </div>
                            <!-- end row -->
                            <!-- row -->
                            <div class="form-group">
                                <label for="example-text-input">Renter New Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="" name="password-confirm" placeholder="Enter New Password">                            
                            </div>
                            <!-- end row -->
                            <br>
                            <!-- row -->
                            <div class="form-group">
                                <button type="submit" id="submit" name="change-password" class="btn btn-alt-primary btn-rounded" data-toggle="click-ripple"><i class="fa fa-edit mr-5"></i>Change Password</button>
                            </div>
                            <!-- end row -->   
                            <br>
                            <!-- row -->
                            <div class="form-group">
                                <label for="example-text-input">Change Profile Picture</label><hr>
                            </div>
                            <!-- end row -->
                            <!-- row -->
                            <div class="form-group">
                                <label for="example-text-input">User Image (JPG/PNG) - Max 2MB</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="" name="image">
                                    <label class="custom-file-label" for="example-file-input-custom">Choose Image</label>
                                </div>             
                            </div>
                            <!-- end row -->
                            <br>
                            <!-- row -->
                            <div class="form-group">
                                <button type="submit" id="submit" name="update-image" class="btn btn-alt-primary btn-rounded" data-toggle="click-ripple"><i class="fa fa-edit mr-5"></i> Update Image </button>                                
                                <button type="submit" id="submit" name="remove-image" class="btn btn-alt-danger btn-rounded" data-toggle="click-ripple"> 
                                <a style="color:#ff6666"><i class="fa fa-remove mr-5"></i>Remove Image</a> 
                                </button>
                            </div>
                            <!-- end row -->  
                        </form>
                    </div>
                </div>
            </div>
            <!-- end block content -->
        </div>
        <!-- end block -->
    </div>
    <!-- END Page Content -->
</div>
<!-- END Main Content -->
<!-- END Page Content -->

<?php require 'inc/_global/views/page_end.php'; ?>
<?php require 'inc/_global/views/footer_start.php'; ?>
<?php require 'inc/_global/views/footer_end.php'; ?>