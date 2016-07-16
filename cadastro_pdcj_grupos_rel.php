<?php
	
	date_default_timezone_set('America/Sao_Paulo');
	
	include_once('verificaLogin.php');
	include_once('verificapermissao.php');
		
	$menu1	= "PDCJ";
	$menu2	= "Cadastros";	
	$menu3 	= "Grupos";

	include_once("template/header.php");
	include_once("views/cadastro_pdcj_grupos_rel.php");
	include_once("template/footer.php");
	
?>
	
	    
	    