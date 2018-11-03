<?php
//session start
session_start();
session_destroy();

//close db connection
mysqli_close($conn); 

header('location: sign_in.php');