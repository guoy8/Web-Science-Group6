<?php 
if(isset($_POST) and !empty($_POST))
{
  $username=$_POST['email'];
  $password=$_POST['password'];
  require_once('php/User.php');
  $user=new User();
  if(!$result=$user->login($username,$password))
  {
     if($user->getErrMessage()!="")
      {
        echo $user->getErrMessage();
      }else
      {
        echo "Email and password not in database";
      }
    
  }else
  {
    header("Refresh: 1; URL=index.php");
  }
}
?>
<!doctype html>
<!--[if IE 9]><html class="lt-ie10" lang="en" > <![endif]-->
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>.wavpool // Login</title>
    <link rel="stylesheet" href="css/foundation.min.css" />
    <link rel="stylesheet" href="css/general.css" />
  <style>
               
.center.row {
  height: 300px;
  margin-left: auto;
  margin-right: auto;
  margin-top: 5%;
  max-width: 500px;
}

.signup-panel {
  padding: 15px;
}

.signup-panel i {
  font-size: 30px;
  line-height: 50px;
  color: #999;
}

.signup-panel form input, .signup-panel form span {
  height: 50px;
}

.signup-panel .welcome {
  font-size: 26px;
  text-align: center;
  margin-left: 0;
}

.signup-panel .button {
  margin-left: 35%;
}

section.active {
  padding-top: 75px !important;
}

p.title {
  border-bottom: 1px solid #cccccc !important;
}

.content{
  height: 450px;
}
              
  </style>
</head>

<body>

<!-- Top Bar Test -->
    <section class="navigation-section show-for-large-up">
      <div class="row">
        <div class="large-12 columns">
          <nav class="top-bar" data-topbar>
            <ul class="title-area">
              <li class="name">
                <a href="index.php">
                  <img src="img/logo.png" alt="wavpool"/>
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
        
    <!-- End Test -->
    <!--
    <div class="center row">
      <div class="section-container tabs" data-section="tabs">
        
      <section class="active">
      <p class="title" data-section-title><a href="#">Sign Up</a></p>
      <div class="content" data-section-content>
        <p>
          <div class="row">
            <div class="large-12 columns">
              <div class="signup-panel">
                <p class="welcome">Hello, new user!</p>
                <form>
                  <div class="row collapse">
                    <div class="small-2  columns">
                      <span class="prefix"><i class="fi-torso"></i></span>
                    </div>
                    <div class="small-10  columns">
                      <input type="text" placeholder="Full Name">
                    </div>
                  </div>
                  <div class="row collapse">
                    <div class="small-2 columns">
                      <span class="prefix"><i class="fi-mail"></i></span>
                    </div>
                    <div class="small-10  columns">
                      <input type="text" placeholder="Email">
                    </div>
                  </div>
                  <div class="row collapse">
                    <div class="small-2 columns ">
                      <span class="prefix"><i class="fi-lock"></i></span>
                    </div>
                    <div class="small-10 columns ">
                      <input type="text" placeholder="Password">
                    </div>
                  </div>
                </form>
                <a href="#" class="button ">Sign Up! </a>
              </div>
            </div>
           </div></p>
      </div>
    </section>
    -->
        <section class="main-section row">
          <p class="title" data-section-title><a href="#">Sign In</a></p>
          <div class="content" data-section-content>
            <p>
              <div class="row">
                <div class="large-8 large-centered columns ">
                  <div class="signup-panel">
                    <p class="welcome">Welcome back!</p>
                    <!-- RegFrom Begin -->
                    <form action="login.php" method = "POST" id="regform" novalidate="novalidate">
                      <div class="row collapse">
                        <div class="small-2 columns">
                          <span class="prefix"><i class="fi-mail"></i></span>
                        </div>
                        <div class="small-10  columns">
                          <input name="email" type="email" placeholder="Email" 
                          value="<?php 
                          if(isset($_GET) and !empty($_GET))
                          {
                            echo $_GET['email'];
                          }
                          ?>" required>
                        </div>
                      </div>
                      <div class="row collapse">
                        <div class="small-2 columns ">
                          <span class="prefix"><i class="fi-lock"></i></span>
                        </div>
                        <div class="small-10 columns ">
                          <input name="password" type="password" placeholder="Password" required>
                        </div>
                      </div>
                      <input href="#" class="button" type="submit" value="Sign In">
                    </form>
                    <!-- Reg Form End -->
                    
                    <br>New User? Register <a href="register.php">here</a>.
                  </div>
                </div>
               </div></p>
          </div>
        </section>
      </div>
    </div>
             
  <script src="js/vendor/jquery.js"></script>
  <script src="js/vendor/modernizr.js"></script>
  <script src="js/foundation.min.js"></script>
  <script src="js/foundation/foundation.reveal.js"></script>
  <script src="js/jquery.validate.js"></script>
  <script>
  $(document).foundation();
  $(document).ready( function(){
    $("#regform").validate({
                rules: {
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
</html>
