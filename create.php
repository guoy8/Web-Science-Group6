<?php
  session_start();

  $loginlg = '<li><a href="register.php">Login/Register</a></li>';
  $loginsm = '<li><a href="register.php">Login/Register</a></li>';

  $disabled = '';
  $type = isset($_SESSION['premium']) ? $_SESSION['premium'] : 0;
  if ($type === 0) { 
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
      header("Refresh: 0; URL=create.php");
    }
  }
  if(isset($_SESSION['uid']) and isset( $_SESSION['username']) and isset($_SESSION['fullname']))
  {
    echo '<script type="javascript">alert("hi");</script>';
    $loginlg = '<li class="has-dropdown"><a href="#">' . $_SESSION['fullname'] . '</a>';
    $loginlg .= '<ul class="dropdown"><li class="text">' . $type . ' User </li><li><a href="create.php?out=1" onclick="logout()">Logout</a></li></ul></li>';

    $loginsm = '<li class="text username">Logged in as: <span>' . $_SESSION['username'] . '</span></li>';
    $loginsm .= '<li class="text indent"><i class="fa fa-right-arrow"></i>' . $type . ' User </li>';
    $loginsm .= '<li><a href="create.php?out=1" onclick="logout()" class="indent">Logout</a></li>';
  }

  
?>

<!doctype html>
<!--[if IE 9]><html class="lt-ie10" lang="en" > <![endif]-->
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>.wavpool // create </title>
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    <link href='http://fonts.googleapis.com/css?family=Arimo:400,400italic,700,700italic' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="css/foundation.min.css" />
    <link rel="stylesheet" href="css/general.css" />
    <link rel="stylesheet" href="css/create.css" />
  </head>

  <body onload="init()">

<!-- For large screens, show top navbar-->
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
                <li class="active"><a href="create.php">Create</a></li>
                <li><a href="about.php">About</a></li>
                <?php echo $loginlg ?>
              </ul>
            </section>
          </nav>
        </div>
      </div>
    </section>

<!-- For mobile screens, show a menu icon that opens up to side nav-->
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
            <li class="active"><a href="create.php">Create</a></li>
            <li><a href="about.php">About</a></li>
            <?php echo $loginsm ?>
          </ul> 
        </aside> 

<!-- All content goes here -->
        <section class="main-section"> 

          <!-- This holds the audio player interface -->
          <section id="audio" class="full-width">
            <div id="interface" class="row">
              <div class="small-12 medium-12 large-12 columns">
                <div id="maxError"></div>

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
                  <div class="side">
                    <span data-tooltip aria-haspopup="true" data-options="disable_for_touch:true" class="has-tip tip-top" title="Add/load new sounds">
                      <a href="#" class="small button addSound" data-reveal-id="addSoundOpts"><i class="fa fa-fw fa-plus-circle"></i></a>
                    </span>
                    <span data-tooltip aria-haspopup="true" data-options="disable_for_touch:true" class="has-tip tip-top" title="Edit current sounds">
                      <a href="#" class="small button editSound" data-reveal-id="editSoundOpts"><i class="fa fa-fw fa fa-pencil-square-o"></i></a>
                    </span>
                    <span data-tooltip aria-haspopup="true" data-options="disable_for_touch:true" class="has-tip tip-top" title="Save mix">
                      <a href="#" class="small button saveSound" data-reveal-id="saveSound"><i class="fa fa-fw fa-floppy-o"></i></a>
                    </span>
                  </div>
                </div>

                <!-- Add a new sound options -->
                <div id="addSoundOpts" class="reveal-modal medium" data-reveal aria-labelledby="addSoundTitle" aria-hidden="true" role="dialog">
                  <h3 id="addSoundTitle"><i class="fa fa-fw fa-plus-circle"></i> Add a new sound:</h3>
                  <!-- List of available sounds -->
                  <form> 
                    <select id="library" multiple="multiple" class="select"></select>
                  </form>
                  <!-- Preview/Add -->
                  <ul id="addBtns" class="button-group even-2">
                    <li><button id="previewBtn" class="small button disabled"><i class="fa fa-play"></i> Preview</button></li>
                    <li><button id="addBtn" href="#" class="small button disabled"><i class="fa fa-plus"></i> Add Sound</button></li>
                  </ul>

                  <!-- Advanced Options-->
                  <ul class="accordion small-12 medium-12 large-12 columns" data-accordion="">
                    <li class="accordion-navigation">
                      <a href="#addOpts">Sound Options</a>
                      <div id="addOpts" class="content active">

                        <!-- Sound Volume -->
                        <div class="row">
                          <div class="small-2 medium-2 large-2 columns slabel">Volume:</div>
                          <div class="small-9 medium-9 large-9 columns">
                            <div id="addVolume" class="range-slider disabled" data-slider data-options="display_selector: #addVOutput;">
                              <span class="range-slider-handle" role="slider" tabindex="0"></span>
                              <span class="range-slider-active-segment"></span>
                            </div>
                          </div>
                          <div class="small-1 medium-1 large-1 columns slabel">
                            <span id="addVOutput" class="output"></span>
                          </div>
                        </div>

                        <!-- Loop -->
                        <div class="row">
                          <div class="small-2 medium-2 large-2 columns">Loop:</div>
                          <div id="addLoop" class="loopbtn small-10 medium-10 large-10 columns">
                            <input type="radio" name="addloop" value="-1" id="yesLoop" checked disabled><label for="yesLoop">Yes</label>
                            <input type="radio" name="addloop" value="1" id="noLoop" disabled><label for="noLoop">No</label>
                          </div>
                        </div>

                        <!-- Pan -->
                        <div class="row">
                          <div class="small-2 medium-2 large-2 columns">
                            <span class="has-tip" data-tooltip aria-haspopup="true" title="If able, sound is localized to the chosen direction.">Pan:</span>
                          </div>
                          <div id="addPan" class="panbtn small-10 medium-10 large-10 columns">
                            <input type="radio" name="addpan" value="-1" id="addLeft" disabled><label for="addLeft">Left</label>
                            <input type="radio" name="addpan" value="0" id="addCenter" checked disabled><label for="addCenter">Center</label>
                            <input type="radio" name="addpan" value="1" id="addRight" disabled><label for="addRight">Right</label>
                          </div>
                        </div>
                      </div>
                    </li>
                  </ul>

                  <?php
                    if(isset($_SESSION['uid']) and isset( $_SESSION['username']) and isset($_SESSION['fullname'])) {
                      echo '<div class="row"><div class="medium-12 large-6 columns">';
                      // Load Mix
                      echo '<h3 id="loadSoundTitle"><i class="fa fa-fw fa-folder-open"></i> Load sound mix: </h3>';
                      // List of user mixes -->
                      echo '<form><select id="mixLibrary" multiple="multiple" class="select"></select></form>';
                      echo '<button id="loadBtn" class="small button disabled small-12 medium-12 large-12" onclick="loadMix()"><i class="fa fa-plus"></i> Load</button>';
                      echo '</div><div class="medium-12 large-6 columns">';
                      echo '<h3 id="saveFileTitle"><i class="fa fa-fw fa-upload"></i> Upload Sound: </h3>';
                      echo '<form action="uploadFile.php" class="dropzone" id="fileUpload">';
                      echo '<div class="fallback"><input type="file" name="file" id="fileToUpload"></div></form>';
                      echo '<label>Sound Title: <input type="text" id="soundTitle"></label>';
                      echo '<button id="upload" class="small button disabled small-12"><i class="fa fa-plus"></i> Upload</button>';
                      echo '</div>';
                    }
                  ?>

                  <a class="close-reveal-modal" aria-label="Close">&#215;</a>
                </div>

                <!-- Edit current playing sounds -->
                <div id="editSoundOpts" class="reveal-modal medium" data-reveal aria-labelledby="editSoundTitle" aria-hidden="true" role="dialog">
                  <h3 id="editSoundTitle"><i class="fa fa-fw fa fa-pencil-square-o"></i> Edit a current sound:</h3>
                  <!-- List of available sounds -->
                  <form>
                    <select id="nowPlaying" multiple="multiple" disabled="disabled" class="select">
                      <option value="-1">-- No Sounds Playing --</option>
                    </select>
                  </form>
                  <!-- Play/Stop/Remove -->
                  <ul id="editBtns" class="button-group">
                    <li><button id="playPauseBtn" class="small button disabled"><i class="fa fa-play"></i> Play</button></li>
                    <li><button id="stopBtn" class="small button disabled"><i class="fa fa-stop"></i> Stop</button></li>
                    <li><button id="removeBtn" class="small button disabled"><i class="fa fa-eject"></i> Remove</button></li>
                    <li><button id="removeAllBtn" class="small button"><i class="fa fa-times"></i> Remove All</button></li>
                  </ul>
                
                  <!-- Advanced Options -->
                  <ul class="accordion small-12 medium-12 large-12 columns" data-accordion="">
                    <li class="accordion-navigation">
                      <a href="#editOpts">Sound Options</a>
                      <div id="editOpts" class="content active">

                        <!-- Sound Volume -->
                        <div class="row">
                          <div class="small-2 medium-2 large-2 columns slabel">Volume:</div>
                          <div class="small-9 medium-9 large-9 columns">
                            <div id="editVolume" class="range-slider disabled" data-slider data-options="display_selector: #editVOutput;">
                              <span class="range-slider-handle" role="slider" tabindex="0"></span>
                              <span class="range-slider-active-segment"></span>
                            </div>
                          </div>
                          <div class="small-1 medium-1 large-1 columns slabel">
                            <span id="editVOutput" class="output"></span>
                          </div>
                        </div>

                        <!-- Loop -->
                        <div class="row">
                          <div class="small-2 medium-2 large-2 columns">Loop:</div>
                          <div id="editLoop" class="loopbtn small-10 medium-10 large-10 columns">
                            <input type="radio" name="editloop" value="-1" id="yesELoop" checked disabled><label for="yesELoop">Yes</label>
                            <input type="radio" name="editloop" value="1" id="noELoop" disabled><label for="noELoop">No</label>
                          </div>
                        </div>

                        <!-- Pan -->
                        <div class="row">
                          <div class="small-2 medium-2 large-2 columns">
                            <span class="has-tip"data-tooltip aria-haspopup="true" title="Pans the sound to the chosen direction">Pan:</span>
                          </div>
                          <div class="panbtn small-10 medium-10 large-10 columns">
                            <input type="radio" name="editpan" value="-1" id="editLeft" disabled><label for="editLeft">Left</label>
                            <input type="radio" name="editpan" value="0" id="editCenter" checked disabled><label for="editCenter">Center</label>
                            <input type="radio" name="editpan" value="1" id="editRight" disabled><label for="editRight">Right</label>
                          </div>
                        </div>
                      </div>
                    </li>
                  </ul>
                  <a class="close-reveal-modal" aria-label="Close">&#215;</a>
                </div>

                <!-- Save mix -->
                <div id="saveSound" class="reveal-modal medium" data-reveal aria-labelledby="saveSoundTitle" aria-hidden="true" role="dialog">
                  <h3 id="saveSoundTitle"><i class="fa fa-fw fa-floppy-o"></i> Save this sound mix:</h3>
                  <form id="saveMixes" onsubmit="return false;">
                    <div class="row"> 
                      <div class="large-12 columns"> 
                        <label>Name <input id="soundname" type="text" placeholder="Name your sound mix" /> </label> 
                      </div> 
                    </div> 
                    <div class="row"> 
                      <div class="large-4 columns">
                        <label>Share your mix with others?</label>
                        <input type="radio" name="savetype" value="public" id="public" checked><label for="public">Public</label>
                        <input type="radio" name="savetype" value="private" id="private" <?php echo $disabled ?> ><label for="private">Private</label>
                      </div>
                      <div class="large-8 columns"> 
                        <label>Category</label> 
                        <input name="category" id="checkbox1" type="checkbox" value="Animal"><label for="checkbox1">Animal</label> 
                        <input name="category" id="checkbox2" type="checkbox" value="Cool"><label for="checkbox2">Cool</label> 
                        <input name="category" id="checkbox3" type="checkbox" value="Human"><label for="checkbox2">Human</label> 
                        <input name="category" id="checkbox4" type="checkbox" value="Instrumental"><label for="checkbox2">Instrumental</label>
                        <input name="category" id="checkbox5" type="checkbox" value="Nature"><label for="checkbox2">Nature</label>
                        <input name="category" id="checkbox6" type="checkbox" value="Warm"><label for="checkbox2">Warm</label>
                      </div> 
                    </div> 
                    <button id="save" class="small button" onclick="saveMix()">Save</button>
                  </form>
                  <a class="close-reveal-modal" aria-label="Close">&#215;</a>
                </div>
              </div>
            </div>

            <!-- If SoundJS is not supported in browser -->
            <div id="error" class="row">
              <div class="small-12 medium-12 large-12 columns">
                <h2>Error!</h2> Your browser does not support SoundJS
              </div> 
            </div>

          </section>

          <!-- Footer copyright -->
          <div class="full-width copyright-section">
            <div class="row">
                <div class="small-12 columns">
                    <p>&copy; 2014</p>
                </div>
            </div>
          </div>
        </section> 
        <a class="exit-off-canvas"></a> 
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
    <script src="js/dropzone.js"></script>
    <script src="js/create.js"></script>
  </body>
</html>