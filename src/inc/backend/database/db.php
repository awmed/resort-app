<?php
try {
    $dbServerName = "localhost";
    $dbUserName = "root";
    $dbPassword = "";
    $dbName = "rms_db";

    $conn = @mysqli_connect($dbServerName,$dbUserName,$dbPassword,$dbName);
    // or die ("<p> Database error</p><p>'.mysqli_error().'</p>")
    if (!$conn)
    {
        // echo '<script type="text/javascript">';
        // echo 'setTimeout(function () { swal("Failed","Database Connection Failed","success");';
        // echo '}, 1000);</script>';
        throw new Exception(mysqli_connect_error());
        exit();
    }
}
catch (Exception $e) {
    exit($e->getMessage());
}