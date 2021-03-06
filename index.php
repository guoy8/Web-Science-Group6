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
    <title>.wavpool // ambient sound mixer</title>
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    <link href='http://fonts.googleapis.com/css?family=Arimo:400,400italic,700,700italic' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="css/foundation.min.css" />
    <link rel="stylesheet" href="css/general.css" />
    <link rel="stylesheet" href="css/home.css" />
  </head>
  <body onload="init()">
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
                <li class="active"><a href="index.php">Home</a></li>
                <li><a href="listen.php">Listen</a></li>
                <li><a href="create.php">Create</a></li>
                <li><a href="about.php">About</a></li>
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
            <li class="active"><a href="index.php">Home</a></li>
            <li><a href="listen.php">Listen</a></li>
            <li><a href="create.php">Create</a></li>
            <li><a href="about.php">About</a></li>
            <?php echo $loginsm; ?>
          </ul> 
        </aside> 

    <!-- Start of Content -->
        <section id="example" class="row">

          <!-- All sound sliders go here -->
          <div id="tracks" class="small-12 medium-12 large-12 columns">
            <div id="track4" class="center"></div>
            <div id="track3" class="center"></div>
            <div id="track2" class="center"></div>
            <div id="track1" class="center"></div>
            <div id="track0" class="center"></div>

            <!-- Buttons on the slider interface -->
            <div class="center">
              <button id="playAll" class="centerbtn small"><i class="fa fa-fw fa-play"></i></button>
            </div>
          </div>
        </section>

        <section id="error" class="row">
          <div class="small-12 medium-12 large-12 columns">
            <h1>Sorry!</h1>
            <p>SoundJS is not currently supported in your browser.</p>
            <p>
              Please <a href="http://github.com/CreateJS/SoundJS/issues" target="_blank">log a bug</a> with the device and browser you are using. Thank you.
              </p>
          </div>
        </section>
        <br>
        <!-- Weather API -->
        <div id="weather_panel" class="row panel">
          <div class="small-8 columns">
            <img id = "weather" src="./img/weather_rain.jpg">
          </div>
          <div class="small-4 columns">
            <h3>Sound of the Day</h3>
            <h4>.WAV Pool's weather API is unlike any other weather API where we will use advanced technology to locate the weather of your current location and formulate an output of the opposite weather condition.</h4> 
          </div>
        </div>

        <!-- Show 3 random mixes -->
        <div class="row small-12">
          <div class="small-12 loading">
            <i class="fa fa-circle-o-notch fa-spin"></i> Please wait while sounds are being loaded...
          </div>
          <ul id="random" class="library small-block-grid-1 medium-block-grid-2 large-block-grid-3"></ul>
        </div>
        
     
        <div class="row">
          <div class="large-12 columns">
          
            <div id="contact" class="panel">
                  
              <div class="row">
                <div class="large-9 columns">
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
                <p>© Copyright 2015</p>
              </div>
            </div>
          </section>
        </section>
      </div>
    </div>

    <script src="js/vendor/jquery.js"></script>
    <script src="js/vendor/modernizr.js"></script>
    <script src="https://code.createjs.com/createjs-2014.12.12.min.js"></script>
    <script src="js/foundation.min.js"></script>
    <script src="js/foundation/foundation.slider.js"></script>
    <script src="js/foundation/foundation.accordion.js"></script>
    <script src="js/foundation/foundation.reveal.js"></script>
    <script src="js/circular-slider.js"></script>
    <script src="js/index.js"></script>
  </body>
</html>
