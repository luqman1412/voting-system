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
  $query="SELECT c.*,v.*,co.candidate_id,co.total_vote,ROUND(co.total_vote/(SELECT SUM(total_vote) from count WHERE section_id ='$sort_id') *100,2) AS percentage 
    FROM count  AS co 
    JOIN candidate AS c 
    ON co.candidate_id=c.candidate_id
    JOIN voter AS v
    ON c.voter_id=v.voter_id
    WHERE c.section_id ='$sort_id'";
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
  // umum results
  $all_status='active';
  // variable for querry

  $query="SELECT c.*,v.*,co.candidate_id,co.total_vote,ROUND(co.total_vote/(SELECT SUM(total_vote) from count WHERE section_id =0)*100,2) AS percentage 
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

// variable for pie chart
$candidatename = array();
$totalvotereceive =array();
$colorscheme= array('#e6194B', '#3cb44b', '#ffe119', '#f58231', '#42d4f4', '#f032e6', '#fabed4', '#469990', '#dcbeff', '#9A6324', '#fffac8', '#800000', '#aaffc3', '#000075', '#a9a9a9', '#000000');

include "include/launchelectionheader.php";
?>



<div class="container-fluid">
<ul class="nav nav-pills">
              <li class="nav-item">
                <a class="nav-link <?=$all_status?> " href="resultpage.php">Umum</a>
              </li>
              <li class="nav-item">
                <a class="nav-link <?=$fstm_status?>" href="resultpage.php?data=1">FSTM</a>
              </li>
              <li class="nav-item">
                <a class="nav-link <?=$fpm_status?> " href="resultpage.php?data=3">FPM</a>
              </li>
              <li class="nav-item">
                <a class="nav-link <?=$fppi_status?> " href="resultpage.php?data=4">FPPI</a>
              </li>
              <li class="nav-item">
                <a class="nav-link <?=$fsu_status?> " href="resultpage.php?data=2">FSU</a>
              </li>
              <li class="nav-item">
                <a class="nav-link <?=$fp_status?> " href="resultpage.php?data=5">FP</a>
              </li>
            </ul>

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">

          </div>
          <div class="row">
     <!-- Project Card Example -->
            <div class="col-xl-12 col-lg-12">
              <div class="card shadow mb-4">
                <div class="card-header py-3">
                  <h6 class="m-0 font-weight-bold text-primary">Result </h6>
                  
                </div>
                <div class="card-body">
                  <div class="row">
                      <!-- voter name -->
                      <div class="col-6">
                        <table class="table table-bordered">
                          <tr>
                            <th>id</th>
                            <th class="row-1">candidate name</th>
                            <th class="row-1">total vote</th>
                            <th class="row-1">percentage</th>
                          </tr>
                        <?php
                          $color=0;
                          while ($candidate=mysqli_fetch_array($qr)) { 
                            // asign value for pie chart value
                            array_push($totalvotereceive, $candidate['total_vote']);
                            array_push($candidatename, $candidate['voter_name']);
                        ?>
                          <tr>
                            <td><?=$candidate['candidate_id']?></td>
                            <td ><i class="fas fa-square" style="color:<?=$colorscheme[$color]?>"></i> <?=$candidate['voter_name']?></td>
                            <td ><?=$candidate['total_vote']?></td>
                            <td ><?=$candidate['percentage']?>%</td>
                          </tr>
                        <?php 
                          $color++;
                          // close loop
                          } 
                        ?>
                        </table>
                      </div>
                      <!-- chart  -->
                      <div class="col-6">
                        <div class="chart-pie pt-4 pb-2">
                          <canvas id="myChart"></canvas>
                        </div>
                      </div>
                  </div>

                </div>
              </div>
            </div>

          </div>

  </div>
</div>
<?php
include "include/footer.template.php";
?>
<!--  Javascript code for Pie Chart -->
<script>
var ctx = document.getElementById('myChart').getContext('2d');
var chart = new Chart(ctx, {
    // The type of chart we want to create
    type: 'doughnut',

    // The data for our dataset
    data: {
        labels: <?php echo json_encode($candidatename);?> ,
        datasets: [{
            backgroundColor: <?php echo json_encode($colorscheme);?>,
            data: <?php echo json_encode($totalvotereceive, JSON_NUMERIC_CHECK);?>
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
      displayColors: false,
      caretPadding: 10,
    },
    legend: {
      display: false
    },
    cutoutPercentage: 0,
  }
});

</script>
 