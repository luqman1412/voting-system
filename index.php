<?php session_start();
if (isset($_POST['btn_login'])) {
  require 'connection.php';

  $matricno=$_POST['txt_matricno'];
  $qr=mysqli_query($db,"SELECT matric_no FROM voter WHERE matric_no='$matricno' ");
  if (mysqli_error($db)==true) {
    echo "Error: ".mysqli_error($db);
    exit();
  }

  if (mysqli_num_rows($qr)!=1) {
    header('Location: login.php?error=usernotfound');
    exit();
  }
  // delete any request record inn DB
  $deleterecord=mysqli_query($db,"DELETE FROM requestlogin WHERE matric_no= '$matricno' ");
  if (mysqli_error($db)) {
    echo "Error: ".mysqli_error($db);
    exit();
  }
  $subject="Kuis e-voting system login request";
  $emailstatus= sendVerification($matricno,$subject);

  if ($emailstatus) {
    $_SESSION['matric_no']=$matricno;
    header('Location: studentverification.php?success=emailsended');
  }
  else
    header('Location:login.php?error=failedtosendemail');
}

 ?>
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
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body class="bg-gradient-primary">

  <div class="container">

    <!-- Outer Row -->
    <div class="row justify-content-center">

      <div class="col-xl-6 col-lg-6 col-md-6">
        <div class="col-lg-6 d-none d-lg-block p-4">
          <img src="image/lambangkuis-full.png" alt="lambang kuis" width="500" height="90">
        </div>
        <div class="card o-hidden border-0 shadow-lg my-3">
          <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">

              <div class="col-lg-12">
                <div class="p-5">
                  <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-4">Welcome To Kuis E-voting System!</h1>

                  </div>
                  <?php 
                  include 'alertfunction.php';
                  // error handling
                  $name="";
                    if (isset($_GET['error'])) {
                      if ($_GET['error'] == "emptyfield") {
                        alertwithclose("Please fill all the field");
                      } elseif ($_GET['error']== "wrongpassword") {
                          $name=$_GET['username'];
                          alertwithclose("Wrong username or password!");
                      } elseif ($_GET['error']=="usernotfound") {
                          alertwithclose("Student not found!");
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
                  <form name="login_form" class="user" method="POST" action="login.php">
                    <div class="form-group">
                      <input name="txt_matricno" type="text" class="form-control form-control-user" id="matric_no" aria-describedby="matric_no" placeholder="Enter Matric number..." required >
                      <small>Insert your matric number (e.g: 123242)</small>
                    </div>

                    <input  class="btn btn-primary btn-user btn-block" type="submit" name="btn_login" value="Continue">
                    
                  </form>

                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

    </div>

  </div>

  <!-- Bootstrap core JavaScript-->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin-2.min.js"></script>

</body>

</html>
<?php 
 function sendVerification($matric_no,$subject){
// DB connection
  require 'connection.php';

 // create pin 
$pin=random_int(10000, 99999);
$hashpin=md5($pin);
 // message in email
$messagesubject=$subject;
$headers = "Content-Type: text/html; charset=ISO-8859-1\r\n";
$message ="This is your code for verify your request";
$message .="<br>Code: "."<b>".$pin."</b>";
$message .="<br>If your not request to reset password, please ignore this email";

$defaultemail= '1839011@student.kuis.edu.my';

      // send email 
    // check whether email has been send and return to login page with message
    if (mail($defaultemail, $messagesubject, $message,$headers)) {
      $query= mysqli_query($db,"INSERT INTO requestlogin (matric_no,otp) VALUES ('$matric_no','$hashpin')");
      if($query==false){
          echo "Failed to load login request<br>";
          echo "SQL error :".mysqli_error($db); 
        }
        else
          return true;
    }
    else
      return false;
 }
 ?>
