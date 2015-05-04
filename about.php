<?php
  session_start();

  $loginlg = '<li><a href="register.php">Login/Register</a></li>';
  $loginsm = '<li><a href="register.php">Login/Register</a></li>';

  $disabled = '';
  $type = isset($_SESSION['premium']) ? $_SESSION['premium'] : 0;
  if ($type == 0) { 
    $disabled = 'disabled'; 
    $type = 'Public';
  } else {
    $type = 'Premium';
  }
  
  if(isset($_GET) and !empty($_GET))
  {
    if($_GET['out']==1)
    {
      session_destroy();
      header("Refresh: 0; URL=index.php");
    }
  }
  if(isset($_SESSION['uid']) and isset( $_SESSION['username']) and isset($_SESSION['fullname']))
  {
    echo '<script type="javascript">alert("hi");</script>';
    $loginlg = '<li class="has-dropdown"><a href="#">' . $_SESSION['username'] . '</a>';
    $loginlg .= '<ul class="dropdown"><li class="text">' . $type . ' User </li><li><a href="index.php?out=1" onclick="logout()">Logout</a></li></ul></li>';

    $loginsm = '<li class="text username">Logged in as: <span>' . $_SESSION['username'] . '</span></li>';
    $loginsm .= '<li class="text indent"><i class="fa fa-right-arrow"></i>' . $type . ' User </li>';
    $loginsm .= '<li><a href="index.php?out=1" onclick="logout()" class="indent">Logout</a></li>';
  }

  
?>
<!doctype html>
<!--[if IE 9]><html class="lt-ie10" lang="en" > <![endif]-->
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>.wavpool // about</title>
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    <link href='http://fonts.googleapis.com/css?family=Arimo:400,400italic,700,700italic' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="css/foundation.min.css" />
    <link rel="stylesheet" href="css/general.css" />
    <link rel="stylesheet" href="css/home.css" />
  </head>
  <body>
  <!-- Start of large navigation bar -->
  <!-- NOTE: The "active" class has to be changed based on the page -->
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
                <li><a href="listen.php">Listen</a></li>
                <li><a href="create.php">Create</a></li>
                <li class="active"><a href="about.php">About</a></li>
                <?php echo $loginlg; ?>
              </ul>
            </section>
          </nav>
        </div>
      </div>
    </section>
    <!-- End of large navigation bar -->

    <!-- Start of wrap-around aside navigation bar -->
    <div class="off-canvas-wrap" data-offcanvas> 
      <div class="inner-wrap"> 
        <nav class="tab-bar hide-for-large-up"> 
          <section class="middle tab-bar-section"> 
            <h1 class="title">
              <a href="index.php">
                <img src="img/logo.png" alt=".wavpool"/>
              </a>
            </h1> 
          </section> 
          <section class="right-small"> 
            <a class="right-off-canvas-toggle menu-icon" href="#"><span></span></a> 
          </section> 
        </nav> 
        <aside class="right-off-canvas-menu"> 
          <ul class="off-canvas-list"> 
            <li><a href="index.php">Home</a></li>
            <li><a href="listen.php">Listen</a></li>
            <li><a href="create.php">Create</a></li>
            <li class="active"><a href="about.php">About</a></li>
            <?php echo $loginsm; ?>
          </ul> 
        </aside> 

    <!-- Start of Content -->
        <div class="row">
          <div class="large-12 large-centered columns">
            <h1>About</h1>
          </div>
        </div>
        <div class="row">
          <div class="large-12 large-centered columns">
            <div class="panel">
              <div class="row">
                <div class="large-11 large-centered columns">
                  <div class="about">
                    <p><strong>.wavpool</strong> is an interactive sound library that allows users to mix and play sounds to create a unique ambient environment. </p>
                    <p>Use preloaded sounds or upload your own! Layer and edit multiple sounds to create a new mix that can be saved with a user account.</p>
                    <p>Allow the weather feature to find your local weather conditions and recommend sounds in the opposite nature to put you in a new mood. The page will also style to match the opposite weather conditions to allow for complete immersion.</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="large-12 large-centered columns">
            <h1>Contact Us:</h1>
          </div>
        </div>
        <div class="row">
          <div class="large-12 columns">
            <div class="panel">
              <div class="row">
                <div class="large-2 columns">
                  <h4>Elise Baik</h4>
                  <h6 id="email">elisebaik@gmail.com</h6>
                </div>
                <div class="large-2 columns">
                  <h4>Kevan DuPont</h4>
                  <h6 id="email">duponk2@rpi.edu</h6>
                </div>
                <div class="large-2 columns">
                  <h4>Tina Guo</h4>
                  <h6 id="email">guoy8@rpi.edu</h6>
                </div>
                <div class="large-2 columns">
                  <h4>Qihua Hao</h4>
                  <h6 id="email">haoqihuadd@gmail.com</h6>
                </div>
                <div class="large-2 columns">
                  <h4>Sylvia He</h4>
                  <h6 id="email">hes2@rpi.edu</h6>
                </div>
                <div class="large-2 columns">
                  <h4>Tim Tse</h4>
                  <h6 id="email">timtse76@gmail.com</h6>
                </div>
              </div>
            </div>
          </div>
        </div>
        <section class="full-width copyright-section">
            <div class="row">
              <div class="small-12 columns">
                <p>© Copyright 2014</p>
              </div>
            </div>
          </section>
        </section>
      </div>
    </div>

    <script src="js/vendor/modernizr.js"></script>
    <script src="https://code.createjs.com/createjs-2014.12.12.min.js"></script>
    <script src="js/vendor/jquery.js"></script>
    <script src="js/foundation.min.js"></script>
    <script src="js/circular-slider.js"></script>
    <script>
      $(document).foundation();
    </script>
  </body>
</html>
