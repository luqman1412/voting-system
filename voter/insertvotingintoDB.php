<?php session_start();
$voter_id= $_SESSION['id'];
include "../connection.php";

foreach ($_POST as $arrayindex => $candidateid) {
    $select['post'][$arrayindex] = $candidateid;
    $insert= $select['post'][$arrayindex];
   // check if the candidate is number if true update count in DB if not throw error
   if (is_numeric($candidateid)==true) {
       $add_vote=mysqli_query($db,"UPDATE count SET total_vote=total_vote+1 WHERE candidate_id='$candidateid' ");
       if ($add_vote==false) {
           echo "Failed to add vote <br>";
           echo "SQL error :".mysqli_error($db);
       }
       else
           echo "vote succesfully submit<br>"; 
   }
   else
       echo "invalid candiate id<br>"; 
   } 
// add voter to alreadyvote table
$alreadyvote=mysqli_query($db,"INSERT INTO alreadyvote (voter_id) VALUES ('$voter_id')");
echo " show successfull mesage ";
?>