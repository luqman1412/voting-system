<?php session_start();
$electionid=$_SESSION['electionid'];

//If your session isn't valid, it returns you to the login screen for protection
if(empty($_SESSION['id'])){
 header("location:../index.php?error=notauthorised");
}

 // push candidate data to database
  // check if the 'voterid' variable is set in URL
  if (isset($_GET['voterid'])){
    
    include '../connection.php';
    // get id value
    $voterid = $_GET['voterid'];
    // to get candidate information from database
    $sql="SELECT * from voter WHERE voter_id= $voterid";
    $voterdetail=mysqli_query($db,$sql);
    if ($voterdetail==false) {
    echo "Failed to get voter information fakulti 2<br>";
    echo "SQL error :".mysqli_error($db);
    }
    else
       $rekod=mysqli_fetch_array($voterdetail);

    $voter_id =$rekod['voter_id'];
    $voter_name =$rekod['voter_name'];
    $voter_faculty =$rekod['faculty'];
      // insert voter data into candidate table in DB
      $sql="INSERT INTO candidate (voter_id,section_id,election_id) VALUES ('$voterid','$voter_faculty','$electionid')";
      $qr=mysqli_query($db,$sql);

      if ($qr==true){
        // get last inserted candidate id from database
        $candidate_id = mysqli_insert_id($db);
        // to add into count database
        $sql="INSERT INTO count (candidate_id,section_id,election_id) VALUES ('$candidate_id','$voter_faculty','$electionid')";
        $qr=mysqli_query($db,$sql);
        if ($qr==false) {
          echo "Failed to add candidate to count table<br>";
          echo "SQL error :".mysqli_error($db);
          exit();
        }
        else 
        // redirect to candidate list page after succesfully insett data into DB 
        header('Location: candidatefakulti.php?success=addcandidate');
      }
      else{
        echo "Fail to add as candidate for $voterid";
        echo mysqli_error($db);
        exit();
      }
   }



?>
