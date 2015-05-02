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
    header("Refresh: 0; URL=login.php?email={$username}");
    echo "<script type='text/javascript'>alert('Now you are in! Jump to login page');</script>";
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
                <li><a href="about.php">About</a></li>
                <li class="active"><a href="register.php">Login/Register</a></li>
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
            <li><a href="about.php">About</a></li>
            <li class="active"><a href="register.php">Login/Register</a></li>
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
                <form action="register.php" method="post" id="regform">
                  <div class="row collapse">
                    <div class="small-2  columns">
                      <span class="prefix"><i class="fi-torso"></i></span>
                    </div>
                    <div class="small-10  columns">
                      <input name="fullname" type="text" placeholder="Username" name="fullname">
                    </div>
                  </div>
                  <div class="row collapse">
                    <div class="small-2 columns">
                      <span class="prefix"><i class="fi-mail"></i></span>
                    </div>
                    <div class="small-10  columns">
                      <input name="email" id="email" class="form-control" type="email" placeholder="Email" name="email">
                    </div>
                  </div>
                  <div class="row collapse">
                    <div class="small-2 columns ">
                      <span class="prefix"><i class="fi-lock"></i></span>
                    </div>
                    <div class="small-10 columns ">
                      <input name="password" type="password" name="password" placeholder="Password">
                    </div>
                  </div>
                  <input type="submit" href="#" class="button " value="Sign Up!"> 
                </form>
                <p>Already registered? Click <a href="login.php">here</a> to sign in</p>
              </div>
            </div>
           </div></p>
      </div>
    </section> 
        <section class="main-section">
        </section>
  <script src="js/vendor/jquery.js"></script>
  <script src="js/jquery.validate.js"></script>
  <script>
  $(document).ready( function(){
    $("#regform").validate({
                rules: {
                    fullname: {
                      required: true

                    },
                    email: {
                        required: true,
                        email: true
                    },
                    password: {
                        required: true,
                        minlength: 5
                    },
                },
                messages: {
                  fullname:{
                    required: "Please enter your name",

                  },
                    password: {
                        required: "Please provide a password",
                        minlength: "Your password must be at least 5 characters long"
                    },
                    email: "Please enter a valid email address",
                },
                submitHandler: function(form) {
                    form.submit();
                }
            });
  }
    );
  </script>
	</body>
</html>