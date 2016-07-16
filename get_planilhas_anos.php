<?php 

	date_default_timezone_set('America/Sao_Paulo');
	
	include_once('verificaLogin.php');
	include_once('verificapermissao.php');
	
	//if(!verificapermissao("Funcionários",$permissoes))
	//{
	//	header("Location: home.php");  
	//	exit;
	//}	
	
	include_once('connect.php');
	
	global $mysqli;
	
	if (!$mysqli->set_charset("utf8")) 
	{
		printf("Error loading character set utf8: %s\n", $mysqli->error);
	}
	
	$sql = "SELECT subg_ano ANO
			FROM `tb_pdcj_subgrupo_rel_valores` 
			GROUP BY subg_ano
			ORDER BY subg_ano";
	
	$rs = $mysqli->query($sql);
	
	if($rs)
	{
		$regs = array();
        
		while($reg = $rs->fetch_object())
		{
			array_push($regs, $reg);
		}
		
		$result["rows"] = $regs;
		echo json_encode($result);
		
		$rs->close();
	}

	$mysqli->close();

?>