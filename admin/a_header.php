<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>

  <title>Administrator</title>

  <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
  <meta name="viewport" content="width=device-width" />

  <script data-require="jquery@*" data-semver="2.0.3" src="http://code.jquery.com/jquery-2.0.3.min.js"></script>
  <script data-require="bootstrap@*" data-semver="3.1.1" src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
  <!-- Latest compiled and minified CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

  <!-- Optional theme -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

  <link rel="stylesheet" href="../assets/css/style.css">
    
</head>
<body>

<nav class="navbar navbar-default">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="#" style="color:#fff;">@Admin</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav wow">
        <li><a href="a_index.php" style="color:#fff;">Anggota</a></li>
        <li><a href="a_produk.php" style="color:#fff;">Produk</a></li>
        <li><a href="a_kategori.php" style="color:#fff;">Kategori</a></li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" style="color:#fff;"> Pemesanan <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="a_belum_bayar.php">Belum Bayar</a></li>
            <li role="separator" class="divider"></li>
            <li><a href="a_pending.php">Pending</a></li>
            <li role="separator" class="divider"></li>
            <li><a href="a_bayar.php">Bayar</a></li>
            <li role="separator" class="divider"></li>
            <li><a href="a_dikirim.php">Dikirim</a></li>
            <li role="separator" class="divider"></li>
            <li><a href="a_selesai.php">Selesai</a></li>
          </ul>
        </li>
        <li><a href="a_laporan.php" style="color:#fff;">Laporan</a></li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li><a href="a_logout.php" style="color:#fff;">Logout</a></li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>

<nav class="navbar navbar-inverse navbar-fixed-bottom">
  <div class="container-fluid">
    <center style="padding-top:10px;">@Persogi Administrator</center>
  </div>
</nav>