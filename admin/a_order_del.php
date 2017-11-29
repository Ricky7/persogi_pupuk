<?php  
  
    require_once "../class/Database.php";
    require_once "../class/Admin.php";
    require_once "../class/Produk.php";

    $admin = new Admin($db);
    $datas = $admin->getAdmin();

    $produk = new Produk($db);

    if(isset($_REQUEST['id'])) {

      try {
          $produk->hapusOrder($_REQUEST['id']);
          $produk->hapusOrderDetail($_REQUEST['id']);
          header("Location: a_belum_bayar.php");
      } catch (Exception $e) {
          die($e->getMessage());
      }
  }

?>