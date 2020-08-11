<?php session_start();

$username=$_POST["txt_username"];
$password=$_POST["txt_password"];

// check if the text field are empty
if (empty($username)|| empty($password)) {
  header("Location: index.php?error=emptyfield");
  exit();
} 

include "connection.php";
$query="SELECT * FROM login WHERE username='$username' ";
$qr=mysqli_query($db,$query);
if($qr==false){
    echo "Failed to find user<br>";
    echo "SQL error :".mysqli_error($db);
}
//check password 
if (mysqli_num_rows($qr)==1) {
    $record =mysqli_fetch_array($qr);
    if($qr==false){
      echo "Failed to get user information<br>";
      echo "SQL error :".mysqli_error($db);
    }
    $dbpassword=$record['password'];
    $userid=$record['id'];
    $userlevel=$record['user_level'];
    echo "status:". $record['status'];

    // check if student are register
    if ($record['status']=="registed") {
        // check if password != null if true check the user password
        if ($record['password']!=NULL) {
            //compare the passsword 
            if ($dbpassword==$password) {
                // check user admin or voter
                if ($userlevel==1) {
                    $_SESSION['id']=$userid;
                    $_SESSION['name']=$username;
                    header('Location: admin/admindashboard.php');
                    exit(); 
                }
                else if ($userlevel==2){
                    $get_voter_id=mysqli_query($db,"SELECT voter_id,voter_name,faculty FROM voter WHERE matric_no='$username'");
                    if($get_voter_id==false){
                        echo "Failed to find user <br>";
                        echo "SQL error :".mysqli_error($db);
                    }
                    else
                      $voter_id=mysqli_fetch_array($get_voter_id);

                      $_SESSION['id']=$voter_id['voter_id'];
                      $_SESSION['name']=$voter_id['voter_name'];
                      $_SESSION['faculty']=$voter_id['faculty'];
                      header('Location: voter/votingpageumum.php');
                      exit(); 
                }
                else
                  echo "Error on find user";
            }
            else{
              header("Location: index.php?error=wrongpassword&username=$username");
              exit();
              
            }
        }
        // check if password = null if true redirect user to set new password page
        elseif ($record['password']==NULL) {
            if ($record['OTP']==$password) {
                $get_matric_no=mysqli_query($db,"SELECT matric_no FROM voter WHERE matric_no='$username'");
                    $matric_no=mysqli_fetch_array($get_matric_no);
                    if($get_matric_no==false){
                        echo "Failed to find user <br>";
                        echo "SQL error :".mysqli_error($db);
                    }
                $_SESSION['matric_no']=$matric_no['matric_no'];
                header('Location:newpasswordpage.php');
                exit();
            }
            elseif ($record['OTP'] !=$password) {
              header("Location: index.php?error=wrongpassword&username=$username");
              exit();
            }
        }
    }
    // return to login page if student are not register
    elseif ($record['status']=='not register') {
      header("Location: index.php?error=notregister");
      exit();
    }
    else
      echo " Error on status user";
}
else
  header("Location: index.php?error=usernotfound");
?>