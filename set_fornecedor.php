<?php 

	header ('Content-type: text/html; charset=UTF-8');

	include 'verificaLogin.php';
	include 'connect.php';
		
	global $mysqli;
	
	if (!$mysqli->set_charset("utf8")) 
	{
		printf("Error loading character set utf8: %s\n", $mysqli->error);
	}
	
	if(isset($_POST['acaofornecedor']))
	{
		$acaofornecedor = $_POST['acaofornecedor'];
	}
	else
	{
		echo json_encode(array('msg'=>'Erro Inesperado! Ação não identificada'));
		return false;
	}
	
	if(!strcmp($acaofornecedor,'adicionar') || !strcmp($acaofornecedor,'editar'))
	{
		$nomefornecedor		= $mysqli->real_escape_string($_POST['nomefornecedor']);
		$documentofornecedor	= $mysqli->real_escape_string($_POST['documentofornecedor']);		
		$emailfornecedor		= $mysqli->real_escape_string($_POST['emailfornecedor']);
		$telefonefornecedor	= $mysqli->real_escape_string($_POST['telefonefornecedor']);
		$enderecofornecedor	= $mysqli->real_escape_string($_POST['enderecofornecedor']);
		$numerofornecedor		= $mysqli->real_escape_string($_POST['numerofornecedor']);
		$bairrofornecedor		= $mysqli->real_escape_string($_POST['bairrofornecedor']);
		$cidadefornecedor		= $mysqli->real_escape_string($_POST['cidadefornecedor']);
		$uffornecedor			= $mysqli->real_escape_string($_POST['uffornecedor']);
		$cepfornecedor			= $mysqli->real_escape_string($_POST['cepfornecedor']);
		
		if(!strcmp($acaofornecedor,'adicionar'))
		{		
			if(!$rs = $mysqli->query("INSERT INTO tb_fornecedor(
															      fornec_nome,
															      fornec_cnpj,															      
															      fornec_email,
															      fornec_telefone,															      
															      fornec_rua,
															      fornec_numero,
															      fornec_bairro,
															      fornec_cidade,
															      fornec_estado,
															      fornec_cep
								  						         ) 
								 					       values(
															      '$nomefornecedor',
															      '$documentofornecedor',															      
															      '$emailfornecedor',
															      '$telefonefornecedor',															      
															      '$enderecofornecedor',
															      '$numerofornecedor',
															      '$bairrofornecedor',
															      '$cidadefornecedor',
															      '$uffornecedor',
															      '$cepfornecedor'
														         )"))
			{
				echo json_encode(array('msg'=>'Erro ao gravar fornecedor'));
				return false;
			}
			
			$idfornecedor = $mysqli->insert_id;
			
			echo json_encode(array('msg' => 'sucesso'));
		}
		elseif(!strcmp($acaofornecedor,'editar'))
		{
			if(isset($_POST['idfornecedor']))
			{
				$idfornecedor = $_POST['idfornecedor'];
			}
			else
			{
				echo json_encode(array('msg'=>'fornecedor não identificado para alteração'));
				return false;
			}

			if(!$rs = $mysqli->query("UPDATE tb_fornecedor 
									  SET fornec_nome 	= '$nomefornecedor',
										  fornec_cnpj 		= '$documentofornecedor',										  
									      fornec_email 	= '$emailfornecedor',
									      fornec_telefone = '$telefonefornecedor',									      
									      fornec_rua 		= '$enderecofornecedor',
									      fornec_numero 	= '$numerofornecedor',
									      fornec_bairro 	= '$bairrofornecedor',
									      fornec_cidade 	= '$cidadefornecedor',
									      fornec_estado	= '$uffornecedor',
									      fornec_cep		= '$cepfornecedor' 
									  WHERE id_fornec = $idfornecedor"))
			{
				echo json_encode(array('msg'=>'Erro na alteração do fornecedor'));
				return false;
			}
			
			echo json_encode(array ('msg' => 'sucesso'));
		}
	}
	elseif(!strcmp($acaofornecedor,'excluir'))
	{
		if(isset($_POST['idfornecedor']))
		{
			$idfornecedor = $_POST['idfornecedor'];
		}
		else
		{
			echo json_encode(array('msg'=>'fornecedor não identificado para exclusão'));
			return false;
		}
		
        if(!$res = $mysqli->query("DELETE FROM tb_fornecedor WHERE id_fornec = " . $idfornecedor))
        {
            echo json_encode(array('msg'=>'Erro na exclusão do fornecedor'));
            return false;
        }
		
		echo json_encode( array ( "msg" => 'sucesso'));
	}
	
?>