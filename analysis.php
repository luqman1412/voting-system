<?php
// declare array for chart
$label = array("Total Voter Turout","Total Student");
$total =array();
$colorscheme= array('#3cb44b','#e6194B');

require 'connection.php';
// get faculty id in URL
if (isset($_GET['data'])) {
  $faculty=$_GET['data'];

  switch ($faculty) {
    case '1':
      $facultyname="FSTM Student";
      $fstm="active";
      break;
    case '2':
      $facultyname="FSU Student";
      $fsu="active";
      break;
    case '3':
      $facultyname="FPM Student";
      $fpm="active";
      break;
    case '4':
      $facultyname="FPPI Student";
      $fppi="active";
      break;
    case '5':
      $facultyname="FP Student"; 
      $fp="active";
      break;

  }

  // get total alreadyvote by faculty
  $sql= "SELECT COUNT(v.voter_id) AS ttl,v.faculty 
         FROM alreadyvote as a 
         JOIN voter as v
         ON v.voter_id=a.voter_id
         WHERE v.faculty='$faculty' ";
  $get_ttlAlreadyvote=mysqli_query($db,$sql);
  if (mysqli_error($db)) {
    echo "ERROR : ".mysqli_error($db);
    exit();
  }
  // check total voter
  $sql="SELECT COUNT(voter_id) as ttl 
        FROM voter 
        WHERE NOT EXISTS(SELECT voter_id FROM alreadyvote WHERE alreadyvote.voter_id=voter.voter_id) 
        AND faculty='$faculty' ";
  $get_ttlVoter=mysqli_query($db,$sql);
  if (mysqli_error($db)) {
    echo "Error".mysql_error($db);
    exit();
  }

}
else{
  $all="active";
  $facultyname= "All Student";
    // get total alreadyvote
    $sql= "SELECT COUNT(voter_id)as ttl 
           FROM alreadyvote";
    $get_ttlAlreadyvote=mysqli_query($db,$sql);
    if (mysqli_error($db)) {
      echo "ERROR : ".mysqli_error($db);
      exit();
    }
     // check total voter
    $sql="SELECT COUNT(voter_id) as ttl 
          FROM voter 
          WHERE NOT EXISTS(SELECT voter_id FROM alreadyvote WHERE alreadyvote.voter_id=voter.voter_id)";
    $get_ttlVoter=mysqli_query($db,$sql);
    if (mysqli_error($db)) {
      echo "Error".mysql_error($db);
      exit();
    }

}
  // get total already vote
  $ttlAlreadyVote=mysqli_fetch_array($get_ttlAlreadyvote);
  array_splice($total, 0,2,$ttlAlreadyVote['ttl']);
  // get total voter
  $ttlVoter=mysqli_fetch_array($get_ttlVoter);
  array_splice($total, 1,2,$ttlVoter['ttl']);

?>
<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Kuis e-voting system</title>

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
  <!-- CDN link for pie chart -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body class="bg-gradient-primary">

  <div class="container">

    <!-- Outer Row -->
    <div class="row justify-content-center">

      <div class="col-xl-6 col-lg-6 col-md-6">
        <div class="col-lg-6 d-none d-lg-block p-4">
          <img src="image/lambangkuis-full.png" alt="lambang kuis" width="500" height="90">
        </div>
        <div class="card o-hidden border-0 shadow-lg my-3">
          <div class="card-body p-5">
            <!-- mini navbar -->
            <ul class="nav nav-pills nav-fill">
              <li class="nav-item">
                <a class="nav-link <?=$all?>" href="analysis.php">All</a>
              </li>
              <li class="nav-item">
                <a class="nav-link <?=$fstm?>" href="analysis.php?data=1">FSTM</a>
              </li>
              <li class="nav-item">
                <a class="nav-link <?=$fsu?>" href="analysis.php?data=2">FSU</a>
              </li>
              <li class="nav-item">
                <a class="nav-link <?=$fpm?>" href="analysis.php?data=3">FPM</a>
              </li>
              <li class="nav-item">
                <a class="nav-link <?=$fppi?>" href="analysis.php?data=4">FPPI</a>
              </li>
              <li class="nav-item">
                <a class="nav-link <?=$fp?>" href="analysis.php?data=5">FP</a>
              </li>
            </ul>
            <!-- Nested Row within Card Body -->
            <div class="row">

              <div class="col-lg-12">
                <div class="p-4">
                  <div class="text-center">
                    <h1 class="h3 text-gray-900 mb-4">Voter Turnout for <?=$facultyname?> </h1>
                    <div class="chart-pie pt-4 pb-2">
                      <canvas id="myChart"></canvas>
                    </div>
                    <hr>
                    <div>
                    <a class="btn btn-primary" href="index.php">Vote Now!</a>
                      
                    </div>
                  </div>
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
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin-2.min.js"></script>

</body>

</html>
<!--  Javascript code for Pie Chart -->
<script>
var ctx = document.getElementById('myChart').getContext('2d');
var chart = new Chart(ctx, {
    // The type of chart we want to create
    type: 'doughnut',

    // The data for our dataset
    data: {
        labels: <?php echo json_encode($label);?> ,
        datasets: [{
            backgroundColor: <?php echo json_encode($colorscheme);?>,
            data: <?php echo json_encode($total, JSON_NUMERIC_CHECK);?>
        }]
    },

    // Configuration options go here
      options: {
    maintainAspectRatio: false,
    tooltips: {
      backgroundColor: "rgb(255,255,255)",
      bodyFontColor: "#858796",
      borderColor: '#dddfeb',
      borderWidth: 1,
      xPadding: 15,
      yPadding: 15,
      displayColors: true,
      caretPadding: 10,
    },
    legend: {
      display: true
    },
    cutoutPercentage: 0,
  }
});

</script>