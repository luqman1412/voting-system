<?php session_start();
include 'sendVerification.php';
require '../connection.php';

if (isset($_POST['btn_resetpassword'])) {
    $matricno=$_POST['txt_matric_number'];
    $_SESSION['matric_no']=$matricno;

    // find match in DB
    $querry=mysqli_query($db,"SELECT * FROM login WHERE username= $matricno");
    if($querry==false){
      echo "Failed to find student<br>";
      echo "SQL error :".mysqli_error($db);
    }
    $record = mysqli_fetch_array($querry);

    if ($record['status'] !="registed") {
      echo "your are not register yet, please register";
      exit();
    }

     // delete request record 
    $deleterecord= mysqli_query($db,"DELETE FROM resetpassword WHERE matric_no = $matricno");
    if($deleterecord==false){
      echo "Failed to delete request reset password record<br>";
      echo "SQL error :".mysqli_error($db);
      exit();
    }
    $subject="Kuis e-voting system reset pasword request";
    $emailstatus=sendVerification($matricno,$subject);
    
    // send the user to the next if true and user to index login page if false
    header(($emailstatus) ? 'Location: requestverification.php?success=emailsended': 'Location: ../index.php?error=failedtoresetpassword') ;

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

  <title>KUIS E-voting System - Forgot Password</title>

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

      <div class="col-xl-6 col-lg-6 col-md-9">

        <div class="card o-hidden border-0 shadow-lg my-5">
          <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
              <div class="col-lg-12">
                <div class="p-5">
                  <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-4">Forgot Your Password?</h1>

                  </div>
                  <form class="user" method="POST" action="<?=$_SERVER['PHP_SELF'];?>">
                    <div class="form-group">
                      <input type="text" name="txt_matric_number" class="form-control form-control-user" placeholder="Enter Matric Number...">
                    </div>
                    <input type="submit" name="btn_resetpassword" class="btn btn-primary btn-user btn-block" value="Reset Password"> 

                  </form>
                  <hr>
                  <div class="text-center">
                    <a class="small" href="../register">Not register yet? Register here!</a>
                  </div>
                  <div class="text-center">
                    <a class="small" href="../index.php">Already have an account? Login!</a>
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
