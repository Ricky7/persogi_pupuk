<?php
    error_reporting(0);
    require_once "class/Database.php";
    require_once "class/Produk.php";

    $produk = new Produk($db);
?>
<?php
    include "header.php";
?>
<div class="container"> 
  <div class="row">
    <?php

        if(isset($_GET['kelas']) && !empty($_GET['kelas'])) {

            $kelas = $_GET['kelas'];

            $query = "SELECT * FROM tbl_indekos WHERE kelas='{$kelas}'";

            $records_per_page=8;
            $newquery = $produk->paging($query,$records_per_page);
            $produk->index($newquery);
        } else if ($datas['status'] == 'Unverified'){
            $query = "SELECT * FROM produk WHERE stok > 0 AND jenis = 'Non Subsidi'";       
            $records_per_page=8;
            $newquery = $produk->paging($query,$records_per_page);
            $produk->index($newquery);
          
        } else {
            $query = "SELECT * FROM produk WHERE stok > 0";       
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
