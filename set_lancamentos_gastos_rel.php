<?php 
 
    header ('Content-type: text/html; charset=UTF-8');
 
    include 'verificaLogin.php';
    include 'connect.php';
         
    global $mysqli;
     
    if (!$mysqli->set_charset("utf8")) 
    {
        printf("Error loading character set utf8: %s\n", $mysqli->error);
    }
     
    if(isset($_POST['acao']))
    {
        $acao = $_POST['acao'];
		
    }
    else
    {
        echo json_encode(array('msg'=>'Erro Inesperado! Ação não identificada'));
        return false;
    }
    
		 
    if(!strcmp($acao,'adicionar') || !strcmp($acao,'editar'))
    {
    	
		$idsubgrupo = $mysqli->real_escape_string($_POST['idsubgrupo']);
		$descricao = $mysqli->real_escape_string($_POST['descricao']);		
		$valorpremio = $mysqli->real_escape_string($_POST['valorpremio']);
		$valoroutros = $mysqli->real_escape_string($_POST['valoroutros']);
		$responsavel = $mysqli->real_escape_string($_POST['responsavel']);		
		$data		= implode("-",array_reverse(explode("/",($_POST['data']))));
			
		$valorpremio = str_replace(',','.',$valorpremio);
		$valoroutros = str_replace(',','.',$valoroutros);
		 
        if(!strcmp($acao,'adicionar'))
        {           
            if(!$rs = $mysqli->query("INSERT INTO tb_pdcj_item_rel
            											 (            											  
            											 fk_id_subg, 
            											 item_descricao, 
            											 item_valor_premio, 
            											 item_valor_outros,
            											 item_responsavel,
            											 item_data
														 ) 
														 VALUES 
														 (
														  $idsubgrupo,
														 '$descricao',
														  $valorpremio,
														  $valoroutros,
														 '$responsavel',
														 '$data'														  
														 )
														 "))
									
            {
            	echo json_encode(array('msg'=>'Error ao gravar gasto'));
                return false;
            }
             
            $iditem = $mysqli->insert_id;
            echo json_encode(array('msg' => 'sucesso')); 
       	}
        elseif(!strcmp($acao,'editar'))
        {
            if(isset($_POST['iditem']))
            {
            	$iditem 		= $_POST['iditem'];                					
            }
            else
            {
                echo json_encode(array('msg'=>'grupo não identificado para alteração'));
                return false;
            }
 
            if(!$rs = $mysqli->query("UPDATE tb_pdcj_item_rel 
                                  		SET 
                                  		fk_id_subg 			=  $idsubgrupo,
                                  		item_descricao 		= '$descricao',            						
            							item_valor_premio	=  $valorpremio,
            							item_valor_outros	=  $valoroutros,
            							item_responsavel	= '$responsavel',
            							item_data			= '$data'
                                      	WHERE id_item 		=  $iditem"))
            {
            	
                echo json_encode(array('msg'=>'Erro na alteração do gasto'));
                return false;
            }
			
			echo json_encode(array ('msg' => 'sucesso'));
        }
    }
    elseif(!strcmp($acao,'excluir'))
    {
    	
        if(isset($_POST['iditem']))
        {
            $id = $_POST['iditem'];
        }
        else
        {
            echo json_encode(array('msg'=>'orçamento não identificado para exclusão'));
            return false;
        }
		
		         
        if(!$res = $mysqli->query("DELETE FROM tb_pdcj_item_rel WHERE id_item = " . $id))
        {
            echo json_encode(array('msg'=>'Erro na exclusão do gasto'));
            return false;
        }
         
        echo json_encode( array ( "msg" => 'sucesso'));
    }
     
?>