<?php
session_start();
//If your session isn't valid, it returns you to the login screen for protection
if(empty($_SESSION['id'])){
    header("location:../index.php?error=alreadylogout");
}

$voterid=$_SESSION['id'];
include "../connection.php";
// get candidate from DB
$query="SELECT c.*,v.voter_name,v.matric_no,v.voter_id
FROM candidate as c 
JOIN voter as v 
ON c.voter_id=v.voter_id
WHERE section_id=0";

$qr=mysqli_query($db,$query);
if ($qr==false) {
    echo "Query cannot been executed<br>";
    echo "SQL error :".mysqli_error($db);
}
$get_voterdetail=mysqli_query($db,"SELECT * FROM voter");
if ($qr==false) {
    echo "Failed to get voter information <br>";
    echo "SQL error :".mysqli_error($db);
}
$voter_record=mysqli_fetch_array($get_voterdetail);
include 'include/header_votingpage.php';
?>
<html>
<body>
  <div class="container" align="center" >

    
      <div class="col-xl-7 col-lg-12 col-md-9">

        <div class="card o-hidden border-0 shadow-lg my-5">
          <div class="card-body p-0">
                <div class="p-5">

                  <!-- form start -->
                  <form name="form_umum" method="POST" action="votingpagefakulti.php">
                    <table class="table ">
                      <thead>
                        <h2>calon umum</h2>
                        
                        <!-- error handling -->
                        <?php 
                            if (isset($_GET['error'])) {
                              if ($_GET['error'] == "selection_empty") {
                                echo '<div class="alert alert-danger" role="alert">Please select (amount) candidate!</div> ';
                              }
                            }
                            else
                                echo '<div class="alert alert-info" role="alert">Please select (amount) candidate!</div> ';
                        
                         ?>

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
                            <input name="umum_candidate_selected[]" class="form-check-input" type="checkbox" value="<?=$record['candidate_id']?>" >
                          </div>
                        </td>
                      </tr>
                      <?php
                        }//end of records
                      ?>   
                      </tbody>
                    </table>
                    <input  class="btn btn-primary" type="submit" name="btn_submit" value="Next">
                  </form>
                </div>
          </div>
        </div>
      </div>


</body>
</html>
<?php include 'include/footer_votingpage.php' ?>