<?php

	date_default_timezone_set('America/Sao_Paulo');
	
	include_once('verificaLogin.php');
	include_once('verificapermissao.php');
			
	$menu1	= "PDCJ";
	$menu2	= "Cadastros";	
	$menu3 	= "SubGrupos";

	include_once("template/header.php");
	include_once("views/consulta_pdcj_subgrupos_rel.php");
	include_once("template/footer.php");
	
?>
	
	    
	    