<?php session_start();

// get current local time
date_default_timezone_set('Asia/Kuala_Lumpur');
$time=date('Y-m-d H:i:s');

include "../connection.php";

//If your session isn't valid, it returns you to the login screen for protection
if(empty($_SESSION['id'])){
  echo " session id not exsit";
  header("location:../index.php?error=alreadylogout");
  exit();
}
// get voter id from SESSIONs
$voterid=$_SESSION['id'];

// check if election are ended if true go to election_endedpage.php
$check_running_election=mysqli_query($db,"SELECT * FROM election WHERE status = 'Running'");
if(mysqli_num_rows($check_running_election)==0){
  echo "election has ended";
  header("Location: error_votingpage.php?error=electionended");
  exit();
}
else{
  $election_detail=mysqli_fetch_array($check_running_election);
  $electionid=$election_detail['election_id'];
  // get election id,name and end time and assign to session
  $_SESSION['electionid']=$electionid;
  $_SESSION['electiontitle']=$election_detail['title'];
  $_SESSION['electionendtime']=$election_detail['end'];
  // change time format to match with current times
  $endtime=date("Y-m-d H:i:s", strtotime($_SESSION['electionendtime']));
    // check if election reach end time if true set election status to end
    if($time>="$endtime"){
      $status="End";
      $election_detail['status']=$status;
      $qr=mysqli_query($db,"UPDATE election set status='$status' WHERE  election_id=$electionid");
      if ($qr==false) {
        echo "Failed to update election status<br>";
        echo "SQL error :".mysqli_error($db);
      }
      echo "election has ended";
      header("Location: error_votingpage.php?error=electionended");
      exit();
    }
    else{
      // page will be refresh in 30 second
     header("Refresh:30");
    }
}
// check if voter already vote
$alreadyvote_inDB=mysqli_query($db,"SELECT * FROM alreadyvote WHERE voter_id= '$voterid' ");
if(mysqli_num_rows($alreadyvote_inDB)>0){
  echo ("Your already vote<br>");
  header('Location: error_votingpage.php?error=alreadyvote');
  exit();
}
// section instrution 
$section_instrution=mysqli_query($db,"SELECT * FROM section WHERE section_id=0");
$umum_instrution=mysqli_fetch_array($section_instrution);

// check data verification if all data okay insert the selection into the session and go to next page
if(isset($_POST['btn_submit_umum'])) {
    // check in no candidate is selected
    if (empty($_POST['umum_candidate_selected'])) {
        header("Location:votingpageumum.php?error=selection_empty");
    }
    // check if number of candidate > max vote
    elseif (count($_POST['umum_candidate_selected'])>$umum_instrution['max_vote']) {
        header('Location: votingpageumum.php?error=overflow');
    }
    elseif (count($_POST['umum_candidate_selected'])<$umum_instrution['max_vote']) {
        header('Location: votingpageumum.php?error=notenough');
    }
    else{
      $_SESSION['umum']=$_POST['umum_candidate_selected'];
      header('Location: votingpagefakulti.php');
    }
}

// get candidate informatiom from DB
$query="SELECT c.*,v.voter_name,v.matric_no,v.voter_id
FROM candidate as c 
JOIN voter as v 
ON c.voter_id=v.voter_id
WHERE c.section_id=0";

$candidate_information=mysqli_query($db,$query);
if ($candidate_information==false) {
    echo "Failed to get candidate information<br>";
    echo "SQL error :".mysqli_error($db);
}

include 'include/header_votingpage.php';
?>
<html>
<body>
  <div class="container" align="center" >  
      <div class="col-xl-7 col-lg-12 col-md-9">
        <div class="card o-hidden border-0 shadow-lg my-5">
          <div class="card-header py-3" >
            <h5 class="m-0  font-weight-bold text-primary">Calon Umum</h5>
          </div>
          <div class="card-body p-0">
                <div class="p-5">
                  <!-- error handling -->
                  <?php 
                      if (isset($_GET['error'])) {
                        if ($_GET['error'] == "selection_empty") {
                            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">'.$umum_instrution['section_instrution'].' '.$umum_instrution['max_vote'].' candidate!<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div> '; 
                        }
                        elseif ($_GET['error'] == "overflow") {
                            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">'.$umum_instrution['section_instrution'].' '.$umum_instrution['max_vote'].' candidate only!<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div> '; 
                        }
                        elseif ($_GET['error'] == "notenough") {
                            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert"> Not enough..'.$umum_instrution['section_instrution'].' '.$umum_instrution['max_vote'].' candidate!<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div> '; 
                        }
                      }
                      else
                          echo '<div class="alert alert-light" role="alert">'.$umum_instrution['section_instrution'].' '.$umum_instrution['max_vote'].' candidate!</div> ';
                   ?>
                  <!-- form start -->
                  <form name="form_umum" method="POST" action="votingpageumum.php">
                    <table class="table ">
                      <thead>
                         <tr>
                            <th scope="col">Candidate ID</th>
                            <th scope="col">Candidate Name</th>
                            <th scope="col">Action</th>
                          </tr>
                      </thead>
                      <tbody>
                        <?php
                          while ($record=mysqli_fetch_array($candidate_information)){//redo to other records
                        ?>
                      <tr>
                        <td ><?=$record['candidate_id']?></td>
                        <td><?=$record['voter_name']?></td>
                        <td>
                          <div class="form-check">
                            <input name="umum_candidate_selected[]" class="form-check-input" type="checkbox" value="<?=$record['candidate_id']?>" >
                          </div>
                        </td>
                      </tr>
                      <?php
                        }//end of records
                      ?>   
                      </tbody>
                    </table>
                    <input  class="btn btn-primary" type="submit" name="btn_submit_umum" value="Next">
                  </form>
                </div>
          </div>
        </div>
      </div>

</body>
</html>

<?php

 include 'include/footer_votingpage.php'
 ?>