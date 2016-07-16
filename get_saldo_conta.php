<?php
	
	header ('Content-type: text/html; charset=UTF-8');

	include 'verificaLogin.php';
	include 'connect.php';
		
	global $mysqli;
	
	if (!$mysqli->set_charset("utf8")) 
	{
		printf("Error loading character set utf8: %s\n", $mysqli->error);
	}
	
	$idconta = $mysqli->real_escape_string($_POST['idconta']);
	
	if(!$rs = $mysqli->query("SELECT *
	                          FROM tb_conta_saldo 
							  WHERE fk_id_conta = $idconta"))
	{
		echo json_encode(array('msg'=>'Não foi possivel consultar o saldo'));
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