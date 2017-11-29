<?php
  require_once "../class/Database.php";
  require_once "../class/Admin.php";

  $admin = new Admin($db);

  $datas = $admin->getAdmin();

?>
<?php
  include "a_header.php";
?>
<div class="container">
  <div class="row">
    <div class="col-md-6 col-md-offset-3">
      
      <div class="panel panel">
        <div class="panel-heading">
          <h3 class="panel-title">Laporan Penjualan</h3>
        </div>

          <form method="post" action="laporan_transaksi.php">
            <h4 align="center">Input Tanggal</h4>
            <div class="form-group" style="padding-top:10px;">
              <input type="date" name="tgl_awal" class="form-control" aria-describedby="basic-addon1">
            </div>

            <div class="form-group" style="padding-top:10px;">
              <input type="date" name="tgl_akhir" class="form-control" aria-describedby="basic-addon1">
            </div>
            <center>
              <button type="submit" name="submit" class="btn btn-primary">SUbmit</button>
            </center>
          </form>


        </div>
    </div>
  </div>
</div>
