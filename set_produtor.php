<?php 

	header ('Content-type: text/html; charset=UTF-8');

	include 'verificaLogin.php';
	include 'connect.php';
		
	global $mysqli;
	
	if (!$mysqli->set_charset("utf8")) 
	{
		printf("Error loading character set utf8: %s\n", $mysqli->error);
	}
	
	if(isset($_POST['acaoprodutor']))
	{
		$acaoprodutor = $_POST['acaoprodutor'];
	}
	else
	{
		echo json_encode(array('msg'=>'Erro Inesperado! Ação não identificada'));
		return false;
	}
	
	if(!strcmp($acaoprodutor,'adicionar') || !strcmp($acaoprodutor,'editar'))
	{
		$nomeprodutor		= $mysqli->real_escape_string($_POST['nomeprodutor']);
		$documentoprodutor	= $mysqli->real_escape_string($_POST['documentoprodutor']);
		$emailprodutor		= $mysqli->real_escape_string($_POST['emailprodutor']);
		$telefoneprodutor	= $mysqli->real_escape_string($_POST['telefoneprodutor']);
		$enderecoprodutor	= $mysqli->real_escape_string($_POST['enderecoprodutor']);
		$numeroprodutor		= $mysqli->real_escape_string($_POST['numeroprodutor']);
		$bairroprodutor		= $mysqli->real_escape_string($_POST['bairroprodutor']);
		$cidadeprodutor		= $mysqli->real_escape_string($_POST['cidadeprodutor']);
		$ufprodutor			= $mysqli->real_escape_string($_POST['ufprodutor']);
		$cepprodutor		= $mysqli->real_escape_string($_POST['cepprodutor']);
		$prodbanco			= $mysqli->real_escape_string($_POST['prodbanco']);
		$prodagencia		= $mysqli->real_escape_string($_POST['prodagencia']);
		$prodconta			= $mysqli->real_escape_string($_POST['prodconta']);
		$proddescricaoconta	= $mysqli->real_escape_string($_POST['proddescricaoconta']);
				
		if(!strcmp($acaoprodutor,'adicionar'))
		{			
			if(!$rs = $mysqli->query("INSERT INTO tb_produtor(
															      prod_nome,
															      prod_cpf,
															      prod_email,
															      prod_telefone,
															      prod_rua,
															      prod_numero,
															      prod_bairro,
															      prod_cidade,
															      prod_estado,
															      prod_cep,
															      prod_banco,
															      prod_agencia,
															      prod_conta,
															      prod_conta_descricao
								  						         ) 
								 					       values(
															      '$nomeprodutor',
															      '$documentoprodutor',
															      '$emailprodutor',
															      '$telefoneprodutor',
															      '$enderecoprodutor',
															      '$numeroprodutor',
															      '$bairroprodutor',
															      '$cidadeprodutor',
															      '$ufprodutor',
															      '$cepprodutor',
															      '$prodbanco',
															      '$prodagencia',
															      '$prodconta',
															      '$proddescricaoconta'
														         )"))
			{
				echo json_encode(array('msg'=>'Erro ao gravar produtor'));
				return false;
			}
			
			$idprodutor = $mysqli->insert_id;
			
			echo json_encode(array('msg' => 'sucesso'));
		}
		elseif(!strcmp($acaoprodutor,'editar'))
		{
			if(isset($_POST['idprodutor']))
			{
				$idprodutor = $_POST['idprodutor'];
			}
			else
			{
				echo json_encode(array('msg'=>'Produtor não identificado para alteração'));
				return false;
			}

			if(!$rs = $mysqli->query("UPDATE tb_produtor 
									  SET prod_nome 			= '$nomeprodutor',
										  prod_cpf 				= '$documentoprodutor',
									      prod_email 			= '$emailprodutor',
									      prod_telefone 		= '$telefoneprodutor',
									      prod_rua 				= '$enderecoprodutor',
									      prod_numero 			= '$numeroprodutor',
									      prod_bairro 			= '$bairroprodutor',
									      prod_cidade 			= '$cidadeprodutor',
									      prod_estado			= '$ufprodutor',
									      prod_cep				= '$cepprodutor',
									      prod_banco			= '$prodbanco',
										  prod_agencia			= '$prodagencia',
										  prod_conta			= '$prodconta',
										  prod_conta_descricao 	= '$proddescricaoconta'
									  WHERE id_prod = $idprodutor"))
			{
				echo json_encode(array('msg'=>'Erro na alteração do produtor'));
				return false;
			}
			
			echo json_encode(array ('msg' => 'sucesso'));
		}
	}
	elseif(!strcmp($acaoprodutor,'excluir'))
	{
		if(isset($_POST['idprodutor']))
		{
			$idprodutor = $_POST['idprodutor'];
		}
		else
		{
			echo json_encode(array('msg'=>'Produtor não identificado para exclusão'));
			return false;
		}
		
        if(!$res = $mysqli->query("DELETE FROM tb_produtor WHERE id_prod = " . $idprodutor))
        {
            echo json_encode(array('msg'=>'Erro na exclusão do produtor'));
            return false;
        }
		
		echo json_encode( array ( "msg" => 'sucesso'));
	}
	
?>