<?php session_start();
  require '../connection.php';

//If your session isn't valid, it returns you to the login screen for protection
if(empty($_SESSION['id'])){
 header("location:../index.php?error=alreadylogout");
}
// update when confrim button is selected
if (isset($_POST['btn_updatevoter'])) {
  $matric_no=$_POST['matricno'];
  $newname=$_POST['update_name'];
  $newfaculty=$_POST['update_faculty'];

$qr=mysqli_query($db,"UPDATE voter SET voter_name ='$newname',faculty='$newfaculty' WHERE matric_no='$matric_no' ");
if ($qr==false) {
  echo "Failed to update voter information<br>";
  echo "SQL error :".mysqli_error($db);
}
else
header('Location:voterlist.php?success=updated');
}
// get election id from session 
$electionid=$_SESSION['electionid'];

$voterid=$_GET['voter_id'];
$query ="SELECT v.*,f.* FROM voter AS v
         JOIN faculty AS f
         ON v.faculty =f.faculty_id
         WHERE voter_id='$voterid' ";

$qr=mysqli_query($db,$query);
if ($qr==false) {
  echo "Failed to get voter information<br>";
  echo "SQL error :".mysqli_error($db);
}
$record=mysqli_fetch_array($qr);

$total_faculty=mysqli_query($db,"SELECT * FROM faculty");
if ($qr==false) {
  echo "Failed to get faculty information<br>";
  echo "SQL error :".mysqli_error($db);
}

include "include/header.template.php";
?>
          <!-- Align element to center -->
          <div align="center">
            <!-- Card element  -->
            <div class="col-xl-7 col-lg-7">
              <div class="card shadow mb-4">
                <!-- Card Header -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Edit voter Information</h6>
                  <!-- End Card Header -->
                </div>
                <!-- Card Body -->
                <div class="card-body">
                  <form class="user" method="POST" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>">

                    <!-- align left for label  -->
                    <div align="left">
                     <!-- NAME textbox -->
                      <div class="form-group">
                          <label  for="title" >Name</label>
                          <input name="update_name" type="text"  class="form-control" id="title" value="<?=$record['voter_name']?>" placeholder="Voter Name" required>
                        </div>
                      <div class="form-row">
                        <!-- Matric no textbox -->
                        <div class="form-group col-md-6">
                          <label for="matric_no">Matric No</label> 
                          <input name="matricno" type="text" class="form-control " id="matric_no" value="<?=$record['matric_no']?>" placeholder="Matric No" readonly>
                        </div>
                        <!-- Faculty Dropbox -->
                        <div class="form-group col-md-6">
                           <label>Selects Faculty</label>
                           <select name="update_faculty" class="form-control">

                            <?php
                            // display all facult in DB 
                            while ($faculty=mysqli_fetch_array($total_faculty)) {
                              if ($record['faculty']==$faculty['faculty_id']) {
                                echo "<option selected value='".$record['faculty']."'>".$record['name']."</option>";
                              }
                              else
                                echo "<option value='".$faculty['faculty_id']."'>".$faculty['name']."</option>";
                            }
                            ?>
                           </select>
                        </div>
                     </div>
                    </div>
                    <div align="right">
                      <div class="col-md-3" >
                          <input class="btn btn-primary btn-user btn-block" type="submit" name="btn_updatevoter" value="Update">
                      </div>
                    </div>
                  </form>
                  
                </div>
              </div>
              <!-- Card element end -->
            </div>
          <!-- Align element to center end -->
          </div>
<?php
include "include/footer.template.php";
?>