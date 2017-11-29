<?php
    error_reporting(0);
    require_once "class/Database.php";
    require_once "class/Produk.php";

    $produk = new Produk($db);

    if(isset($_GET['search'])){

      $search = $_GET['search'];

    }
?>
<?php
    include "header.php";
?>
<div class="container">
  <h3><span>Hasil Pencarian</span></h3>
  <div class="row">
    <?php

        if ($datas['status'] == 'Verified') {

            $query = "SELECT * FROM produk WHERE nama_produk LIKE '%{$search}%' AND stok > 0";      
            $records_per_page=8;
            $newquery = $produk->paging($query,$records_per_page);
            $produk->index($newquery);

        } else if ($datas['status'] == 'Unverified'){

            $query = "SELECT * FROM produk WHERE nama_produk LIKE '%{$search}%' AND stok > 0 AND jenis = 'Non Subsidi'";       
            $records_per_page=8;
            $newquery = $produk->paging($query,$records_per_page);
            $produk->index($newquery);
          
        } else {

            $query = "SELECT * FROM produk WHERE nama_produk LIKE '%{$search}%' AND stok > 0";       
            $records_per_page=8;
            $newquery = $produk->paging($query,$records_per_page);
            $produk->index($newquery);
        }
        
    ?>
      <center>
          <div class="pagination-wrap col-md-12">
          <?php $produk->paginglink($query,$records_per_page); ?>
          </div>
      </center>
    
  </div>
</div><br>

<?php
  include "footer.php";
?>
