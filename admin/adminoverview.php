<?php
session_start();
require '../connection.php';

    // get election detail
if (isset($_GET['electionid'])) {
    $_SESSION['electionid']=$_GET['electionid'];
}
if (empty($electionid)) {
    $electionid=$_SESSION['electionid'];
}
//If your session isn't valid, it returns you to the login screen for protection
if(empty($_SESSION['id'])){
 header("location:../index.php?error=alreadylogout");
}    
    $query="SELECT * FROM election WHERE election_id=$electionid ";
    $qr=mysqli_query($db,$query);
    if ($qr==false) {
        echo "Query cannot been executed<br>";
        echo "SQL error :".mysqli_error($db);
    }
    $electiondetail=mysqli_fetch_array($qr);
    $_SESSION['canvote']=$electiondetail['canvote'];
    // get total voter
    $query="SELECT COUNT(*) FROM VOTER";
    $qr=mysqli_query($db,$query);
    if ($qr==false) {
        echo "Query cannot been executed<br>";
        echo "SQL error :".mysqli_error($db);
    }
    $record=mysqli_fetch_array($qr);
    $totalvoter=$record[0];
    // get total candidate
    $query="SELECT COUNT(*) FROM candidate";
    $qr=mysqli_query($db,$query);
    if ($qr==false) {
        echo "Query cannot been executed<br>";
        echo "SQL error :".mysqli_error($db);
    }
    $record2=mysqli_fetch_array($qr);
    $totalcandidate=$record2[0];

include "include/header.template.php";
?>
<div class="container-fluid">

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>

          </div>
		<div class="row">

			<!-- start date card -->
			<div class="col-xl-4 col-md-6 mb-4">
              <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                    	<!-- label -->
                      <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Start Date</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?=$electiondetail['start']?></div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-calendar fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- end date card -->
			<div class="col-xl-4 col-md-6 mb-4">
              <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                    	<!-- label -->
                      <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">End Date</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?=$electiondetail['end']?></div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-calendar fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
		</div>

		<div class="row">

			 <!-- Total voter card -->
			<div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-primary shadow h-100 py-1">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                    	<!-- label -->
                      <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Voter</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?=$totalvoter?></div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-user fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
		</div>

		<div class="row">

			 <!-- Total candidate card -->
			<div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-primary shadow h-100 py-1">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                    	<!-- label -->
                      <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Candidate</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?=$totalcandidate?></div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-user fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
		</div>
		<hr>
		<div class="p-2" align="right">
			<a href="electionlaunch.php"><button class="btn btn-primary">Launch Election</button></a>
		</div>

	</div>
</div>
<?php
include "include/footer.template.php";
?>