<?php 

	header ('Content-type: text/html; charset=UTF-8');

	include 'verificaLogin.php';
	include 'connect.php';
		
	global $mysqli;
	
	if (!$mysqli->set_charset("utf8")) 
	{
		printf("Error loading character set utf8: %s\n", $mysqli->error);
	}
	
	if(isset($_POST['acaopropriedade']))
	{
		$acaopropriedade = $_POST['acaopropriedade'];
	}
	else
	{
		echo json_encode(array('msg'=>'Erro Inesperado! Ação não identificada'));
		return false;
	}
	
	if(!strcmp($acaopropriedade,'adicionar') || !strcmp($acaopropriedade,'editar'))
	{
		$nomepropriedade	= $mysqli->real_escape_string($_POST['nomepropriedade']);
		$iepropriedade		= $mysqli->real_escape_string($_POST['iepropriedade']);
		$areatotal			= str_replace(',','.',$mysqli->real_escape_string($_POST['areatotal']));
		$areacafe			= str_replace(',','.',$mysqli->real_escape_string($_POST['areacafe']));
		$previsaosacas		= str_replace(',','.',$mysqli->real_escape_string($_POST['previsaosacas']));
		
		if (empty($areatotal)) 		$areatotal 		= 0;
		if (empty($areacafe)) 		$areacafe 		= 0;
		if (empty($previsaosacas)) 	$previsaosacas	= 0;		
				
		if(!strcmp($acaopropriedade,'adicionar'))
		{		
			if(!$rs = $mysqli->query("INSERT INTO tb_propriedade(
															      prop_nome,
															      prop_ie,
															      prop_areatotal,
															      prop_areatotalcafe,
															      prop_previsaosacas
								  						         ) 
								 					       values(
															      '$nomepropriedade',
															      '$iepropriedade',															      																      	
															       $areatotal,
															       $areacafe,
															       $previsaosacas
														         )"))
			{
				echo json_encode(array('msg'=>'Erro ao gravar propriedade'));
				return false;
			}
			
			$idpropriedade = $mysqli->insert_id;
			
			echo json_encode(array('msg' => 'sucesso'));
		}
		elseif(!strcmp($acaopropriedade,'editar'))
		{
			if(isset($_POST['idpropriedade']))
			{
				$idpropriedade = $_POST['idpropriedade'];
			}
			else
			{
				echo json_encode(array('msg'=>'Produtor não identificado para alteração'));
				return false;
			}

			if(!$rs = $mysqli->query("UPDATE tb_propriedade 
									  SET prop_nome 			= '$nomepropriedade',
										  prop_ie 				= '$iepropriedade',
									      prop_areatotal 		=  $areatotal,
									      prop_areatotalcafe 	=  $areacafe,
									      prop_previsaosacas 	=  $previsaosacas
									  WHERE id_prop = $idpropriedade"))
			{
				echo json_encode(array('msg'=>'Erro na alteração da propriedade'));
				return false;
			}
			
			echo json_encode(array ('msg' => 'sucesso'));
		}
	}
	elseif(!strcmp($acaopropriedade,'excluir'))
	{
		if(isset($_POST['idpropriedade']))
		{
			$idpropriedade = $_POST['idpropriedade'];
		}
		else
		{
			echo json_encode(array('msg'=>'Produtor não identificado para exclusão'));
			return false;
		}
		
        if(!$res = $mysqli->query("DELETE FROM tb_propriedade WHERE id_prop = " . $idpropriedade))
        {
            echo json_encode(array('msg'=>'Erro na exclusão da propriedade'));
            return false;
        }
		
		echo json_encode( array ( "msg" => 'sucesso'));
	}
	
?>