<?php

    require_once "class/Database.php";
    require_once "class/Buyer.php";
    require_once "class/Produk.php";

    $buyer = new Buyer($db);
    $datas = $buyer->getUser();

    $produk = new Produk($db);  

    //Jika tidak login
    if(!$buyer->isUserLoggedIn()){
        header("location: login.php"); //redirect ke login
    }
    
    if(isset($_POST['id'])) {

    	$id = $_POST['id'];
    	$produk->ubahStatusOrder($id, 'Selesai', '1');
        header("Location: order_selesai.php");
    }
    


?>
