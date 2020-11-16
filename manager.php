<?php

require_once("common/accesscontrol.php");

if(isset($_GET['page'])){
  $page = (string) $_GET['page'];
}else
  header('Location: index.php');

if($page=='product')
  $titlePg = (string) 'Product';
  
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" cont="ie=edge">
  
  <link href="lib/fontawesome-5.13.1/css/fontawesome.min.css" rel="stylesheet">
	<link href="lib/fontawesome-5.13.1/css/brands.min.css" rel="stylesheet">
	<link href="lib/fontawesome-5.13.1/css/solid.min.css" rel="stylesheet">
  <link href="lib/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
  <link href="css/style.css" rel="stylesheet">
  <link rel="shortcut icon" type="image/png" href="assets/favicon.png" />
  <title>CRUD - (<?php echo $titlePg;?>)</title>

  <style>  
  .theme-switcher {
    display: flex;
    align-items: center;
    position: fixed;
    bottom: 10px;
    right: 10px;
  }
  
  .theme-switcher div {
    cursor: pointer;
    transition: color 100ms ease-out, transform 100ms ease-out;
    padding: 9.5px 19px !important;
  }
  
  .theme-switcher p {
    font-size: 17.00006px !important;
  }
  
  .active-highlight {
    position: absolute;
    background: white;
    height: 27px;
    width: 42%;
    margin: 0 10px;
    z-index: -1;
  }
  
  .active-theme {
    color: #002236;
  }

  .ds-input {
    position: relative;
  }

  .right-add-on {
    position: absolute;
    top: 0px;
    right: 0px;
    padding-right: 25px;
    padding-top: 5px;
    color: black;
  }

  .ds-three-quarter-height {
    height: 75%;
  } 
  </style>
</head>

<body>
<?php
  include_once('view/nav.php');
?>
  <!-- Body -->
  <div class="container-fluid">
    <div class="row">

      <!-- Sidebar -->
      <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
        <div class="sidebar-sticky pt-3">
          <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
            <span>TABLES</span>
            <a class="d-flex align-items-center text-muted" href="#" aria-label="Add a new report">
              <span data-feather="plus-circle"></span>
            </a>
          </h6>
          <ul class="nav flex-column mb-2">
            <!-- PRODUCT -->
            <li class="nav-item">
              <a class="nav-link" href="#">
                <?=($page=='product'?'<i class="fas fa-tools"></i>':'')?>
                Product
              </a>
            </li>
          </ul>
        </div>
      </nav>



      <!-- Main -->
      <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
          <h1 class="h2"><?=$titlePg;?></h1>
          <div class="btn-toolbar mb-2 mb-md-0">
            <button type="button" class="btn btn-sm btn-outline-secondary">
              <i class="fas fa-file-csv"></i>
              Export CSV
            </button>
          </div>
        </div>
        <form id="search" data-page="<?php echo $page;?>">
          <input type="hidden" name="stoken" value="<?php echo $_SESSION['user']->getTokenCSFR();?>">
          <div class="col-1 float-left">
            <div class="form-group">
              <select id="select-menu" class="form-control" required>
                <option value="10">10</option>
                <option value="50">50</option>
                <option value="100">100</option>
              </select>
            </div>
          </div>
          <div class="col-7 float-left">
            <div class="form-group">
              <input name="search" class="form-control" placeholder="Search..." type="text">
            </div>
          </div>
          <div class="col-1 float-left">
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-search" alt="Search" title="Search" aria-label="Search"></i>
            </button>
          </div>
          <div class="col-1 float-right">
            <button type="" class="btn btn-primary" alt="AddRecord" title="Add record" aria-label="Add record" data-toggle="modal" data-target="#overlay-new">
              <i class="fas fa-plus"></i>
            </button>
          </div>
        </form>
        <div class="clearfix"></div>

<?php
// load table and windows of crud (insert, update and delete)
switch ($page) {
  case 'product':
    include_once('view/manager-product.php');
    break;
}
?>
      </main>
    </div>
  </div>

<?php
  include_once('view/footer.php');
  crudModal();
?>


<script src="lib/jquery/jquery-3.5.1.min.js" type="text/javascript"></script>
<script src="lib/bootstrap/js/popper.min.js" type="text/javascript"></script>
<script src="lib/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>

<script src="./js/scripts.js" type="text/javascript"></script>
<script src="./js/scripts<?php echo $page?"-$page":'';?>.js" type="text/javascript"></script>
<script type="text/javascript">
$(function() {
  pagination(1);
});
</script>
</body>
</html>