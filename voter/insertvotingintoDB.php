<?php session_start();
$voter_id= $_SESSION['id'];
$electionid=$_SESSION['electionid'];
include "../connection.php";
// insert candidate umum into DB
foreach($_SESSION['umum'] as $index => $candidateid) {
  if (is_numeric($candidateid)==true) {

       $add_vote=mysqli_query($db,"UPDATE count SET total_vote=total_vote+1 WHERE candidate_id='$candidateid' ");
       if ($add_vote==false) {
           echo "Failed to add vote <br>";
           echo "SQL error :".mysqli_error($db);
           exit();    
       }
   }else{ 
        echo "invalid candidate id<br>";
        exit();
       }
}

// insert candidate fakulti into DB
foreach($_SESSION['fakulti'] as $index => $candidateid) {
  if (is_numeric($candidateid)==true) {
       $add_vote=mysqli_query($db,"UPDATE count SET total_vote=total_vote+1 WHERE candidate_id='$candidateid' ");
       if ($add_vote==false) {
           echo "Failed to add vote <br>";
           echo "SQL error :".mysqli_error($db);
           exit();
       }
   }else{
     echo "invalid candidate id<br>";
     exit();
      }
}

// add voter to alreadyvote table
$alreadyvote=mysqli_query($db,"INSERT INTO alreadyvote (voter_id,election_id) VALUES ('$voter_id','$electionid')");
if ($alreadyvote==false) {
  echo "Failed to add voter to already voter table in DB <br>";
  echo "SQL error :".mysqli_error($db);
}
else
  header('Location: error_votingpage.php?success=succesfullyvote');
?>