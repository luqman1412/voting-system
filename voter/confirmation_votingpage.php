<?php session_start();
include '../connection.php';
    // get pilihan fakulti 
    $pilihanfakulti=$_POST['fakulti_candidate_selected'];
        // get pilihan umum
    $pilihanumum=$_SESSION['umum'];

    // print out pilihan (for test only)
    foreach ($pilihanfakulti as $key => $candidate_id) {
        echo " fakulti candidate id: ".$candidate_id ;
    }

    foreach ($pilihanumum as $key => $candidate_id) {
        echo " umum candidate id: ".$candidate_id ;
    }


include 'include/header_votingpage.php' 
?>
<html>
<body>
  <div class="container-fluid" align="center" >
    <!-- Outer Row -->
    <div class="row justify-content-center">

      <div class="col-xl-9 col-lg-12 col-md-9">
        <!-- card element -->
        <div class="card o-hidden border-0 shadow-lg my-5">
          <div class="card-header py-3" >
            <h5 class="m-0  font-weight-bold text-primary">Confirmation</h5>
          </div>
          <div class="card-body p-0">
            <div class="p-5">
              <div class="row">
                <div class="col">
                  <h3>candidate umum</h3>
                    <form method="POST" action="insertvotingintoDB.php">
                      <table class="table table-bordered">
                        <tr>
                          <th class="th-sm" >Candidate ID</th>
                          <th class="th-sm" >Name</th>
                          <th class="th-sm" >Action</th>
                        </tr>
                        <?php
                          // get umum candidate information from DB
                          foreach ($pilihanumum as $key => $candidate_id) {
                            $query="SELECT c.*,v.*
                                    FROM candidate as c 
                                    JOIN voter as v
                                    ON c.voter_id=v.voter_id 
                                    WHERE c.candidate_id='$candidate_id'";
                            $qr=mysqli_query($db,$query);
                            if ($qr==false) {
                                echo "Query cannot been executed<br>";
                                echo "SQL error :".mysqli_error($db);
                            }
                            $record=mysqli_fetch_array($qr);
                            $name=$record['voter_name'];
                        ?> 
                        <tr>
                          <td><input name="umum<?=$key?>" type="text" readonly class="form-control-plaintext form-control-sm" value="<?=$candidate_id?>"></td>
                          <td><?=$name?></td>
                           <td><button class="btn btn-info btn-circle btn-sm "><i class="fas fa-info-circle"></i></button> </td>

                        </tr>
                        <!-- close loop -->
                        <?php } ?>
                      </table>
                </div>
                      <div class="col">
                        <h3>candidate fakulti</h3>
                        <table class="table table-bordered">
                          <tr>
                            <th class="th-sm" >Candidate ID</th>
                            <th class="th-sm" >Name</th>
                            <th class="th-sm" >Action</th>

                          </tr>
                          <?php
                            // get fakulti candidate information from DB
                            foreach ($pilihanfakulti as $key => $candidate_id) {
                              $query="SELECT c.*,v.*,f.*
                                      FROM candidate as c 
                                      JOIN voter as v
                                      ON c.voter_id=v.voter_id 
                                      JOIN faculty as f
                                      ON v.faculty=f.faculty_id
                                      WHERE c.candidate_id='$candidate_id'";
                              $qr=mysqli_query($db,$query);
                              if ($qr==false) {
                                  echo "Query cannot been executed<br>";
                                  echo "SQL error :".mysqli_error($db);
                              }
                              $record=mysqli_fetch_array($qr);
                              $name=$record['voter_name'];
                          ?> 
                          <tr>
                            <td><input name="<?=$record['name']?><?=$key?>" type="text" readonly class="form-control-plaintext form-control-sm" value="<?=$candidate_id?>"></td>            
                            <td><?=$name?></td>
                            <td><button class="btn btn-info btn-circle btn-sm "><i class="fas fa-info-circle"></i></button> </td>

                          </tr>
                          <!-- close loop -->
                          <?php }?>
                        </table>
                      </div>
                </div>
                 <input class="btn btn-danger " name="btn_back" type="submit"value="Back">
                 <input class="btn btn-primary " name="submit" type="button" value="Submit" onclick="history.back()" >
                    </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</body>
</html>
<?php include 'include/footer_votingpage.php' ?>