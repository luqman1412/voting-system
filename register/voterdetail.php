 <?php session_start();
include('../connection.php');
// get matric number from session
$usermatric=$_SESSION['matric_no'];
echo "matric no:".$usermatric;

// check if btn_inserttoDB is clicked
if (isset($_POST['btn_inserttoDB'])) {
  $email=$_POST['txt_email'];
  $voterid=$_POST['txt_voter_id'];

  // validate the email format
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "not valid email";
    header('Location: voterdetail.php?error=invalidemailformat');
  }

  else{  
    $query="UPDATE  voter SET email='$email' WHERE voter_id='$voterid' ";
    $qr=mysqli_query($db,$query);
    if ($qr==false) {
        echo "Failed to insert email into DB<br>";
        echo "SQL error :".mysqli_error($db);
    }
    else {
      echo "succes";
      $_SESSION['email']=$email;
      header('Location: ../testmail.php');
    }
  }
}

// check if student already redister
$get_login_status=mysqli_query($db,"SELECT status FROM login WHERE username='$usermatric' ");
$user_status=mysqli_fetch_array($get_login_status);
if ($user_status['status'] == "registed") {
  echo "alredy register";
  header("Location: ../index.php?error=alreadyregister");
  exit();
}
// get student information from DB
$querry ="SELECT * FROM voter WHERE matric_no ='$usermatric' ";
$qr = mysqli_query($db,$querry);
if($qr==false){
    echo "Failed to find student<br>";
    echo "SQL error :".mysqli_error($db);
}
// check the matric number in DB
if (mysqli_num_rows($qr)==0) {
 header("Location: register.php?error=notfound");
}
else if (mysqli_num_rows($qr)==1){
    $record=mysqli_fetch_array($qr);
    $dbvoter_id=$record['voter_id'];
    $dbemail=$record['email'];
    // assign voter id to session
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

  <title>Kuis E-votings - Register</title>

  <!-- Custom fonts for this template-->
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="../css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body >

  <div class="container" align="center" style="width: 40rem;">

    <div class="card o-hidden border-0 shadow-lg my-5"  >
      <div class="card-header py-3" align="center">
        <h5 class="m-0  font-weight-bold text-primary">Insert Your Email</h5>
      </div>
      <div class="card-body p-0" >

        <!-- align to center in Card Body -->
        <div align="center">
            <!-- card padding -->
            <div class="p-5">
                      <!-- error handling -->
        <?php 
          if (isset($_GET['error'])) {
            if ($_GET['error']=="invalidemailformat") {
              echo '<div class="alert alert-danger" role="alert">Invalid Email Format</div> ';
            }
          }
         ?>
                <form class="user" method="post" action="voterdetail.php">

                    <!-- email textbox -->
                    <div class="form-group row">
                      <label for="email" class="col-sm-3 col-form-label">Email</label>
                      <div class="col-sm-9">
                        <input name="txt_email" type="text" class="form-control form-control-user" id="email" placeholder="Email" value="<?=$dbemail?>">
                        <input type="hidden" name="txt_voter_id" value="<?=$dbvoter_id?>">
                      </div>
                    </div>
                    <hr>
                    <!-- Submit button -->
                    <div class="col-sm-6">
                        <input class="btn btn-primary btn-user btn-block" type="Submit" name="btn_inserttoDB">
                    </div>    
                  </form>

              <!-- close card padding -->
            </div>
            <!-- close div size element in card  -->

          <!-- close align center in card body -->
        </div>
      </div>
    </div>

  </div>
</body>

</html>
