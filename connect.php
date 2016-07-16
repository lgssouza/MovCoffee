<?php
	
	$host 		= "localhost";
	$user 		= "root";
	$password 	= "";
	$database 	= "db_movcoffee";
	$mysqli 	= new mysqli($host, $user, $password, $database);
	
	if ($mysqli->connect_errno) 
	{
		echo "Erro ao conectar: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
	}
	
?>