<?php 
 
    header ('Content-type: text/html; charset=UTF-8');
 
    include 'verificaLogin.php';
    include 'connect.php';
         
    global $mysqli;
     
    if (!$mysqli->set_charset("utf8")) 
    {
        printf("Error loading character set utf8: %s\n", $mysqli->error);
    }
     
    if(isset($_POST['acaoprodutos']))
    {
        $acaoprodutos = $_POST['acaoprodutos'];
		
    }
    else
    {
        echo json_encode(array('msg'=>'Erro Inesperado! Ação não identificada'));
        return false;
    }
    		 
    if(!strcmp($acaoprodutos,'adicionar') || !strcmp($acaoprodutos,'editar'))
    {
    	$idproduto		= $mysqli->real_escape_string($_POST['idproduto']);
        $descricao		= $mysqli->real_escape_string($_POST['descricao']);
		
					

        if(!strcmp($acaoprodutos,'adicionar'))
        {           
            if(!$rs = $mysqli->query("INSERT INTO tb_produto (
                                                            	descricao
                                                           	  ) 
                                                     	values(
                                                        		'$descricao'                                                             	                                                    
                                                           	  )"))
            {
                echo json_encode(array('msg'=>'Error ao gravar produto'));
                return false;
            }
             
            $idproduto = $mysqli->insert_id;
            echo json_encode(array('msg' => 'sucesso')); 
       	}
        elseif(!strcmp($acaoprodutos,'editar'))
        {
            if(isset($_POST['idproduto']))
            {
                $idproduto 		= $_POST['idproduto'];                 
            }
            else
            {
                echo json_encode(array('msg'=>'Produto não identificado para alteração'));
                return false;
            }
 
            if(!$rs = $mysqli->query("UPDATE tb_produto 
                                  		SET descricao 	= '$descricao'            
                                      	WHERE id 		=  $idproduto"))
            {
            	echo json_encode(array('msg'=>'Erro na alteração do produto'));
                return false;
            }
			
			echo json_encode(array ('msg' => 'sucesso'));
        }
    }
    elseif(!strcmp($acaoprodutos,'excluir'))
    {
    	
        if(isset($_POST['idproduto']))
        {
            $idproduto = $_POST['idproduto'];
        }
        else
        {
            echo json_encode(array('msg'=>'produto não identificado para exclusão'));
            return false;
        }
				         
        if(!$res = $mysqli->query("DELETE FROM tb_produto WHERE id = " . $idproduto))
        {
            echo json_encode(array('msg'=>'Erro na exclusão do produto'));
            return false;
        }
         
        echo json_encode( array ( "msg" => 'sucesso'));
    }
     
?>