<?php
  require_once "../class/Database.php";
  require_once "../class/Admin.php";

  $admin = new Admin($db);

  $datas = $admin->getAdmin();

  if(isset($_POST['edit'])) {
    
    try {
        $admin->ubahStatus($_POST['status'], $_POST['id']);
        header("Location: a_index.php");
    } catch (Exception $e) {
      die($e->getMessage());
    }
  }

  if(isset($_GET['search'])){

      $search = $_GET['search'];

    }

?>
<?php
  include "a_header.php";
?>
<div class="container">
  <div class="row">
    <form class="navbar-form navbar-left" method="get" action="a_index.php">
      <div class="form-group">
        <input type="text" class="form-control" name="search" placeholder="Search">
      </div>
      <button type="submit" class="btn btn-default">Search</button>
    </form>
    <div class="col-md-12">
      
      <div class="panel panel">
        <div class="panel-heading">
          <h3 class="panel-title">Anggota</h3>
        </div>

          <center style="padding-top:10px;">
            <button type="button" class="btn btn-sm btn-add" data-toggle="modal" data-target="#myModal">Reset Kuota Subsidi</button>
          </center>

          <div class="table-responsive">
            <table class="table table-hover">
              <thead>
                <th>Nama</th>
                <th>Username</th>
                <th>Status</th>
                <th>No Kontak</th>
                <th colspan="2">Opsi</th>
              </thead>
              <tbody>
                <?php

                  if(isset($_GET['search'])){

                      $search = $_GET['search'];

                      $query = "SELECT * FROM customer WHERE username LIKE '%{$search}%'";

                      $records_per_page=10;
                      $newquery = $admin->paging($query,$records_per_page);
                      $admin->listAnggota($newquery);

                  } else {

                      $query = "SELECT * FROM customer";       
                      $records_per_page=10;
                      $newquery = $admin->paging($query,$records_per_page);
                      $admin->listAnggota($newquery);
                  }
                  
                 ?>
                 <tr>
                    <td colspan="8" align="center">
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
        <h4 class="modal-title">Reset Kuota Subsidi</h4>
      </div>
      <div class="modal-body">
        <center><a href="a_reset.php" class="btn btn-success btn-sm" style="width:150px;height:90px;"><h3>Reset</h3></a></center>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
<!-- //Modal -->

<!-- Modal Member-->
<div id="myModalMember" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Edit Status</h4>
      </div>
      <div class="modal-body">
        <form method="post" enctype="multipart/form-data">
          <div class="form-group" >
            <small>Username</small>
            <input type="hidden" class="form-control id" name="id">
            <input type="text" class="form-control username" readonly>
          </div>
          <div class="form-group" >
            <small>Status Member</small>
            <select class="form-control status" name="status" required>
              <option></option>
              <option value="Verified">Verified</option>
              <option value="Unverified">Unverified</option>
            </select>
          </div>
      </div>
      <div class="modal-footer">
        <button type="submit" name="edit" class="btn btn-success btn-sm">Submit</button>
        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
        </form>
      </div>
    </div>

  </div>
</div><!-- //Modal Member-->

<script type="text/javascript">
$('#confirm-delete').on('show.bs.modal', function(e) {
    $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
    $(this).find('.btn-ok').attr('nama', $(e.relatedTarget).data('nama'));
    
    $('.debug-url').html('Delete : <strong>' + $(this).find('.btn-ok').attr('nama') + '</strong>');

});
</script>
<script>
$(document).on( "click", '.member_button',function(e) {

    var username = $(this).data('username');
    var status = $(this).data('status');
    var id = $(this).data('id');
    var option = $('<option value="'+status+'" selected>'+status+'</option>');

    $(".id").val(id);
    $(".username").val(username);
    $('.status').append(option);
  
});
$(".modal").on("hide.bs.modal", function(){
    location.reload();
});
</script>