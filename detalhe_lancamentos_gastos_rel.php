<?php

	date_default_timezone_set('America/Sao_Paulo');
	
	include_once('verificaLogin.php');
	include_once('verificapermissao.php');
			
	$menu1	= "PDCJ";
	$menu2	= "Lançamentos";
	$menu3	= "Relatório";
	$menu4 	= "Gastos";

	include_once("template/header.php");
	include_once("views/detalhe_lancamentos_gastos_rel.php");
	include_once("template/footer.php");
	
?>
	
	    
	    