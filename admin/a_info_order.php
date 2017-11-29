<?php
  require_once "../class/Database.php";
  require_once "../class/Admin.php";
  require_once "../class/Produk.php";

  $admin = new Admin($db);
  $datas = $admin->getAdmin();

  $produk = new Produk($db);

  if(isset($_GET['id'])){

    $id_order = $_GET['id'];
    $data_order = $produk->getOrderData($id_order);

  }

  if(isset($_POST['konfirmasi'])) {

      try {
        $produk->ubahStatusOrder($id_order, $_POST['status_order'], $datas['id_admin']);
        switch ($data_order['status_order']) {
          case 'Pending':
            header("location: a_bayar.php");
            break;
          
          case 'Bayar':
            header("location: a_dikirim.php");
            break;

          case 'Dikirim':
            header("location: a_selesai.php");
            break;
        }
      } catch (Exception $e) {
      die($e->getMessage());
      }
  }

  $jumlah_desimal = "0";
  $pemisah_desimal = ",";
  $pemisah_ribuan = ".";

?>
<?php
  include "a_header.php";
?>
<div class="container">
  <div class="row">
    <?php

        switch ($data_order['status_order']) {

          case 'Pending':
            ?>
            <div class="panel panel col-md-4 col-md-offset-4">
              <div class="panel-heading">
                <h3 class="panel-title">Aksi</h3>
              </div>
              <form method="post">
                <div class="form-group" style="padding-top:10px;">
                  <small>Konfirmasi Pemesanan</small>
                  <select name="status_order" class="form-control" required>
                    <option></option>
                    <option value="Bayar">DIBAYAR</option>
                  </select><br>
                  <center>
                    <button type="submit" name="konfirmasi" class="btn btn-info">Submit</button>
                  </center>
                </div>
              </form>
            </div>
            <?php
            break;

          case 'Bayar':
            ?>
            <div class="panel panel col-md-4 col-md-offset-4">
              <div class="panel-heading">
                <h3 class="panel-title">Aksi</h3>
              </div>
              <form method="post">
                <div class="form-group" style="padding-top:10px;">
                  <small>Konfirmasi Pemesanan</small>
                  <select name="status_order" class="form-control" required>
                    <option></option>
                    <option value="Dikirim">Dikirim</option>
                  </select><br>
                  <center>
                    <button type="submit" name="konfirmasi" class="btn btn-primary">Kirim</button>
                  </center>
                </div>
              </form>
            </div>
            <?php
            break;

          case 'Dikirim':
            ?>
            <div class="panel panel col-md-4 col-md-offset-4">
              <div class="panel-heading">
                <h3 class="panel-title">Aksi</h3>
              </div>
              <form method="post">
                <div class="form-group" style="padding-top:10px;">
                  <small>Konfirmasi Pemesanan</small>
                  <select name="status_order" class="form-control" required>
                    <option></option>
                    <option value="Selesai">Terkirim</option>
                  </select><br>
                  <center>
                    <button type="submit" name="konfirmasi" class="btn btn-primary">Kirim</button>
                  </center>
                </div>
              </form>
            </div>
            <?php
            break;

          case 'Selesai':
            ?>
            <div class="panel panel col-md-4 col-md-offset-4">
              <div class="panel-heading">
                <h3 class="panel-title">Aksi</h3>
              </div>
              <center>
                <h4>Terkirim : <?php echo $data_order['tgl_terkirim'] ?></h4>
              </center>
            </div>
            <?php
            break;
        }

      ?>
  </div>
  <div class="row">
    <div class="col-md-6">
      
      <div class="panel panel">
        <div class="panel-heading">
          <h3 class="panel-title">Info Produk</h3>
        </div>

        <div class="table-responsive">
          <table class="table table-hover">
            <thead>
              <th>Pupuk</th>
              <th>Nama</th>
              <th>Jenis</th>
              <th>Qty</th>
              <th>Harga</th>
            </thead>
            <tbody>
              <?php

                $tb = "SELECT *, order_detail.harga as tharga FROM order_detail INNER JOIN produk ON (order_detail.id_produk=produk.id_produk) WHERE order_detail.id_order={$id_order}";
                $tbl = $db->prepare($tb);
                $tbl->execute();

                if($tbl->rowCount()>0)
                {
                    while($torder=$tbl->fetch(PDO::FETCH_ASSOC))
                    {
                       ?>
                            <tr>
                                <td>
                                  <img src="../assets/gambar_produk/<?php print($torder['gambar']); ?>" class="produk">
                                </td>
                                <td><?php echo $torder['nama_produk'] ?></td>
                                <td><?php echo $torder['jenis'] ?></td>
                                <td><?php echo $torder['jumlah'] ?></td>
                                <td><?php print('Rp.'.number_format($torder['tharga'],$jumlah_desimal, $pemisah_desimal, $pemisah_ribuan)) ?></td>
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

    <div class="col-md-6">
      <div class="panel panel">
        <div class="panel-heading">
          <h3 class="panel-title">Info Pemesanan</h3>
        </div>

      <table class="table table-bordered" style="text-align:center;">
        <thead>
          <th style="text-align:center;">Data</th>
          <th style="text-align:center;">Informasi</th>
        </thead>
        <tr>
          <td>Alamat</td>
          <td><?php echo $data_order['alamat'] ?></td>
        </tr>
        <tr>
          <td>Tanggal Pesanan</td>
          <td><?php echo $data_order['tgl_order'] ?></td>
        </tr>
        <tr>
          <td>Keterangan</td>
          <td><?php echo $data_order['desk_order'] ?></td>
        </tr>
        <tr>
          <td>Ongkos/jarak</td>
          <td><?php echo "Rp. ".number_format($data_order['ongkos'],$jumlah_desimal, $pemisah_desimal, $pemisah_ribuan).' / '.$data_order['jarak'].' km' ?></td>
        </tr>
        <tr>
          <td>Data Pemesan</td>
          <td><?php echo $data_order['jenis_bank'].' '.$data_order['nama_rek'].' '.$data_order['no_rek'] ?></td>
        </tr>
        <tr>
          <td>Nilai transfer</td>
          <td><?php echo "Rp. ".number_format($data_order['nilai_transfer'],$jumlah_desimal, $pemisah_desimal, $pemisah_ribuan) ?></td>
        </tr>
        <tr>
          <td>Catatan Transfer</td>
          <td><?php echo $data_order['ket_transfer'] ?></td>
        </tr>
        <tr>
          <td>Status Pesanan</td>
          <td>
            <?php 
              switch ($data_order['status_order']) {
                  case 'Pending':
                    echo '<font color="red">Menunggu Konfirmasi</font>';
                    break;
                  case 'Bayar':
                    echo '<font color="red">Lunas</font>';
                    break;
                  case 'Dikirim':
                    echo '<font color="red">Sedang dikirim</font>';
                    break;
                  case 'Selesai':
                    echo '<font color="red">Telah diterima</font>';
                    break;
              }
            ?>
          </td>
        </tr>
        <tr>
            <td>Bukti Transfer</td>
            <td><img src="../assets/img_bukti/<?php echo $data_order['bukti_bayar'] ?>" class="img-responsive"></td>
        </tr>
      </table>
    </div>
  </div>
</div>
<br><br>

