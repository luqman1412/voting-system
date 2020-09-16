<?php session_start();
$electionid=$_SESSION['electionid'];
//If your session isn't valid, it returns you to the login screen for protection
if(empty($_SESSION['id'])){
 header("location:../index.php?error=alreadylogout");
}
include "../connection.php";
// fakulti navbar and active tab navbar
$status="active";
if (isset($_GET['data'])) {
  if ($_GET['data']=="fstm") {
      $fstm_status=$status;
      $query="SELECT c.*,v.*,f.name,s.*
              FROM candidate as c 
              JOIN voter as v 
              ON c.voter_id=v.voter_id
              JOIN faculty as f 
              ON v.faculty=f.faculty_id
              JOIN section as s 
              ON c.section_id= s.section_id 
              WHERE c.section_id = 1 AND c.election_id = '$electionid' ";

      }
      elseif ($_GET['data']=="fsu") {
        $fsu_status=$status;
        $query="SELECT c.*,v.*,f.name,s.*
              FROM candidate as c 
              JOIN voter as v 
              ON c.voter_id=v.voter_id
              JOIN faculty as f 
              ON v.faculty=f.faculty_id
              JOIN section as s 
              ON c.section_id= s.section_id 
              WHERE c.section_id = 2 AND c.election_id = '$electionid'";

      }
      elseif ($_GET['data']=="fpm") {
        $fpm_status=$status;
        $query="SELECT c.*,v.*,f.name,s.*
              FROM candidate as c 
              JOIN voter as v 
              ON c.voter_id=v.voter_id
              JOIN faculty as f 
              ON v.faculty=f.faculty_id
              JOIN section as s 
              ON c.section_id= s.section_id 
              WHERE c.section_id = 3 AND c.election_id = '$electionid'";
      }
      elseif ($_GET['data']=="fppi") {
        $fppi_status=$status;
        $query="SELECT c.*,v.*,f.name,s.*
              FROM candidate as c 
              JOIN voter as v 
              ON c.voter_id=v.voter_id
              JOIN faculty as f 
              ON v.faculty=f.faculty_id
              JOIN section as s 
              ON c.section_id= s.section_id 
              WHERE c.section_id = 4 AND c.election_id = '$electionid'";
      }
      elseif ($_GET['data']=="fp") {
        $fp_status=$status;
        $query="SELECT c.*,v.*,f.name,s.*
              FROM candidate as c 
              JOIN voter as v 
              ON c.voter_id=v.voter_id
              JOIN faculty as f 
              ON v.faculty=f.faculty_id
              JOIN section as s 
              ON c.section_id= s.section_id 
              WHERE c.section_id = 5 AND c.election_id = '$electionid'";
      }
}
else{
    $all_status=$status;
    $query="SELECT c.*,v.*,f.name,s.*
            FROM candidate as c 
            JOIN voter as v 
            ON c.voter_id=v.voter_id
            JOIN faculty as f 
            ON v.faculty=f.faculty_id
            JOIN section as s 
            ON c.section_id= s.section_id 
            WHERE c.section_id !=0 AND c.election_id = '$electionid'";
}


$qr=mysqli_query($db,$query);
if ($qr==false) {
    echo "Failed to get candidates information<br>";
    echo "SQL error :".mysqli_error($db);
}

include "include/header.template.php";
?>
<div class="container-fluid">
  <div class="card shadow mb-4">
    <div class="card-header py-3" >
      <div class="d-sm-flex align-items-center justify-content-between mb-1">
        <h5 class="m-0  font-weight-bold text-primary">Calon Fakulti</h5>
        <div class="pull-right"><a href="fakulti_voterlist.php"><button type="button" class="btn btn-primary">Add Candidates</button></a></div>
      </div>
    </div>
    <div class="card-body">
      <ul class="nav nav-pills">
      <li class="nav-item">
        <a class="nav-link <?=$all_status?> " href="candidatefakulti.php">All</a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?=$fstm_status?>" href="candidatefakulti.php?data=fstm">FSTM</a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?=$fpm_status?> " href="candidatefakulti.php?data=fpm">FPM</a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?=$fppi_status?> " href="candidatefakulti.php?data=fppi">FPPI</a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?=$fsu_status?> " href="candidatefakulti.php?data=fsu">FSU</a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?=$fp_status?> " href="candidatefakulti.php?data=fp">FP</a>
      </li>
    </ul>
    <hr>
    <?php 
      if (isset($_GET['success'])) {
        if ($_GET['success']=="addcandidate") {
            echo '<div class="alert alert-success" role="success">Succesfully add candidate !</div> ';
        }
      }
       ?>
      <table id="dtBasicExample" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
        <thead>
          <tr>
            <th class="th-sm">Candidate ID
            </th>
            <th class="th-sm">Section 
            </th>
            <th class="th-sm">Name
            </th>
            <th class="th-sm">Fakulti
            <th >Action
            </th>

          </tr>
        </thead>
        <tbody>
          <?php
      while ($rekod=mysqli_fetch_array($qr)){//redo to other records
        $candidateid=$rekod['candidate_id'];
      ?>
          <tr>
            <td><?=$rekod['candidate_id']?></td>
            <td><?=$rekod['section_name']?></td>
            <td><?=$rekod['voter_name']?></td>
            <td><?=$rekod['name']?></td>
            <td>
              <a href="#" class="btn btn-danger btn-circle btn-sm" data-toggle="modal" data-target="#message<?=$rekod['candidate_id']?>"> <i class="fas fa-trash"></i></a>
            </td>
          </tr>
           <!-- Logout Modal-->
                    <div class="modal fade" id="message<?=$rekod['candidate_id']?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Are you sure want to delete this candidate?</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                          </div>
                          <div class="modal-body"><?=$rekod['voter_name']?></div>
                          <div class="modal-footer">
                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                            <a class="btn btn-danger" href="deletecandidate.php?deletecandidate_id=<?=$candidateid?>">Delete</a>
                          </div>
                        </div>
                      </div>
                    </div>
        <?php
        }//end of records
      ?>
        </tbody>
        <tfoot>
          <tr>
            <th >Candidate ID
            </th>
            <th >Section 
            </th>
            <th >Name
            </th>
            <th >Fakulti
            </th>
            <th >Action
            </th>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>
</div>
<?php
include "include/footer.template.php";
?>	

<script>
	$(document).ready(function () {
$('#dtBasicExample').DataTable();
$('.dataTables_length').addClass('bs-select');
});
</script>

