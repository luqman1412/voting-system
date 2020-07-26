<?php session_start();
include 'include/header_votingpage.php';
if (isset($_GET['error'])) {
	$header_message="Sorry..";
	if ($_GET['error']=="electionended") {
		$error_message= "Election has Ended";
	}
	elseif ($_GET['error']=="alreadyvote") {
		$error_message= "You already vote for this election";
	}

}
if (isset($_GET['success'])) {
	$header_message="Congratulation..";

	if ($_GET['success']=="succesfullyvote") {
		$error_message=" Your vote succesfully inserted";
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
<div class="container" align="center" >  
      <div class="col-xl-7 col-lg-12 col-md-9">
        <div class="card o-hidden border-0 shadow-lg my-5">
          <div class="card-header py-3" >
            <h5 class="m-0  font-weight-bold text-primary"><?=$header_message?></h5>
          </div>
          <div class="card-body p-0">
                <div class="p-5">
                	<h4><?=$error_message?> </h4>
                	<br>
                	<h5>Your will logout in 5 second </h5>

                </div>
          </div>
      </div>
  </div>
</div>
</body>
</html>

<?php 
 header('Refresh:5; url= ../logout.php');
include 'include/footer_votingpage.php';
 ?>