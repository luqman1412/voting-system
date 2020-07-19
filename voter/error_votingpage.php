<?php
if (isset($_GET['error'])) {
	if ($_GET['error']=="electionended") {
		echo "Election Ended";
	}
	elseif ($_GET['error']=="alreadyvote") {
		echo "Already Vote";
	}

}
?>