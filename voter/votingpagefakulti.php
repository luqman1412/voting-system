<?php
session_start();
include "../connection.php";
// get current local time
date_default_timezone_set('Asia/Kuala_Lumpur');
$time=date('Y-m-d H:i:s');

//If your session isn't valid, it returns you to the login screen for protection
if(empty($_SESSION['id'])){
header("location:../index.php?error=alreadylogout");
}

// get voter id, voter faculty_id & election id from session
$voterid=$_SESSION['id'];
$voterfaculty=$_SESSION['faculty'];
$electionid=$_SESSION['electionid'];

// check if voter already vote
$alreadyvote_inDB=mysqli_query($db,"SELECT * FROM alreadyvote WHERE voter_id= '$voterid' AND election_id= '$electionid' ");
if(mysqli_num_rows($alreadyvote_inDB)>0){
  echo ("Your already vote<br>");
  header('Location: error_votingpage.php?error=alreadyvote');
}
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
  }
  else{
   header("Refresh:30");
  }

$pilihanumum=$_SESSION['umum'];

// get section instrution from DB
$get_section_instruction=mysqli_query($db,"SELECT * FROM section WHERE section_id =$voterfaculty ");
$section_instrution_maxvote=mysqli_fetch_array($get_section_instruction);

// check data verification if all data okay insert the selection into the session and go to next page
if(isset($_POST['btn_submit_vote'])) {
    // check in no candidate is selected
    if (empty($_POST['fakulti_candidate_selected'])) {
        header("Location: votingpagefakulti.php?error=selection_empty");
    }
    // check if number of candidate > max vote
    elseif (count($_POST['fakulti_candidate_selected'])>$section_instrution_maxvote['max_vote']) {
        header('Location: votingpagefakulti.php?error=overflow');
    }
    // check if number of candidate < max vote
    elseif (count($_POST['fakulti_candidate_selected'])<$section_instrution_maxvote['max_vote']) {
        header('Location: votingpagefakulti.php?error=notenough');
    }
    else{
      $_SESSION['fakulti']=$_POST['fakulti_candidate_selected'];
      header('Location: confirmation_votingpage.php');
    }
}

// get fakulti candidate from DB
$query="SELECT c.*,v.*,s.*
        FROM candidate as c 
        JOIN voter as v
        ON c.voter_id=v.voter_id 
        JOIN section as s
        ON c.section_id=s.section_id 
        WHERE s.section_id='$voterfaculty' ";
$qr=mysqli_query($db,$query);
if ($qr==false) {
    echo "Failed to get fakulti candidate information (Query failed)<br>";
    echo "SQL error :".mysqli_error($db);
}
include 'include/header_votingpage.php';

?>
<html>
<body>
  <div class="container" align="center" >

    <!-- Outer Row -->
    <div class="row justify-content-center">
      <div class="col-xl-7 col-lg-12 col-md-9">
        <div class="card o-hidden border-0 shadow-lg my-5">
          <div class="card-header py-3" >
            <h5 class="m-0  font-weight-bold text-primary">Calon Fakulti</h5>
          </div>
          <div class="card-body p-0">
            <div class="p-5">
              <!-- error handling -->
              <?php 
                if (isset($_GET['error'])) {
                  if ($_GET['error'] == "selection_empty") {
                    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">'.$section_instrution_maxvote['section_instrution'].' '.$section_instrution_maxvote['max_vote'].' candidate!<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div> ';
                  }
                  elseif ($_GET['error'] == "overflow") {
                    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">'.$section_instrution_maxvote['section_instrution'].' '.$section_instrution_maxvote['max_vote'].' candidate only!<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div> '; 
                  }
                  elseif ($_GET['error'] == "notenough") {
                    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert"> Not enough..'.$section_instrution_maxvote['section_instrution'].' '.$section_instrution_maxvote['max_vote'].' candidate!<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div> '; 
                  }
                }
                else
                  echo '<div>'.$section_instrution_maxvote['section_instrution'].' '.$section_instrution_maxvote['max_vote']. ' candidate!</div> <br>';
               ?>
              <!-- form start -->
              <form name="form_fakulti" method="POST" action="votingpagefakulti.php">
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
                      while ($record=mysqli_fetch_array($qr)){//redo to other records
                    ?>
                  <tr>
                    <td ><?=$record['candidate_id']?></td>
                    <td><?=$record['voter_name']?></td>
                    <td>
                      <div class="form-check">
                        <input name="fakulti_candidate_selected[]" class="form-check-input" type="checkbox" value="<?=$record['candidate_id']?>" >
                      </div>
                    </td>
                  </tr>
                  <?php
                    }//end of records
                  ?>   
                  </tbody>
                </table>
                <input class="btn btn-danger" name="btn_back" type="button" value="Back" onclick="history.back()">
                <input class="btn btn-primary" type="submit" name="btn_submit_vote" value="Submit">
              </form>
            </div>
          </div>
        </div>

      </div>

    </div>

  </div>

</body>
</html>
<?php include 'include/footer_votingpage.php' ?>