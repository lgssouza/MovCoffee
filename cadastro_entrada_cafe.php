<?php
	
	date_default_timezone_set('America/Sao_Paulo');
	
	include_once('verificaLogin.php');
	include_once('verificapermissao.php');
		
	$menu1	= "Movimentação de Café";
	$menu2	= "Entradas";

	include_once("template/header.php");
	include_once("views/cadastro_entrada_cafe.php");
	include_once("template/footer.php");
	
?>
	
	    
	    