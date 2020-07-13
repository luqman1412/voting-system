<?php
$electionid=$_GET['electionid'];

if (isset($_GET['election_id'])) {
  require '../connection.php';

$election_id=$_GET['election_id'];
$qr=mysqli_query($db,"DELETE FROM election WHERE election_id='$election_id' ");
if ($qr=true) {
  header('Location: admindashboard.php?success=successfullydeleted');
}
else{
  echo "Fail to delete record for $election_id";
  echo mysqli_error($db);
}

}

include "include/blankheader.php";
?>
          <!-- Align element to center -->
          <div align="center">
            <!-- Card element  -->
            <div class="col-xl-5 col-lg-7">
              <div class="card shadow mb-4">
                <!-- Card Header -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Delete Election</h6>
                  <!-- End Card Header -->
                </div>
                <!-- Card Body -->
                <div class="card-body">
                 <h4>Are sure want to delete this election?</h4> 
                  <a href="admindashboard.php"> <button name="btn_no" class="btn btn-primary">No</button></a>
                  <a href="deleteelection.php?election_id=<?php echo $electionid?>"><button name="btn_yes" class="btn btn-danger">Yes</button></a>
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