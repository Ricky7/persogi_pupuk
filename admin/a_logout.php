<?php  
  
    require_once "../class/Database.php";
    require_once "../class/Admin.php";


    $admin = new Admin($db);

    // Logout! hapus session user
    $admin->logout();

    // Redirect ke login
    header('location: a_login.php');
 ?>