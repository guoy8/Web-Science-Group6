<?php
if(isset($_POST) and !empty($_POST))
{
  $username=$_POST['email'];
  $password=$_POST['password'];
  $fullname=$_POST['fullname'];
  require_once('php/User.php');
  $user=new User();
  if(!$result=$user->register([$username,$password,$fullname]))
  {
    echo $user->getErrMessage();
  }else
  {
    echo "<script type='text/javascript'>alert('Now you are in!jump to login page');</script>";
    header("Refresh: 3; URL=login.php?email={$username}");
  }
}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
    	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
    	<title>Register</title>
    	<link rel="stylesheet" href="css/foundation.min.css" />
    	<link rel="stylesheet" href="css/general.css" />
      <style>
        .welcome {
          font-family: 'Arimo', sans-serif;
          font-size: 2em;
          color: #FFF;
        }
      </style>
	</head>
	<body>
	<!-- TOP BAR -->
	<section class="navigation-section show-for-large-up">
      <div class="row">
        <div class="large-12 columns">
          <nav class="top-bar" data-topbar>
            <ul class="title-area">
              <li class="name">
                <a href="index.html">
                  <img src="img/logo.png" alt=".wavpool"/>
                </a>
              </li>
            </ul>
            <section class="top-bar-section">
              <ul class="right">
                <li><a href="index.html">Home</a></li>
                <li><a href="listen.html">Listen</a></li>
                <li><a href="create.html">Create</a></li>
                <li><a href="about.html">About</a></li>
                <li class="active"><a href="register.html">Login/Register</a></li>
              </ul>
            </section>
          </nav>
        </div>
      </div>
    </section>
    <div class="off-canvas-wrap" data-offcanvas> 
      <div class="inner-wrap"> 
        <nav class="tab-bar hide-for-large-up"> 
          <section class="middle tab-bar-section"> 
            <h1 class="title">
              <a href="index.html">
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
            <li><a href="index.html">Home</a></li>
            <li><a href="listen.html">Listen</a></li>
            <li><a href="create.html">Create</a></li>
            <li><a href="about.html">About</a></li>
            <li class="active"><a href="register.html">Login/Register</a></li>
          </ul> 
        </aside>
        <!-- END TOP BAR -->
         <section class="active">
      <p class="title" data-section-title><a href="#"></a></p>
      <div class="content" data-section-content>
        <p>
          <div class="row">
            <div class="large-4 large-centered columns">
              <div class="signup-panel">
                <p class="welcome">Hello, new user!</p>
                <form action="register.php" method="post">
                  <div class="row collapse">
                    <div class="small-2  columns">
                      <span class="prefix"><i class="fi-torso"></i></span>
                    </div>
                    <div class="small-10  columns">
                      <input type="text" placeholder="Full Name" name="fullname">
                    </div>
                  </div>
                  <div class="row collapse">
                    <div class="small-2 columns">
                      <span class="prefix"><i class="fi-mail"></i></span>
                    </div>
                    <div class="small-10  columns">
                      <input id="email" class="form-control" type="email" placeholder="Email" name="email">
                    </div>
                  </div>
                  <div class="row collapse">
                    <div class="small-2 columns ">
                      <span class="prefix"><i class="fi-lock"></i></span>
                    </div>
                    <div class="small-10 columns ">
                      <input type="password" name="password" placeholder="Password">
                    </div>
                  </div>
                  <input type="submit" href="#" class="button " value="Sign Up!"> 
                </form>
                <p>Already registered? Click <a href="login.html">here</a> to sign in</p>
              </div>
            </div>
           </div></p>
      </div>
    </section> 
        <section class="main-section">
        </section>
    <script src="js/validate.min.js"></script>
    <script>
      (function() {
      // These are the constraints used to validate the form
      var constraints = {
        email: {
          // Email is required
          presence: true,
          // and must be an email (duh)
          email: true
        }
    };
})
    </script>
	</body>
</html>