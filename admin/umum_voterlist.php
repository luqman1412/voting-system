<?php session_start();
$electionid=$_SESSION['electionid'];

//If your session isn't valid, it returns you to the login screen for protection
if(empty($_SESSION['id'])){
 header("location:../index.php");
}

include "../connection.php";
$query="SELECT * FROM voter";

$qr=mysqli_query($db,$query);
if ($qr==false) {
    echo "Query cannot been executed<br>";
    echo "SQL error :".mysqli_error($db);
}


include "include/header.template.php";
?>
        <div class="container-fluid">

             <div class="card shadow mb-4">

            <div class="card-header py-3" >
              <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h5 class="m-0  font-weight-bold text-primary">Sila pilih calon</h5>
              </div>
           </div>
            <div class="card-body">
              <table id="dtBasicExample" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
                <p id="demo"></p>

                 <?php  if (isset($_GET['error'])) {
                      if ($_GET['error'] == "alreadyumumcandidate") {
                        echo '<div class="alert alert-danger" role="alert">Voter already umum candidate! Please choose another voter</div> ';
                      }
                      elseif ($_GET['error'] == "alreadyfacultycandidate") {
                        echo '<div class="alert alert-danger" role="alert">Voter already faculty candidate! Please choose another voter</div> ';
                      }
                      elseif ($_GET['error'] == "alreadycandidate") {
                        echo '<div class="alert alert-danger" role="alert">Voter already candidate! Please choose another voter</div> ';
                      }

                    }
                 ?>
  <thead>
    <tr>
      <th class="th-sm">Voter ID
      </th>
      <th class="th-sm">Email
      </th>
      <th class="th-sm">Name
      </th>
      <th class="th-sm">Matric No
      </th>
      <th class="th-sm">Faculty
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
 <td><?=$rekod['voter_id']?></td>
 <td><?=$rekod['email']?></td>
 <td><?=$rekod['voter_name']?></td>
 <td><?=$rekod['matric_no']?></td>
 <td><?=$rekod['faculty']?></td>
      <td>
        <a href="umum_confirmation.php?voter_id=<?=$rekod['voter_id']?>&section=1">  <button type="button" class="btn btn-primary btn-rounded btn-sm my-0"> Add </button></a>
      </td>
    </tr>
  <?php
  }//end of records
?>
  </tbody>
  <tfoot>
    <tr>
      <th>Voter ID
      </th>
      <th>Email
      </th>
      <th>Name
      </th>
      <th>Matric No
      </th>
      <th>Faculty
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

