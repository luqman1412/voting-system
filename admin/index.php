<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Kuis e-voting system- Login</title>

  <!-- Custom fonts for this template-->
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="../css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body class="bg-gradient-primary">

  <div class="container">

    <!-- Outer Row -->
    <div class="row justify-content-center">

      <div class="col-xl-6 col-lg-6 col-md-6">
        <div class="col-lg-6 d-none d-lg-block p-4">
          <img src="../image/lambangkuis-full.png" alt="lambang kuis" width="500" height="90">
        </div>
        <div class="card o-hidden border-0 shadow-lg my-3">
          <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">

              <div class="col-lg-12">
                <div class="p-5">
                  <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-4">Welcome To Kuis E-voting System!</h1>
                    <h3 class="h6 text-gray-900 mb-4"> Adminstration Only</h3>


                  </div>
                  <?php 
                  include '../alertfunction.php';
                  // error handling
                  $name="";
                    if (isset($_GET['error'])) {
                      if ($_GET['error'] == "emptyfield") {
                        alertwithclose("Please fill all the field");
                      } elseif ($_GET['error']== "wrongpassword") {
                          $name=$_GET['username'];
                          alertwithclose("Wrong username or password!");
                      } elseif ($_GET['error']=="usernotfound") {
                          alertwithclose("User not found!");
                      } elseif ($_GET['error']=="failedtosendemail") {
                          alertwithclose("Failed to send email. Please try again in few minute!");
                      } elseif ($_GET['error']=="alreadylogout") {
                          alertwithclose("You Already Logout. Please Log in again!");
                      } elseif ($_GET['error']=="notregister") {
                          alertwithclose("Please register first!");
                      } elseif ($_GET['error']=="alreadyregister") {
                          alertwithclose("You already register!");
                      } elseif ($_GET['error']=="notauthorised") {
                          alertwithclose("You not authorised");
                      } elseif($_GET['error']=="failedtoresetpassword") {
                          alertwithclose("Failed to reset password, Please try again in few minute!");
                      }
                    } else if (isset($_GET['success'])) {
                        if ($_GET['success']=="checkemail") {
                          successwithclose("Please check your student email, We have sent you the otp for login");
                        } elseif ($_GET['success']=="registered") {
                          successwithclose("Succesfully registered, you now can continue to login");
                        } elseif ($_GET['success']=="successfullylogout") {
                          successwithclose("Logout Succesfully");
                        } elseif ($_GET['success']=="passwordchange") {
                          successwithclose("Please login using your new password");
                        }
                    }
                   ?>
                  <form name="login_form" class="user" method="POST" action="validateuser.php">
                    <div class="form-group">
                      <input name="txt_username" type="text" class="form-control form-control-user" id="matric_no" aria-describedby="matric_no" placeholder="Enter Username..." required autofocus>

                    </div>
                    <div class="form-group">
                      <input name="txt_password" type="password" class="form-control form-control-user" id="password" placeholder="Password" required>
                    </div>
                    <input  class="btn btn-primary btn-user btn-block" type="submit" name="btn_login" value="Login" autofocus>
                    
                  </form>
                  <hr>
                  <div class="text-center">
                    <a class="small" href="../index.php">I'am Student</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

    </div>

  </div>

  <!-- Bootstrap core JavaScript-->
  <script src="../vendor/jquery/jquery.min.js"></script>
  <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="../js/sb-admin-2.min.js"></script>

</body>

</html>
