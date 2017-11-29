<?php
  require_once "../class/Database.php";
  require_once "../class/Admin.php";

  $admin = new Admin($db);

  $datas = $admin->getAdmin();
  $kat_datas = $admin->getKategori();

  if(isset($_POST['submit'])) {
      
      $imgFile = $_FILES['gambar']['name'];
      $tmp_dir = $_FILES['gambar']['tmp_name'];
      $imgSize = $_FILES['gambar']['size'];


      if(empty($imgFile)) {
        $errMsg = "File gambar belum dipilih..";
      } else {
        $upload_dir = '../assets/gambar_produk/'; // upload directory
 
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
              $errMSG = "Maaf, ukuran file anda terlalu besar.";
            }
        } else {
            $errMSG = "Maaf, hanya ekstensi JPG, JPEG, PNG & GIF yang diterima.";  
        }
      }

      date_default_timezone_set('Asia/Jakarta');
      $tanggal = date('Y-m-d H:i:s');
      
      if(!isset($errMsg)) {

        try {
            $admin->insertProduk(array(
              'id_kategori' => $_POST['kategori'],
              'jenis' => $_POST['jenis'],
              'nama_produk' => $_POST['nama'],
              'desk_produk' => $_POST['desk'],
              'harga' => $_POST['harga'],
              'stok' => $_POST['stok'],
              'tgl_update' => $tanggal,
              'gambar' => $userpic
            ));
              header("Location: a_produk.php");
        } catch (Exception $e) {
          die($e->getMessage());
        }
      }
      
    }

    if(isset($_POST['edit'])) {
    
      $imgFile = $_FILES['gambar']['name'];
      $tmp_dir = $_FILES['gambar']['tmp_name'];
      $imgSize = $_FILES['gambar']['size'];

      date_default_timezone_set('Asia/Jakarta');
      $tanggal = date('Y-m-d H:i:s');

      if(empty($imgFile)) {
        
        try {
            $admin->editProduk(array(
              'id_kategori' => $_POST['kategori'],
              'jenis' => $_POST['jenis'],
              'nama_produk' => $_POST['nama'],
              'desk_produk' => $_POST['desk'],
              'harga' => $_POST['harga'],
              'stok' => $_POST['stok'],
              'tgl_update' => $tanggal
            ), $_POST['id']);
              header("Location: a_produk.php");
        } catch (Exception $e) {
          die($e->getMessage());
        }

      } else {
        $upload_dir = '../assets/gambar_produk/'; // upload directory
 
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
              $errMSG = "Maaf, ukuran file anda terlalu besar.";
            }
        } else {
            $errMSG = "Maaf, hanya ekstensi JPG, JPEG, PNG & GIF yang diterima.";  
        }

        if(!isset($errMsg)) {

          try {
              $admin->editProduk(array(
                'id_kategori' => $_POST['kategori'],
                'jenis' => $_POST['jenis'],
                'nama_produk' => $_POST['nama'],
                'desk_produk' => $_POST['desk'],
                'harga' => $_POST['harga'],
                'stok' => $_POST['stok'],
                'tgl_update' => $tanggal,
                'gambar' => $userpic
              ), $_POST['id']);
                header("Location: a_produk.php");
          } catch (Exception $e) {
            die($e->getMessage());
          }
        }
      }
    }
?>
<?php
  include "a_header.php";
?>
<div class="container">
  <div class="row">
    <div class="col-md-12">
      
      <div class="panel panel">
        <div class="panel-heading">
          <h3 class="panel-title">Produk</h3>
        </div>

          <center style="padding-top:10px;">
            <button type="button" class="btn btn-sm btn-add" data-toggle="modal" data-target="#myModal">Add Produk</button>
          </center>

          <div class="table-responsive">
            <table class="table table-hover">
              <thead>
                <th>Pupuk</th>
                <th>Nama</th>
                <th>Kategori</th>
                <th>Jenis</th>
                <th>Harga</th>
                <th>Stok</th>
                <th colspan="2">Opsi</th>
              </thead>
              <tbody>
                <?php
                  $query = "SELECT * FROM produk INNER JOIN kategori ON (produk.id_kategori=kategori.id_kategori) ORDER BY produk.nama_produk ASC";       
                  $records_per_page=6;
                  $newquery = $admin->paging($query,$records_per_page);
                  $admin->listProduk($newquery);
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
        <h4 class="modal-title">Form Produk</h4>
      </div>
      <div class="modal-body">
        <form method="post" enctype="multipart/form-data">
          <div class="form-group" >
            <small>Nama Produk</small>
            <input type="text" class="form-control" name="nama" required>
          </div>
          <div class="form-group" >
            <small>Gambar</small>
            <input type="file" class="form-control" name="gambar" required>
          </div>
          <div class="form-group" >
            <small>Kategori</small>
            <select class="form-control kategori" name="kategori" required>
              <option></option>
              <?php foreach ($kat_datas as $value): ?>
                <option value="<?php echo $value['id_kategori']; ?>"><?php echo $value['nama_kategori']; ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group" >
            <small>Jenis</small>
            <select class="form-control" name="jenis" required>
              <option></option>
              <option value="Subsidi">Subsidi</option>
              <option value="Non Subsidi">Non Subsidi</option>
            </select>
          </div>
          <div class="form-group" >
            <small>Harga</small>
            <input type="number" class="form-control" name="harga" required>
          </div>
          <div class="form-group" >
            <small>Stok</small>
            <input type="number" class="form-control" name="stok" required>
          </div>
          <div class="form-group" >
            <small>Deskripsi Produk</small>
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
        <form method="post" enctype="multipart/form-data">
          <div class="form-group" >
            <small>Nama Produk</small>
            <input type="hidden" class="form-control id" name="id" required>
            <input type="text" class="form-control nama" name="nama" required>
          </div>
          <div class="form-group" >
            <small>Gambar</small>
            <input type="file" class="form-control" name="gambar">
          </div>
          <div class="form-group" >
            <small>Kategori</small>
            <select class="form-control kategori" name="kategori" required>
              <?php foreach ($kat_datas as $value): ?>
                <option value="<?php echo $value['id_kategori']; ?>"><?php echo $value['nama_kategori']; ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group" >
            <small>Jenis</small>
            <select class="form-control jenis" name="jenis" required>
              <option></option>
              <option value="Subsidi">Subsidi</option>
              <option value="Non Subsidi">Non Subsidi</option>
            </select>
          </div>
          <div class="form-group" >
            <small>Harga</small>
            <input type="number" class="form-control harga" name="harga" required>
          </div>
          <div class="form-group" >
            <small>Stok</small>
            <input type="number" class="form-control stok" name="stok" required>
          </div>
          <div class="form-group" >
            <small>Deskripsi Produk</small>
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
                <h4 class="modal-title" id="myModalLabel">Delete Produk</h4>
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
    var kat = $(this).data('kat');
    var katid = $(this).data('katid');
    var jenis = $(this).data('jenis');
    var stok = $(this).data('stok');
    var harga = $(this).data('harga');
    var desk = $(this).data('desk');
    var id = $(this).data('id');
    var option = $('<option value="'+katid+'" selected>'+kat+'</option>');
    var option2 = $('<option value="'+jenis+'" selected>'+jenis+'</option>');

    $(".id").val(id);
    $(".nama").val(nama);
    $(".stok").val(stok);
    $(".harga").val(harga);
    $(".desk").val(desk);
    $('.kategori').append(option);
    $('.jenis').append(option2);
  
});
$(".modal").on("hide.bs.modal", function(){
    location.reload();
});
</script>