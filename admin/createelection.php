<?php session_start();
//If your session isn't valid, it returns you to the login screen for protection
if(empty($_SESSION['id'])){
 header("location:../index.php?error=alreadylogout");
}
include "../connection.php";
include "include/blankheader.php";
$check_activeelection=mysqli_query($db,"SELECT * FROM election WHERE status!='End' ");
// check if there are active election
if(mysqli_num_rows($check_activeelection)>0){
header("Location: admindashboard.php?error=activeelection");
}
// set default value
$default_electionname="";

if (isset($_POST['btn_submit_createelection'])) {
    // get current time
    date_default_timezone_set('Asia/Kuala_Lumpur');
    $crttime=date('Y-m-d H:i:s');
    // get data and set to local variable
    $id=$_SESSION['id'];
    $electionname=$_POST['txt_electionname'];
    $electionstart=$_POST['txt_start'];
    $electionend=$_POST['txt_end'];
    $elction_eligible=$_POST['canvote'];
    // check if there are field that empty
    if (empty($electionname)||empty($electionstart)||empty($electionend)) {
      // if there are empty field return the page with error message
      header('Location: createelection.php?error=emptyfield');
    }
    elseif ($electionend <= $crttime) {
      header('Location: createelection.php?error=pastendtime&elecname='.$electionname);
    }

    else{
        $status="Paused";

        $query="INSERT INTO election (owner_id,title,start,end,status,canvote) VALUES('$id','$electionname','$electionstart','$electionend','$status','$elction_eligible')";
        $qr=mysqli_query($db,$query);
        if ($qr==false) {
            echo "Failed to save election into DB<br>";
            echo "SQL error :".mysqli_error($db);
        }
        else {
            // get the lastest id in DB
            $lastid=mysqli_insert_id($db);
            $_SESSION['electionid']=$lastid;
            header('Location: adminoverview.php');
        }
    }
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

  <title>KUIS e-voting - Create new Election</title>

  <!-- Custom fonts for this template-->
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
  
  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body >
  <div class="container" align="center" style="width: 40rem;">
    <div class="card o-hidden border-0 shadow-lg my-5"  >
      <div class="card-header py-3" >
        <h4 class="m-0  font-weight-bold text-primary">Create an Election</h4>
      </div>
      <div class="card-body p-0" >
        <!-- align to center in Card Body -->
        <div align="center">
            <!-- card padding -->
            <div class="p-5">
              <!-- form start -->
              <form class="user" method="post" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>">
              		<!-- align left for label  -->
              	<div align="left">

     		           <!-- title textbox -->
              		 <div class="form-group">
                      <div align="center">
                      </div>
                      <?php 
                        if (isset($_GET['error'])) {
                          if ($_GET['error'] == "emptyfield") {
                            echo '<div class="alert alert-danger" role="alert">Please fill all the Field!</div> ';
                          } 
                          if ($_GET['error']==="pastendtime") {
                            $default_electionname=$_GET['elecname'];
                            echo '<div class="alert alert-danger" role="alert">Election time is pasted!</div> ';
                            
                          }
                        }
                        else
                            // echo '<div class="alert alert-danger" role="alert">Please fill all the Field!</div> ';
                      ?>
                     	<label>Title</label>
                     	<input name="txt_electionname" type="text"  class="form-control form-control-user" placeholder="Election Title" value="<?=$default_electionname?>">
                   	 </div>
                   	 <!-- start date textbox -->
              		 <div class="form-row">
	    				<div class="form-group col-md-6">
	    					<label>Election Start</label>
	    					<input name="txt_start" type="datetime-local" class="form-control form-control-user" >
	 				   	</div>
	 				   	<!-- end date textbox -->

					   	<div class="form-group col-md-6">
					  		<label>Election End</label>
					   		<input name="txt_end" type="datetime-local" class="form-control form-control-user" >
					  	</div>
              <div class="form-group col-md-6">
                <label>who eligible to vote?</label>
                <select name='canvote'  class='form-control'>
                  <option value="0" selected >All voter</option>
                  <option value="1" >Fstm voter</option>
                  <option value="2" >Fsu voter</option>
                  <option value="3" >Fpm voter</option>
                  <option value="4" >Fppi voter</option>
                  <option value="5" >Fp voter</option>
                </select>
              </div>
					 </div>
              	</div>
              	<hr> 
                <div class="col-md-6">
                  <input class="btn btn-primary btn-user btn-block" type="submit" name="btn_submit_createelection" value="Continue">
                </div>   
              </form>
              <!-- close card padding -->
            </div>
          <!-- close align center in card body -->
        </div>
      </div>
    </div>
  </div>
</body>
</html>
<?php include 'include/footer.template.php' ?>