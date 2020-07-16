<?php session_start();
$electionid=$_SESSION['electionid'];
//If your session isn't valid, it returns you to the login screen for protection
if(empty($_SESSION['id'])){
 header("location:../index.php");
}
include "../connection.php";
if (isset($_GET['section'])) {
    $section=$_GET['section'];
    $query="SELECT c.*,v.voter_name,v.matric_no,s.*
            FROM candidate as c 
            JOIN voter as v 
            ON c.voter_id=v.voter_id
            JOIN section as s 
            ON c.section_id=s.section_id 
            WHERE c.section_id='$section' ";
}
else
    $query="SELECT c.*,v.voter_name,v.matric_no,s.*
    FROM candidate as c 
    JOIN voter as v 
    ON c.voter_id=v.voter_id
    JOIN section as s 
    ON c.section_id=s.section_id ";

$qr=mysqli_query($db,$query);
if ($qr==false) {
    echo "Query cannot been executed<br>";
    echo "SQL error :".mysqli_error($db);
}

//Check the record effected, if no records,
//display a message
if(mysqli_num_rows($qr)==0){
echo ("No record fetched...<br>");
}//end no record

include "include/header.template.php";
?>
        <div class="container-fluid">

             <div class="card shadow mb-4">

            <div class="card-header py-3" >
              <div class="d-sm-flex align-items-center justify-content-between mb-1">
                <h5 class="m-0  font-weight-bold text-primary">Candidate</h5>
                <div class="pull-right"><a href="addcandidate.php"><button type="button" class="btn btn-primary">Add Candidates</button></a></div>
              </div>
           </div>
            <div class="card-body">
              <ul class="nav nav-pills">
                <li class="nav-item">
                  <a class="nav-link <?=$all_status?> " href="candidatelist.php">All</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link <?=$fstm_status?>" href="candidatelist.php?section=1">FSTM</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link <?=$fpm_status?> " href="candidatelist.php?section=3">FPM</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link <?=$fppi_status?> " href="candidatelist.php?section=4">FPPI</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link <?=$fsu_status?> " href="candidatelist.php?section=2">FSU</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link <?=$fp_status?> " href="candidatelist.php?section=5">FP</a>
                </li>
              </ul>
<hr>
              <table id="dtBasicExample" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
                <thead>
                  <tr>
                    <th class="th-sm">Candidate ID
                    </th>
                    <th class="th-sm">Section
                    </th>
                    <th class="th-sm">Name
                    </th>
                    <th class="th-sm">Matric No
                    </th>
                    <th >Action
                    </th>

                  </tr>
                </thead>
                <tbody>
                  <?php
              while ($rekod=mysqli_fetch_array($qr)){//redo to other records
              ?>
                  <tr>
                    <td><?=$rekod['candidate_id']?></td>
                    <td><?=$rekod['section_name']?></td>
                    <td><?=$rekod['voter_name']?></td>
                    <td><?=$rekod['matric_no']?></td>
                    <td>
                        <a href="deletecandidate.php?candidate_id=<?=$rekod['candidate_id']?>" class="btn btn-danger btn-circle btn-sm">
                        <i class="fas fa-trash"></i>
                    </td>
                  </tr>
                <?php
                }//end of records
              ?>
                </tbody>
                <tfoot>
                  <tr>
                    <th >Candidate ID
                    </th>
                    <th >Voter ID
                    </th>
                    <th >Name
                    </th>
                    <th >Matric No
                    </th>
                    <th >Action
                    </th>
                  </tr>
                </tfoot>
              </table>

<?php
include "include/footer.template.php";
?>	

<script>
	$(document).ready(function () {
$('#dtBasicExample').DataTable();
$('.dataTables_length').addClass('bs-select');
});
</script>

