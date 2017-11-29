<?php
  require_once "../class/Database.php";
  require_once "../class/Admin.php";
  require_once "../class/Produk.php";

  $admin = new Admin($db);
  $datas = $admin->getAdmin();

  $produk = new Produk($db);

?>
<?php
  include "a_header.php";
?>
<div class="container">
  <div class="row">
    <div class="col-md-8 col-md-offset-2">
      
      <div class="panel panel">
        <div class="panel-heading">
          <h3 class="panel-title">Order Lunas</h3>
        </div>

          <div class="table-responsive">
            <table class="table table-hover">
              <thead>
                <th>#</th>
                <th>Tanggal Order</th>
                <th>Nama Pemesan</th>
                <th>Total Biaya</th>
                <th>Opsi</th>
              </thead>
              <tbody>
                <?php
                  $query = "SELECT * FROM tbl_order INNER JOIN customer ON (tbl_order.id_customer=customer.id_customer) WHERE tbl_order.status_order='Bayar' ORDER BY tbl_order.tgl_order ASC";       
                  $records_per_page=10;
                  $newquery = $produk->paging($query,$records_per_page);
                  $produk->listBayar($newquery);
                 ?>
                 <tr>
                    <td colspan="5" align="center">
                  <div class="pagination-wrap">
                        <?php $produk->paginglink($query,$records_per_page); ?>
                      </div>
                    </td>
                </tr>
              </tbody>
            </table>
          </div>

        </div>
    </div>
  </div>
</div>

