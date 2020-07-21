<?php
session_start();
include "../connection.php";
    // get election id
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
// get data from url
if (isset($_GET['data'])) {
  $sort_id=$_GET['data'];

  $query="SELECT c.*,v.*,co.candidate_id,co.total_vote/(SELECT COUNT(voter_id) * 8 FROM voter)*100 AS percentage 
    FROM count  AS co 
    JOIN candidate AS c 
    ON co.candidate_id=c.candidate_id
    JOIN voter AS v
    ON c.voter_id=v.voter_id
    WHERE c.section_id='$sort_id'";
      switch ($sort_id) {
    case '1':
      $fstm_status="active";
    break;
    case '2':
      $fsu_status="active";
    break;
    case '3':
      $fpm_status="active";
    break;
    case '4':
      $fppi_status="active";
    break;
    case '5':
      $fp_status="active";
    break;
  }
}
else{
  $all_status='active';
  $query="SELECT c.*,v.*,co.candidate_id,co.total_vote/(SELECT COUNT(voter_id) * 8 FROM voter)*100 AS percentage 
    FROM count  AS co 
    JOIN candidate AS c 
    ON co.candidate_id=c.candidate_id
    JOIN voter AS v
    ON c.voter_id=v.voter_id
    WHERE c.section_id =0";
}
    
    $qr=mysqli_query($db,$query);
    if ($qr==false) {
        echo "Query cannot been executed<br>";
        echo "SQL error :".mysqli_error($db);
    }

include "include/launchelectionheader.php";
?>
<div class="container-fluid">
<ul class="nav nav-pills">
              <li class="nav-item">
                <a class="nav-link <?=$all_status?> " href="resultfakulti.php">Umum</a>
              </li>
              <li class="nav-item">
                <a class="nav-link <?=$fstm_status?>" href="resultfakulti.php?data=1">FSTM</a>
              </li>
              <li class="nav-item">
                <a class="nav-link <?=$fpm_status?> " href="resultfakulti.php?data=3">FPM</a>
              </li>
              <li class="nav-item">
                <a class="nav-link <?=$fppi_status?> " href="resultfakulti.php?data=4">FPPI</a>
              </li>
              <li class="nav-item">
                <a class="nav-link <?=$fsu_status?> " href="resultfakulti.php?data=2">FSU</a>
              </li>
              <li class="nav-item">
                <a class="nav-link <?=$fp_status?> " href="resultfakulti.php?data=5">FP</a>
              </li>
            </ul>

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">

          </div>
          <div class="row">
     <!-- Project Card Example -->
            <div class="col-xl-8 col-lg-7">
              <div class="card shadow mb-4">
                <div class="card-header py-3">
                  <h6 class="m-0 font-weight-bold text-primary">Result </h6>
                </div>
                <div class="card-body">
                  <?php                     
                     while ($candidate=mysqli_fetch_array($qr)) { ?>
                            <h4 class="small font-weight-bold"><?="id".$candidate['voter_id']." ".$candidate['voter_name']?> <span class="float-right"><?=$candidate['percentage']?></span></h4>
                            <div class="progress mb-4">
                            <div class="progress-bar" role="progressbar" style="width: <?=$candidate['percentage']?>%" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                  <?php } ?>
                </div>
              </div>
            </div>

            <!-- Pie Chart -->
            <div class="col-xl-4 col-lg-5">
              <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Pie chart</h6>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                  <div class="chart-pie pt-4 pb-2">
                    <canvas id="myPieChart"></canvas>
                  </div>
                  <div class="mt-4 text-center small">
                    <span class="mr-2">
                      <i class="fas fa-circle text-primary"></i> Direct
                    </span>
                    <span class="mr-2">
                      <i class="fas fa-circle text-success"></i> Social
                    </span>
                    <span class="mr-2">
                      <i class="fas fa-circle text-info"></i> Referral
                    </span>
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
 <!-- Bootstrap core JavaScript-->
  <script src="../vendor/jquery/jquery.min.js"></script>
  <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="../js/sb-admin-2.min.js"></script>

  <!-- Page level plugins -->
  <script src="../vendor/chart.js/Chart.min.js"></script>

  <!-- Page level custom scripts -->
  <script src="../js/demo/chart-area-demo.js"></script>
  <script src="../js/demo/chart-pie-demo.js"></script>
  <script src="../js/demo/chart-bar-demo.js"></script>
