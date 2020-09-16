<?php session_start();
$electionid=$_SESSION['electionid'];

// get current local time
date_default_timezone_set('Asia/Kuala_Lumpur');
$currenttime=date('Y-m-d H:i:s');

//If your session isn't valid, it returns you to the login screen for protection
if(empty($_SESSION['id'])){
 header("location:../index.php?error=alreadylogout");
}
include "../connection.php";

    $query="SELECT * FROM election WHERE election_id=$electionid ";
    $qr=mysqli_query($db,$query);
    if ($qr==false) {
        echo "Failed to get election detail<br>";
        echo "SQL error :".mysqli_error($db);
    }
    $electiondetail=mysqli_fetch_array($qr);
    $endtime=$electiondetail['end'];
    $start_time=$electiondetail['start'];

    // default value for error message
    $message=array();
    $endtime_valid="valid";
    $starttime_valid="valid";
    // check election time

    if($currenttime>= "$endtime" ){
      $endtime_valid="invalid";
      array_push($message,'<div class="alert alert-danger" role="alert"><i class="fas fa-exclamation-circle"></i> <b>Warning: </b> Election time has passed. Please change the times </div>');

      }
    elseif($currenttime>= "$start_time" ){
      $starttime_valid="invalid";
      array_push($message,'<div class="alert alert-danger" role="alert"><i class="fas fa-exclamation-triangle"></i> <b>Warning: </b> This election already started </div>');

      }
    if ($start_time>= "$endtime") {
      $starttime_valid="invalid";
      array_push($message,'<div class="alert alert-danger" role="alert"><i class="fas fa-exclamation-circle"></i> <b>Warning: </b> Election start time is over than election end time</div>');
    }


// to change string to date format (start time)
$start = strtotime($electiondetail['start']); 
$starttime= date("Y-m-d\\TH:i:s", $start); 
// to change string to date format (end time)
$end = strtotime($electiondetail['end']); 
$endtime= date("Y-m-d\\TH:i:s", $end); 

include "include/header.template.php";
?>

        <div class="container-fluid">
            <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Launch Review</h1>
          </div>
      <div class="col-xl-12 col-lg-12 col-md-9">
         
<?php  if (isset($_GET['page2'])) { ?>

          <div class="card shadow mb-4">
            <div class="card-header py-3" >
              <div class="d-sm-flex align-items-center justify-content-between mb-1">
               <h5 class="m-0  font-weight-bold text-primary">Candidate </h5>     
              </div>
            </div>
            <div class="card-body">
              
  <?php 

  // get section 
  $get_all_section=mysqli_query($db,"SELECT section_id,section_name,max_vote FROM section");
  
  if ($get_all_section==false) {
     echo "Failed to get section information<br>";
     echo "SQL error :".mysqli_error($db);
  }
$candidate_message="";
  while ($section=mysqli_fetch_array($get_all_section)) {
     $section_id=$section['section_id'];

  // get total candidate from DB (for dropbox on section setting)
  $query="SELECT count(c.candidate_id)AS ttl 
          FROM candidate AS c  
          WHERE c.section_id = '$section_id'
          AND c.election_id='$electionid' ";

  $get_ttl_candidate=mysqli_query($db,$query);
  if ($get_ttl_candidate==false) {
     echo "Failed to get candidate<br>";
     echo "SQL error :".mysqli_error($db);
     exit();
  }
  else
    $ttl_candidate=mysqli_fetch_array($get_ttl_candidate);
            
             if ($ttl_candidate['ttl']==0) {
              $candidate_status="is-invalid";
              $candidate_message='<div class="alert alert-danger" role="alert"><i class="fas fa-exclamation-circle"></i> <b>Warning: </b>There are section that didnt have candidate </div>';
            }
            else{
              $candidate_status="is-valid";
            }
            // check for section id for redirect
            $redirectname_addcandidate= ($section_id==0 ? "candidateumum" : "candidatefakulti" );
 ?>
               <div class="row">
                <div class="col-1 p-2">
                  <label><strong><?=$section['section_name']?>:</strong></label>
                </div>
                <div class="col-2 p-2">
                  <label>voter can vote </label>
                </div>
                <div class="col-1 p-2">
                  <input class="form-control " type="text" name="ttl_umum" value="<?=$section['max_vote']?>" readonly>
                </div>
                <div class="col-1 p-2">
                  <label>per </label>
                </div>
                <div class="col-1 p-2">
                <input class="form-control <?=$candidate_status?>" type="text" name="ttl_umum" value="<?=$ttl_candidate['ttl']?>" readonly>
                </div>
                <div class="col-2 p-2">
                  <label>Candidate </label>
                </div>
                <div class="col-1 p-2">
                  <a href="adminsetting.php?section=<?=$section['section_id']?>"><button class="btn btn-primary">Edit  </button></a>
                </div>
                <div class="col-3 p-2">
                  <a href="<?=$redirectname_addcandidate?>.php"><button class="btn btn-primary">Add Candidate</button></a>
                </div>
               </div>

<?php  //close loop         
            } ?>  
              <hr>  
              <?php 
                echo "$candidate_message";
                $btn_launch_status = (!empty($candidate_message)? "disabled" : " " );
               ?>
                <div class="p-2" align="right">
                  <a href="launchmenu.php?"><button class="btn btn-primary">Back</button></a>
                  <!-- <a href="launchmenu.php?page3"><button class="btn btn-primary" <?=$btn_launch_status?>>Next</button></a> -->
                  <a href="#"  data-toggle="modal" data-target="#launchelectionmodal" > <button class="btn btn-primary"<?=$btn_launch_status?>>Launch</button></a>

                </div>
            </div>
          </div>
<?php 
// close page 2
  }
  elseif (isset($_GET['page3'])) {
?>
<div class="card shadow mb-4">
            <div class="card-header py-3" >
              <div class="d-sm-flex align-items-center justify-content-between mb-1">
               <h5 class="m-0  font-weight-bold text-primary">Term</h5>     
              </div>
            </div>
            <div class="card-body">
                <div class="form-row ">
                <div class="col">
                <h5>You will not allowed to change this setting after launced</h5><br>
                <p>1. add,edit or delete voter
                <br>2. add,edit or delete candidate
                <br>3. change the election start date and end date</p>
                </div>
                </div>
                
                <hr>
                <div class="p-2" align="right">
                  <a href="electionlaunch.php"><button class="btn btn-primary" >Launch</button></a>
                </div>
            </div>
          </div>

<?php
  }
  else{
  ?>
 <div class="card shadow mb-4">
            <div class="card-header py-3" >
              <div class="d-sm-flex align-items-center justify-content-between mb-1">
               <h5 class="m-0  font-weight-bold text-primary">Election Time</h5>     
              </div>
            </div>
            <div class="card-body">
                <div class="form-row ">
                  <div class="col-3">
                      <label>Election start time: </label>
                  </div>
                  <div class="col-auto p-2">
                    <input class="form-control is-<?=$starttime_valid?>" value="<?=$starttime?>" type="datetime-local" name="start_date" readonly>
                   
                    <div class="valid-feedback">
                    Election start time look good
                    </div> 
                  </div>

                </div>

                <div class="form-row ">
                    <div class="col-3">
                      <label>Election end time: </label>
                  </div>
                    <div class="col-auto">
                      <input class="form-control is-<?=$endtime_valid?>" value="<?=$endtime?>" type="datetime-local" name="end_time" readonly>
                    <div class="invalid-feedback">
                    Election end time has passed
                    </div>
                    <div class="valid-feedback">
                    Election end time look good
                    </div> 
                    </div>
                </div>

                <hr>
                <?php 
                // dislplay error message
                echo "<div>";
                  foreach ($message as $key=>$value){
                    echo "$value <br>";
                  } 
                echo "</div>";
                
                $button_next_status = (!empty($message)? "disabled" : " " );

                  ?>
                <div class="p-2" align="right">
                  <a href="launchmenu.php?page2"><button class="btn btn-primary" <?=$button_next_status?>>Next</button></a>
                </div>
            </div>
          </div>

          <?php 
           //close else
              }
            ?>
        </div>
</div>
<?php 
include 'include/footer.template.php' ?>