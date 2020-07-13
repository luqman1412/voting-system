<?php session_start();
  require '../connection.php';

//If your session isn't valid, it returns you to the login screen for protection
if(empty($_SESSION['id'])){
 header("location:../index.php?error=alreadylogout");
}
$electionid=$_SESSION['electionid'];
if (isset($_GET['delete_voter_id'])) {
    $voterid=$_GET['delete_voter_id'];

    $qr=mysqli_query($db,"DELETE FROM voter WHERE voter_id='$voterid' ");
    if ($qr==true){
        header('Location: voterlist.php?success=deleted');
    }
    else{
    echo "Fail to delete record for $voterid ";
    echo mysqli_error($db);
    }
}

$voterid=$_GET['voter_id'];
include "include/header.template.php";
?>
          <!-- Align element to center -->
          <div align="center">
            <!-- Card element  -->
            <div class="col-xl-5 col-lg-7">
              <div class="card shadow mb-4">
                <!-- Card Header -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Add voter</h6>
                  <!-- End Card Header -->
                </div>
                <!-- Card Body -->
                <div class="card-body">
                 <h4>Are sure want to delete voter?</h4> 
                  <a href="voterlist.php"> <button name="yes" class="btn btn-primary">No</button></a>
                  <a href="deletevoter.php?delete_voter_id=<?php echo $voterid?>"><button name="no" class="btn btn-danger">Yes</button></a>
                  <!-- End Card Body -->
                </div>
              </div>
              <!-- Card element end -->
            </div>
          <!-- Align element to center end -->
          </div>
<?php
include "include/footer.template.php";
?>