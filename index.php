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

      <div class="col-xl-10 col-lg-12 col-md-9">

        <div class="card o-hidden border-0 shadow-lg my-5">
          <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div align="center">
              <div class="col-lg-6">
                <div class="p-5">
                  <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-4">Login</h1>
                  </div>

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
                      elseif ($_GET['error']=="alreadylogout") {
                        echo '<div class="alert alert-danger" role="alert">Your Already Logout. Please Log in again</div> ';
                      }
                    }
                    elseif (isset($_GET['succes'])) {
                      if ($_GET['succes']=="registered") {
                        echo '<div class="alert alert-success" role="succes">Succesfully registered, you now can continue to login </div> ';
                      }
                    }
                   ?>
                  <!-- form start -->
                  <form class="user" method="post" action="index.php">

                    <div class="form-group">
                      <input name="username" type="text" class="form-control form-control-user"  placeholder="Enter Username" value="<?=$name?>">
                    </div>

                    <div class="form-group">
                      <input name="password" type="password" class="form-control form-control-user" id="exampleInputPassword" placeholder="Password">
                    </div>

                    <input  class="btn btn-primary btn-user btn-block" type="submit" name="btn_submit" value="Login">
                    
                  </form>
                  <hr>
                  <div class="text-center">
                    <a class="small" href="forgot-password.html">Forgot Password?</a>
                  </div>
                  <div class="text-center">
                    <a class="small" href="register.php">Register Now</a>
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
    $dbpassword=$record['password'];
    $userid=$record['id'];
    $userlevel=$record['user_level'];

    //compare the passsword 
    if ($dbpassword==$password) {
        // check user admin or voter
        if ($userlevel==1) {
            $_SESSION['id']=$userid;
            header('Location: admin/admindashboard.php');
            exit(); 
        }
        else {
            $get_voter_id=mysqli_query($db,"SELECT voter_id,voter_name,faculty FROM voter WHERE email='$username'");
            $voter_id=mysqli_fetch_array($get_voter_id);
            $_SESSION['id']=$voter_id['voter_id'];
            $_SESSION['name']=$voter_id['voter_name'];
            $_SESSION['faculty']=$voter_id['faculty'];
             header('Location: voter/votingpageumum.php');
            exit(); 

        }

    }
    else 
       header("Location: index.php?error=wrongpassword&username=$username");

}
// else
//     header("Location: index.php?error=usernotfound");
}
?>