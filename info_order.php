<?php
    require_once "class/Database.php";
    require_once "class/Buyer.php";
    require_once "class/Produk.php";

    $buyer = new Buyer($db);
    $datas = $buyer->getUser();

    $produk = new Produk($db);

    $jumlah_desimal = "0";
    $pemisah_desimal = ",";
    $pemisah_ribuan = ".";

    if(isset($_REQUEST['slug']))
    {
       $id_order = $_REQUEST['slug'];
       $data_order = $produk->getOrderData($id_order);
    }

?>
<?php
    include "header.php";
?>
<div class="container">    
  <div class="row">
    <div class="col-md-12">
      
      <div class="panel panel">
        <div class="panel-heading">
          <h3 class="panel-title">Produk yang dibeli</h3>
        </div>

          <div class="table-responsive">
            <table class="table table-hover">
              <thead>
                <th>Produk</th>
                <th>Nama</th>
                <th>Jenis</th>
                <th>Jumlah</th>
                <th>Harga</th>
              </thead>
              <tbody>
                <?php

                  $tb = "SELECT * FROM produk INNER JOIN order_detail ON (produk.id_produk=order_detail.id_produk) WHERE order_detail.id_order={$id_order}";
                  $tbl = $db->prepare($tb);
                  $tbl->execute();
                  $total = 0;
                  if($tbl->rowCount()>0)
                  {
                      while($torder=$tbl->fetch(PDO::FETCH_ASSOC))
                      {
                         $total += $torder['harga'];
                         ?>
                              <tr>
                                  <td><img src="assets/gambar_produk/<?php echo $torder['gambar'] ?>" style="width:50px;height:50px;"></td>
                                  <td><?php echo $torder['nama_produk'] ?></td>
                                  <td><?php echo $torder['jenis'] ?></td>
                                  <td><?php echo $torder['jumlah'] ?></td>
                                  <td><?php print("Rp. ".number_format($torder['harga'], $jumlah_desimal, $pemisah_desimal, $pemisah_ribuan)); ?></td>
                              </tr>
                         <?php 
                      }
                      ?>
                        <tr>
                          <td colspan="4"><center>Ongkos Kirim</center></td>
                          <td><?php print("Rp. ".number_format($data_order['ongkos'], $jumlah_desimal, $pemisah_desimal, $pemisah_ribuan)); ?></td>
                        </tr>
                        <tr>
                          <td colspan="4"><center>Total Biaya yg harus ditransfer</center></td>
                          <td><?php print("Rp. ".number_format($data_order['ongkos']+$total, $jumlah_desimal, $pemisah_desimal, $pemisah_ribuan)); ?></td>
                        </tr>
                      <?php
                  }
                ?>
              </tbody>
            </table>
          </div>
        </div>
    </div>
  </div>
</div><br>



<?php
  include "footer.php";
?>
