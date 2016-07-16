<?php

	date_default_timezone_set('America/Sao_Paulo');
	
	include_once('verificaLogin.php');
	include_once('verificapermissao.php');
		
	$menu1	= "Cadastros";
	$menu2	= "Propriedades";

	include_once("template/header.php");
	include_once("views/consulta_propriedade.php");
	include_once("template/footer.php");
	
?>
	
	    
	    