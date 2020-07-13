<?php
session_start();
// unset($_SESSION["id"]);
// unset($_SESSION["electionid"]);
session_destroy();
  header('Location: index.php');

?>