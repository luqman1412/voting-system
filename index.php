<?php  session_start(); ?>
<!DOCTYPE html>
<html>
<head>  
  
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>KUIS e-voting - Login</title>

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet"> 
</head>

<body>
  <div class="container" align="center" >

    <!-- Outer Row -->
    <div class="row justify-content-center">

      <div class="col-xl-7 col-lg-12 col-md-9">
        <!-- image on top  -->
        <div class="p-5">
         <img src="image/Lambang Kuis.png" alt="lambang kuis" width="550" height="90">
        </div>

        <div class="card o-hidden border-0 shadow-lg my-1">
          <div class="card-header py-3" align="center">
            <h4 class="m-0  font-weight-bold text-primary">Login</h4>
          </div>
          <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div align="center">
              <div class="col-lg-10">
                <div class="p-4">

                  <?php 
                  // error handling
                  $name="";
                    if (isset($_GET['error'])) {
                      if ($_GET['error'] == "emptyfield") {
                        echo '<div class="alert alert-danger" role="alert">Please fill all the Field!</div> ';
                      }
                      elseif ($_GET['error']== "wrongpassword") {
                        $name=$_GET['username'];
                        echo '<div class="alert alert-danger" role="alert">Wrong username or password!</div> ';
                      }
                      elseif ($_GET['error']=="usernotfound") {
                        echo '<div class="alert alert-danger" role="alert">User not found!</div> ';
                      }
                      elseif ($_GET['error']=="failedtosendemail") {
                        echo '<div class="alert alert-danger" role="alert">Failed to send email. Please Try again in few minute</div> ';
                      }
                      elseif ($_GET['error']=="alreadylogout") {
                        echo '<div class="alert alert-danger" role="alert">Your Already Logout. Please Log in again</div> ';
                      }
                      elseif ($_GET['error']=="notregister") {
                        echo '<div class="alert alert-danger" role="alert">Please register first</div> ';
                      }
                      elseif ($_GET['error']=="alreadyregister") {
                        echo '<div class="alert alert-danger" role="alert">Your already register?</div> ';
                      }
                    }
                    else if (isset($_GET['success'])) {
                      if ($_GET['success']=="checkemail") {
                        echo '<div class="alert alert-success" role="success">Succesfully send the OTP. Please check your email</div> ';
                      }
                      elseif ($_GET['success']=="registered") {
                        echo '<div class="alert alert-success" role="succes">Succesfully registered, you now can continue to login </div> ';
                      }
                      elseif ($_GET['success']=="successfullylogout") {
                        echo '<div class="alert alert-success" role="succes">Logout Succesfully</div> ';
                        
                      }
                      elseif ($_GET['success']=="passwordchange") {
                        echo '<div class="alert alert-success" role="succes">You password has been change, Please login using your new password</div> ';
                      }
                    }
                    else
                      echo '<div class="alert alert-info" role="succes">Please insert yout matric number and password</div> ';
                   ?>
                  <!-- form start -->
                  <form name="login_form" class="user" method="POST" action="index.php">

                    <div class="form-group">
                      <input name="username" type="text" class="form-control form-control-user"  placeholder="Enter Matric Number" value="<?=$name?>">
                    </div>

                    <div class="form-group">
                      <input name="password" type="password" class="form-control form-control-user" id="exampleInputPassword" placeholder="Password">
                    </div>

                    <input  class="btn btn-primary btn-user btn-block" type="submit" name="btn_submit" value="Login">
                    
                  </form>
                  </div>
              </div>
                  <div class="col-lg-11">
                    <hr>
                    <div class="p-3">
                      <div class="text-center">
                        <a class="small" href="forgot-password.html">Forgot Password?</a>
                      </div>
                      <div class="text-center">
                        <a class="small" href="register/register.php">Register Now</a>
                      </div>
                    </div>
                  </div>          
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
 <?php
if(isset($_POST['btn_submit'])){
$username=$_POST["username"];
$password=$_POST["password"];

// check if the text field are empty
if (empty($username)|| empty($password)) {
  header("Location: index.php?error=emptyfield");
  exit();
} 

include "connection.php";
$query="SELECT * FROM login WHERE username='$username' ";
$qr=mysqli_query($db,$query);
if($qr==false){
    echo "Failed to find user<br>";
    echo "SQL error :".mysqli_error($db);
}
//check password 
if (mysqli_num_rows($qr)==1) {
    $record =mysqli_fetch_array($qr);
    if($qr==false){
      echo "Failed to get user information<br>";
      echo "SQL error :".mysqli_error($db);
    }
    $dbpassword=$record['password'];
    $userid=$record['id'];
    $userlevel=$record['user_level'];
    echo "status:". $record['status'];

    // check if student are register
    if ($record['status']=="registed") {
        // check if password != null if true check the user password
        if ($record['password']!=NULL) {
            //compare the passsword 
            if ($dbpassword==$password) {
                // check user admin or voter
                if ($userlevel==1) {
                    $_SESSION['id']=$userid;
                    $_SESSION['name']=$username;
                    header('Location: admin/admindashboard.php');
                    exit(); 
                }
                else if ($userlevel==2){
                    $get_voter_id=mysqli_query($db,"SELECT voter_id,voter_name,faculty FROM voter WHERE matric_no='$username'");
                    $voter_id=mysqli_fetch_array($get_voter_id);
                    if($get_voter_id==false){
                        echo "Failed to find user <br>";
                        echo "SQL error :".mysqli_error($db);
                    }
                    $_SESSION['id']=$voter_id['voter_id'];
                    $_SESSION['name']=$voter_id['voter_name'];
                    $_SESSION['faculty']=$voter_id['faculty'];
                     header('Location: voter/votingpageumum.php');
                    exit(); 
                }
                else
                  echo "Error on find user";
            }
            else{
              header("Location: index.php?error=wrongpassword&username=$username");
              exit();
              
            }
        }
        // check if password = null if true redirect user to set new password page
        elseif ($record['password']==NULL) {
            if ($record['OTP']==$password) {
                $get_matric_no=mysqli_query($db,"SELECT matric_no FROM voter WHERE matric_no='$username'");
                    $matric_no=mysqli_fetch_array($get_matric_no);
                    if($get_matric_no==false){
                        echo "Failed to find user <br>";
                        echo "SQL error :".mysqli_error($db);
                    }
                $_SESSION['matric_no']=$matric_no['matric_no'];
                header('Location:newpasswordpage.php');
                exit();
            }
            elseif ($record['OTP'] !=$password) {
              header("Location: index.php?error=wrongpassword&username=$username");
              exit();
            }
        }
    }
    // return to login page if student are not register
    elseif ($record['status']=='not register') {
      header("Location: index.php?error=notregister");
      exit();
      
    }
    else
      echo " Error on status user";
}
else
    header("Location: index.php?error=usernotfound");
}
include 'include/footer.template.php';
?>