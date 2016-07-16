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
    	
		$descricao = $mysqli->real_escape_string($_POST['descricao']);
		 
        if(!strcmp($acao,'adicionar'))
        {           
            if(!$rs = $mysqli->query("INSERT INTO tb_pdcj_grupo_plan (
                                                            	grup_descricao
                                                           	  ) 
                                                     	values(
                                                              	'$descricao'                                                               
                                                           	  )"))
            {            	
                echo json_encode(array('msg'=>'Error ao gravar grupo'));
                return false;
            }
             
            $idgrupo = $mysqli->insert_id;
            echo json_encode(array('msg' => 'sucesso')); 
       	}
        elseif(!strcmp($acao,'editar'))
        {
            if(isset($_POST['idgrupo']))
            {
                $idgrupo 		= $_POST['idgrupo'];
                 
            }
            else
            {
                echo json_encode(array('msg'=>'grupo não identificado para alteração'));
                return false;
            }
 
            if(!$rs = $mysqli->query("UPDATE tb_pdcj_grupo_plan 
                                  		SET grup_descricao = '$descricao'
                                      	WHERE id_grupo 	=  $idgrupo"))
            {
                echo json_encode(array('msg'=>'Erro na alteração do grupo'));
                return false;
            }
			
			echo json_encode(array ('msg' => 'sucesso'));
        }
    }
    elseif(!strcmp($acao,'excluir'))
    {
    	
        if(isset($_POST['idgrupo']))
        {
            $idgrupo = $_POST['idgrupo'];
        }
        else
        {
            echo json_encode(array('msg'=>'grupo não identificado para exclusão'));
            return false;
        }
		
		         
        if(!$res = $mysqli->query("DELETE FROM tb_pdcj_grupo_plan WHERE id_grupo = " . $idgrupo))
        {
            echo json_encode(array('msg'=>'Erro na exclusão do grupo'));
            return false;
        }
         
        echo json_encode( array ( "msg" => 'sucesso'));
    }
     
?>