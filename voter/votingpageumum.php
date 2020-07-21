<?php
session_start();
include "../connection.php";

//If your session isn't valid, it returns you to the login screen for protection
if(empty($_SESSION['id'])){
    header("location:../index.php?error=alreadylogout");
}
// get voter id from SESSIONs
$voterid=$_SESSION['id'];

// check if election are ended if true go to election_endedpage.php
$check_running_election=mysqli_query($db,"SELECT * FROM election WHERE status = 'Running'");
if(mysqli_num_rows($check_running_election)==0){
  echo "election has ended";
  // header("Location: error_votingpage.php?error=electionended");
}
// check if voter already vote
$alreadyvote_inDB=mysqli_query($db,"SELECT * FROM alreadyvote WHERE voter_id= '$voterid' ");
if(mysqli_num_rows($alreadyvote_inDB)>0){
  echo ("Your alredy vote<br>");
  // header('Location: error_votingpage.php?error=alreadyvote');
}

// insert the selection into the session
if (isset($_POST['btn_submit_umum'])) {
$_SESSION['umum']=$_POST['umum_candidate_selected'];
header('Location: votingpagefakulti.php');
}

// get candidate from DB
$query="SELECT c.*,v.voter_name,v.matric_no,v.voter_id
FROM candidate as c 
JOIN voter as v 
ON c.voter_id=v.voter_id
WHERE section_id=0";

$qr=mysqli_query($db,$query);
if ($qr==false) {
    echo "Query cannot been executed<br>";
    echo "SQL error :".mysqli_error($db);
}
$get_voterdetail=mysqli_query($db,"SELECT * FROM voter");
if ($qr==false) {
    echo "Failed to get voter information <br>";
    echo "SQL error :".mysqli_error($db);
}
$voter_record=mysqli_fetch_array($get_voterdetail);
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
                            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">Please select (amount) candidate!<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div> ';                        }
                      }
                      else
                          echo '<div class="alert alert-dark" role="alert">Please select (amount) candidate!</div> ';
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
                          while ($record=mysqli_fetch_array($qr)){//redo to other records
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