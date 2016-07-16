<?php

	date_default_timezone_set('America/Sao_Paulo');
	
	include_once('verificaLogin.php');
	include_once('verificapermissao.php');
			
	$menu1	= "PDCJ";
	$menu2	= "Lançamentos";
	$menu3	= "Planejamento2";
	$menu4 	= "Orçamentos2";

	include_once("template/header.php");
	include_once("views/consulta_lancamentos_orcamentos_plan.php");
	include_once("template/footer.php");
	
?>
	
	    
	    