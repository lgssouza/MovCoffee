<?php

	include 'verificaLogin.php';
	include 'connect.php';
		
	global $mysqli;
	
	if (!$mysqli->set_charset("utf8")) 
	{
		printf("Error loading character set utf8: %s\n", $mysqli->error);
	}
	
	if(!isset($_SESSION))
    {
        session_start();
    }
	
	if (isset($_SESSION['fk_categoria'])) 
    {
		$fk_categoria = $_SESSION['fk_categoria'];
    }
	
	$sql = "SELECT * FROM tb_permissao_formulario 
			INNER JOIN tb_formulario ON fk_id_form = id_form 
			WHERE fk_id_cat = $fk_categoria";
	
	if(!empty($sql))
	{
		$rs 	= $mysqli->query($sql);
		
		if($rs)
		{
			$permissoes = array();
        
			while($permissao = $rs->fetch_object())
			{
				array_push($permissoes, $permissao);
			}
			
			$rs->close();
		}
	}
	
	function verificapermissao($formulario,$permissoes)
	{
		for($i=0;$i<count($permissoes);$i++)
		{
			if($permissoes[$i]->form_nome == $formulario)
			{
				if(
				  	$permissoes[$i]->perm_visualizar 	||
				   	$permissoes[$i]->perm_incluir 		||
				   	$permissoes[$i]->perm_alterar 		||
				   	$permissoes[$i]->perm_excluir 
				  )
				{
					return true;
				}
			}
		}
	}
	
	function verificapermissaoleitura($formulario,$permissoes)
	{
		for($i=0;$i<count($permissoes);$i++)
		{
			if($permissoes[$i]->form_nome == $formulario)
			{
				if($permissoes[$i]->perm_visualizar)
				{
					return true;
				}
			}
		}
	}
	
	function verificapermissaogravacao($formulario,$permissoes)
	{
		for($i=0;$i<count($permissoes);$i++)
		{
			if($permissoes[$i]->form_nome == $formulario)
			{
				if($permissoes[$i]->perm_incluir)
				{
					return true;
				}
			}
		}
	}
	
	function verificapermissaoedicao($formulario,$permissoes)
	{
		for($i=0;$i<count($permissoes);$i++)
		{
			if($permissoes[$i]->form_nome == $formulario)
			{
				if($permissoes[$i]->perm_alterar)
				{
					return true;
				}
			}
		}
	}
	
	function verificapermissaoexclusao($formulario,$permissoes)
	{
		for($i=0;$i<count($permissoes);$i++)
		{
			if($permissoes[$i]->form_nome == $formulario)
			{
				if($permissoes[$i]->perm_excluir)
				{
					return true;
				}
			}
		}
	}
	
?>