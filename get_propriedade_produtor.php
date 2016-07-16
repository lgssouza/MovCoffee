<?php
	
	header ('Content-type: text/html; charset=UTF-8');

	include 'verificaLogin.php';
	include 'connect.php';
		
	global $mysqli;
	
	if (!$mysqli->set_charset("utf8")) 
	{
		printf("Error loading character set utf8: %s\n", $mysqli->error);
	}
	
	$idpropriedadeprodutor = $mysqli->real_escape_string($_POST['idpropriedadeprodutor']);
	
	if(!$rs = $mysqli->query("SELECT *
	                          FROM mov_prop_prod 
							  WHERE id_mov_pp = '$idpropriedadeprodutor'"))
	{
		echo json_encode(array('msg'=>'Não foi possivel verificar se ja existe usuário cadastrado'));
		return false;
	}
	
	if($rs)
	{
		while($row = $rs->fetch_object())
		{
			echo json_encode( array ( "msg" => 'sucesso', "dados" => $row));
		}
	}

	
		
?>