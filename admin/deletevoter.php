<?php 
// check if variable is set in the url
if (isset($_GET['delete_voter_id'])) {
    require '../connection.php';
    $voterid=$_GET['delete_voter_id'];

    $qr=mysqli_query($db,"DELETE FROM voter WHERE voter_id='$voterid' ");
    if ($qr==true){
        header('Location: voterlist.php?success=deleted');
    }
    else{
    echo "Fail to delete record for $voterid ";
    echo mysqli_error($db);
    }
}
else
  header('Location:voterlist.php');
?>