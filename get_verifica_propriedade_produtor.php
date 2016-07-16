<?php
	
	header ('Content-type: text/html; charset=UTF-8');

	include 'verificaLogin.php';
	include 'connect.php';
		
	global $mysqli;
	
	if (!$mysqli->set_charset("utf8")) 
	{
		printf("Error loading character set utf8: %s\n", $mysqli->error);
	}
	
	$propriedade	= $mysqli->real_escape_string($_POST['propriedade']);
	$produtor 		= $mysqli->real_escape_string($_POST['produtor']);
	
	if(!$rs = $mysqli->query("SELECT COUNT(*) totalregistros
	                          FROM mov_prop_prod 
							  WHERE fk_id_prop = $propriedade
							  AND 	fk_id_prod = $produtor"))
	{
		echo json_encode(array('msg'=>'Não foi possivel verificar se ja existe propriedade cadastrada para esse produtor'));
		return false;
	}
	
	$verifica1 = "";
	$verifica2 = "";
	
	if($rs)
	{
		$verifica1 = $rs->fetch_object();
	}
	
	if(!$rs = $mysqli->query("SELECT SUM(mov_pp_percentual) totalpercentual
	                          FROM mov_prop_prod 
							  WHERE fk_id_prop = $propriedade"))
	{
		echo json_encode(array('msg'=>'Não foi possivel verificar se propriedade já exedeu a porcentagem'));
		return false;
	}

	if($rs)
	{
		$verifica2 = $rs->fetch_object();
	}
	
	echo json_encode( array ( "verifica1" => $verifica1, "verifica2" => $verifica2));
	
?>