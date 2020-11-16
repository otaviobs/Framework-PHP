<?php

require_once('config/constants.php');

if(!isset($_SESSION)) {
  session_start();
}
if(isset($_SESSION['user']))
  header('Location: manager.php?page=product');

//  header('Location: '.OPENDIDAUTH_URL);
?>
<!DOCTYPE HTML>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="shortcut icon" type="image/png" href="./assets/favicon.png" />
  <link href="lib/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
  <title>CRUD</title>
  <style type="text/css">
/* Override some defaults */
    html, body {
        background-color: #eee;
    }
    body {
        padding-top: 40px;
    }
    .container {
        width: 50%;
    }
/* The white background content wrapper */
    .container > .content {
        background-color: #fff;
        padding: 20px;
        margin: 0px;
        -webkit-border-radius: 10px 10px 10px 10px;
        -moz-border-radius: 10px 10px 10px 10px;
        border-radius: 10px 10px 10px 10px;
        -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.15);
        -moz-box-shadow: 0 1px 2px rgba(0,0,0,.15);
        box-shadow: 0 1px 2px rgba(0,0,0,.15);
    }
  </style>
</head>

<body>
<!-- Page content here-->
<div class="container" style="margin-top: 55px;">
  <div class="content">
		<div class="row" style="padding-left:15px">
			<div style="float:right; margin-left: 5px; font-size:20px;padding-right:15px;">
				<span style="color:#818181c7;"><strong>Framework</strong></span> <span style="color:#428bca;">PHP</span>
			</div>
    </div>
    <hr>
    <div class="row">
			<div class="col-12">
        Welcome! <br/>
        Please click on the Sign-in button below to be authenticated.
      </div>
      <div class="clearfix"></div>
      <div class="col-12">
        <div class="mute pb-3"><small></small></div>
        <form action="<?php echo OPENDIDAUTH_URL; ?>" method="post">
          <fieldset>                        
            <button class="btn btn-primary float-right" type="submit" name="submit" value="Submit">Sign in</button>
          </fieldset>
        </form>
      </div>
    </div>  
  </div>
</div> <!-- /container -->

<?php
  include_once('view/footer.php');
?>

<script src="lib/bootstrap/js/bootstrap.min.js"></script>

<script>
  
</script>
</body>
</html>


