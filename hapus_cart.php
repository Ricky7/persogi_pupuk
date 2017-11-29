<?php  
  
    require_once "class/Database.php";
    require_once "class/Buyer.php";
    require_once "class/Produk.php";

    $buyer = new Buyer($db);
    $datas = $buyer->getUser();

    $produk = new Produk($db);

    if(isset($_REQUEST['id'])) {

      try {
          $produk->delCart($_REQUEST['id']);
          header("Location: cart.php");
      } catch (Exception $e) {
          die($e->getMessage());
      }
  }

?>