<?php
  session_start();

  $loginlg = '<li><a href="register.php">Login/Register</a></li>';
  $loginsm = '<li><a href="register.php">Login/Register</a></li>';

  $type = 'Public';
  $disabled = '';
  if ($type === 'Public') { $disabled = 'disabled'; }
  if(isset($_GET) and !empty($_GET))
  {
    if($_GET['out']==1)
    {
      session_destroy();
      header("Refresh: 0; URL=listen.php");
    }
  }
  if(isset($_SESSION['uid']) and isset( $_SESSION['username']) and isset($_SESSION['fullname']))
  {
    echo '<script type="javascript">alert("hi");</script>';
    $loginlg = '<li class="has-dropdown"><a href="#">' . $_SESSION['fullname'] . '</a>';
    $loginlg .= '<ul class="dropdown"><li class="text">' . $type . 'User </li><li><a href="listen.php?out=1" onclick="logout()">Logout</a></li></ul></li>';

    $loginsm = '<li class="text username">Logged in as: <span>' . $_SESSION['username'] . '</span></li>';
    $loginsm .= '<li class="text indent"><i class="fa fa-right-arrow"></i>' . $type . ' User </li>';
    $loginsm .= '<li><a href="listen.php?out=1" onclick="logout()" class="indent">Logout</a></li>';
  }

  
?>
<!doctype html>
<!--[if IE 9]><html class="lt-ie10" lang="en" > <![endif]-->
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>.wavpool // Listen</title>
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    <link href='http://fonts.googleapis.com/css?family=Arimo:400,400italic,700,700italic' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="css/foundation.min.css" />
    <link rel="stylesheet" href="css/general.css" />
    <link rel="stylesheet" href="css/listen.css" />

  </head>
  <body onload="init()">
  <!-- Start of large navigation bar -->
    <section class="navigation-section show-for-large-up">
      <div class="row">
        <div class="large-12 columns">
          <nav class="top-bar" data-topbar>
            <ul class="title-area">
              <li class="name">
                <a href="index.php">
                  <img src="img/logo.png" alt=".wavpool"/>
                </a>
              </li>
            </ul>
            <section class="top-bar-section">
              <ul class="right">
                <li><a href="index.php">Home</a></li>
                <li class="active"><a href="listen.php">Listen</a></li>
                <li><a href="create.php">Create</a></li>
                <li><a href="about.php">About</a></li>
                <?php echo $loginlg; ?>
              </ul>
            </section>
          </nav>
        </div>
      </div>
    </section>
    <!-- End of side navigation bar -->

    <!-- Start of wrap-around aside navigation bar --> 
    <div class="off-canvas-wrap" data-offcanvas> 
      <div class="inner-wrap"> 
        <nav class="tab-bar hide-for-large-up"> 
          <section class="middle tab-bar-section"> 
            <a href="index.php">
              <img src="img/logo.png" alt=".wavpool"/>
            </a>
          </section> 
          <section class="right-small"> 
            <a class="right-off-canvas-toggle menu-icon" href="#"><span></span></a> 
          </section> 
        </nav> 
        <aside class="right-off-canvas-menu"> 
          <ul class="off-canvas-list"> 
            <li><a href="index.php">Home</a></li>
            <li class="active"><a href="listen.php">Listen</a></li>
            <li><a href="create.php">Create</a></li>
            <li><a href="about.php">About</a></li>
            <?php echo $loginsm; ?>
          </ul> 
        </aside> 
        <!-- Start of Content -->
        <h1 class="row">Listen</h1>

        <?php
          if(isset($_SESSION['uid']) and isset( $_SESSION['username']) and isset($_SESSION['fullname'])) {
            echo '<h2 class="row">User Mixes</h2>';
            echo '<div id="userLibrary" class="library row">';
            echo '<div class="small-12 loading">';
            echo '<i class="fa fa-circle-o-notch fa-spin"></i> Please wait while sounds are being loaded...';
            echo '</div></div>';
          }
        ?>

        <h2 class="row">Sound Library</h2>
        <div id="defaultLibrary" class="library row">
          <div class="small-12 loading">
            <i class="fa fa-circle-o-notch fa-spin"></i> Loading sounds...
          </div>
        </div>

        <div id="contact" class="row">
          <div class="large-12 columns">
            <div class="panel">
              <h4>Get in touch!</h4>
              <div class="row">
                <div class="large-9 columns">
                  <p>We'd love to hear from you, you attractive person you.</p>
                </div>
                <div class="large-3 columns">
                  <a href="#" class="radius button right">Contact Us</a>
                </div>
              </div>
            </div>
          </div>
        </div>      

      <section class="full-width copyright-section">
          <div class="row">
            <div class="small-12 columns">
              <p>© Copyright no one at all. Go to town.</p>
            </div>
          </div>
        </section>
      </section>

    <script src="js/vendor/modernizr.js"></script>
    <script src="https://code.createjs.com/createjs-2014.12.12.min.js"></script>
    <script src="js/vendor/jquery.js"></script>
    <script src="js/foundation.min.js"></script>
    <script src="js/circular-slider.js"></script>
    <script src="js/listen.js"></script>
    <script>
      $(document).foundation();
    </script>
  </body>
</html>
