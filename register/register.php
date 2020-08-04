<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>KUIS e-voting - Register</title>

  <!-- Custom fonts for this template-->
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
  
  <!-- Custom styles for this template-->
  <link href="../css/sb-admin-2.min.css" rel="stylesheet">

</head>
<body >
  <div class="container">
    <div class="row">
      <!-- for empty space on the left -->
      <div class="col"> </div>
      <div class="col-xl-6"> <br>    
        <div class="card o-hidden border-0 shadow-lg my-5">
          <div class="card-header py-3" align="center">
            <h5 class="m-0  font-weight-bold text-primary">Insert Your Matric Number for Verification</h5>
          </div>
          <div class="card-body p-1" >        
          <div class="p-5">
            <?php 
              if (isset($_GET['error'])) {
                if ($_GET['error']=="notfound") {
                  echo '<div class="alert alert-danger" role="alert">Matric number did not exist!</div> ';
                }
                else if ($_GET['error']=="emptyfield") {
                  echo '<div class="alert alert-danger" role="alert">Please insert Your matric number</div> ';
                }
              }
            ?>
          <!-- form start -->
          <form class="user" method="POST" action="register.php">
            <div align="center" class="form-group row">
              <label for="matric_no" class="col-sm-4 col-form-label">Matric number: </label>
              <div class="col-sm-8">
                <input name="txt_matric_no" type="text" class="form-control form-control-user" id="matric_no" placeholder="Please insert your matric number">
              </div>
            </div>
            <hr>
            <div align="center">
              <div class="form-group col-xl-6">
               <input class="btn btn-primary btn-user btn-block" type="submit" name="btn_submit_matric_no" value="Submit">
              </div>
            </div>
          </form>
          </div>
          </div>
        </div>
      </div>
      <!-- for empty space on the right -->
      <div class="col"> </div>
    </div>
  </div>
</body>
</html>

<?php 
if (isset($_POST['btn_submit_matric_no'])) {
  $_SESSION['matric_no']=$_POST['txt_matric_no'];

  if (empty($_POST['txt_matric_no'])) {
    header('Location: register.php?error=emptyfield');
  }
  else
   header('Location: voterdetail.php');
}

include '../admin/include/footer.template.php'; ?>