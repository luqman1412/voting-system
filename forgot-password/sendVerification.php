<?php 
 function sendVerification($matric_no){
require '../connection.php';

 // create pin 
$pin=random_int(10000, 99999);

 // message in email
$messagesubject= "Kuis e-voting system reset pasword request ";
$headers = "Content-Type: text/html; charset=ISO-8859-1\r\n";
$message ="This is your code for verify your request";
$message .="<br>Code: "."<b>".$pin."</b>";
$message .="<br>If your not request to reset password, please ignore this email";

$defaultemail= '1839011@student.kuis.edu.my';

    	// send email 
		// check whether email has been send and return to login page with message
		if (mail($defaultemail, $messagesubject, $message,$headers)) {

			$query= mysqli_query($db,"INSERT INTO resetpassword (matric_no,reset_token) VALUES ($matric_no,$pin)");
			if($query==false){
		      echo "Failed to delete student from reset password<br>";
		      echo "SQL error :".mysqli_error($db); 
		    }
		    else
		      return true;
		}
		else
		  return false;
 }
 ?>
