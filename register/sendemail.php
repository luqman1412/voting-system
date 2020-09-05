<?php session_start();
require '../connection.php';

// get data from session
// $useremail =$_SESSION['email'];
$matric_no= $_SESSION['matric_no'];

// check if user authorised
if (empty($matric_no)) {
	header('Location: ../index.php?error=notauthorised');
	exit();
}
// create pin 
$pin=random_int(10000, 99999);

// message in email
$messagesubject= "Kuis e-voting system register verification ";
$headers = "Content-Type: text/html; charset=ISO-8859-1\r\n";
$message ="Please use this <b>One time Password</b> to verify yourself";
$message .="<br>This is your One time Password: "."<b>".$pin."</b>";
$message .="<br>If your not request this code, please ignore this email";


$defaultemail= '1839011@student.kuis.edu.my';

    	// send email 
		// check whether email has been send and return to login page with message
		if (mail($defaultemail, $messagesubject, $message,$headers)) {
			$hashpin=md5($pin);
			// save pin to DB 
			$saveOTPtoDB=mysqli_query($db,"INSERT INTO register (matric_no,register_token) VALUES ('$matric_no','$hashpin') ");
			if ($saveOTPtoDB==false) {
			echo "Failed to register student <br>";
			echo "SQL error :".mysqli_error($db);
			exit();
			}
		  	header('Location: userverification.php?success=emailsended');
		  	exit();
		}
		else
			header('Location: ../index.php?error=failedtosendemail');
		  	exit();
 ?>