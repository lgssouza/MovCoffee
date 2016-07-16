<?php

	if(!isset($_SESSION))
	{
		session_start();
	}
	
	if (!isset($_SESSION['logado'])) 
	{
		session_destroy();
		header("Location: login.php");
		exit;
	}
	elseif(!$_SESSION['logado'])
	{
		session_destroy();
		header("Location: login.php");
		exit;
	}
	
?>