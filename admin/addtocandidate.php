<?php session_start();
$electionid=$_SESSION['electionid'];

//If your session isn't valid, it returns you to the login screen for protection
if(empty($_SESSION['id'])){
 header("location:../index.php");
}

include '../connection.php';
$voterid=$_GET['voter_id'];
$query="SELECT voter_id from candidate WHERE voter_id= '$voterid' ";
$qr=mysqli_query($db,$query);
if ($qr==false) {
    echo "Query cannot been executed<br>";
    echo "SQL error :".mysqli_error($db);
  }
// check the voter id if exist in candidate DB
if (mysqli_num_rows($qr)>0) {
echo "Voter Already candidate";
// redirect in 2 second after display the error
header( "refresh:2;url=candidatelist.php" );
}
else{

  // check if the 'voterid' variable is set in URL
  if (isset($_GET['voterid'])){
      // get id value
      $voterid = $_GET['voterid'];
      // insert voter data into candidate table in DB
      $sql="INSERT INTO candidate (voter_id) VALUES ('$voterid')";
      $qr=mysqli_query($db,$sql);
      if ($qr==true){
        // get candidate id from database
        $candidate_id = mysqli_insert_id($db);
        // to add into count database
        $qr=mysqli_query($db,"INSERT INTO count (candidate_id) VALUES ('$candidate_id')");
        // redirect to candidate list page after succesfully insett data into DB 
        header('Location: candidatelist.php');
      }
      else{
      echo "Fail to add as candidate for $voterid";
      echo mysqli_error($db);
      }
      // redirect back to candidates
       header("Location: candidatelist.php");
   }
   else
    // do nothing
  
include "include/header.template.php";

?>
            <!-- align element to center -->
            <div align="center">
            <!-- Start card element -->
            <div class="col-xl-5 col-lg-7">
              <div class="card shadow mb-4">
                <!-- Card Header -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Add candidate</h6>
                  <!-- Card Header End -->
                </div>

                <!-- Card Body -->
                <div class="card-body">
                 <h4>Are sure want to add as candidate?</h4> 
                  <a href="candidatelist.php"> <button name="btn_no" class="btn btn-danger">No</button></a>
                  <a href="addtocandidate.php?voterid=<?php echo $voterid?>"><button name="btn_yes" class="btn btn-primary">Yes</button></a>
                <!-- Card Body End -->
                </div>
              </div>
              <!-- end card div -->
            </div>
            <!-- end center element -->
            </div>
<?php
include "include/footer.template.php";
}

?>