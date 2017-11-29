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

?>
<?php
    include "header.php";
?>
<div class="container">    
  <div class="row">
    <div class="col-md-12">
      
      <div class="panel panel">
        <div class="panel-heading">
          <h3 class="panel-title">Belanjaan Proses</h3>
        </div>

          <div class="table-responsive">
            <table class="table table-hover">
              <thead>
                <th>#</th>
                <th>Tanggal Pesanan</th>
                <th>Jarak</th>
                <th>Total Biaya</th>
                <th>Status Pesanan</th>
                <th>Opsi</th>
              </thead>
              <tbody>
                <?php

                  $tb = "SELECT * FROM tbl_order WHERE (id_customer, status_order) IN (({$datas['id_customer']}, 'Dikirim'), ({$datas['id_customer']}, 'Bayar')) ORDER BY status_order ASC";
                  $tbl = $db->prepare($tb);
                  $tbl->execute();

                  if($tbl->rowCount()>0)
                  {
                      while($torder=$tbl->fetch(PDO::FETCH_ASSOC))
                      {
                        $tb2 = "SELECT SUM(harga) as hargax FROM order_detail WHERE id_order={$torder['id_order']}";
                        $tbl2 = $db->prepare($tb2);
                        $tbl2->execute();
                        $total=$tbl2->fetch(PDO::FETCH_ASSOC);
                         ?>
                              <tr>
                                  <td><?php echo $torder['id_order'] ?></td>
                                  <td><?php echo $torder['tgl_order'] ?></td>
                                  <td><?php echo $torder['jarak'].' km' ?></td>
                                  <td><?php print('Rp.'.number_format($torder['ongkos']+$total['hargax'],$jumlah_desimal, $pemisah_desimal, $pemisah_ribuan)) ?></td>
                                  <td><?php echo $torder['status_order'] ?></td>
                                  <td><a href="#" id="<?php echo $torder['id_order'] ?>" class="ganti">Konfirmasi</a></td>
                                  <td><a href="info_order.php?slug=<?php print($torder['id_order']); ?>">Info</a></td>
                              </tr>
                         <?php 
                      }
                  }

                ?>
              </tbody>
            </table>
          </div>
        </div>
    </div>
  </div>
</div><br>
<script type="text/javascript">
$(function() {
$(".ganti").click(function() {
  var id = $(this).attr("id");
  var dataString = 'id='+ id ;
  var parent = $(this).parent();
  if (confirm('Apakah kamu yakin ingin mengkonfirmasi kiriman ini?'+dataString)) {
    $.ajax({
      type: "POST",
      url: "konfirmasi.php",
      data: dataString,
      cache: false,

      success: function(html)
      {
        // var redirect = 'cart.php';
        // location.href = redirect;
        window.location.reload();
      }
    });
  }
  return false;
  });
});
</script>
<?php
  include "footer.php";
?>
