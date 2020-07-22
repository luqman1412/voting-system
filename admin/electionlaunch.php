<?php
session_start();
include "../connection.php";
// get current local time
date_default_timezone_set('Asia/Kuala_Lumpur');
$time=date('Y-m-d H:i:s');
echo "this is current time $time<br>"; 
// get election detail
if (isset($_GET['electionid'])) {
    $_SESSION['electionid']=$_GET['electionid'];
}
if (empty($electionid)) {
    $electionid=$_SESSION['electionid'];
}
//If your session isn't valid, it returns you to the login screen for protection
if(empty($_SESSION['id'])){
 header("location:../index.php");
}
    // set election status to running
    $status="Running";
    $query="UPDATE election set status='$status'
            WHERE  election_id=$electionid";
    $qr=mysqli_query($db,$query);
    if ($qr==false) {
         echo "Query cannot been executed<br>";
        echo "SQL error :".mysqli_error($db);
    }

    $query="SELECT * FROM election WHERE election_id=$electionid ";
    $qr=mysqli_query($db,$query);
    if ($qr==false) {
        echo "Query cannot been executed<br>";
        echo "SQL error :".mysqli_error($db);
    }
    $electiondetail=mysqli_fetch_array($qr);
    $election_starttime=date("Y-m-d H:i:s", strtotime($electiondetail['start']));
    echo "<br> ".$election_starttime ;
    $electionstatus=$electiondetail['status'];
    $endtime=date("Y-m-d H:i:s", strtotime($electiondetail['end']));

    // check if election reach end time
    if($time>="$endtime"){
      // set election status to end
      $status="End";
      $electionstatus=$status;

      $qr=mysqli_query($db,"UPDATE election set status='$status' WHERE  election_id=$electionid");
      if ($qr==false) {
        echo "Failed to update election status<br>";
        echo "SQL error :".mysqli_error($db);
      }
    }
    else{
     header("Refresh:60");
    }
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
    $totalcandidate=mysqli_fetch_array($qr);
    $totalcandidate=$totalcandidate[0];

include "include/launchelectionheader.php";
?>
<div class="container-fluid">

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">

          </div>
          <?php 
          // check election status
          if ($time>="$endtime") {
              echo '<div class="alert alert-success" role="alert">The election has ended <a href="resultpage.php" class="alert-link">View result >> </a>.</div> ';
          }
          elseif ($time <"$election_starttime") {
              echo '<div class="alert alert-success" role="alert">The election will start on '.$election_starttime.' This page should refresh in 60 seconds..</div> ';
          }

          elseif ($time >="$election_starttime") {
            echo '<div class="alert alert-success" role="alert">Your election is running and will automatically end on: '.$endtime.' .</div> ';
          } 


           ?>

    <div class="row">

       <!-- Election status card -->
      <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-primary shadow h-100 py-1">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <!-- label -->
                      <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Election Name</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?=$electiondetail['title']?></div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-archive fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
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
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?=$election_starttime?> </div>
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
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?=$endtime?></div>
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
              <div class="card border-left-info shadow h-100 py-1">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                    	<!-- label -->
                      <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Voter</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?=$totalvoter?></div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

       <!-- Total candidate card -->
      <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-info shadow h-100 py-1">
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

            <!-- Election status card -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-warning shadow h-100 py-1">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <!-- label -->
                      <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Election Status</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?=$electionstatus?></div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-battery-three-quarters fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

		</div>

		<hr>
		<div class="p-2" align="right">
			<button class="btn btn-primary">End Election</button>
		</div>

	</div>
</div>
<?php
include "include/footer.template.php";
?>