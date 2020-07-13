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

    $query="SELECT c.*,v.*,co.candidate_id,co.total_vote/(SELECT COUNT(voter_id) * 8 FROM voter)*100 AS percentage 
    FROM count  AS co 
    JOIN candidate AS c 
    ON co.candidate_id=c.candidate_id
    JOIN voter AS v
    ON c.voter_id=v.voter_id
    WHERE c.section_id=0";
    $qr=mysqli_query($db,$query);
    if ($qr==false) {
        echo "Query cannot been executed<br>";
        echo "SQL error :".mysqli_error($db);
    }




include "include/launchelectionheader.php";
?>
<div class="container-fluid">

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">

          </div>

     <!-- Project Card Example -->
              <div class="card shadow mb-4">
                <div class="card-header py-3">
                  <h6 class="m-0 font-weight-bold text-primary">Result </h6>
                </div>
                <div class="card-body">

                  <?php                     
                      while ($candidate=mysqli_fetch_array($qr)) {       
                    ?>
                  <h4 class="small font-weight-bold"><?="id".$candidate['voter_id']." ".$candidate['voter_name']?> <span class="float-right"><?=$candidate['percentage']?></span></h4>
                  <div class="progress mb-4">
                    <div class="progress-bar" role="progressbar" style="width: <?=$candidate['percentage']?>%" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
                  </div>
<?php 
}
   ?>
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