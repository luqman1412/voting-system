<?php session_start();
    include "../connection.php";
    include "../alertfunction.php";
    // get election id from session
    $electionid=$_SESSION['electionid'];
    //If your session isn't valid, it returns you to the login screen for protection
    if(empty($_SESSION['id'])){
    header("location:../index.php?error=alreadylogout");
    exit();
    }    
    // get current local time
    date_default_timezone_set('Asia/Kuala_Lumpur');
    $currenttime=date('Y-m-d H:i:s');

    // check if save setting button is clicked
    if (isset($_POST['btn_general_setting'])) {
        $newelectionname=$_POST['txt_electionname'];
        $newelectionstart=$_POST['txt_start'];
        $newelectionend=$_POST['txt_end'];

        // check data validation
        if ($currenttime>$newelectionend) {
         header('Location: adminsetting.php?error=endtime');
         exit();
        }
        // update data in db
        $query="UPDATE election SET title='$newelectionname',start='$newelectionstart',end='$newelectionend' WHERE election_id='$electionid' ";
        $qr=mysqli_query($db,$query);
        if ($qr==false) {
            echo "Failed to update election information<br>";
            echo "SQL error :".mysqli_error($db);
        }
        else
          header('Location: adminsetting.php?success=saved');
    }
    // if fakulti or umum save button is selected 
    if (isset($_POST['btn_Umum_setting'])||isset($_POST['btn_Fstm_setting'])||isset($_POST['btn_Fsu_setting'])||isset($_POST['btn_Fpm_setting'])||isset($_POST['btn_Fppi_setting'])||isset($_POST['btn_Fp_setting'])) {

      $section_id=$_POST['section_id'];
      $max_candiate=$_POST['number_ofcandidate'];
      $query="UPDATE section SET max_vote='$max_candiate' WHERE section_id= '$section_id'";
      $qr=mysqli_query($db,$query);
        if ($qr==false) {
            echo "Failed to update election instrution and max vote<br>";
            echo "SQL error :".mysqli_error($db);
        }
      header('Location: adminsetting.php?section='.$section_id.'&success=updated');
    }
    // get election detail from DB
    $qr=mysqli_query($db,"SELECT * FROM election WHERE election_id ='$electionid'");
    if ($qr==false) {
        echo "Failed to get election information<br>";
        echo "SQL error :".mysqli_error($db);
    }
    else
    $electiondetail=mysqli_fetch_array($qr);

// to change string to date format (start time)
$start = strtotime($electiondetail['start']); 
$starttime= date("Y-m-d\\TH:i:s", $start); 
// to change string to date format (end time)
$end = strtotime($electiondetail['end']); 
$endtime= date("Y-m-d\\TH:i:s", $end); 

include "include/header.template.php";
?>
<div class="container-fluid">

 
    <?php 
    // show section setting
    if (isset($_GET['section'])) {
         $section_id=$_GET['section'];
         switch ($section_id) {
           case '0':
              $umum_status="active";
             break;
           case '1':
              $fstm_status="active";
             break;
           case '2':
              $fsu_status="active";
             break;             
           case '3':
              $fpm_status="active";
             break;             
           case '4':
              $fppi_status="active";
             break;
           case '5':
              $fp_status="active";
             break;

           default:
             # code...
             break;
         }
         // get total candidate from DB (for dropbox on section setting)
         $query="SELECT s.section_name, s.max_vote, s.section_instrution,count(c.candidate_id)AS ttl 
                 FROM candidate AS c 
                 JOIN section AS s 
                 ON c.section_id=s.section_id 
                 WHERE c.section_id='$section_id' AND c.election_id='$electionid' ";

         $get_ttl_candidate=mysqli_query($db,$query);
         $ttl_candidate=mysqli_fetch_array($get_ttl_candidate);
    ?>
     <ul class="nav nav-pills">
              <li class="nav-item">
                <a class="nav-link <?=$all_status?> " href="adminsetting.php">General </a>
              </li>
              <li class="nav-item">
                <a class="nav-link <?=$umum_status?>" href="adminsetting.php?section=0">Umum</a>
              </li>
              <li class="nav-item">
                <a class="nav-link <?=$fstm_status?>" href="adminsetting.php?section=1">FSTM</a>
              </li>
              <li class="nav-item">
                <a class="nav-link <?=$fpm_status?> " href="adminsetting.php?section=3">FPM</a>
              </li>
              <li class="nav-item">
                <a class="nav-link <?=$fppi_status?> " href="adminsetting.php?section=4">FPPI</a>
              </li>
              <li class="nav-item">
                <a class="nav-link <?=$fsu_status?> " href="adminsetting.php?section=2">FSU</a>
              </li>
              <li class="nav-item">
                <a class="nav-link <?=$fp_status?> " href="adminsetting.php?section=5">FP</a>
              </li>
            </ul>
            <hr>  

    <!-- fakulti setting section -->
    <?php
    // errore handling
      if (isset($_GET['success'])) {
        if ($_GET['success'] == "updated") {
          successwithclose("Succesfully saved");
        }
      }
    ?>
    <form class="user" method="post" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>">
        <div class="card o-hidden border-0 shadow-lg my-1"  >
            <div class="card-header py-3" >
                <h5 class="m-0  font-weight-bold text-primary"><?=$ttl_candidate['section_name']?> setting</h5>
            </div>
            <div class="card-body p-0" >
             <!-- card padding -->
                <div class="p-4">
                  <div class='form-group col-md-10'>
                    <div class="row">
                      <div class="col-auto">
                       <label for="section" class="col-auto col-form-label">Maximum candidate voter allowed to select:</label>
                      </div>

                      <div class="col-auto">
                        <!-- number of candidate -->
                      <select name='number_ofcandidate'  class='form-control '>
                          <?php 
                              for($i=1;$i<=$ttl_candidate['ttl'];$i++){
                                // check previos selected value
                                if ($i==$ttl_candidate['max_vote']) {
                                  echo " <option selected value='".$i."' >".$i." Candidate</option>";
                                }
                                else
                                  echo " <option value='".$i."' >".$i." Candidate</option>";
                              }
                              if (empty($ttl_candidate['ttl'])) {
                                  echo " <option selected value='0'  >No Candidate</option>";
                              }
                           ?>
                      </select>
                      </div>
                    </div>
                      <!-- section id hidden -->
                      <input type="hidden" name="section_id" value="<?=$section_id?>">
                  </div>
                  <hr>
                  <div align="right">
                      <div  class="col-md-2">
                          <input class="btn btn-primary btn-user btn-block" type="submit" name="btn_<?=$ttl_candidate['section_name']?>_setting" value="Save">
                      </div>
                  </div>
                    
                </div>
            </div>
        </div>

    </form>
    <?php }

    else{

      ?>
           <ul class="nav nav-pills">
              <li class="nav-item">
                <a class="nav-link active " href="adminsetting.php">General </a>
              </li>
              <li class="nav-item">
                <a class="nav-link " href="adminsetting.php?section=0">Umum</a>
              </li>
              <li class="nav-item">
                <a class="nav-link " href="adminsetting.php?section=1">FSTM</a>
              </li>
              <li class="nav-item">
                <a class="nav-link  " href="adminsetting.php?section=3">FPM</a>
              </li>
              <li class="nav-item">
                <a class="nav-link  " href="adminsetting.php?section=4">FPPI</a>
              </li>
              <li class="nav-item">
                <a class="nav-link  " href="adminsetting.php?section=2">FSU</a>
              </li>
              <li class="nav-item">
                <a class="nav-link  " href="adminsetting.php?section=5">FP</a>
              </li>
            </ul>
            <hr> 
            <?php 
              if (isset($_GET['error'])) {
                if ($_GET['error']=="endtime") {
                    alertwithclose("Election end time already passed! Please change election end time");
                }
              }
              if (isset($_GET['success'])) {
                if ($_GET['success']=="saved") {
                    successwithclose("Succesfully saved");
                }
              }
             ?>
       <!-- general setting card -->
       <div class="card o-hidden border-0 shadow-lg my-1"  >
        <div class="card-header py-3" >
          <h5 class="m-0  font-weight-bold text-primary">General Settings</h5>
        </div>
      <div class="card-body p-0" >
         <!-- card padding -->
        <div class="p-4">
           <!-- form start -->
              <form class="user" method="post" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>">

             <!-- title textbox -->
             <div class="form-row">
                <div class="form-group col-md-7">
                    <label  for="title" >Title</label>
                    <input name="txt_electionname" type="text"  class="form-control form-control" id="title" placeholder="Election Title" value="<?=$electiondetail['title']?>" required>
                 </div>
             </div>
             <!-- start date textbox -->
             <div class="form-row">
               <div class="form-group col-md-4">
                      <label for="starttime">Election Start</label>
                      <input name="txt_start" type="datetime-local" class="form-control form-control" id="starttime" value="<?=$starttime?>" required>
                   </div>
                     <!-- end date textbox -->
                     <div class="form-group col-md-4">
                         <label for="endtime">Election End</label>
                         <input name="txt_end" type="datetime-local" class="form-control form-control " id="endtimes" value="<?=$endtime?>" required>
                     </div>
                 </div>
                 <hr>
                 
                 <div align="right">
                        <div  class="col-md-2">
                    <input class="btn btn-primary btn-user btn-block" type="submit" name="btn_general_setting" value="Save">
                </div>
             </div>

          </form>
        </div>  
      <!-- close card padding -->
      </div>
    <!-- close card element-->
    </div>
    <?php 
    } ?>
    
<!-- close container-fluid -->
</div>

<?php
include "include/footer.template.php";
?>