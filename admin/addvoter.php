<?php session_start();
$electionid=$_SESSION['electionid'];

if(empty($_SESSION['id'])){
 header("location:../index.php");
}
include "../connection.php";

if (isset($_POST['btn_add_voter'])) {

    // get voter information
    $votername=$_POST['name'];
    $votermatricno=$_POST['matric_no'];
    $voterfaculty=$_POST['faculty'];
    $voteremail=$votermatricno."@student.kuis.edu.my";

    //to if check matric no exist in database
    $data_in_DB = mysqli_query($db,"SELECT matric_no FROM voter WHERE matric_no='$votermatricno' ") or die(mysql_error());
    if (mysqli_num_rows($data_in_DB)==0) {
        // insert voter information in voter and login table
        $querytovotertable="INSERT INTO voter  (email,voter_name,matric_no,faculty) 
                            VALUES('$voteremail','$votername','$votermatricno','$voterfaculty'); 
                            INSERT INTO login (user_level,username) 
                            VALUES (2,'$votermatricno')  ";
        $qr=mysqli_multi_query($db,$querytovotertable);
        if ($qr==false) {
            echo "Failed to add voter<br>";
            echo "SQL error :".mysqli_error($db);
        }
        else
            header('Location: addvoter.php?succes=addvoter');
    }
    else
      // if voter exist in DB
        header('Location: addvoter.php?error=alreadyexist');

}

 if(isset($_POST["Import"])){
    $mimes = array('application/vnd.ms-excel','text/plain','text/csv','text/tsv');
    if(in_array($_FILES['file']['type'],$mimes)){
        $filename=$_FILES["file"]["tmp_name"];    


     if($_FILES["file"]["size"] > 0){
        $file = fopen($filename, "r");
        // to skip the 1st row
        fgetcsv($file);

          while (($getData = fgetcsv($file, 10000, ",")) !== FALSE){ 
            switch ($getData[3]) {
              case 'fstm':
                $getData[3]="1";
                break;
              case 'fsu':
                $getData[3]="2";
                break;
              case 'fpm':
                $getData[3]="3";
                break;
              case 'fppi':
                $getData[3]="4";
                break;
              case 'fp':
                $getData[3]="5";
                break;
              default:
                $getData[3]="0";
                break;
            }
            // insert voter information into DB
        $sql = "INSERT into voter (voter_id,email,voter_name,matric_no,faculty)VALUES (NULL,'$getData[0]','$getData[1]','$getData[2]','$getData[3]') ";
        $result = mysqli_query($db, $sql);
        if(!isset($result)){
          header("Location: addvoter.php?error=failedtosubmit");
        }
        if ($result==true) {
          // insert login detail into DB
          $insert_login_detail = mysqli_query($db,"INSERT INTO login (user_level,username) VALUES (2,'$getData[2]')");
          if ($insert_login_detail==false) {
            echo "Failed to insert login detail into DB (import form)<br>";
            echo "SQL error :".mysqli_error($db);
          }
          else
            header('Location:voterlist.php?succes=inserted');
        }
        
           }
           fclose($file); 
     }
  }
  else
    header("Location: addvoter.php?error=Invalidfile");
}

include "include/header.template.php";
?>
<div class="container-fluid">
  <div class="row">
            <!-- Area Card -->
            <div class="col-xl-8 col-lg-7">
              <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Add voter</h6>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                  <?php 
                    // error handling
                    if (isset($_GET['error'])) {
                      if ($_GET['error'] == "alreadyexist") {
                        echo '<div class="alert alert-danger" role="alert">Already a voter! <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div> ';
                      }
                    }
                    if (isset($_GET['succes'])) {
                      if ($_GET['succes']=="addvoter") {
                        echo '<div class="alert alert-success" role="alert">Succesfully add voter <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div> ';
                          
                      }
                    }
                   ?>
                  <!-- form start -->
                 <form class="user" method="post" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>">

                    <!-- align left for label  -->
                    <div align="left">
                     <!-- NAME textbox -->
                      <div class="form-group">
                          <label  for="title" >Name</label>
                          <input name="name" type="text"  class="form-control" id="title" placeholder="Voter Name">
                        </div>
                      <div class="form-row">
                        <!-- Matric no textbox -->
                        <div class="form-group col-md-6">
                          <label for="matric_no">Matric No</label>
                          <input name="matric_no" type="text" class="form-control " id="matric_no" placeholder="Matric No" >
                        </div>
                        <!-- Faculty Dropbox -->
                        <div class="form-group col-md-6">
                           <label>Selects Faculty</label>
                           <select name="faculty" class="form-control">
                              <option selected>Select Faculty</option>
                              <option value="1">FSTM</option>
                              <option value="2">FSU</option>
                              <option value="3">FPM</option>
                              <option value="4">FPPI</option>
                              <option value="5">FP</option>
                           </select>
                        </div>
                     </div>
                    </div>
                    <div align="right">
                      <div class="col-md-2" >
                          <input class="btn btn-primary btn-user btn-block" type="submit" name="btn_add_voter" value="Continue">
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>

            <!-- Import Card -->
            <div class="col-xl-4 col-lg-5">
              <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Import file</h6>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                   <?php 
                  // error handling
                    if (isset($_GET['error'])) {
                      if ($_GET['error'] == "Invalidfile") {
                        echo '<div class="alert alert-danger" role="alert">Please Make Sure the file is .csv !</div> ';
                      }
                    elseif ($_GET['error'] == "failedtosubmit") {
                        echo '<div class="alert alert-danger" role="alert">Please Try Again !</div> ';
                      }
                    }

                   ?>
                  <!-- form start -->
                  <form class="form-horizontal"  method="post" name="upload_voter" enctype="multipart/form-data" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>">
                      <!-- File Button -->
                      <div class="form-group">
                        <label class="col-md-4 control-label" for="filebutton">Select File</label>
                        <div class="col-md-4">
                          <input type="file" name="file" id="file" class="input-large">
                        </div>
                      </div>
                        <!--Import Button -->
                        <div class="form-group">
                          <div class="col-md-4">
                                <button type="submit" id="submit" name="Import" class="btn btn-primary button-loading" data-loading-text="Loading...">Import</button>
                            </div>                          
                        </div>
                </form>
                </div>
              </div>
            </div>
</div>

</div>

<?php
include "include/footer.template.php";
?>