<?php
    require_once "class/Database.php";
    require_once "class/Buyer.php";
    require_once "class/Produk.php";

    $buyer = new Buyer($db);
    $datas = $buyer->getUser();

    $produk = new Produk($db);

    if(isset($_REQUEST['slug']))
    {
       $id = $_REQUEST['slug'];
       extract($produk->getProdukID($id));
       extract($produk->getKategoriID($id_kategori));
    }

    $jumlah_desimal = "0";
    $pemisah_desimal = ",";
    $pemisah_ribuan = ".";

    if(isset($_POST['beli'])) {

      if($buyer->isUserLoggedIn()){

        if($datas['status'] == 'Verified' && $jenis == 'Subsidi') {
          if($datas['kuota'] < $_POST['qty']){
            header("location: single.php?slug=$id&errKuota");
            stop();
          }
        }
          
        $total_harga = $harga * $_POST['qty'];
        try {
            $produk->addCart(array(
              'id_produk' => $id_produk,
              'id_customer' => $datas['id_customer'],
              'tharga' => $total_harga,
              'jumlah' => $_POST['qty']
            ));
            //header("refresh:0");
            header("location: cart.php");
          } catch (Exception $e) {
            die($e->getMessage());
          }
      } else {
        header("location: index.php?error");
      } 
    }
?>
<?php
    include "header.php";
?>
<div class="container">    
  <div class="row">
    <div class="col-sm-3">
      <div class="panel panel-success">
        <div class="panel-heading"><center></center></div>
        <div class="panel-body"><img src="assets/gambar_produk/<?php echo $gambar ?>" class="img-responsive" style="width:230px;height:200px;" alt="Image"></div>
        <div class="panel-footer"><center></center></div>
      </div>
    </div>

    <div class="panel panel-default col-sm-9">
      <div class="panel-heading">Informasi Pupuk</div>
      <form class="form-horizontal" method="post">
        <div class="form-group">
          <label class="control-label col-sm-2" for="email">Nama Pupuk:</label>
          <div class="col-sm-10">
            <p class="form-control-static"><?php echo $nama_produk ?></p>
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="email">Kategori:</label>
          <div class="col-sm-10">
            <p class="form-control-static"><?php echo $nama_kategori ?></p>
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="email">Jenis:</label>
          <div class="col-sm-10">
            <p class="form-control-static"><?php echo $jenis ?></p>
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="email">Harga:</label>
          <div class="col-sm-10">
            <p class="form-control-static"><?php print("Rp. ".number_format($harga, $jumlah_desimal, $pemisah_desimal, $pemisah_ribuan)); ?></p>
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="email">Stok:</label>
          <div class="col-sm-10">
            <p class="form-control-static"><?php echo $stok ?></p>
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="email">Jumlah:</label>
          <div class="col-sm-10">
            <input type="number" value="1" class="form-control log" max="<?php echo $stok ?>" name="qty" required>
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="email"></label>
          <div class="col-sm-10">
            <button type="submit" name="beli" class="log">Beli</button>
          </div>
        </div>
      </form>
    </div>

    <div class="panel panel-default col-sm-12">
      <div class="panel-heading">Deskripsi</div>
      <form class="form-horizontal">
        <div class="form-group">
          <div class="col-sm-10">
            <p class="form-control-static"><?php echo $desk_produk ?></p></p>
          </div>
        </div>
      </form>
    </div>
  </div>
</div><br>

<?php
  include "footer.php";
?>
