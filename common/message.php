<?php
require_once("CommonFunctions.php");

// filter all parameters
if(isset($_GET))
    $_GET = filter($_GET, array('html','sql','others'),'get');

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="shortcut icon" type="image/png" href="../assets/favicon.png" />
  <link rel="stylesheet" class="style-switch" href="../lib/w3ds/w3ds.css">
  <title><?php echo $_GET['title']; ?></title>
</head>

<body class="ds-bg-blue-6 ds-has-sticky-footer">
  <!-- Nav -->
  <div class="ds-grid">
    <div class="ds-row ds-affix ds-bg-blue-8 ds-bg-dark ds-pad-lg-1 ds-full-width" style="z-index: 99; top: 0;">

      <!-- Hamburger -->
      <div class="ds-col-xs-5 ds-col-md-2 ds-pad-xs-0 ds-hide-lg">
        <button class="ds-button ds-width-auto ds-primary ds-no-expand ds-pad-l-1 ds-pad-r-1 ds-mar-b-0"
          id="overlay-nav-open">
          <span class="ds-icon-menu ds-heading-4" style="position:relative;top:-.2rem;"></span>
        </button>
      </div>

      <!-- Mail heading -->
      <div class="ds-col-xs-4 ds-col-md-2 ds-col-lg-3 ds-mar-t-xs-0_5 ds-mar-t-lg-0">
        <h1 class="ds-heading-3 ds-mar-b-0">Management@<span class="ds-font-weight-bold">PASIR</span></h1>
      </div>
    </div>
  </div>

<!-- Main Content -->
<div class="main ds-flex ds-bg-dark ds-pad-3 ds-mar-t-3" >
  <div class="ds-flex ds-flex-justify-center ds-flex-col">
    <h2 class="ds-heading-1 ds-mar-b-3 ds-slide-up ds-animation-delay-2"><?php echo $_GET['type']; ?></h2>
    <h4 class="ds-heading-3 ds-mar-b-3 ds-slide-up ds-animation-delay-4"><?php echo $_GET['body']; ?></h4>
    <!-- Width auto not working? -->
  </div> 
</div>

<?php
  include_once('../view/footer.php');
?>

</body>
</html>
<script src="../lib/w3ds/w3ds.js"></script>

<script>
  var themes = {
    // Default state properties and els
    compact: false,
    styleLink: document.querySelector('.style-switch'),
    highlight: document.querySelector('.active-highlight'),
    themes: [].slice.call(document.querySelectorAll('.theme')),
    
    // relevant options for the two themes
    opts: [
    {
      el: document.querySelector('.theme-default'),
      href: '../lib/w3ds/w3ds.css',
      status: false,
      translation: '0',
    },
    ],
    // Change the stylesheet to the corresponding theme, animate widget.
    setTheme: function(opt) {
      var _this = this;
      opt.el.classList.add('active-theme');
      this.highlight.style.transform = 'translate3d(' + opt.translation + 'px, 0, 0)';
      
      // Timeout so not too much is changing at once.
      setTimeout(function() {
        _this.styleLink.href = opt.href;
        _this.compact = opt.status;
      }, 100);
    },
    // Reset widget active theme.
    resetActive: function() {
      this.themes.forEach(function(theme) {
        theme.classList.remove('active-theme');
      });
    },
  };
    
</script>

<style>  
  .theme-switcher {
    display: flex;
    align-items: center;
    position: fixed;
    bottom: 10px;
    right: 10px;
    z-index: 9999;
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
  
  .ds-full-height {
    height: 100vh;
  }
  
  @media (max-width: 778px) {
    #ds-w3-injectable-nav {
      top: 45% !important;
    }
  }
</style>