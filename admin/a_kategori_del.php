<?php  
  
    require_once "../class/Database.php";
    require_once "../class/Admin.php";

    $admin = new Admin($db);

    if(isset($_REQUEST['id'])) {

      try {
          $admin->delKategori($_REQUEST['id']);
          header("Location: a_kategori.php");
      } catch (Exception $e) {
          die($e->getMessage());
      }
  }

?>