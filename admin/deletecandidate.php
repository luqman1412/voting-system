<?php session_start();
$electionid=$_SESSION['electionid'];

if(empty($_SESSION['id'])){
 header("location:../index.php");
}
$candidate_id=$_GET['candidate_id'];

// if delete button is press
if (isset($_GET['deletecandidate_id'])) {
    // delete from database
    include "../connection.php";
    // get the id from url
    $candidate_id= $_GET['deletecandidate_id'];  
    // get 'section_id' from DB
    $qr=mysqli_query($db,"SELECT section_id FROM candidate WHERE candidate_id='$candidate_id' ");
      if ($qr==true){
          $section_id=mysqli_fetch_array($qr);
      }
    // delete candidate information from DB
    $qr=mysqli_query($db,"DELETE FROM candidate WHERE candidate_id ='$candidate_id' ");
    if ($qr==true){
        $qr=mysqli_query($db,"DELETE FROM count WHERE candidate_id='$candidate_id' ");

        // return to page based on 'section_id'
        if ($section_id['section_id']==0) {
             header('Location: candidateumum.php');
        }
        elseif ($section_id>0) {
             header('Location: candidatefakulti.php');
        }
    }
    else{
      echo "Fail to delete record for $candidate_id";
      echo mysqli_error($db);
    }
}

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
                 <h4>Are sure want to delete Candidate?</h4> 
                   <button name="yes" class="btn btn-primary" onclick="history.back()">No</button>
                  <a href="deletecandidate.php?deletecandidate_id=<?php echo $candidate_id?>"><button name="btn_deletecandidate" class="btn btn-danger">Yes</button></a>
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