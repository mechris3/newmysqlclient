<?php 
session_start();
function setSession()
{
	$_SESSION['server']="localhost";
	$_SESSION['username']="root";
	$_SESSION['password']="password";
	$_SESSION['db']="test";
}
 ?>