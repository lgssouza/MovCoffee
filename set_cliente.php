<?php 

	header ('Content-type: text/html; charset=UTF-8');

	include 'verificaLogin.php';
	include 'connect.php';
		
	global $mysqli;
	
	if (!$mysqli->set_charset("utf8")) 
	{
		printf("Error loading character set utf8: %s\n", $mysqli->error);
	}
	
	if(isset($_POST['acaocliente']))
	{
		$acaocliente = $_POST['acaocliente'];
	}
	else
	{
		echo json_encode(array('msg'=>'Erro Inesperado! Ação não identificada'));
		return false;
	}
	
	if(!strcmp($acaocliente,'adicionar') || !strcmp($acaocliente,'editar'))
	{
		$nomecliente		= $mysqli->real_escape_string($_POST['nomecliente']);
		$documentocliente	= $mysqli->real_escape_string($_POST['documentocliente']);
		$dtnasccliente		= implode("-",array_reverse(explode("/",($_POST['dtnasccliente'])))); 
		$emailcliente		= $mysqli->real_escape_string($_POST['emailcliente']);
		$telefonecliente	= $mysqli->real_escape_string($_POST['telefonecliente']);
		$enderecocliente	= $mysqli->real_escape_string($_POST['enderecocliente']);
		$numerocliente		= $mysqli->real_escape_string($_POST['numerocliente']);
		$bairrocliente		= $mysqli->real_escape_string($_POST['bairrocliente']);
		$cidadecliente		= $mysqli->real_escape_string($_POST['cidadecliente']);
		$ufcliente			= $mysqli->real_escape_string($_POST['ufcliente']);
		$cepcliente			= $mysqli->real_escape_string($_POST['cepcliente']);
		
		if(!strcmp($acaocliente,'adicionar'))
		{		
			if(!$rs = $mysqli->query("INSERT INTO tb_cliente(
															      cli_nome,
															      cli_cpf,
															      cli_nasc,
															      cli_email,
															      cli_telefone,															      
															      cli_rua,
															      cli_numero,
															      cli_bairro,
															      cli_cidade,
															      cli_estado,
															      cli_cep
								  						         ) 
								 					       values(
															      '$nomecliente',
															      '$documentocliente',
															      '$dtnasccliente',
															      '$emailcliente',
															      '$telefonecliente',															      
															      '$enderecocliente',
															      '$numerocliente',
															      '$bairrocliente',
															      '$cidadecliente',
															      '$ufcliente',
															      '$cepcliente'
														         )"))
			{
				echo json_encode(array('msg'=>'Erro ao gravar Cliente'));
				return false;
			}
			
			$idcliente = $mysqli->insert_id;
			
			echo json_encode(array('msg' => 'sucesso'));
		}
		elseif(!strcmp($acaocliente,'editar'))
		{
			if(isset($_POST['idcliente']))
			{
				$idcliente = $_POST['idcliente'];
			}
			else
			{
				echo json_encode(array('msg'=>'Cliente não identificado para alteração'));
				return false;
			}

			if(!$rs = $mysqli->query("UPDATE tb_cliente 
									  SET cli_nome 	= '$nomecliente',
										  cli_cpf 		= '$documentocliente',
										  cli_nasc		= '$dtnasccliente',
									      cli_email 	= '$emailcliente',
									      cli_telefone = '$telefonecliente',									      
									      cli_rua 		= '$enderecocliente',
									      cli_numero 	= '$numerocliente',
									      cli_bairro 	= '$bairrocliente',
									      cli_cidade 	= '$cidadecliente',
									      cli_estado	= '$ufcliente',
									      cli_cep		= '$cepcliente' 
									  WHERE id_cliente = $idcliente"))
			{
				echo json_encode(array('msg'=>'Erro na alteração do Cliente'));
				return false;
			}
			
			echo json_encode(array ('msg' => 'sucesso'));
		}
	}
	elseif(!strcmp($acaocliente,'excluir'))
	{
		if(isset($_POST['idcliente']))
		{
			$idcliente = $_POST['idcliente'];
		}
		else
		{
			echo json_encode(array('msg'=>'Cliente não identificado para exclusão'));
			return false;
		}
		
        if(!$res = $mysqli->query("DELETE FROM tb_cliente WHERE id_cliente = " . $idcliente))
        {
            echo json_encode(array('msg'=>'Erro na exclusão do Cliente'));
            return false;
        }
		
		echo json_encode( array ( "msg" => 'sucesso'));
	}
	
?>