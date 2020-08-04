<?php session_start();
require 'connection.php';
// get pin from function
$pin=getpin();
// get data from session
$useremail =$_SESSION['email'];
$matric_no= $_SESSION['matric_no'];

// message in email
$messageheader= "Kuis e-voting system OTP ";
$message= "Please use this OTP as password "."\nOTP : ".$pin;

// save pin to DB 
$saveOTPtoDB=mysqli_query($db,"UPDATE login SET OTP = $pin,status = 'registed' WHERE username= '$matric_no' ");
	if ($saveOTPtoDB==false) {
        echo "Failed to register student <br>";
        echo "SQL error :".mysqli_error($db);
    }
    else{
    	// send email 
		// check whether email has been send and return to login page with message
		// echo "email send ";
		// echo "<a href=index.php>login<a>";
		if (mail($useremail, $messageheader, $message)) {
		  	header('Location: index.php?success=checkemail');
		}
		else
			header('Location: index.php?error=failedtosendemail');
    }

// make otp function
function getpin(){
	$nums ='';
	for ($i=0; $i <5 ; $i++) { 
		$rand= rand(0,9);
		$nums .= $rand;
	}
	return $nums;
}
 ?>