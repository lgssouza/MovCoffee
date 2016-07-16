<?php 

	header ('Content-type: text/html; charset=UTF-8');

	include 'verificaLogin.php';
	include 'connect.php';
		
	global $mysqli;
	
	if (!$mysqli->set_charset("utf8")) 
	{
		printf("Error loading character set utf8: %s\n", $mysqli->error);
	}
	
	if(isset($_POST['acaopropriedadeprodutor']))
	{
		$acaopropriedadeprodutor = $_POST['acaopropriedadeprodutor'];
	}
	else
	{
		echo json_encode(array('msg'=>'Erro Inesperado! Ação não identificada'));
		return false;
	}
	
	if(!strcmp($acaopropriedadeprodutor,'adicionar') || !strcmp($acaopropriedadeprodutor,'editar'))
	{
		$idmovpp		= $mysqli->real_escape_string($_POST['idmovpp']);
		$produtor		= $mysqli->real_escape_string($_POST['produtor']);
		$propriedade	= $mysqli->real_escape_string($_POST['propriedade']);
		$percentualpp	= str_replace(',','.',$mysqli->real_escape_string($_POST['percentualpp']));
		$arearealpp 	= str_replace(',','.',$mysqli->real_escape_string($_POST['arearealpp']));
		$sacasrealpp	= str_replace(',','.',$mysqli->real_escape_string($_POST['sacasrealpp']));	
				
		if(!strcmp($acaopropriedadeprodutor,'adicionar'))
		{	
			if(!$rs = $mysqli->query("INSERT INTO mov_prop_prod(
														        fk_id_prod,
														        fk_id_prop,
														        mov_pp_percentual,
														        mov_pp_sacasreal,
														        mov_pp_areareal
							  						           ) 
							 					         values(
														        $produtor,
														        $propriedade,															      																      	
														        $percentualpp,
														        $sacasrealpp,
														        $arearealpp
													           )"))
			{
				echo json_encode(array('msg'=>'Erro ao gravar propriedade'));
				return false;
			}
			
			$idpropriedade = $mysqli->insert_id;
			
			echo json_encode(array('msg' => 'sucesso', 'idmovpp' => $idpropriedade));
		}
		elseif(!strcmp($acaopropriedadeprodutor,'editar'))
		{
			if(isset($_POST['idmovpp']))
			{
				$idmovpp = $_POST['idmovpp'];
			}
			else
			{
				echo json_encode(array('msg'=>'Propriedade não identificada para alteração'));
				return false;
			}

			if(!$rs = $mysqli->query("UPDATE mov_prop_prod 
									  SET fk_id_prop 			= $propriedade,
										  mov_pp_percentual 	= $percentualpp,
									      mov_pp_sacasreal 		= $sacasrealpp,
									      mov_pp_areareal 		= $arearealpp
									  WHERE id_mov_pp = $idmovpp"))
			{
				echo json_encode(array('msg'=>'Erro na alteração da propriedade'));
				return false;
			}
			
			echo json_encode(array ('msg' => 'sucesso'));
		}
	}
	elseif(!strcmp($acaopropriedadeprodutor,'excluir'))
	{
		if(isset($_POST['idmovpp']))
		{
			$idmovpp = $_POST['idmovpp'];
		}
		else
		{
			echo json_encode(array('msg'=>'Propriedade não identificada para exclusão'));
			return false;
		}
		
        if(!$res = $mysqli->query("DELETE FROM mov_prop_prod WHERE id_mov_pp = " . $idmovpp))
        {
            echo json_encode(array('msg'=>'Erro na exclusão da propriedade'));
            return false;
        }
		
		echo json_encode( array ( "msg" => 'sucesso'));
	}
	
?>