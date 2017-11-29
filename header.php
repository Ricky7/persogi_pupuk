<?php
    require_once "class/Database.php";
    require_once "class/Produk.php";
    require_once "class/Buyer.php";

    $produk = new Produk($db);

    $buyer = new Buyer($db);

    if($buyer->isUserLoggedIn()){
      $datas = $buyer->getUser();
    }

    if(isset($_POST['signup'])){
        $nama = $_POST['nama'];
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Registrasi user baru
        if($buyer->register($nama, $username, $password)){
            // Jika berhasil set variable success ke true
            $success = true;
            header ("location: index.php");
        }else{
            // Jika gagal, ambil pesan error
            $error = $buyer->getLastError();
        }
    }

    if(isset($_POST['login'])){
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Proses login user
        if($buyer->login($username, $password)){
            header("location: index.php");
        }else{
            // Jika login gagal, ambil pesan error
            $error = $buyer->getLastError();
        }
    }

    if(isset($_POST['update'])) {

        try {
          $buyer->updateUser(array(
            'nama' => $_POST['nama'],
            'alamat' => $_POST['alamat'],
            'no_hp' => $_POST['hp']
          ), $datas['id_customer']);
          header("location: index.php?updated");
        } catch (Exception $e) {
          die($e->getMessage());
        }
    }

    if(isset($_POST['ubah'])) {
        
      try {
          $buyer->ubahPassUser($datas['id_customer'], $_POST['old_pass'], $_POST['new_pass']);
          //header("location: index.php?success");
        } catch (Exception $e) {
          die($e->getMessage());
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>CV PERSOGI</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/login.css">
  
  <style>
    /* Remove the navbar's default rounded borders and increase the bottom margin */ 
    .navbar {
      margin-bottom: 50px;
      border-radius: 0;
    }
   
    /* Add a gray background color and some padding to the footer */
    footer {
      background-color: #f2f2f2;
      padding: 25px;
    }
  </style>
</head>
<body>

<div id="myCarousel" class="carousel slide" data-ride="carousel">
  <!-- Indicators -->
  <ol class="carousel-indicators">
    <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
    <li data-target="#myCarousel" data-slide-to="1"></li>
    <li data-target="#myCarousel" data-slide-to="2"></li>
  </ol>

  <!-- Wrapper for slides -->
  <div class="carousel-inner">
    <div class="item active">
      <img src="assets/img/rice-paddy.jpg" width="1360px" height="200px" alt="Los Angeles">
    </div>

    <div class="item">
      <img src="assets/img/padi.jpg" alt="Chicago">
    </div>

    <div class="item">
      <img src="assets/img/cabai.jpg" alt="New York">
    </div>
  </div>

  <!-- Left and right controls -->
  <a class="left carousel-control" href="#myCarousel" data-slide="prev">
    <span class="glyphicon glyphicon-chevron-left"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="right carousel-control" href="#myCarousel" data-slide="next">
    <span class="glyphicon glyphicon-chevron-right"></span>
    <span class="sr-only">Next</span>
  </a>
</div>

<nav class="navbar navbar-default">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>                        
      </button>
      <a class="navbar-brand" href="#" style="color:#fff;">Logo</a>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav">
        <li><a href="index.php" style="color:#fff;">Home</a></li>
      </ul>
      <form class="navbar-form navbar-left" method="get" action="search.php">
        <div class="form-group">
          <input type="text" class="form-control" name="search" placeholder="Search">
        </div>
        <button type="submit" class="btn btn-default">Search</button>
      </form>
      <ul class="nav navbar-nav navbar-right">
        <?php
            if(!$buyer->isUserLoggedIn()){

                ?>
                    <li><a href="#" style="color:#fff;" onclick="document.getElementById('id01').style.display='block'"><span class="glyphicon glyphicon-user"></span> Login</a></li>
                    <li><a href="#" style="color:#fff;" onclick="document.getElementById('id02').style.display='block'"><span class="glyphicon glyphicon-user"></span> SignUp</a></li>
                    
                <?php
            } else {

                ?>  
                    <li><a href="cart.php" style="color:#fff;"><span class="glyphicon glyphicon-shopping-cart"></span> Cart</a></li> 
                    <li class="dropdown">
                      <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" style="color:#fff;"><span class="glyphicon glyphicon-user"> AkunSaya <span class="caret"></span></a>
                      <ul class="dropdown-menu">
                        <li>
                          <a href="#" class="edit_button"
                              data-toggle="modal" data-target="#myModalProfil"
                              data-nama="<?php print($datas['nama']);?>"
                              data-username="<?php print($datas['username']);?>"
                              data-hp="<?php print($datas['no_hp']);?>"
                              data-alamat="<?php print($datas['alamat']);?>"
                              data-kuota="<?php print($datas['kuota']);?>"
                              data-status="<?php print($datas['status']);?>"
                              data-id="<?php print($datas['id_customer']); ?>">
                              Profil
                          </a>
                        </li>
                        <li role="separator" class="divider"></li>
                        <li><a href="order_pending.php">Order Pending</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="order_proses.php">Order Proses</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="order_selesai.php">Order Selesai</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="#" data-toggle="modal" data-target="#myModalPassword">My Password</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="logout.php">Logout</a></li>
                      </ul>
                    </li>
                <?php
            }
        ?>
        
      </ul>
    </div>
  </div>
</nav>

<!-- The Modal -->
<div id="id01" class="modal1">
  <span onclick="document.getElementById('id01').style.display='none'" 
class="close" title="Close Modal">&times;</span>

  <!-- Modal Content -->
  <form class="modal-content1 animate" action="" method="post">
    <div class="imgcontainer">
      <img src="assets/img/icon.png" alt="Avatar" class="avatar">
    </div>

    <div class="container">
      <label><b>Username</b></label><br>
      <input type="text" placeholder="Enter Username" name="username" class="log" required><br>

      <label><b>Password</b></label><br>
      <input type="password" placeholder="Enter Password" name="password" class="log" required><br>

      <button type="submit" name="login" class="log">Login</button>
    </div>

    <div class="container" style="background-color:#f1f1f1;width:100%;">
      <button type="button" onclick="document.getElementById('id01').style.display='none'" class="cancelbtn log">Cancel</button>
    </div>
  </form>
</div>

<div id="id02" class="modal1">
  <span onclick="document.getElementById('id02').style.display='none'" class="close" title="Close Modal">×</span>
  <form class="modal-content1 animate" action="" method="post">
    <div class="container">
      <label><b>Name</b></label><br>
      <input type="text" placeholder="Enter Name" name="nama" class="log" required><br>

      <label><b>Username</b></label><br>
      <input type="text" placeholder="Enter Username" name="username" class="log" required><br>

      <label><b>Password</b></label><br>
      <input type="password" placeholder="Enter Password" name="password" class="log" required><br>

      <div class="clearfix">
        <button type="submit" name="signup" class="signupbtn log">Sign Up</button>
      </div>
      <button type="button" onclick="document.getElementById('id02').style.display='none'" class="cancelbtn">Cancel</button>
    </div>
  </form>
</div>

<!-- Modal Profil-->
<div id="myModalProfil" class="modal" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Profil Saya</h4>
      </div>
      <div class="modal-body">
        <form method="post">
          <div class="form-group" >
            <small>Nama</small>
            <input type="hidden" class="form-control id" name="id">
            <input type="text" class="form-control nama" id="nama" name="nama" required>
          </div>
          <div class="form-group" >
            <small>Username</small>
            <input type="text" class="form-control username" name="username" disabled>
          </div>
          <div class="form-group" >
            <small>No Hp</small>
            <input type="text" class="form-control hp" name="hp" required>
          </div>
          <div class="form-group" >
            <small>Alamat</small>
            <textarea class="form-control alamat" name="alamat" required></textarea>
          </div>
          <div class="form-group" >
            <small>Status UD</small>
            <input type="text" class="form-control status" name="status" disabled>
          </div>
          <div class="form-group" >
            <small>Kuota</small>
            <input type="text" class="form-control kuota" name="kuota" disabled>
          </div>
      </div>
      <div class="modal-footer">
        <button type="submit" name="update" class="btn btn-success btn-sm">Submit</button>
        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
        </form>
      </div>
    </div>

  </div>
</div>

<!-- Modal Profil-->
<div id="myModalPassword" class="modal" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Ubah Password</h4>
      </div>
      <div class="modal-body">
        <form method="post">
          <div class="form-group" >
            <small>Password Lama</small>
            <input type="password" class="form-control" name="old_pass" required>
          </div>
          <div class="form-group" >
            <small>Password Baru</small>
            <input type="password" class="form-control" name="new_pass" required>
          </div>
      </div>
      <div class="modal-footer">
        <button type="submit" name="ubah" class="btn btn-success btn-sm">Submit</button>
        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
        </form>
      </div>
    </div>

  </div>
</div>

<!-- Error Message -->
<div class="col-md-12">
  <?php if (isset($_GET['updated'])): ?>
    <div class="alert alert-info">
        <?php echo "Berhasil Update Profil..." ?>
    </div>
<?php endif; ?>
</div>

<div class="col-md-12">
  <?php if (isset($_GET['success'])): ?>
    <div class="alert alert-info">
        <?php echo "Berhasil Ubah Password..." ?>
    </div>
<?php endif; ?>
</div>

<div class="col-md-12">
  <?php if (isset($_GET['failed'])): ?>
    <div class="alert alert-warning">
        <?php echo "Gagal Ubah password..." ?>
    </div>
<?php endif; ?>
</div>

<div class="col-md-12">
  <?php if (isset($_GET['error'])): ?>
      <div class="alert alert-warning">
          <?php echo "Silahkan Login terlebih Dahulu...." ?>
          <script type="text/javascript">
            // $(window).on('load',function(){
                
            // });
            //$('#id01').modal('show');
            setTimeout(function(){document.getElementById('id01').style.display='block';},3000);
          </script>
      </div>
  <?php endif; ?> 
</div>

<div class="col-md-12">
  <?php if (isset($_GET['msg'])): ?>
    <?php $ttl = $_GET['msg']; ?>
      <div class="alert alert-warning">
          <?php echo "<center>Total Pesanan anda ".$ttl." sak, melebihi stok</center>" ?>
      </div>
  <?php endif; ?> 
</div>

<div class="col-md-12">
  <?php if (isset($_GET['carterror'])): ?>
      <div class="alert alert-warning">
          <?php echo "<center>Data tidak Lengkap....</center>" ?>
      </div>
  <?php endif; ?> 
</div>

<div class="col-md-12">
  <?php if (isset($_GET['errKuota'])): ?>
      <div class="alert alert-warning">
          <?php echo "<center>Kuota Subsidi anda tidak mencukupi, pastikan jumlah pembelian tidak melebihi kuota subsidi....</center>" ?>
      </div>
  <?php endif; ?> 
</div>


<script>
$(document).on( "click", '.edit_button',function(e) {

    var nama = $(this).data('nama');
    var username = $(this).data('username');
    var hp = $(this).data('hp');
    var alamat = $(this).data('alamat');
    var status = $(this).data('status');
    var kuota = $(this).data('kuota');
    var id = $(this).data('id');


    $(".id").val(id);
    $(".nama").val(nama);
    $(".username").val(username);
    $(".hp").val(hp);
    $(".alamat").val(alamat);
    $(".kuota").val(kuota);
    $(".status").val(status);
  
});
$(".modal").on("hide.bs.modal", function(){
    location.reload();
});
</script>