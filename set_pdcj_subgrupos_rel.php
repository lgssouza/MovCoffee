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
    	
		$descricao 	= $mysqli->real_escape_string($_POST['descricao']);
		$idgrupo 	= $mysqli->real_escape_string($_POST['idgrupo']);
		 
        if(!strcmp($acao,'adicionar'))
        {           
            if(!$rs = $mysqli->query("INSERT INTO tb_pdcj_subgrupo_rel (
                                                            	subg_descricao,
                                                            	fk_id_grupo
                                                           	  ) 
                                                     	values(
                                                              	'$descricao',
                                                              	 $idgrupo                                                               
                                                           	  )"))
            {            	
                echo json_encode(array('msg'=>'Error ao gravar subgrupo'));
                return false;
            }
             
            $idsubgrupo = $mysqli->insert_id;
            echo json_encode(array('msg' => 'sucesso')); 
       	}
        elseif(!strcmp($acao,'editar'))
        {
            if(isset($_POST['idsubgrupo']))
            {
            	$idsubgrupo 	= $_POST['idsubgrupo'];                
                
            }
            else
            {
                echo json_encode(array('msg'=>'subgrupo não identificado para alteração'));
                return false;
            }
 
            if(!$rs = $mysqli->query("UPDATE tb_pdcj_subgrupo_rel 
                                  		SET subg_descricao = '$descricao',
                                  		fk_id_grupo = $idgrupo
                                      	WHERE id_subgrupo 	=  $idsubgrupo"))
            {
                echo json_encode(array('msg'=>'Erro na alteração do subgrupo'));
                return false;
            }
			
			echo json_encode(array ('msg' => 'sucesso'));
        }
    }
    elseif(!strcmp($acao,'excluir'))
    {
    	
        if(isset($_POST['idsubgrupo']))
        {
            $idsubgrupo = $_POST['idsubgrupo'];
        }
        else
        {
            echo json_encode(array('msg'=>'subgrupo não identificado para exclusão'));
            return false;
        }
		
		         
        if(!$res = $mysqli->query("DELETE FROM tb_pdcj_subgrupo_rel WHERE id_subgrupo = " . $idsubgrupo))
        {
            echo json_encode(array('msg'=>'Erro na exclusão do subgrupo'));
            return false;
        }
         
        echo json_encode( array ( "msg" => 'sucesso'));
    }
     
?>