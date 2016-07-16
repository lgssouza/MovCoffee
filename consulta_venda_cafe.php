<?php

	date_default_timezone_set('America/Sao_Paulo');
	
	include_once('verificaLogin.php');
	include_once('verificapermissao.php');
		
	$menu1	= "Movimentação de Café";
	$menu2	= "Vendas";

	include_once("template/header.php");
	include_once("views/consulta_venda_cafe.php");
	include_once("template/footer.php");
	
?>
	
	    
	    