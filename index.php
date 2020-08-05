<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function get_root()
{
  return getcwd() . '/';
}

function display_size($bytes, $precision = 2)
{
  $units = array('B', 'KB', 'MB', 'GB', 'TB');
  $bytes = max($bytes, 0);
  $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
  $pow = min($pow, count($units) - 1);
  $bytes /= (1 << (10 * $pow));
  return round($bytes, $precision) . '<span class="fs-0-8 bold">' . $units[$pow] . "</span>";
}

function ext($filename)
{
  return substr(strrchr($filename, '.'), 1);
}

function get_directory_size($path)
{
  $path = get_root() . $path;
  //echo $path;
  $bytestotal = 0;
  $path = realpath($path);
  if ($path !== false && $path != '' && file_exists($path)) {
    foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS)) as $object) {
      $bytestotal += $object->getSize();
    }
  }

  return display_size($bytestotal);
}

function count_dir_files($dir)
{
  $fi = new FilesystemIterator(__DIR__ . "/" . $dir, FilesystemIterator::SKIP_DOTS);
  return iterator_count($fi);
}

function get_sizedir($path)
{
  $bytestotal = 0;
  $path = realpath($path);
  if ($path !== false && $path != '' && file_exists($path)) {
    foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS)) as $object) {
      $bytestotal += $object->getSize();
    }
  }
  return $bytestotal;
}

$excludedFiles = ['.', '..', 'php-dashboard', 'index.php'];
$files = [];
if (is_dir(get_root())) {
  if ($dh = opendir(get_root())) {
    while (($file = readdir($dh)) !== false) {
      if (!in_array($file, $excludedFiles)) $files[] = $file;
    }
    closedir($dh);
  }
}

$mysqli = new mysqli("localhost", "root", "root");
?>
<!DOCTYPE html>
<!-- saved from url=(0053)https://getbootstrap.com/docs/4.5/examples/jumbotron/ -->
<html lang="en">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Localhost startup page">
  <meta name="author" content="Luiz alberto <luizalbertobm@gmail.com>">
  <title>PHP Dashboard</title>

  <link rel="canonical" href="https://getbootstrap.com/docs/4.5/examples/jumbotron/">

  <!-- Bootstrap core CSS -->
  <link href="./Jumbotron Template · Bootstrap_files/bootstrap.min.css" rel="stylesheet" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">

  <!-- Favicons -->
  <link rel="apple-touch-icon" href="https://getbootstrap.com/docs/4.5/assets/img/favicons/apple-touch-icon.png" sizes="180x180">
  <link rel="icon" href="https://getbootstrap.com/docs/4.5/assets/img/favicons/favicon-32x32.png" sizes="32x32" type="image/png">
  <link rel="icon" href="https://getbootstrap.com/docs/4.5/assets/img/favicons/favicon-16x16.png" sizes="16x16" type="image/png">
  <link rel="manifest" href="https://getbootstrap.com/docs/4.5/assets/img/favicons/manifest.json">
  <link rel="mask-icon" href="https://getbootstrap.com/docs/4.5/assets/img/favicons/safari-pinned-tab.svg" color="#563d7c">
  <link rel="icon" href="https://getbootstrap.com/docs/4.5/assets/img/favicons/favicon.ico">
  <meta name="msapplication-config" content="/docs/4.5/assets/img/favicons/browserconfig.xml">
  <meta name="theme-color" content="#563d7c">


  <style>
    .bd-placeholder-img {
      font-size: 1.125rem;
      text-anchor: middle;
      -webkit-user-select: none;
      -moz-user-select: none;
      -ms-user-select: none;
      user-select: none;
    }

    body {
      background-color: #eeeeee;
    }

    .head {
      background-color: #cccccc;
      padding: 20px;
    }

    .card a:hover i {
      color: #ff9900 !important;
    }

    @media (min-width: 768px) {
      .bd-placeholder-img-lg {
        font-size: 3.5rem;
      }
    }
  </style>
  <!-- Custom styles for this template -->
  <link href="./Jumbotron Template · Bootstrap_files/jumbotron.css" rel="stylesheet">
</head>

<body data-gr-c-s-loaded="true" cz-shortcut-listen="true">
  <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
    <div class="container">
      <!-- <a class="navbar-brand" href="https://getbootstrap.com/docs/4.5/examples/jumbotron/#">Navbar</a> -->
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarsExampleDefault">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item">
            <a class="nav-link" href="javascript:void(0)" data-toggle="modal" data-target="#exampleModal">About</a>
          </li>

          <!-- <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="https://getbootstrap.com/docs/4.5/examples/jumbotron/#" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Dropdown</a>
            <div class="dropdown-menu" aria-labelledby="dropdown01">
              <a class="dropdown-item" href="https://getbootstrap.com/docs/4.5/examples/jumbotron/#">Action</a>
              <a class="dropdown-item" href="https://getbootstrap.com/docs/4.5/examples/jumbotron/#">Another action</a>
              <a class="dropdown-item" href="https://getbootstrap.com/docs/4.5/examples/jumbotron/#">Something else here</a>
            </div>
          </li> -->
        </ul>
        <form class="form-inline my-2 my-lg-0">
          <input class="form-control mr-sm-2" type="text" placeholder="Search" aria-label="Search">
        </form>
      </div>
    </div>
  </nav>

  <main role="main">

    <!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="head">
      <div class="container">
        <h1 class="display-3">PHP Dashboard</h1>

        <p>
          <a class="btn btn-primary btn-lg" href="php-dashboard/info.php" role="button"><i class="fa fa-info-circle" aria-hidden="true"></i> PHP Info »</a>
          <a class="btn btn-success btn-lg" href="php-dashboard/adminer" role="button"><i class="fa fa-database" aria-hidden="true"></i> Adminer »</a>
        </p>
        <?php
        if (mysqli_connect_errno()) { ?>
          <div class="alert alert-danger">
            <strong>Erro na base de dados:</strong> Não foi possível conectar-se com o Mysql. Verifique os dados de acesso.
          </div>
        <?php }
        ?>
      </div>
    </div>


    <div class="container">
      <h3 class="mt-3 mb-0">Files list</h3>
      <p><?= get_root() ?></p>
      <!-- Example row of columns -->

      <div class="row">
        <?php foreach ($files as $file) { ?>
          <?php $file_ext = !ext($file) ? "dir" : ext($file);


          switch ($file_ext) {
            case 'txt':
            case 'php':
              $icon = 'fa-file-code-o';
              break;
            case 'dir':
              $icon = 'fa-folder';
              break;
            case 'jpg':
            case 'png':
            case 'gif':
            case 'svg':
              $icon = 'fa-file-image-o';
              break;
            default:
              $icon = 'fa-file';
              break;
          }
          ?>

          <div class="col-md-4">
            <div class="card  mb-4">
              <div class="row no-gutters">
                <div class="col-auto pt-3 pb-3 pl-3">
                  <a href="<?= $file ?>"><i class="fa fa-3x <?= $icon  ?> text-secondary" aria-hidden="true"></i></a>
                </div>
                <div class="col-auto">
                  <div class="card-body p-3">
                    <p class="card-text">
                      <?= $file ?> <br>
                      <small class="text-muted"><?= $file_ext == 'dir' ? get_directory_size($file) : display_size(filesize($file)) ?></small>
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>

        <?php } ?>
      </div>

      <hr>

    </div> <!-- /container -->

  </main>

  <!-- Modal -->
  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">About PHP Dashboard</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          This file was created by Luiz Alberto Mesquita, to be used as a root page for localhost servers, giving shortcuts and useful tools
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <footer class="container">
    <p>© PHP Dashboard 2020 - By Luiz A. Mesquita</p>
  </footer>
  <script src="./Jumbotron Template · Bootstrap_files/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
  <script>
    window.jQuery || document.write('<script src="/docs/4.5/assets/js/vendor/jquery.slim.min.js"><\/script>')
  </script>
  <script src="./Jumbotron Template · Bootstrap_files/bootstrap.bundle.min.js" integrity="sha384-1CmrxMRARb6aLqgBO7yyAxTOQE2AKb9GfXnEo760AUcUmFx3ibVJJAzGytlQcNXd" crossorigin="anonymous"></script>
  <script src="https://use.fontawesome.com/c41c56d25a.js"></script>
</body>

</html>
<?php $mysqli->close(); ?>