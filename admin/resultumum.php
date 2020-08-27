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

   $query="SELECT c.*,v.*,co.candidate_id,co.total_vote,ROUND(co.total_vote/(SELECT SUM(total_vote) from count WHERE section_id =0)*100,2) AS percentage 
    FROM count  AS co 
    JOIN candidate AS c 
    ON co.candidate_id=c.candidate_id
    JOIN voter AS v
    ON c.voter_id=v.voter_id
    WHERE c.section_id =0";
    $qr=mysqli_query($db,$query);
    if ($qr==false) {
        echo "Query cannot been executed<br>";
        echo "SQL error :".mysqli_error($db);
    }




include "include/launchelectionheader.php";
$colorscheme= array('#e6194B', '#3cb44b', '#ffe119', '#f58231', '#42d4f4', '#f032e6', '#fabed4', '#469990', '#dcbeff', '#9A6324', '#fffac8', '#800000', '#aaffc3', '#000075', '#a9a9a9', '#000000');

$dataPoints = array();

?>
<div class="container-fluid">

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">

          </div>

     <!-- Project Card Example -->
                            <div  class="card shadow mb-4">
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
                            array_push($dataPoints,array("label"=>$candidate['voter_name'], "y"=>$candidate['percentage']));
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
                        <div id="chartContainer" style="height: 100%; width: 100%;"></div>
                        <script src="../js/canvasjs.min.js"></script>
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
<script>
window.onload = function() {
         CanvasJS.addColorSet("colorchart",
                [//colorSet Array
                '#e6194B',
                '#3cb44b',
                '#ffe119',
                '#f58231',
                '#42d4f4',
                '#f032e6',
                '#fabed4',
                '#469990',
                '#dcbeff',
                '#9A6324',
                '#fffac8',
                '#800000',
                '#aaffc3',
                '#000075',
                '#a9a9a9',
                '#000000'               
                ]);
 
var chart = new CanvasJS.Chart("chartContainer", {
  theme: "light2",
  exportEnabled: true,
	animationEnabled: true,
  colorSet: "colorchart",

	data: [{
		type: "pie",
		indexLabel: "{y}",
		yValueFormatString: "#,##0.00\"%\"",
		indexLabelPlacement: "inside",
		indexLabelFontColor: "#36454F",
		indexLabelFontSize: 18,
		indexLabelFontWeight: "bolder",
		showInLegend: false,
		legendText: "{label}",
		dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
	}]
});
chart.render();
document.getElementById("printChart").addEventListener("click",function(){
    	chart.print();
    });  
}
</script>
<?php
include "include/footer.template.php";
?>