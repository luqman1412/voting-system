<?php
include "../connection.php";

 foreach ($_POST as $arrayindex => $candidateid) {
     $select['post'][$arrayindex] = $candidateid;
     $insert= $select['post'][$arrayindex];
    // check if the value is number
     if (is_numeric($candidateid)==true) {
        $query="UPDATE count SET total_vote=total_vote+1 WHERE candidate_id='$candidateid' ";
        $qr=mysqli_query($db,$query);
        if ($qr==false) {
            echo "Query cannot been executed<br>";
            echo "SQL error :".mysqli_error($db);
        }
    }
    else
    echo "vote succesfully submit";   

    } 

?>