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

    if(isset($_POST['bayar'])) {

        $imgFile = $_FILES['bukti']['name'];
        $tmp_dir = $_FILES['bukti']['tmp_name'];
        $imgSize = $_FILES['bukti']['size'];


        if(empty($imgFile)) {
            $errMsg = "Please select image File..";
        } else {
            $upload_dir = 'assets/img_bukti/'; // upload directory
 
            $imgExt = strtolower(pathinfo($imgFile,PATHINFO_EXTENSION)); // get image extension
          
            // valid image extensions
            $valid_extensions = array('jpeg', 'jpg', 'png', 'gif'); // valid extensions
          
            // rename uploading image
            $userpic = rand(1000,1000000).".".$imgExt;

            // allow valid image file formats
            if(in_array($imgExt, $valid_extensions)){   
                // Check file size '5MB'
                if($imgSize < 5000000)    {
                    move_uploaded_file($tmp_dir,$upload_dir.$userpic);
                } else {
                    $errMSG = "Sorry, your file is too large.";
                }
            } else {
                $errMSG = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";  
            }
        }

        if(!isset($errMsg)) {

            try {
              $produk->insertBayar(array(
                'jenis_bank' => $_POST['jenis_bank'],
                'no_rek' => $_POST['no_rek'],
                'nama_rek' => $_POST['nama_rek'],
                'nilai_transfer' => $_POST['nilai_transfer'],
                'ket_transfer' => $_POST['ket_transfer'],
                'bukti_bayar' => $userpic,
                'status_order' => 'Pending'
              ), $_POST['id']);
              header("location: order_pending.php");
            } catch (Exception $e) {
              die($e->getMessage());
            }
        }

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
          <h3 class="panel-title">Belanjaan Pending</h3>
        </div>

          <div class="table-responsive">
            <table class="table table-hover">
              <thead>
                <th>#</th>
                <th>Tanggal Pesanan</th>
                <th>Jarak</th>
                <th>Total Biaya</th>
                <th>Status Pesanan</th>
                <th colspan='2'><center>Opsi</center></th>
              </thead>
              <tbody>
                <?php

                  $tb = "SELECT * FROM tbl_order WHERE (id_customer, status_order) IN (({$datas['id_customer']}, 'Pending'), ({$datas['id_customer']}, 'Belum Bayar')) ORDER BY status_order ASC";
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
                                  <td>
                                    <?php 
                                    if($torder['status_order'] == 'Pending') {
                                      ?>
                                      <p><i>Menunggu</i></p>
                                      <?php
                                    } else {
                                      ?>
                                      <a href="#" class="bayar_button"
                                          data-toggle="modal" data-target="#myModalBayar"
                                          data-id="<?php print($torder['id_order']); ?>">
                                          Bayar
                                      </a>
                                      <?php
                                    }
                                    ?>
                                  </td>
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

<!-- Modal Bayar-->
<div id="myModalBayar" class="modal" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Bayar</h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <div class="table-responsive">
              <table class="table table-hover">
                <thead>
                  <th>Atas Nama</th>
                  <th>Type Bank</th>
                  <th>NO Rek</th>
                </thead>
                <tbody>
                  <tr>
                    <td>Simon Dachi</td>
                    <td>BRI</td>
                    <td>03842020844</td>
                  </tr>
                  <tr>
                    <td>Simon Dachi</td>
                    <td>BCA</td>
                    <td>046373947393</td>
                  </tr>
                  <tr>
                    <td>Simon Dachi</td>
                    <td>MANDIRI</td>
                    <td>940340343355</td>
                  </tr>
                  <tr>
                    <td>Simon Dachi</td>
                    <td>BNI</td>
                    <td>03403403483</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <hr>
        <div class="row">
          <form method="post" enctype="multipart/form-data">
          <div class="col-md-6">
            <div class="form-group" >
              <small>Jenis Bank</small>
              <input type="hidden" class="form-control id" name="id">
              <select class="form-control" name="jenis_bank" required>
                <option></option>
                <option value="BCA">BCA</option>
                <option value="BNI">BNI</option>
                <option value="BRI">BRI</option>
                <option value="MANDIRI">MANDIRI</option>
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group" >
              <small>No Rekening</small>
              <input type="number" class="form-control" name="no_rek" required>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group" >
              <small>Nama Pemilik Rekening</small>
              <input type="text" class="form-control" name="nama_rek" required>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group" >
              <small>Nilai Transfer</small>
              <input type="number" class="form-control" name="nilai_transfer" required>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group" >
              <small>Keterang Tambahan</small>
              <textarea class="form-control" name="ket_transfer"></textarea>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group" >
              <small>Bukti Pembayaran</small>
              <input type="file" class="form-control" name="bukti" required>
            </div>
          </div>
        </div>          
      </div>
      <div class="modal-footer">
        <button type="submit" name="bayar" class="btn btn-success btn-sm">Submit</button>
        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
        </form>
      </div>
    </div>

  </div>
</div>

<script>
$(document).on( "click", '.bayar_button',function(e) {

    var id = $(this).data('id');
    $(".id").val(id);
  
});
$(".modal").on("hide.bs.modal", function(){
    location.reload();
});
</script>
<?php
  include "footer.php";
?>
