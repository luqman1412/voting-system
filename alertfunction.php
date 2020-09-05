<?php

function alertwithclose($message)
{
	echo '<div class="alert alert-danger" role="alert"> '.$message.' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
}
function alert($message)
{
	echo '<div class="alert alert-danger" role="alert"> '.$message.' </div>';
}
function successwithclose($message)
{
	echo '<div class="alert alert-success" role="alert"> '.$message.' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
}
function success($message)
{
	echo '<div class="alert alert-success" role="alert"> '.$message.' </div>';
}
?>