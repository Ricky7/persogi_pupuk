<?php
    require_once "class/Database.php";
    require_once "class/Buyer.php";
    require_once "class/Produk.php";

    $buyer = new Buyer($db);
    $datas = $buyer->getUser();

    $produk = new Produk($db);

    $tcart = $produk->getCartID($datas['id_customer']);
    $ongkir = 2000;

    $jumlah_desimal = "0";
    $pemisah_desimal = ",";
    $pemisah_ribuan = ".";

    date_default_timezone_set('Asia/Jakarta');
    $tanggal = date('Y-m-d H:i:s');
    //-----------------------------------------------------------------------------
    if(isset($_POST['order'])) {

      if($_POST['no_hp'] == NULL || $_POST['alamat'] == NULL || $_POST['jarak'] == NULL || $_POST['ongkos'] == NULL) {
        header("location: cart.php?carterror");
        stop();
      }

      try {
          $produk->insertOrder(array(
            'id_customer' => $datas['id_customer'],
            'no_hp' => $_POST['no_hp'],
            'alamat' => $_POST['alamat'],
            'tgl_order' => $tanggal,
            'desk_order' => $_POST['desk_order'],
            'jarak' => $_POST['jarak'],
            'ongkos' => $_POST['ongkos'],
            'status_order' => 'Belum Bayar'
          ), $datas['id_customer']);
          header("location: order_pending.php");
        } catch (Exception $e) {
        die($e->getMessage());
      }
    }
?>
<?php
    include "header.php";
?>
<script src="assets/dist/jquery.addressPickerByGiro.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD7zVeusOAU0YBF9JtwV97OXVM9dowacso&sensor=false&language=en"></script>
<link href="assets/dist/jquery.addressPickerByGiro.css" rel="stylesheet" media="screen">
<div class="container">    
  <div class="row">
    <div class="col-md-12">
      
      <div class="panel panel">
        <div class="panel-heading">
          <h3 class="panel-title">Keranjang Belanja</h3>
        </div>

          <div class="table-responsive">
            <table class="table table-hover">
              <thead>
                <th>Pupuk</th>
                <th>Nama</th>
                <th>Jenis</th>
                <th>Qty</th>
                <th>Harga</th>
                <th>Opsi</th>
              </thead>
              <tbody>
                <?php
                  $query = "SELECT * FROM cart INNER JOIN produk ON (cart.id_produk=produk.id_produk) WHERE cart.id_customer={$datas['id_customer']}";       
                  $records_per_page=20;
                  $newquery = $produk->paging($query,$records_per_page);
                  $produk->listCart($newquery);
                 ?>
                 <tr>
                    <td colspan="8" align="center">
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
</div><br>

<div class="container">
  <div class="row-fluid">
    <div class="row-fluid">

  <form autocomplete="off" class="form-horizontal" method="post">
  <div class="row-fluid">
    <div class="span9">
    <div class="row-fluid col-md-8">
      <label class="control-label" for="inputAddress">Input Alamat</label>
      <div class="controls">
        <input type="text" class="inputAddress input-xxlarge form-control" autocomplete="off" placeholder="Type in your address">
      </div>
    </div>

    <div class="row col-md-4">
      <div class="">
        <div class="control-group">
          <label class="control-label">No Kontak</label>
          <div class="controls">
            <input type="text" name="no_hp" value="<?php echo $datas['no_hp'] ?>" class="form-control" required>
          </div>
        </div>
      </div>
      <div class="">
        <div class="control-group">
        <label class="control-label">Deskripsi</label>
        <div class="controls">
          <textarea class="form-control" name="desk_order" rows="4"></textarea>
        </div>
      </div>
      </div>
      <div class="">
        <div class="control-group">
        <label class="control-label">Jarak Kirim /KM</label>
        <div class="controls">
          <input type="text" val="" name="jarak" id="distance" class="form-control" readonly>
          <!-- <p class="distance"></p> -->
        </div>
      </div>
      </div>
      <div class="">
        <div class="control-group">
        <label class="control-label">Ongkos Kirim</label>
        <div class="controls">
          <input type="text" value="<?php echo $tcart['jum']*$ongkir ?>" name="ongkos" id="shipping_c" class="form-control" readonly>
          <!-- <p class="shipping_c"></p> -->
        </div>
      </div>
      </div>
      <div class="">
        <div class="control-group">
        <label class="control-label">Cek Ongkos Kirim</label>
        <div class="controls">
          <a id="calculate" class="btn btn-info" name="calculate" >Hitung Jarak</a>
        </div>
        </div>
      </div>
    </div>       

    <div class="control-group col-md-8">
      <label class="control-label">Dikirim dari Alamat</label>
      <div class="controls">
        <input type="text" class="form-control" value="Jl. Setia Budi No.479, Tj. Sari, Medan Selayang, Kota Medan, Sumatera Utara 20133, Indonesia" disabled="disabled">
      </div>
      </div>  
    
    <div class="control-group col-md-8">
      <label class="control-label">Dikirim ke Alamat</label>
      <div class="controls">
        <input type="text" class="formatted_address input-xxlarge form-control" name="alamat" readonly="readonly" required="required">
      </div>
      </div>
  
    
    <div class="control-group">
    <div class="controls">
      <input type="hidden" class="latitude" id="latitude">
      <input type="hidden" class="longitude" id="longitude">
    </div>
    </div>

    <!-- // koordinat penjual -->
    <div class="control-group">
    <div class="controls">
      <input type="hidden" value="3.5437539" id="x_lat">
      <input type="hidden" value="98.6224125" id="x_long">
    </div>
    </div>

    <div class="row" style="padding-top:20px">
      <div class="col-md-3"></div>
      <div class="col-md-4">
        <div class="wrapper">
              <span class="group-btn">     
                  <button type="submit" name="order" class="btn btn-primary log">Order</button>
              </span>
          </div>
      </div>
      <div class="col-md-3"></div>
    </div>

    </div>
  </div>
  </form>
      </div><!--/span-->
    <script>
        $('.inputAddress').addressPickerByGiro({
            distanceWidget: true,
            boundElements: {
                'region': '.region',
                'county': '.county',
                'street': '.street',
                'street_number': '.street_number',
                'latitude': '.latitude',
                'longitude': '.longitude',
                'formatted_address': '.formatted_address'
            }
        });
    </script>
  </div><!--/row-->
</div><!--/.fluid-container-->

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

<script>

  $("#calculate").click(function(){
  var lat = $("#latitude").val();
  var longs = $("#longitude").val();
  var x_lat = $("#x_lat").val();
  var x_long = $("#x_long").val();
  $.ajax({
      url: "http://localhost:9000/si_simon/ongkir.php",
      data: 'lat=' + lat + '&longs=' + longs + '&x_lat=' + x_lat + '&x_long=' + x_long,
      cache: false,
      dataType: 'json', 
      success: function(data){
        var d = data.distance;
        var s = data.shipping;
        //$(".shipping_c").append( "<h3><font color='red'>Rp "+s+"</font></h3>" );
        //$(".distance").append( "<h3><font color='red'>"+d+" km</font></h3>" );
        //$('#shipping_c').val(s);
        $('#distance').val(d);
      }
  });
  });
        
</script>
<script type="text/javascript">
$('#confirm-delete').on('show.bs.modal', function(e) {
    $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
    $(this).find('.btn-ok').attr('nama', $(e.relatedTarget).data('nama'));
    
    $('.debug-url').html('Delete : <strong>' + $(this).find('.btn-ok').attr('nama') + '</strong>');

});
</script>
<?php
  include "footer.php";
?>
