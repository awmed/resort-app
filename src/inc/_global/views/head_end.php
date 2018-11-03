<?php
/**
 * head_end.php
 *
 * Author: pixelcave
 *
 * (continue) The first block of code used in every page of the template
 *
 * The reason we separated head_start.php and head_end.php is for enabling
 * us to include between them extra plugin CSS files needed only in specific pages
 *
 */
?>

    <!-- Codebase framework -->
    <link rel="stylesheet" id="css-main" href="<?php echo $cb->assets_folder; ?>/css/codebase.min.css">
    <!-- sweet alert -->
    <?php $cb->get_css('js/plugins/sweetalert2/sweetalert2.min.css'); ?>

    <!-- You can include a specific file from css/themes/ folder to alter the default color theme of the template. eg: -->
    <!-- <link rel="stylesheet" id="css-theme" href="assets/css/themes/flat.min.css"> -->
    <?php if ($cb->theme) { ?>
    <link rel="stylesheet" id="css-theme" href="<?php echo $cb->assets_folder; ?>/css/themes/<?php echo $cb->theme; ?>.min.css">
    <?php } ?>
    <style>
        * {
            font-family: 'Quicksand', sans-serif;
        }
    </style>
    <!-- END Stylesheets -->
</head>
<body>