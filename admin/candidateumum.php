<?php session_start();
$electionid=$_SESSION['electionid'];
//If your session isn't valid, it returns you to the login screen for protection
if(empty($_SESSION['id'])){
 header("location:../index.php?error=alreadylogout");
}
include "../connection.php";

$query="SELECT c.*,v.*,f.name,s.*
FROM candidate as c 
JOIN voter as v 
ON c.voter_id=v.voter_id
JOIN faculty as f 
ON v.faculty=f.faculty_id
JOIN section as s 
ON c.section_id= s.section_id 
WHERE c.section_id=0 AND c.election_id = '$electionid'";


$qr=mysqli_query($db,$query);
if ($qr==false) {
    echo "Failed to get candidate information<br>";
    echo "SQL error :".mysqli_error($db);
}


include "include/header.template.php";
?>
        <div class="container-fluid">
          <div class="card shadow mb-4">
            <div class="card-header py-3" >
              <div class="d-sm-flex align-items-center justify-content-between mb-1">
                <h5 class="m-0  font-weight-bold text-primary">Calon Umum</h5>
                <div class="pull-right"><a href="umum_voterlist.php"><button type="button" class="btn btn-primary">Add Candidates</button></a></div>
              </div>
           </div>
           <div class="card-body">
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
              ?>
                  <tr>
                    <td><?=$rekod['candidate_id']?></td>
                    <td><?=$rekod['section_name']?></td>
                    <td><?=$rekod['voter_name']?></td>
                    <td><?=$rekod['name']?></td>
                    <td>
                      <a href="deletecandidate.php?candidate_id=<?=$rekod['candidate_id']?>" class="btn btn-danger btn-circle btn-sm"> <i class="fas fa-trash"></i>
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

