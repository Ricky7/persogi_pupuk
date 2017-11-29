<?php 
	require_once "class/Database.php";
    require_once "class/Buyer.php";

    $buyer = new Buyer($db);
    $datas = $buyer->getUser();

    $buyer->logout();

    // Redirect ke login
    header('location: index.php');
?>