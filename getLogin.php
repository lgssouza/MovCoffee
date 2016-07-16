<?php
	
	include 'connect.php';
	global $mysqli;
	
	$nome 	= $_POST['username'];
	$senha 	= preg_replace('/[^[:alnum:]_]/', '',$_POST['password']);
	$senha	= sha1($senha);
	
	if(!$rs = $mysqli->query("SELECT * FROM tb_funcionario WHERE 
	                          func_usuario = '".$nome."' 
	                          and func_senha = '".$senha."'"))
	{
		$mysqli->errors;
	}
   	
	if (!isset($_SESSION)) session_start();
	
	if($row = $rs->fetch_object())
	{
		$_SESSION['logado']       	= true;
		$_SESSION['id_usuario']   	= $row->id_func;
		$_SESSION['nome_usuario'] 	= $row->func_usuario;
		$_SESSION['fk_categoria'] 	= $row->fk_categoria;
        
		header("Location: inicio.php");
	}
	else
	{
		header("Location: login.php?errologin='Erro na validacao, verifique as informacoes e tente novamente'");
	}

?>