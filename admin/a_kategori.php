<?php
  require_once "../class/Database.php";
  require_once "../class/Admin.php";

  $admin = new Admin($db);

  $datas = $admin->getAdmin();

  if(isset($_POST['submit'])) {
    
      try {
          $admin->insertKategori(array(
            'nama_kategori' => $_POST['nama'],
            'desk_kategori' => $_POST['desk']
          ));
            header("Location: a_kategori.php");
      } catch (Exception $e) {
        die($e->getMessage());
      }
    }

    if(isset($_POST['edit'])) {
    
      try {
          $admin->editKategori(array(
            'nama_kategori' => $_POST['nama'],
            'desk_kategori' => $_POST['desk']
          ), $_POST['id']);
            header("Location: a_kategori.php");
      } catch (Exception $e) {
        die($e->getMessage());
      }
    }
?>
<?php
  include "a_header.php";
?>
<div class="container">
  <div class="row">
    <div class="col-md-6 col-md-offset-3">
      
      <div class="panel panel">
        <div class="panel-heading">
          <h3 class="panel-title">Kategori</h3>
        </div>

          <center style="padding-top:10px;">
            <button type="button" class="btn btn-sm btn-add" data-toggle="modal" data-target="#myModal">Add Kategori</button>
          </center>

          <div class="table-responsive">
            <table class="table table-hover">
              <thead>
                <th>#</th>
                <th>Nama</th>
                <th>Deskripsi</th>
                <th colspan="2" style="text-align:center;">Opsi</th>
              </thead>
              <tbody>
                <?php
                  $query = "SELECT * FROM kategori";       
                  $records_per_page=10;
                  $newquery = $admin->paging($query,$records_per_page);
                  $admin->listKategori($newquery);
                 ?>
                 <tr>
                    <td colspan="5" align="center">
                  <div class="pagination-wrap">
                        <?php $admin->paginglink($query,$records_per_page); ?>
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

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Form Kategori</h4>
      </div>
      <div class="modal-body">
        <form method="post">
          <div class="form-group" >
            <small>Nama Kategori</small>
            <input type="text" class="form-control" name="nama" required>
          </div>
          <div class="form-group" >
            <small>Deskripsi Kategori</small>
            <textarea class="form-control" row="4" name="desk" required></textarea>
          </div>  
      </div>
      <div class="modal-footer">
        <button type="submit" name="submit" class="btn btn-success btn-sm">Submit</button>
        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
        </form>
      </div>
    </div>

  </div>
</div>
<!-- //Modal -->

<!-- Modal Edit-->
<div id="myModalEdit" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Edit Kategori</h4>
      </div>
      <div class="modal-body">
        <form method="post">
          <div class="form-group">
            <small>Nama Kategori</small>
            <input type="hidden" class="id" name="id" required>
            <input type="text" class="form-control nama" name="nama" required>
          </div>
          <div class="form-group">
            <small>Deskripsi Kategori</small>
            <textarea class="form-control desk" row="4" name="desk" required></textarea>
          </div>  
      </div>
      <div class="modal-footer">
        <button type="submit" name="edit" class="btn btn-success btn-sm">Submit</button>
        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
        </form>
      </div>
    </div>

  </div>
</div>
<!-- //Modal Edit-->

<!-- Modal Delete -->
<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Delete Kategori</h4>
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
<script>
$(document).on( "click", '.edit_button',function(e) {

    var nama = $(this).data('nama');
    var desk = $(this).data('desk');
    var id = $(this).data('id');

    $(".id").val(id);
    $(".nama").val(nama);
    $(".desk").val(desk);
  
});
$(".modal").on("hide.bs.modal", function(){
    location.reload();
});
</script>