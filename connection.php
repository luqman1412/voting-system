
<?php

$dbhost="localhost";
$dbusername="";
$dbpassword="";
$dbname="voting";

$db=mysqli_connect($dbhost,$dbusername,$dbpassword,$dbname);
if (!$db) {
	die("Connection Failed: ".mysqli_connect_error());
}
?>
