<?php  
  
    require_once "../class/Database.php";
    require_once "../class/Admin.php";

    $admin = new Admin($db);

    try {
        $admin->reset($_REQUEST['id']);
        header("Location: a_index.php");
    } catch (Exception $e) {
        die($e->getMessage());
    }

?>