<?php 

	header ('Content-type: text/html; charset=UTF-8');

	include 'verificaLogin.php';
	include 'connect.php';
		
	global $mysqli;
	
	if (!$mysqli->set_charset("utf8")) 
	{
		printf("Error loading character set utf8: %s\n", $mysqli->error);
	}
	
	if(isset($_POST['acaofuncionario']))
	{
		$acaofuncionario = $_POST['acaofuncionario'];
	}
	else
	{
		echo json_encode(array('msg'=>'Erro Inesperado! Ação não identificada'));
		return false;
	}
	
	if(!strcmp($acaofuncionario,'adicionar') || !strcmp($acaofuncionario,'editar'))
	{
		$nomefuncionario		= $mysqli->real_escape_string($_POST['nomefuncionario']);
		$documentofuncionario	= $mysqli->real_escape_string($_POST['documentofuncionario']);
		$dtnascfuncionario		= implode("-",array_reverse(explode("/",($_POST['dtnascfuncionario'])))); 
		$emailfuncionario		= $mysqli->real_escape_string($_POST['emailfuncionario']);
		$telefonefuncionario	= $mysqli->real_escape_string($_POST['telefonefuncionario']);
		$usuariofuncionario		= $mysqli->real_escape_string($_POST['usuariofuncionario']);
		$categoriafuncionario	= $mysqli->real_escape_string($_POST['categoriafuncionario']);
		$senhafuncionario		= $mysqli->real_escape_string($_POST['senhafuncionario']);
		$senhaantfuncionario	= $mysqli->real_escape_string($_POST['senhaantfuncionario']);
		$enderecofuncionario	= $mysqli->real_escape_string($_POST['enderecofuncionario']);
		$numerofuncionario		= $mysqli->real_escape_string($_POST['numerofuncionario']);
		$bairrofuncionario		= $mysqli->real_escape_string($_POST['bairrofuncionario']);
		$cidadefuncionario		= $mysqli->real_escape_string($_POST['cidadefuncionario']);
		$uffuncionario			= $mysqli->real_escape_string($_POST['uffuncionario']);
		$cepfuncionario			= $mysqli->real_escape_string($_POST['cepfuncionario']);
		
		if(strcmp($senhafuncionario, $senhaantfuncionario) != 0)
		{
			$senhafuncionario 	= sha1($senhafuncionario);
		}
						
		if(!strcmp($acaofuncionario,'adicionar'))
		{		
			if(!$rs = $mysqli->query("INSERT INTO tb_funcionario(
															      func_nome,
															      func_cpf,
															      func_nasc,
															      func_email,
															      func_telefone,
															      func_usuario,
															      fk_categoria,
															      func_senha,
															      func_rua,
															      func_numero,
															      func_bairro,
															      func_cidade,
															      func_estado,
															      func_cep
								  						         ) 
								 					       values(
															      '$nomefuncionario',
															      '$documentofuncionario',
															      '$dtnascfuncionario',
															      '$emailfuncionario',
															      '$telefonefuncionario',
															      '$usuariofuncionario',
															       $categoriafuncionario,
															      '$senhafuncionario',
															      '$enderecofuncionario',
															      '$numerofuncionario',
															      '$bairrofuncionario',
															      '$cidadefuncionario',
															      '$uffuncionario',
															      '$cepfuncionario'
														         )"))
			{
				echo json_encode(array('msg'=>'Erro ao gravar funcionário'));
				return false;
			}
			
			$idfuncionario = $mysqli->insert_id;
			
			echo json_encode(array('msg' => 'sucesso'));
		}
		elseif(!strcmp($acaofuncionario,'editar'))
		{
			if(isset($_POST['idfuncionario']))
			{
				$idfuncionario = $_POST['idfuncionario'];
			}
			else
			{
				echo json_encode(array('msg'=>'Funcionário não identificado para alteração'));
				return false;
			}

			if(!$rs = $mysqli->query("UPDATE tb_funcionario 
									  SET func_nome 	= '$nomefuncionario',
										  func_cpf 		= '$documentofuncionario',
										  func_nasc		= '$dtnascfuncionario',
									      func_email 	= '$emailfuncionario',
									      func_telefone = '$telefonefuncionario',
									      func_usuario 	= '$usuariofuncionario',
									      fk_categoria 	=  $categoriafuncionario,
									      func_senha 	= '$senhafuncionario',
									      func_rua 		= '$enderecofuncionario',
									      func_numero 	= '$numerofuncionario',
									      func_bairro 	= '$bairrofuncionario',
									      func_cidade 	= '$cidadefuncionario',
									      func_estado	= '$uffuncionario',
									      func_cep		= '$cepfuncionario' 
									  WHERE id_func = $idfuncionario"))
			{
				echo json_encode(array('msg'=>'Erro na alteração do funcionário'));
				return false;
			}
			
			echo json_encode(array ('msg' => 'sucesso'));
		}
	}
	elseif(!strcmp($acaofuncionario,'excluir'))
	{
		if(isset($_POST['idfuncionario']))
		{
			$idfuncionario = $_POST['idfuncionario'];
		}
		else
		{
			echo json_encode(array('msg'=>'Funcionário não identificado para exclusão'));
			return false;
		}
		
        if(!$res = $mysqli->query("DELETE FROM tb_funcionario WHERE id_func = " . $idfuncionario))
        {
            echo json_encode(array('msg'=>'Erro na exclusão do funcionário'));
            return false;
        }
		
		echo json_encode( array ( "msg" => 'sucesso'));
	}
	
?>