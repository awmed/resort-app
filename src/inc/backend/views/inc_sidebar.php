<?php
//start session
//session_start();

/*
 Sidebar 

    Helper classes

    Adding .sidebar-mini-hide to an element will make it invisible (opacity: 0) when the sidebar is in mini mode
    Adding .sidebar-mini-show to an element will make it visible (opacity: 1) when the sidebar is in mini mode
        If you would like to disable the transition, just add the .sidebar-mini-notrans along with one of the previous 2 classes

    Adding .sidebar-mini-hidden to an element will hide it when the sidebar is in mini mode
    Adding .sidebar-mini-visible to an element will show it only when the sidebar is in mini mode
        - use .sidebar-mini-visible-b if you would like to be a block when visible (display: block)
 */
?>
<nav id="sidebar">
    <!-- Sidebar Scroll Container -->
    <div id="sidebar-scroll">
        <!-- Sidebar Content -->
        <div class="sidebar-content">
            <!-- Side Header -->
            <div class="content-header content-header-fullrow px-15">
                <!-- Mini Mode -->
                <div class="content-header-section sidebar-mini-visible-b">
                    <!-- Logo -->
                    <span class="content-header-item font-w700 font-size-xl float-left animated fadeIn">
                        <span class="text-dual-primary-dark">c</span><span class="text-primary">b</span>
                    </span>
                    <!-- END Logo -->
                </div>
                <!-- END Mini Mode -->

                <!-- Normal Mode -->
                <div class="content-header-section text-center align-parent sidebar-mini-hidden">
                    <!-- Close Sidebar, Visible only on mobile screens -->
                    <!-- Layout API, functionality initialized in Codebase() -> uiApiLayout() -->
                    <button type="button" class="btn btn-circle btn-dual-secondary d-lg-none align-v-r" data-toggle="layout" data-action="sidebar_close">
                        <i class="fa fa-times text-danger"></i>
                    </button>
                    <!-- END Close Sidebar -->

                    <!-- Logo -->
                    <div class="content-header-item">
                        <a class="link-effect font-w700" href="#">
							<!-- <i class="fa fa-apple text-primary"></i>-->
                            <span class="font-size-xl text-dual-primary-dark">Grand</span><span class="font-size-xl text-primary">Resort</span>
                        </a>
                    </div>
                    <!-- END Logo -->
                </div>
                <!-- END Normal Mode -->
            </div>
            <!-- END Side Header -->

            <!-- Side User -->
            <div class="content-side content-side-full content-side-user px-10 align-parent">
                <!-- Visible only in mini mode -->
                <!-- <div class="sidebar-mini-visible-b align-v animated fadeIn">
                    <?php $cb->get_avatar('15', '', 32); ?>
                </div> -->
                <!-- END Visible only in mini mode -->

                <!-- Visible only in normal mode -->
                <div class="sidebar-mini-hidden-b text-center">
                    <a class="img-link" href="generic_profile.php">
                        <?php 
                        //$cb->get_avatar('15'); 
                        
                        if($_SESSION ['imgStatus'] != 1) {
                            echo '<img class="" src="assets/uploads/user-image/default.jpg" alt="" style="border-radius:80px;width:80px;height:80px;">';
                        }
                        if($_SESSION ['imgStatus'] == 1) {
                            $imagename = "assets/uploads/user-image/username-".$_SESSION['user']."*"; //select the customer image with any image extension
                            $imageinfo = glob($imagename);
                            $imageExt = explode(".", $imageinfo[0]);
                            $imageActualExt = $imageExt[1];
                            echo '<img class="" src="assets/uploads/user-image/username-'.$_SESSION['user'].'.'.$imageActualExt.'?'.mt_rand().' " alt="" style="border-radius:80px;width:80px;height:80px;object-fit:cover;">';

                        }
                                                
                        ?>
                    </a>
                    <ul class="list-inline mt-10">
                        <li class="list-inline-item">
                            <a class="link-effect text-dual-primary-dark font-size-xs font-w600 text-uppercase" href="#">
                            	<?php echo $_SESSION['user']; ?>
                            </a>
                        </li>                        
                    </ul>
                </div>
                <!-- END Visible only in normal mode -->
            </div>
            <!-- END Side User -->

            <!-- Side Navigation -->
            <div class="content-side content-side-full">
                <ul class="nav-main">
                    <?php $cb->build_nav(); ?>
                </ul>
            </div>
            <!-- END Side Navigation -->
        </div>
        <!-- Sidebar Content -->
    </div>
    <!-- END Sidebar Scroll Container -->
</nav>
<!-- END Sidebar -->
