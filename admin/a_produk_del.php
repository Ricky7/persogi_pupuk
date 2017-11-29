<?php  
  
    require_once "../class/Database.php";
    require_once "../class/Admin.php";

    $admin = new Admin($db);

    if(isset($_REQUEST['id'])) {

      try {
          $admin->delProduk($_REQUEST['id']);
          header("Location: a_produk.php");
      } catch (Exception $e) {
          die($e->getMessage());
      }
  }

?>