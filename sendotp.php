<?php session_start();
  require 'connection.php';
  include 'systememail.php';
	$matric_no=$_SESSION['matric_no'];
	$name=$_SESSION['name'];
	$subject=$_SESSION['subject'];

	 // create pin 
	$pin=random_int(10000, 99999);
	$hashpin=md5($pin);
	include 'emailtemplate.php';
	
	 // message in email
	$message=$template;
	$email= $matric_no. "@student.kuis.edu.my";

	require 'PHPMailerAutoload.php';

	$mail = new PHPMailer;

	// $mail->SMTPDebug = 4;                               // Enable verbose debug output

	$mail->isSMTP();                                      // Set mailer to use SMTP
	$mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
	$mail->SMTPAuth = true;                               // Enable SMTP authentication
	$mail->Username = $systememail;                 // SMTP username
	$mail->Password = $systempassword;                           // SMTP password
	$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
	$mail->Port = 587;                                    // TCP port to connect to

	$mail->setFrom($systememail, 'KUIS Voting system');
	$mail->addAddress($email, $name);     // Add a recipient
	$mail->addReplyTo($systememail, 'NO-REPLY');

	$mail->isHTML(true);                                  // Set email format to HTML

	$mail->Subject = $subject;
	$mail->Body    = $message;
	$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

	if(!$mail->send()) {
	    echo 'Message could not be sent.';
	    echo 'Mailer Error: ' . $mail->ErrorInfo;
	    header('Location: index.php?error=failedtosendemail');
	} else {
	    echo 'Message has been sent';
	     $query= mysqli_query($db,"INSERT INTO requestlogin (matric_no,otp) VALUES ('$matric_no','$hashpin')");
      	if($query==false){
          echo "Failed to load login request<br>";
          echo "SQL error :".mysqli_error($db); 
          exit();
        }
        else
    		header('Location: studentverification.php?success=emailsended');
	}
 ?>