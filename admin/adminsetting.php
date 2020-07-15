<?php
    session_start();
    include "../connection.php";
    // get election id from session
    $electionid=$_SESSION['electionid'];
    //If your session isn't valid, it returns you to the login screen for protection
    if(empty($_SESSION['id'])){
    header("location:../index.php?error=alreadylogout");
    }

    // check if section setting button is clicked
    if (isset($_POST['btn_section_setting'])) {
        $query="SELECT section_name from section";
        $qr=mysqli_query($db,$query);
        if ($qr==false) {
            echo "Query cannot been executed<br>";
            echo "SQL error :".mysqli_error($db);
        }
        while ($record=mysqli_fetch_array($qr)) {
            $max_vote=$_POST[$record['section_name']];
            $section_name=$record['section_name'];
            $sql=mysqli_query($db,"UPDATE section SET max_vote='$max_vote' WHERE section_name ='$section_name' ");
        }

    }
    
    // check if save setting button is clicked
    if (isset($_POST['btn_general_setting'])) {
        $newelectionname=$_POST['txt_electionname'];
        $newelectionstart=$_POST['txt_start'];
        $newelectionend=$_POST['txt_end'];
        // update data in db
        $query="UPDATE election SET title='$newelectionname',start='$newelectionstart',end='$newelectionend' WHERE election_id='$electionid' ";
        $qr=mysqli_query($db,$query);
        if ($qr==false) {
            echo "Query cannot been executed<br>";
            echo "SQL error :".mysqli_error($db);
        }
    }
    // get election detail from DB
    $qr=mysqli_query($db,"SELECT * FROM election WHERE election_id ='$electionid'");
    if ($qr==false) {
        echo "Query cannot been executed<br>";
        echo "SQL error :".mysqli_error($db);
    }
    else
    $electiondetail=mysqli_fetch_array($qr);
    
    // get section record from DB
    $qr= mysqli_query($db,"SELECT * FROM section ");
    if ($qr==false) {
        echo "Query cannot been executed<br>";
        echo "SQL error :".mysqli_error($db);
    }
    else

// to change string to date format (start time)
$start = strtotime($electiondetail['start']); 
$starttime= date("Y-m-d\\Th:i:s", $start); 
// to change string to date format (end time)
$end = strtotime($electiondetail['end']); 
$endtime= date("Y-m-d\\Th:i:s", $end); 

include "include/header.template.php";
?>
<div class="container-fluid">

     <ul class="nav nav-pills">
              <li class="nav-item">
                <a class="nav-link <?=$all_status?> " href="adminsetting.php">General </a>
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
    <?php 
    if (isset($_GET['section'])) {
         $data_id=$_GET['section'];
         $get_ttl_candidate=mysqli_query($db,"SELECT s.section_name, count(c.candidate_id)as ttl from candidate as c JOIN section as s ON c.section_id=s.section_id WHERE c.section_id='$data_id'");
        $ttl_candidate=mysqli_fetch_array($get_ttl_candidate);
    ?>
    <form class="user" method="post" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>">
        <div class="card o-hidden border-0 shadow-lg my-1"  >
            <div class="card-header py-3" >
                <h5 class="m-0  font-weight-bold text-primary"><?=$ttl_candidate['section_name']?> setting</h5>
            </div>
            <div class="card-body p-0" >
             <!-- card padding -->
                <div class="p-4">
   

                        <div class='form-group col-md-4'>
                            <label>Section Instrution</label>
                            <input name="txt_<?=$ttl_candidate['section_name']?>_instrution" type="text"  class="form-control " id="title" placeholder="Please select " value="">
                            <label><?=$ttl_candidate['section_name']?> Max vote (total candidate:<?=$ttl_candidate['ttl']?>) </label>
                            <select name='<?=$ttl_candidate['section_name']?>'  class='form-control '>
                                <?php 
                                    for($i=1;$i<=$ttl_candidate['ttl'];$i++){
                                        echo " <option value='".$i."' >".$i."</option>";}
                                 ?>
                            </select>
                        </div>
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
                    <input name="txt_electionname" type="text"  class="form-control form-control-user" id="title" placeholder="Election Title" value="<?=$electiondetail['title']?>">
                 </div>
             </div>
             <!-- start date textbox -->
             <div class="form-row">
               <div class="form-group col-md-4">
                      <label for="starttime">Election Start</label>
                      <input name="txt_start" type="datetime-local" class="form-control form-control-user" id="starttime" value="<?=$starttime?>">
                   </div>
                     <!-- end date textbox -->
                     <div class="form-group col-md-4">
                         <label for="endtime">Election End</label>
                         <input name="txt_end" type="datetime-local" class="form-control form-control-user" id="endtimes" value="<?=$endtime?>">
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