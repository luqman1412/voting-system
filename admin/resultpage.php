<?php
include "connection.php";
$query="SELECT c.*,v.voter_name,v.matric_no
FROM candidate as c 
JOIN voter as v 
ON c.voter_id=v.voter_id ";

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
else//there is/are record(s)

include "header.template.php";
?>
    <div class="container-fluid">

             <div class="card shadow mb-4">

            <div class="card-header py-3" >
              <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h5 class="m-0  font-weight-bold text-primary">Candidate</h5>
                <div class="pull-right"><a href="#"><button type="button" class="btn btn-primary">Add Candidates</button></a></div>
              </div>
           </div>
            <div class="card-body">
              <table id="dtBasicExample" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
  <thead>
    <tr>
      <th class="th-sm">Candidate ID
      </th>
      <th class="th-sm">Voter ID
      </th>
      <th class="th-sm">Name
      </th>
      <th class="th-sm">Matric No
      </th>
      <th >Total
      </th>

    </tr>
  </thead>
  <tbody>

  <?php
while ($rekod=mysqli_fetch_array($qr)){//redo to other records
?>
    <tr>
      <td><?=$rekod['candidate_id']?></td>
      <td><?=$rekod['voter_id']?></td>
      <td><?=$rekod['voter_name']?></td>
      <td><?=$rekod['matric_no']?></td>
      <td> hai</td>

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
      <th >Total
      </th>
    </tr>
  </tfoot>
</table>
<?php
include "footer.template.php"
?>

<script>
	$(document).ready(function () {
$('#dtBasicExample').DataTable();
$('.dataTables_length').addClass('bs-select');
});
</script>