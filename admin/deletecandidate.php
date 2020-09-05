<?php session_start();
$electionid=$_SESSION['electionid'];

if(empty($_SESSION['id'])){
 header("location:../index.php");
}

// if delete button is press
if (isset($_GET['deletecandidate_id'])) {
    // delete from database
    include "../connection.php";
    // get the id from url
    $candidate_id= $_GET['deletecandidate_id'];  
    // get 'section_id' from DB
    $qr=mysqli_query($db,"SELECT section_id FROM candidate WHERE candidate_id='$candidate_id' ");
      if ($qr==true){
          $section_id=mysqli_fetch_array($qr);
      }
    // delete candidate information from DB
    $qr=mysqli_query($db,"DELETE FROM candidate WHERE candidate_id ='$candidate_id' ");
    if ($qr==true){
        $qr=mysqli_query($db,"DELETE FROM count WHERE candidate_id='$candidate_id' ");

        // return to page based on 'section_id'
        if ($section_id['section_id']==0) {
             header('Location: candidateumum.php');
        }
        elseif ($section_id>0) {
             header('Location: candidatefakulti.php');
        }
    }
    else{
      echo "Fail to delete record for $candidate_id";
      echo mysqli_error($db);
    }
}

?>
