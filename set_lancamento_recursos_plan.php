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
		$valor = $mysqli->real_escape_string($_POST['valor']);
		$qtd = $mysqli->real_escape_string($_POST['qtd']);
		$data = implode("-",array_reverse(explode("/",($_POST['data']))));
			
		$valor = str_replace(',','.',$valor);
				 
        if(!strcmp($acao,'adicionar'))
        {           
            if(!$rs = $mysqli->query("INSERT INTO tb_pdcj_recursos (
                                                            	recursos_descricao,
                                                            	recursos_valor,
                                                            	recursos_qtd,
                                                            	recursos_data
                                                           	  ) 
                                                     	values(
                                                              	'$descricao',
                                                          		 $valor,
                                                              	 $qtd,
                                                              	'$data'                                                               
                                                           	  )"))
            {            	
                echo json_encode(array('msg'=>'Error ao gravar recurso'));
                return false;
            }
             
            $idrecurso = $mysqli->insert_id;
            echo json_encode(array('msg' => 'sucesso')); 
       	}
        elseif(!strcmp($acao,'editar'))
        {
            if(isset($_POST['idrecursos']))
            {
                $idrecurso 		= $_POST['idrecursos'];
                 
            }
            else
            {
                echo json_encode(array('msg'=>'recurso não identificado para alteração'));
                return false;
            }
 
            if(!$rs = $mysqli->query("UPDATE tb_pdcj_recursos 
                                  		SET recursos_descricao 	= '$descricao',
                                  		recursos_valor 			=  $valor,
                                  		recursos_qtd 			=  $qtd,
                                  		recursos_data 			= '$data'
                                      	WHERE id_recursos 		=  $idrecurso"))
            {
                echo json_encode(array('msg'=>'Erro na alteração do recurso'));
                return false;
            }
			
			echo json_encode(array ('msg' => 'sucesso'));
        }
    }
    elseif(!strcmp($acao,'excluir'))
    {
    	
        if(isset($_POST['idrecursos']))
        {
            $idrecurso = $_POST['idrecursos'];
        }
        else
        {
            echo json_encode(array('msg'=>'recurso não identificado para exclusão'));
            return false;
        }
		
		         
        if(!$res = $mysqli->query("DELETE FROM tb_pdcj_recursos WHERE id_recursos = " . $idrecurso))
        {
            echo json_encode(array('msg'=>'Erro na exclusão do recurso'));
            return false;
        }
         
        echo json_encode( array ( "msg" => 'sucesso'));
    }
     
?>