<?php

	date_default_timezone_set('America/Sao_Paulo');
	
	include_once('verificaLogin.php');
	include_once('verificapermissao.php');
			
	$menu1	= "PDCJ";
	$menu2	= "Exportação";	
		
	include_once("template/header.php");
	include_once("views/tela_exporta_pdcj.php");
	include_once("template/footer.php");
	
?>
	
	    
	    