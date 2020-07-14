<?php
session_start();
//If your session isn't valid, it returns you to the login screen for protection
if(empty($_SESSION['id'])){
header("location:../index.php?error=alreadylogout");
}
// get voter id & voter faculty_id  from session
$voterid=$_SESSION['id'];
$voterfaculty=$_SESSION['faculty'];
// print the voter id
echo "voter id:". $voterid."<br>";

// get selected from previos page
$_SESSION['umum']=$_POST['umum_candidate_selected'];

// check selection from previos page
if (empty($_SESSION['umum'])) {
  // if selection empty return to previos page
   header("Location:votingpageumum.php?error=selection_empty");
}

$pilihanumum=$_SESSION['umum'];
//print the umum selection 
foreach ($pilihanumum as $indexarray => $datainarray) {
    echo "key: ".$indexarray." value: ".$datainarray ."- ";
}
include "../connection.php";
// get fakulti candidate from DB
$query="SELECT c.*,v.*,s.*
        FROM candidate as c 
        JOIN voter as v
        ON c.voter_id=v.voter_id 
        JOIN section as s
        ON c.section_id=s.section_id 
        WHERE s.section_id='$voterfaculty' ";
$qr=mysqli_query($db,$query);
if ($qr==false) {
    echo "Failed to get fakulti candidate information (Query failed)<br>";
    echo "SQL error :".mysqli_error($db);
}
include 'include/header_votingpage.php';

?>
<html>
<body>
  <div class="container" align="center" >

    <!-- Outer Row -->
    <div class="row justify-content-center">

      <div class="col-xl-7 col-lg-12 col-md-9">

        <div class="card o-hidden border-0 shadow-lg my-5">
          <div class="card-body p-0">
            <div class="p-5">
              <!-- form start -->
              <form name="form_fakulti" method="POST" action="confirmation_votingpage.php">
                <table class="table ">
                  <thead>
                    <h2>calon fakulti</h2>
                    <div class="alert alert-info" role="alert">Please select (amount) candidate!</div>
                      <tr>
                        <th scope="col">Candidate ID</th>
                        <th scope="col">Candidate Name</th>
                        <th scope="col">Action</th>
                      </tr>
                  </thead>
                  <tbody>
                    <?php
                      while ($record=mysqli_fetch_array($qr)){//redo to other records
                    ?>
                  <tr>
                    <td ><?=$record['candidate_id']?></td>
                    <td><?=$record['voter_name']?></td>
                    <td>
                      <div class="form-check">
                        <input name="fakulti_candidate_selected[]" class="form-check-input" type="checkbox" value="<?=$record['candidate_id']?>" >
                      </div>
                    </td>
                  </tr>
                  <?php
                    }//end of records
                  ?>   
                  </tbody>
                </table>
                <input class="btn btn-danger" name="btn_back" type="button" value="Back" onclick="history.back()">
                <input class="btn btn-primary" type="submit" name="btn_submit_vote" value="Submit">
              </form>
            </div>
          </div>
        </div>

      </div>

    </div>

  </div>

</body>
</html>
<?php include 'include/footer_votingpage.php' ?>