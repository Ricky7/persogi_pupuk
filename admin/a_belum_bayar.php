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
          <h3 class="panel-title">Order Belum Dibayar</h3>
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
                  $query = "SELECT * FROM tbl_order INNER JOIN customer ON (tbl_order.id_customer=customer.id_customer) WHERE tbl_order.status_order='Belum Bayar' ORDER BY tbl_order.tgl_order ASC";       
                  $records_per_page=10;
                  $newquery = $produk->paging($query,$records_per_page);
                  $produk->listBelum($newquery);
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

<!-- Modal Delete -->
<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Delete Pesanan</h4>
            </div>
        
            <div class="modal-body">
                <p>Are you sure ..?</p>
                <p class="debug-url"></p>
            </div>
            
            <div class="modal-footer">
              <a class="btn btn-danger btn-ok">Delete</a>
              <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal Delete -->

<script type="text/javascript">
$('#confirm-delete').on('show.bs.modal', function(e) {
    $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
    $(this).find('.btn-ok').attr('nama', $(e.relatedTarget).data('nama'));
    
    $('.debug-url').html('Delete : <strong>' + $(this).find('.btn-ok').attr('nama') + '</strong>');

});
</script>
