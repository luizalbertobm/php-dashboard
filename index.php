<?php

/* ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); */

$action = @$_REQUEST['action'];
if (!empty($action)) {
  call_user_func($action);
  exit;
}
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

// ajax functions
function save_inifile()
{
  file_put_contents(php_ini_loaded_file(), $_REQUEST['data']);
}

function read_phpini()
{
  echo file_get_contents(php_ini_loaded_file());
}

function get_phpinfo()
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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.57.0/codemirror.min.css" integrity="sha512-/BlxZbYLtYGExexketXsTi47eHp+r2kTeq2OHecQPZlfbc7WFXVrwbVW9HOYjI6c9Ti+P60ASmVLxittZ0EBGw==" crossorigin="anonymous" />
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

    .CodeMirror {
      border: 1px solid #666;
      height: 500px;
    }
    
    .projects a, .projects a svg{
      color: #666666;
    }

    .projects a:hover{
      text-decoration: none;
    }

    .projects a .card{
      transition: all .1s ease-in;
    }

    .projects a:hover .card{
      /* background-color: #eeeeee; */
      transform: scale(1.05);
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
        <h1 class="display-4">PHP Dashboard</h1>
        <p>A dashboard to be used as a root page for php/localhost servers, giving some shortcuts and useful tools to developers.</p>
        <p>
          <a id="btn-phpinfo" class="btn btn-primary mb-1" href="javascript:void(0)" data-toggle="modal" data-target="#phpInfo">
            <svg style="width:24px;height:24px" viewBox="0 0 24 24">
              <path fill="currentColor" d="M13,9H11V7H13M13,17H11V11H13M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2Z" />
            </svg> Info</a>
          <a id="btn-phpini" class="btn btn-info mb-1" href="javascript:void(0)" data-toggle="modal" data-target="#phpIni">
            <svg style="width:24px;height:24px" viewBox="0 0 24 24">
              <path fill="currentColor" d="M14 2H6C4.89 2 4 2.9 4 4V20C4 21.11 4.89 22 6 22H18C19.11 22 20 21.11 20 20V8L14 2M18 20H6V4H13V9H18V20M9.54 15.65L11.63 17.74L10.35 19L7 15.65L10.35 12.3L11.63 13.56L9.54 15.65M17 15.65L13.65 19L12.38 17.74L14.47 15.65L12.38 13.56L13.65 12.3L17 15.65Z" />
            </svg>
            PHP.ini</a>
          <a id="btn-adminer" class="btn btn-success mb-1" href="php-dashboard/adminer" role="button">
            <svg style="width:24px;height:24px" viewBox="0 0 24 24">
              <path fill="currentColor" d="M4,14V17C4,19 7.05,20.72 11,21V18.11L11.13,18C7.12,17.76 4,16.06 4,14M12,13C7.58,13 4,11.21 4,9V12C4,14.21 7.58,16 12,16C12.39,16 12.77,16 13.16,16L17,12.12C15.4,12.72 13.71,13 12,13M12,3C7.58,3 4,4.79 4,7C4,9.21 7.58,11 12,11C16.42,11 20,9.21 20,7C20,4.79 16.42,3 12,3M21,11.13C20.85,11.13 20.71,11.19 20.61,11.3L19.61,12.3L21.66,14.3L22.66,13.3C22.87,13.1 22.88,12.76 22.66,12.53L21.42,11.3C21.32,11.19 21.18,11.13 21.04,11.13M19.04,12.88L13,18.94V21H15.06L21.12,14.93L19.04,12.88Z" />
            </svg>
            Adminer</a>
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
            case 'html':
              $icon = '<svg style="width:48px;height:48px" viewBox="0 0 24 24"><path fill="#0088ff" d="M13,9H18.5L13,3.5V9M6,2H14L20,8V20A2,2 0 0,1 18,22H6C4.89,22 4,21.1 4,20V4C4,2.89 4.89,2 6,2M6.12,15.5L9.86,19.24L11.28,17.83L8.95,15.5L11.28,13.17L9.86,11.76L6.12,15.5M17.28,15.5L13.54,11.76L12.12,13.17L14.45,15.5L12.12,17.83L13.54,19.24L17.28,15.5Z" /></svg>';
              break;
            case 'dir':
              $icon = '<svg style="width:48px;height:48px" viewBox="0 0 24 24"><path fill="currentColor" d="M15.25,13H17.25L16.75,15H14.75L15.25,13M22,8V18A2,2 0 0,1 20,20H4A2,2 0 0,1 2,18V6C2,4.89 2.89,4 4,4H10L12,6H20A2,2 0 0,1 22,8M20,12H18.5L19,10H18L17.5,12H15.5L16,10H15L14.5,12H13V13H14.25L13.75,15H12V16H13.5L13,18H14L14.5,16H16.5L16,18H17L17.5,16H19V15H17.75L18.25,13H20V12Z" /></svg>';
              break;
            case 'jpg':
            case 'png':
            case 'gif':
            case 'svg':
            case 'bmp':
              $icon = '<svg style="width:48px;height:48px" viewBox="0 0 24 24"><path fill="currentColor" d="M13,9H18.5L13,3.5V9M6,2H14L20,8V20A2,2 0 0,1 18,22H6C4.89,22 4,21.1 4,20V4C4,2.89 4.89,2 6,2M6,20H15L18,20V12L14,16L12,14L6,20M8,9A2,2 0 0,0 6,11A2,2 0 0,0 8,13A2,2 0 0,0 10,11A2,2 0 0,0 8,9Z" /></svg>';
              break;
            default:
              $icon = '<svg style="width:48px;height:48px" viewBox="0 0 24 24"><path fill="currentColor" d="M13,9V3.5L18.5,9M6,2C4.89,2 4,2.89 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2H6Z" /></svg>';
              break;
          }
          ?>

          <div class="col-md-4 projects">
          <a href="<?= $file ?>">
          <div class="card  mb-4">
              <div class="row no-gutters">
                <div class="col-auto pt-3 pb-3 pl-3">
                  <?= $icon ?>
                </div>
                <div class="col-auto">
                  <div class="card-body p-3">
                    <p class="card-text">
                      <strong><?= $file ?></strong><br>
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

          <span>The current file is located at: <strong><?= php_ini_loaded_file() ?></strong>
            <textarea spellcheck="false" class="form-control bg-secondary text-white" name="phpini" id="phpini" cols="45" rows="14"></textarea>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" id="btn-save-ini">Save changes</button>
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
          <div class="table-responsive" id="phpinfo">

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
  <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.1/js/bootstrap.min.js" integrity="sha384-XEerZL0cuoUbHE4nZReLT7nx9gQrQreJekYhJD9WNWhH8nEW+0c5qq7aIo2Wl30J" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.57.0/codemirror.min.js" integrity="sha512-K8GMktcEqOI28I3f5C6kraFm1F4wVLvnBxGU+imS/zOLT1COAT799Ln4DJyAbpdyNt6LgMIWcwy4ptCYXCIDqA==" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.57.0/mode/properties/properties.min.js" integrity="sha512-KFE2cwTwAMmZ2uQzOh8sRi0pBCHJBXyzbhhELf7gfx0n/m8no2tQpEQDW8dxSNCO1jaNAmFJ5KuXfZ/oCjoLDQ==" crossorigin="anonymous"></script>

  <script>
    //window.jQuery || document.write('<script src="/docs/4.5/assets/js/vendor/jquery.slim.min.js"><\/script>')
    $('#filter').keyup(function() {
      $('.card:not(:contains(' + $(this).val() + '))').parent().parent().hide();
      $('.card:contains(' + $(this).val() + ')').parent().parent().show();
    })



    $(document).ready(function() {
      var cm = new CodeMirror.fromTextArea(document.getElementById('phpini'), {
        // theme: "ambiance",
        mode: 'properties',
        lineNumbers: true,
        lineWrapping: false,
      });

      $('#btn-phpinfo').click(function() {
        $.get("?action=get_phpinfo", function(data) {
          $("#phpinfo").html(data);
        });
      })

      $('#btn-phpini').click(function() {
        $.get("?action=read_phpini", function(data) {
          setTimeout(function(params) {
            cm.getDoc().setValue(data)
          }, 500)
        });
      })

      $('#btn-save-ini').click(function() {
        $.post("?action=save_inifile", {
          data: cm.getValue()
        }).done(function(data) {
          alert('Saved successfully. Restart the server for the changes to take effect.')
        })
      });
    });
  </script>
</body>

</html>
<?php $mysqli->close(); ?>