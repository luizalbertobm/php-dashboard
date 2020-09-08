<?php

/* ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); */

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

function embedded_phpinfo()
{
    ob_start();
    phpinfo();
    $phpinfo = ob_get_contents();
    ob_end_clean();
    $phpinfo = preg_replace('%^.*<body>(.*)</body>.*$%ms', '$1', $phpinfo);
    echo "
        <style type='text/css'>
            #phpinfo {}
            #phpinfo pre {margin: 0; font-family: monospace;}
            #phpinfo a:link {color: #009; text-decoration: none; background-color: #fff;}
            #phpinfo a:hover {text-decoration: underline;}
            #phpinfo table {border-collapse: collapse; border: 0; width: 934px; box-shadow: 1px 2px 3px #ccc;}
            #phpinfo .center {text-align: center;}
            #phpinfo .center table {margin: 1em auto; text-align: left;}
            #phpinfo .center th {text-align: center !important;}
            #phpinfo td, th {border: 1px solid #666; font-size: 75%; vertical-align: baseline; padding: 4px 5px;}
            #phpinfo h1 {font-size: 150%;}
            #phpinfo h2 {font-size: 125%;}
            #phpinfo .p {text-align: left; display:none}
            #phpinfo .e {background-color: #ccf; width: 300px; font-weight: bold;}
            #phpinfo .h {background-color: #99c; font-weight: bold;}
            #phpinfo .v {background-color: #ddd; max-width: 300px; overflow-x: auto; word-wrap: break-word;}
            #phpinfo .v i {color: #999;}
            #phpinfo img {float: right; border: 0;}
            #phpinfo hr {width: 934px; background-color: #ccc; border: 0; height: 1px;}
        </style>
        <div id='phpinfo'>
            $phpinfo
        </div>
        ";
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
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.1/css/bootstrap.min.css" integrity="sha384-VCmXjywReHh4PwowAiWNagnWcLhlEJLA5buUprzK8rxFgeH0kww/aWY76TfkUoSX" crossorigin="anonymous">
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;700&display=swap" rel="stylesheet">

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
      font-family: 'Nunito', sans-serif !important;
      font-weight: 400;
      ;
    }

    .head {
      background-color: #cccccc;
      padding: 20px;
    }

    .card {
      border-radius: 8px;
      box-shadow: 3px 3px 5px rgba(0, 0, 0, .1);
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
</head>

<body data-gr-c-s-loaded="true" cz-shortcut-listen="true">
  <nav class="navbar navbar-expand-md navbar-dark bg-dark">
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
        </ul>
        <form class="form-inline my-2 my-lg-0">
          <input id="filter" class="form-control mr-sm-2" type="text" placeholder="Search" aria-label="Search">
        </form>
      </div>
    </div>
  </nav>

  <main role="main">

    <!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="head">
      <div class="container">
        <h1 class="display-3">PHP Dashboard</h1>
        <p>A dashboard to be used as a root page for php/localhost servers, giving some shortcuts and useful tools to developers.</p>
        <p>
          <a class="btn btn-primary btn-lg" href="javascript:void(0)" data-toggle="modal" data-target="#phpInfo"><i class="fa fa-info-circle" aria-hidden="true"></i> PHP Info</a>
          <a class="btn btn-info btn-lg" href="javascript:void(0)" data-toggle="modal" data-target="#phpIni"><i class="fa fa-code" aria-hidden="true"></i> PHP.ini</a>
          <a class="btn btn-success btn-lg" href="php-dashboard/adminer" role="button"><i class="fa fa-database" aria-hidden="true"></i> Adminer</a>
        </p>
        <?php
        if (mysqli_connect_errno()) { ?>
          <div class="alert alert-danger">
            <strong>Database error:</strong> Could not connect to Mysql. Check the access data.
          </div>
        <?php }
        ?>
      </div>
    </div>

    <div class="container">
      <h3 class="mt-3 mb-0">Files list</h3>
      <small> The following files are in: <?= get_root() ?></small>
      <!-- Example row of columns -->

      <div class="row mt-3">
        <?php foreach ($files as $file) { ?>
          <?php $file_ext = !ext($file) && is_dir($file) ? "dir" : ext($file);


          switch ($file_ext) {
            case 'txt':
            case 'php':
            case 'js':
              $icon = 'fa-file-code-o';
              break;
            case 'dir':
              $icon = 'fa-folder';
              break;
            case 'jpg':
            case 'png':
            case 'gif':
            case 'svg':
            case 'bmp':
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
                      <strong><?= $file ?></strong> <br>
                      <small class="text-muted">Size: <?= $file_ext == 'dir' ? get_directory_size($file) : display_size(filesize($file)) ?></small>
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

  <!-- Modal About -->
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
          This file was created by <a href="mailto:luizalbertobm@gmail.com">Luiz Alberto Mesquita</a>, to be used as a root page for localhost servers, giving shortcuts and useful tools
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal php.ini -->
  <div class="modal  fade" id="phpIni" tabindex="-1" aria-labelledby="phpIniLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="phpIniLabel">PHP Configuration</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <textarea spellcheck="false" class="form-control bg-secondary text-white" name="" id="" cols="30" rows="14"><?= file_get_contents(php_ini_loaded_file()); ?></textarea>
          <span>Current file path: <span class="badge badge-info"><?= php_ini_loaded_file() ?></span></span>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal PHP Info -->
  <div class="modal  fade" id="phpInfo" tabindex="-1" aria-labelledby="phpInfoLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="phpInfoLabel">PHP Version: <?= phpversion(); ?></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="table-responsive">
            <?php embedded_phpinfo(); ?>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <footer class="container">
    <p>© <a href="https://github.com/luizalbertobm/php-dashboard">PHP Dashboard</a> 2020 - By <a href="https://www.linkedin.com/in/luizalbertobm/">Luiz A. Mesquita</a></p>
  </footer>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.slim.min.js" integrity="sha512-/DXTXr6nQodMUiq+IUJYCt2PPOUjrHJ9wFrqpJ3XkgPNOZVfMok7cRw6CSxyCQxXn6ozlESsSh1/sMCTF1rL/g==" crossorigin="anonymous"></script>
  <script>
    window.jQuery || document.write('<script src="/docs/4.5/assets/js/vendor/jquery.slim.min.js"><\/script>')
    $('#filter').keyup(function() {
      $('.card:not(:contains(' + $(this).val() + '))').parent().hide();
      $('.card:contains(' + $(this).val() + ')').parent().show();
    })
  </script>
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.1/js/bootstrap.min.js" integrity="sha384-XEerZL0cuoUbHE4nZReLT7nx9gQrQreJekYhJD9WNWhH8nEW+0c5qq7aIo2Wl30J" crossorigin="anonymous"></script>
  <script src="https://use.fontawesome.com/c41c56d25a.js"></script>
</body>

</html>
<?php $mysqli->close(); ?>