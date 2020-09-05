<?php 
 function sendVerification($matric_no,$subject){
// DB connection
$dbhost="localhost";
$dbusername="root";
$dbpassword="";
$dbname="voting";

$db=mysqli_connect($dbhost,$dbusername,$dbpassword,$dbname);
if (!$db) {
	die("Connection Failed: ".mysqli_connect_error());
}

 // create pin 
$pin=random_int(10000, 99999);
$hashpin=md5($pin);

 // message in email
$messagesubject=$subject;
$headers = "Content-Type: text/html; charset=ISO-8859-1\r\n";
$message ="This is your code for verify your request";
$message .="<br>Code: "."<b>".$pin."</b>";
$message .="<br>If your not request to reset password, please ignore this email";

$defaultemail= '1839011@student.kuis.edu.my';

    	// send email 
		// check whether email has been send and return to login page with message
		if (mail($defaultemail, $messagesubject, $message,$headers)) {
			$query= mysqli_query($db,"INSERT INTO resetpassword (matric_no,reset_token) VALUES ('$matric_no','$hashpin')");
			if($query==false){
		      echo "Failed to load reset password request<br>";
		      echo "SQL error :".mysqli_error($db); 
		    }
		    else
		      return true;
		}
		else
		  return false;
 }
 ?>
