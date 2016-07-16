<?php

	date_default_timezone_set('America/Sao_Paulo');
	
	include_once('verificaLogin.php');
	include_once('verificapermissao.php');
			
	$menu1	= "Financeiro";
	$menu2	= "Contas a Pagar";

	include_once("template/header.php");
	include_once("views/consulta_conta_pagar.php");
	include_once("template/footer.php");
	
?>
	
	    
	    