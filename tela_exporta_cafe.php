<?php

	date_default_timezone_set('America/Sao_Paulo');
	
	include_once('verificaLogin.php');
	include_once('verificapermissao.php');
			
	$menu1	= "Movimentação de Café";
	$menu2	= "Relatórios";	
		
	include_once("template/header.php");
	include_once("views/tela_exporta_cafe.php");
	include_once("template/footer.php");
	
?>
	
	    
	    